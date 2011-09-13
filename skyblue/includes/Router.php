<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version     2.0 2009-04-19 10:37:00 $
 * @package        SkyBlueCanvas
 * @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license        GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General License, and as distributed it includes or
 * is derivative of works licensed under the GNU General License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */
 
/**
 * The Router class replaces the hard-coded redirection in the .htaccess file
 * for providing a flexible base for url-rewriting
 * Examples: http://mydomain.tld/sbc/parentpagename/pagename.html
 *           -> http://mydomain.tld/sbc/index.php?pid=pageid
 *           http://mydomain.tld/sbc/parentpagename/pagename.25.htm
 *           -> http://mydomain.tld/sbc/index.php?pid=pageid&show=25
 */

class Router {

    var $request;
    
    /**
     * Used to store the requested "file" passed to SBC via $_GET['route']
     * @access private
     * @var string
     */
    
    var $route;
    
    /**
     * The name of the current page (e.g., About.html)
     * @access private
     * @var string
     */
    
    var $pageName;
    
    /**
     * Used to store the pages form the xml File
     * @access private
     * @var Array
     */
    
    var $pages;
    
    /**
     * Used to store the xmparser
     * needed, because the core allready needs the pid for creating himself.
     * @access private
     * @var xmlHandler
     */
    
    var $xmlHandler;
    
    /**
     * LanguageHelper object for i18n support
     * @access private
     * @var LanguageHelper
     */
    
    var $LanguageHelper;
    
    /*
     * @constructor
     */
    
    function __construct($path=null) {
        $this->LanguageHelper = Singleton::getInstance('LanguageHelper');
        $this->pages = get_pages();
    }
    
    /**
     * Retrieves the page route
     * @access private
     * @return string
     */
    
    function getRoute() {
        $this->route = null;
        if (isset($_GET['route'])) {
            $this->route = $_GET['route'];
        }
        $this->route = $this->removeLastSlash($this->route);
    }
    
    /**
     * @description Determines if the URL format is the legacy format
     * @access private
     * @return boolean
     */
    
    // http://www.mydomain.com/photo-pg-26-c3-p1-41.html
    // http://www.mydomain.com/photo-pg-26-c3-41.html
    
    function isLegacyLink() {
       $isLegacyLink = false; 
       if (preg_match_all("/pg-([0-9]+)-c([0-9]+)-p([0-9]+)-([0-9]+)\.htm/i", $this->route, $matches)) {
           $_GET['pid']  = $matches[1][0];
           $_GET['cid']  = $matches[2][0];
           $_GET['page'] = $matches[3][0];
           $_GET['show'] = $matches[4][0];
           $isLegacyLink = true;
       }
       if (preg_match_all("/pg-([0-9]+)-c([0-9]+)-p([0-9]+)\.htm/i", $this->route, $matches)) {
           $_GET['pid']  = $matches[1][0];
           $_GET['cid']  = $matches[2][0];
           $_GET['page'] = $matches[3][0];
           $isLegacyLink = true;
       }
       else if (preg_match_all("/pg-([0-9]+)-c([0-9]+)-([0-9]+)\.htm/i", $this->route, $matches)) {
           $_GET['pid']  = $matches[1][0];
           $_GET['cid']  = $matches[2][0];
           $_GET['show'] = $matches[3][0];
           $isLegacyLink = true;
       }
       else if (preg_match_all("/pg-([0-9]+)-c([0-9]+)\.htm/i", $this->route, $matches)) {
           $_GET['pid']  = $matches[1][0];
           $_GET['cid']  = $matches[2][0];
           $isLegacyLink = true;
       }
       else if (preg_match_all("/pg-([0-9]+)-([0-9]+)\.htm/i", $this->route, $matches)) {
           $_GET['pid']  = $matches[1][0];
           $_GET['show'] = $matches[2][0];
           $isLegacyLink = true;
       }
       else if (preg_match_all("/pg-([0-9]+)\.htm/i", $this->route, $matches)) {
           $_GET['pid']  = $matches[1][0];
           $isLegacyLink = true;
       }
       return $isLegacyLink;
    }
    
    /**
     * @description    Gets the text name of the current page
     * @access public
     * @return true|false returns true if everything is gone right, false if not
     */
    
    function getPageName() {
        $pageName = basename($this->route);
        if (empty($this->route)) {
            $defaultPage = $this->getDefaultPage();
            $defaultPage = $defaultPage[0];
            $pageId = Filter::get($_GET, 'pid', $defaultPage->id);
            $pageName = basename($this->GetSefLink($pageId));
        }
        $this->setPageName($pageName);
        return $this->pageName;
    }
    
    function setPageName($pageName) {
        $this->pageName = $pageName;
    }
    
