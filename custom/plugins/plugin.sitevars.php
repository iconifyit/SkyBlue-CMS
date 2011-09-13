<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version        v 1.1 2009-04-19 10:37:00 $
 * @package        SkyBlueCanvas
 * @copyright      Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license        GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

/*
site.name
site.url
site.map
site.contact_name
site.contact_title
site.contact_address
site.contact_city
site.contact_state
site.contact_zip
site.contact_email
site.contact_phone
site.contact_fax
site.slogan

page.id
page.title
page.url
page.modified("F d, Y h:i A")
page.parent.id
page.parent.title
page.menutext
page.keywords
page.description
page.css_id
page.css_class
page.author_username
page.author_name
page.route

page.link(*, li)
page.link(1, 2, 3, li)
page.link(parent)
page.link(children)
page.link(first)
page.link(last)
page.link(next)
page.link(previous)

page.metadata
skin.path

*/

global $Core;

Event::register('system.display', 'plgSiteVars');

function plgSiteVars($html) {
    Loader::load("managers.extensions.ExtensionsHelper", true, _SBC_SYS_);
    $SiteVars = new SiteVars($html);
    return $SiteVars->getHtml();
}

class SiteVars {
    
    var $html;
    
    function __construct($html) {
        Loader::load("managers.page.PageHelper", true, _SBC_SYS_);
        $this->execute($html);
    }
    
    function getHtml() {
        return $this->html;
    }

    function execute($html) {

        global $Router;
        
        // Handle redirects first
        
        $this->handleRedirect($html);
        
        $pages = get_pages();
        $page  = current_page();
    
        $pageId     = $page->getId();
        $pageTitle  = $page->getTitle();
        $pageUrl    = $Router->GetLink($pageId);
        
        
        $parent = $page->getParent();
        
        $parentTitle = "";
        if (is_callable(array($parent, 'getTitle'))) {
            $parentTitle = $parent->getTitle();
        }
        
        $modified = $this->formatDate($page->getModified());
        
        $regex = '/\[\[page.modified\((.*)\)\]\]/i';
        preg_match_all($regex, $html, $matches);
        
        if (count($matches) ==  2) {
            for ($i=0; $i<count($matches[0]); $i++) {
                $token = $matches[0][$i];
                $format = $matches[1][$i];
                $html = str_replace(
                    $token,
                    $this->formatDate($modified, $format),
                    $html
                );
            }
        }
                
        $this->html = $this->getLinks($html, $pages, $page);
        
        $this->assign('site.name',   Config::get('site_name'));
        $this->assign('site.url',    Config::get('site_url'));
        $this->assign('site.slogan', Config::get('site_slogan'));
        $this->assign('site.lang',   Config::get('site_lang'));
        
        foreach (Config::getConfig() as $key=>$value) {
            if (is_array($value)) continue;
            $this->assign("site.{$key}", $value);
        }
        
        $this->assign('page.id',              $this->get($page, 'id'));
        $this->assign('page.title',           $this->get($page, 'title'));
        $this->assign('page.menutext',        $this->get($page, 'name'));
        $this->assign('page.url',             $pageUrl);
        $this->assign('page.modified',        $modified);
        $this->assign('page.parent.id',       $this->get($page, 'parent'));
        $this->assign('page.parent.title',    $parentTitle);
        $this->assign('page.keywords',        $this->get($page, 'keywords'));
        $this->assign('page.description',     $this->get($page, 'meta_description'));
        $this->assign('page.name',            $this->get($page, 'name'));
        $this->assign('page.css_id',          $this->getCssId($page));
        $this->assign('page.css_class',       $this->get($page, 'pagetype'));
        $this->assign('page.metadata',        $this->getMetadata());
        $this->assign('page.author',          $this->get($page, 'author'));
        $this->assign('page.author_fullname', PageHelper::getUserFullName($this->get($page, 'author')));
        $this->assign('page.route',           $this->getPageRoute());
        $this->assign('skin.path',            ACTIVE_SKIN_DIR);
        $this->assign('site.map', $this->getSiteMap($pages, $pageId));

        $myVars = $this->getSettingsFile();
        if (count($myVars)) {
            foreach ($myVars as $var=>$value) {
                $this->assign($var, $value);
            }
        }
        
    }
    
    function getPageRoute() {
        global $Router;
        $route = Filter::get($Router, 'route');
        return ( $route{0} == "/" ? $route : "/{$route}" ) ;
    }
    
    function getSettingsFile() {
        $Dao = ExtensionsHelper::getDao();
        return $Dao->getItem("plugin.sitevars.php", array('context'=>'plugins'));
    }
    
    function get($subject, $key, $default=null) {
        if (is_object($subject)) {
            if (isset($subject->$key)) return $subject->$key;
            return $default;
        }
        else if (is_array($subject)) {
            if (isset($subject[$key])) return $subject[$key];
            return $default;
        }
        return $default;
    }
    
    function getMetadata() {
        ob_start();
        the_page_meta();
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }
    
    function getCssId($page) {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789_-";
        $str = strtolower($page->name);
        for ($i=0; $i<strlen($str); $i++) {
            if (strpos($chars, $str{$i}) === false) {
                $str{$i} = "-";
            }
        }
        return $str;
    }
    
    function assign($key, $value) {
        $this->html = str_replace("[[{$key}]]", $value, $this->html);
    }
    
    function getCurrentPage($pages) {
        return Utils::selectObject(
            $pages, 
            Filter::get($_GET, 'pid', DEFAULT_PAGE)
        );
    }
    