    /**
     * Used to initiate the routing
     * @access public
     * @return true|false returns true if everything is gone right, false if not
     */
    
    function route() {
    
        $this->getRoute();
        
        // If this is not an SEF URL, let the request pass through
        
        if (empty($this->route) && ! empty($_GET)) {
            return true;
        }
        
        // If this is a legacy-format link (Name-pg-N.html), 
        // let the request pass through
        
        else if ($this->isLegacyLink()) {
            if (! $this->getPageById($_GET['pid'])) {
                $_GET['pid'] = 'notfound';
            }
            return true;
        }
        
        // Otherwise, process the SEF URL
        
        else {
            
            $isPermalink = false;
            $selectedPages = array();
            
            // Check to see if the page route matches a permalink

            foreach ($this->pages as $p) {
                if (! empty($this->route) && strcasecmp(basename($this->route), $p->getPermalink()) === 0) {
                    array_push($selectedPages, $p);
                    $isPermalink = true;
                }
            }
            
            // If not, treat the request as a page route
            
            if (! $isPermalink) {
                $pageTree = explode('/', $this->route);
                
                foreach ($pageTree as $pageName) {
                
                    $treelength = count($selectedPages);
        
                    $parentPage = null;
                    $parentPageId = null;
                    if ($treelength) {
                        $parentPage   = $selectedPages[$treelength-1];
                        $parentPageId = $parentPage->id;
                    }
                    
                    $foundPages = $this->getPageByNameAndParent($pageName, $parentPageId);
                    
                    if (! count($foundPages)) {
                        $_GET['pid'] = 'notfound';
                        return false;
                    }
                    
                    $isChild = false;
                    foreach ($foundPages as $foundPage) {
                        if ($this->checkParent($foundPage, $parentPage)) {
                            array_push($selectedPages, $foundPage);
                            $isChild = true;
                            break;
                        }
                    }
                    if (!$isChild) return false;
                }
            }
            
            $_GET['pid'] = null;
            if (count($selectedPages)) {
                $_GET['pid'] = $selectedPages[count($selectedPages)-1]->id;
            }
            return true;
        }
        return true;
    }
    
    /**
     * GetLink returns the HREF URL in the proper format depending on whether or not
     * USE_SEF_URLS is set to true or not.
     *
     * @access public
     * @param int     $PageID The id of the page to display the object.
     * @param array   $params Key=>value pairs of URL vars
     * @return string $link
     */
    
    function GetLink($pageID, $params=array(), $die=0) {
        if (defined('USE_SEF_URLS') && USE_SEF_URLS == 1) {
            $link = $this->getPageTree($pageID);
            if (is_array($params) && count($params)) {
                foreach ($params as $k=>$v) {
                    $link .= "{$k}{$v}";
                }
            }
            return FULL_URL . $link ; // . ".html";
        }
        else {
            return FULL_URL . "index.php?pid={$pageID}" . $this->build_query($params);
        }
    }
    
    /**
     * Builds a URL Query String
     *
     * @param array   $params Key=>value pairs of URL vars
     * @return string $query
     */
    
    function build_query($params) {
        if (function_exists('http_build_query')) {
            return http_build_query($params);
        }
        $query = array();
        foreach ($params as $k=>$v) {
            array_push($query, "$k=$v");
        }
        return implode("&", $query);
    }
    
    /**
     * @description    GetLink returns SEF URL of the requested page
     * @access public
     * @param int     $PageID The id of the page to display the object.
     * @param array   $params Key=>value pairs of URL vars
     * @return string $link
     */
    
    function GetSefLink($pageID, $params=array()) {
        $link = $this->getPageTree($pageID);
        if (is_array($params) && count($params)) {
            foreach ($params as $k=>$v) {
                $link .= "{$k}{$v}";
            }
        }
        return $link . ".html";
    }
    
    /**
     * @description    Gets a fingerprint of the current page request. 
     * The fingerprint is used to refer to a page without having to 
     * worry about whether the page is being requested via SEF URL or 
     * a URL query string.
     * @access public
     * @return string  A fingerprint of the current page request
     */
     
    function getFingerprint() {
        $str = $this->route;
        if (empty($this->route)) {
            foreach ($_GET as $k=>$v) {
                $str .= "{$k}{$v}";
            }
        }
        return md5($str) . ".html";
    }
    
    /**
     * removes the '.htm' or '.html' from a string
     * @access private
     * @return string
     */
    
    function removeFileExt($string) {
        if (preg_match('/^(.*)\.(htm|html)$/', $string, $matches)) {
            return $matches[1];
        }
        return $string;
    }
    
    /**
     * removes the last / from a string
     * @access private
     * @return string
     */
    