    function getSiteMap($pages, $pageId) {
        global $Router;
        $map = null;
        $count = count($pages);
        for ($i=0; $i<$count; $i++) {
            $page =& $pages[$i];
            if (! $page->getPublished()) continue;
            if ($Router->normalize($page->getName()) == "404-page-not-found") continue;
            if ($page->getParent()) {
                $map .= '<li>' . $this->getPageLink($page, null) 
                     . $this->getChildren($page, $pages) . '</li>';
            }
            else {
                $map .= '<li>' . $this->getPageLink($page, null)  . '</li>';
            }
        }
        return "<ul class=\"sitemap\">\n{$map}\n</ul>\n";
    }
    
    function getChildren($page, $pages) {
        $children = null;
        for ($n=0; $n<count($pages); $n++) {
            if ($page->getId() != $pages[$n]->getParent()) continue;
            $children .= '<li>';
            $children .= $this->getPageLink($pages[$n], null);
            $children .= $this->getChildren($pages[$n], $pages);
            $children .= '</li>';
        }
        return (!empty($children) ? "<ul>$children</ul>" : null);
    }
    
    function handleRedirect($html) {
        $regex = '/\[\[page.redirect\(([^\)]+)\)\]\]/i';
        if (preg_match_all($regex, $html, $matches) && count($matches) == 2) {
            Utils::redirect($matches[1][0]);
        }
    }
    
    function getLinks($html, $pages, $page) {
        $keywords = array(
            'parent',
            'children',
            'previous',
            'next',
            'first',
            'last'
        );
        
        $regex = '/\[\[page.link\(([^\)]+)\)\]\]/i';
        preg_match_all($regex, $html, $matches);
        
        if (count($matches) ==  2) {
            for ($i=0; $i<count($matches[0]); $i++) {
                $token = $matches[0][$i];
                $args  = $matches[1][$i];
                
                $args = explode(',', $args);
                
                $tag = null;
                if (!is_numeric($args[count($args)-1]) && 
                    !in_array($args[count($args)-1], $keywords))
                {
                    $tag = trim($args[count($args)-1]);
                    unset($args[count($args)-1]);
                }
                
                if ($args[0] == "*") {
                    $links = null;
                    for ($j=0; $j<count($pages); $j++) {
                        if (!$pages[$j]->id || !$pages[$j]->published) continue;
                        $links .= $this->getPageLink($pages[$j], $tag);
                    }
                }
                else if ($args[0] == "parent") {
                    $links = null;
                    if ($parent = Utils::selectObject($pages, $page->parent)) {
                        $links .= $this->getPageLink($parent, $tag);
                    }
                }
                else if ($args[0] == "previous") {
                    $links = null;
                    for ($j=0; $j<count($pages); $j++) {
                        if ($pages[$j]->id == $page->id && isset($pages[$j-1])) {
                            $links .= $this->getPageLink($pages[$j-1], $tag, 'Previous');
                        }
                    }
                }
                else if ($args[0] == "next") {
                    $links = null;
                    for ($j=0; $j<count($pages); $j++) {
                        if ($pages[$j]->id == $page->id && isset($pages[$j+1])) {
                            $links .= $this->getPageLink($pages[$j+1], $tag, 'Next');
                        }
                    }
                }
                else if ($args[0] == "first") {
                    $links = null;
                    $links .= $this->getPageLink($pages[0], $tag, 'First');
                }
                else if ($args[0] == "last") {
                    $links = null;
                    $links .= $this->getPageLink($pages[count($pages)-1], $tag, 'Last');
                }
                else if ($args[0] == "children") {
                    $links = null;
                    for ($j=0; $j<count($pages); $j++) {
                        if ($pages[$j]->parent == $page->id) {
                            $links .= $this->getPageLink($pages[$j], $tag);
                        }
                    }
                }
                else if (strcasecmp($args[0], "home") == 0) {
                    $links = null;
                    for ($j=0; $j<count($pages); $j++) {
                        if ($pages[$j]->isdefault == 1) {
                            $links .= $this->getPageLink($pages[$j], $tag);
                        }
                    }
                }
                else {
                    $links = null;
                    for ($j=0; $j<count($args); $j++) {
                        $_page = Utils::selectObject($pages, trim($args[$j]));
                        if (!$_page->id || !$_page->published) continue;
                        $links .= $this->getPageLink($_page, $tag);
                    }
                }
                $html = str_replace(
                    $token,
                    $links,
                    $html
                );
            }
        }
        
        if (preg_match_all('/\[\[page.alias\(([^\)]+)\)\]\]/i', $html, $matches) && count($matches) ==  2) {
            for ($i=0; $i<count($matches[0]); $i++) {
                $token = $matches[0][$i];
                $args  = $matches[1][$i];
                for ($j=0; $j<count($pages); $j++) {
                    if ($pages[$j]->id == $args[0]) {
                        $html = str_replace($token, $pages[$j]->getPermalink(), $html);
                    }
                }
                
            }
        }
        
        
        return $html;
    }
    
    function getPageLink($page, $tag, $text=null) {
        global $Router;
        $link = HtmlUtils::tag(
            'a',
            array('href' => $Router->GetLink($page->id)),
            empty($text) ? $page->title : $text
        );
        if (!empty($tag)) {
            $link = HtmlUtils::tag($tag, array(), $link);
        }
        return $link;
    }
    
    function formatDate($modified, $format=null) {
        if (empty($modified)) return null;
        $format = empty($format) ? "F d, Y h:i A" : $format;
        $modified = str_replace('T', '', $modified);
        $bits = explode('+', $modified);
        $modified = $bits[0];
        return date($format, strtotime($modified));
    }

}