    function removeLastSlash($string) {
        if (preg_match('/^(.*)\/$/', $string, $matches)) {
            return $matches[1];
        }
        return $string;
    }
    
    /**
     * returns a pageobject identified by his name or his id (is_int(...))
     * @access private
     * @param string|int     if it's an integer, the page is identified by his id if not, by his name
     * @return object|false
     */
    
    function getPageByNameAndParent($identifier, $parent) {
        
        $identifier = $this->removeFileExt($identifier);
        
        $bits = explode('.', $identifier);
        $identifier = $bits[0];
        if (count($bits) > 1) {
            $_GET['params'] = array_slice($bits, 1);
        }
        
        if (empty($identifier)) {
            return $this->getDefaultPage();
        }
        
        $selectedPages = array();
        foreach ($this->pages as $page) {
            if ($this->normalize($page->name) == $this->normalize($identifier) 
                && $page->parent == $parent)
            {
                array_push($selectedPages, $page);
            }
        }
        return $selectedPages;
    }
    
    /**
     * Retrieves the default page
     * @access private
     * @return object
     */
    
    function getDefaultPage() {
        foreach ($this->pages as $page) {
            if (1 == intval($page->isdefault)) {
                return array($page);
            }
        }
        return array();
    }
    
    /**
     * Retrieves a page by its ID
     * @access private
     * @return object
     */
    
    function getPageById($identifier) {
        foreach ($this->pages as $page) {
            if (intval($page->id) == intval($identifier)) {
                return $page;
            }
        }
        return null;
    }
    
    /**
     * Displays a custom 404 page if it exists
     * @access private
     * @return boolean
     */
    
    function pageNotFound() {
        $errorPage = $this->getPageByNameAndParent("404-page-not-found", "");
        if (is_array($errorPage) && count($errorPage)) {
            return $errorPage[0];
        }
        else if (is_object($errorPage)) {
            return $errorPage;
        }
        return null;
    }
    
    /**
     * Gets the Error Handler page
     */
    
    function getErrorPage() {
        static $errorPage;
        if (!is_object($errorPage)) {
            # Loader::load('managers.page.daos.page');
            # Loader::load('managers.page.models.page');
            $PageModel = Singleton::getInstance('PageDAO');
            if ($pages = $PageModel->index()) {
                foreach ($pages as $Page) {
                    if (Filter::get($Page, 'is_error_page') == 1) {
                        $errorPage = $Page;
                    }
                }
            }
        }
        return $errorPage;
    }
    
    /**
     * normalizes the string for use in an url
     * @access private
     * @return string
     */
    
    function normalize($str) {
        $str = strtolower($str);

        $entMap = $this->LanguageHelper->getEntityMap();
        
        $ents = array();
        $replace = array();
        if (!empty($entMap)) {
            foreach ($entMap as $key=>$value) {
                array_push($ents, $key);
                array_push($replace, $value);
            }
        }
        $str = str_replace($ents, $replace, $str);
        $chars = URL_SAFE_CHARS;
        $str = strtolower($str);
        for ($i=0; $i<strlen($str); $i++) {
            if (false === strpos($chars, $str{$i})) {
                $str{$i} = REPLACEMENT_CHAR;
            }
        }
        $max = 100;
        $n=0;
        if (REPLACEMENT_CHAR !== "") {
            while (strpos($str, REPLACEMENT_CHAR . REPLACEMENT_CHAR) !== false && $n<$max) {
                $str = str_replace(REPLACEMENT_CHAR . REPLACEMENT_CHAR, REPLACEMENT_CHAR, $str);
                $n++;
            }
        }
        return $str;
    }
    
    /**
     * returns an array with the pagename and its parents names
     * @access private
     * @return array of objects
     */
    
    function getPageTree($PageID) {
        $pageTree = array();
        
        $Page = $this->getPageById($PageID);
        $alias = $Page->getName();
        if (trim($Page->getPermalink()) != "") {
            $alias = $Page->getPermalink();
        }
            
        array_push($pageTree, $this->normalize($alias));
    
        $n=0;
        $max = 100;
        while ($Page->getParent() && $n<$max) {
            $Page = $this->getPageById($Page->getParent());
            $alias = $Page->getName();
            if (trim($Page->getPermalink()) != "") {
                $alias = $Page->getPermalink();
            }
            array_push($pageTree, $this->normalize($alias));
            $n++;
        }
        $pageTree = array_reverse($pageTree);
        $pageTree = implode('/',$pageTree);
        return $pageTree;
    }
    
    /**
     * Determines if the current page is a top-level page
     * @access private
     * @return boolean
     */
    
    function checkParent($child, $parent) {
        if (!$parent || !$child) return true;
        if ($child->parent != $parent->id) {
            return false;
        }
        return true;
    }
}