<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version    2.0 2010-07-08 21:30:00 $
 * @package    SkyBlueCanvas
 * @copyright  Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license    GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

class PageHelper {
    
    function initialize() {
    	if (file_exists(_SBC_APP_ . "daos/PageDAO.php")) {
    		require_once(_SBC_APP_ . "daos/PageDAO.php");
    	}
    	else {
    		require_once(SB_MANAGERS_DIR . "page/PageDAO.php");
    	}
    	require_once(SB_MANAGERS_DIR . "page/Page.php");
    	require_once(SB_MANAGERS_DIR . "page/PageView.php");
    	require_once(SB_MANAGERS_DIR . "page/PageController.php");
    }
    
    function getPageDao($refresh=false) {
        static $Dao;
        if (! is_object($Dao) || $refresh) {
            if (! class_exists('PageDAO')) {
                PageHelper::initialize();
            }
            $Dao = new PageDAO();
        }
        return $Dao;
    }
    
    function getPages($refresh=false) {
        static $pages;
        if (! is_array($pages) || $refresh) {
            $Dao = PageHelper::getPageDao();
            $pages = $Dao->index(true);
        }
        return $pages;
    }
    
    function getPermalink($Page) {
        global $Router;
        $permalink = $Page->getPermalink();
        if (empty($permalink)) {
            $permalink = $Router->normalize($Page->getName());
        }
        return $permalink;
    }
    
    function html2xml($html) {
        $Dom = new DOMDocument("1.0", "UTF-8");
		$Dom->loadXML($html);
		$tree  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$tree .= "<site>\n";
		$tree .= PageHelper::buildXmlTree(
		    $Dom->getElementsByTagName('ul')->item(1)->childNodes
		);
		$tree .= "</site>\n";
		return $tree;
    }
    
    function buildXmlTree($nodes) {
        $tree = "";
        if ($nodes->length) {
			foreach ($nodes as $node) {
			    if ($node->nodeType != XML_ELEMENT_NODE) continue;
			    if ($node->nodeName == "li") {
			        $a = $node->getElementsByTagName('a')->item(0);
			        $PageName = $a->nodeValue;
			        $url = Utils::parseQuery($a->attributes->getNamedItem('href')->nodeValue);
			        $PageId    = Filter::get($url, 'id');
					$PageUrl   = $a->attributes->getNamedItem('rel')->nodeValue;
					$published = $a->attributes->getNamedItem('published')->nodeValue;
					$showInNav = $a->attributes->getNamedItem('show_in_navigation')->nodeValue;
					$tree .= "<page id=\"{$PageId}\" url=\"{$PageUrl}\" name=\"{$PageName}\"";
					$tree .= " published=\"{$published}\" show_in_navigation=\"{$showInNav}\"";
					if ($node->getElementsByTagName('ul')->length) {
					    $tree .= ">";
						$tree .= PageHelper::buildXmlTree(
							$node->getElementsByTagName('ul')->item(0)->childNodes
						);
						$tree .= "</page>\n";
					}
					else {
						$tree .= " />\n";
					}
				}
			}
		}
        return $tree;
    }
    
    function buildHtmlTree($pages) {
        $tree = "";
        if ($pages->length) {
			foreach ($pages as $Page) {
			    if ($Page->nodeType != XML_ELEMENT_NODE) continue;
			    $PageId   = $Page->attributes->getNamedItem('id')->nodeValue;
			    $PageName = $Page->attributes->getNamedItem('name')->nodeValue;
			    $PageUrl  = $Page->attributes->getNamedItem('url')->nodeValue;
			    
			    $Dao = PageHelper::getPageDao();
			    $Published = $Dao->getValue('published', $PageId);
			    $show_in_navigation = $Dao->getValue('show_in_navigation', $PageId);
			    
				$tree .= "<li>\n";
				$tree .= "<a";
				$tree .= " href=\"admin.php?com=page&action=edit&id={$PageId}\""; 
				$tree .= " rel=\"{$PageUrl}\"";
				$tree .= " published=\"{$Published}\""; 
				$tree .= " show_in_navigation=\"{$show_in_navigation}\""; 
				$tree .= ">{$PageName}</a>\n";
				if ($Page->childNodes->length) {
					$tree .= PageHelper::buildHtmlTree($Page->childNodes);
				}
				$tree .= "</li>\n";
			}
		}
        return (trim($tree) != "" ? "<ul>{$tree}</ul>" : $tree);
    }
    
    function getTreeView($data, $options=array()) {
        try {
            $DaoClass = ucwords(DB_TYPE) . "DAO";
            $Dao = new $DaoClass(array(
				'type' => 'structure', 
				'bean_class' => 'Structure'
			));
            $Statement = $Dao->query("select structure from Structure where site_id = 'sbc'");
            if ($result = $Statement->fetch()) {
                $Dom = load_xml_string(Filter::getRaw($result, 'structure'));
                $tree .= PageHelper::buildHtmlTree(
                    $Dom->getElementsByTagName('site')->item(0)->childNodes
                );
            }
        }
        catch (PDOException $e) {
			die($e->getMessage());
		}
		$block_id = "";
		if ($block_id = Filter::get($options, 'block_id', '')) {
		    $block_id = " id=\"{$block_id}\"";
		}
    ?>
    <div<?php echo $block_id; ?>>
        <ul>
            <li id="root">
                <a href="#"><?php echo Config::get('site_name'); ?></a>
		        <?php echo $tree; ?>
		    </li>
		</li>
    </div>
    <?php
    }
    
    function getSiteTree($data, $options=array()) {
        return PageHelper::buildSiteTree(
            $data, $options
        );
    }
    
    function getNextPage($node) {
        $n = 0;
        $max = 100;
        $sibling = $node->nextSibling;
        while ($sibling->nodeName != $node->nodeName && $n < $max) {
            $n++;
            $sibling = $sibling->nextSibling;
            return $sibling;
        }
        return false;
    }
    
    function getPreviousPage($node) {
        $n = 0;
        $max = 100;
        $sibling = $node->previousSibling;
        while ($sibling->nodeName != $node->nodeName && $n < $max) {
            $n++;
            $sibling = $sibling->previousSibling;
            return $sibling;
        }
        return false;
    }
    
    function buildSiteTree($pages, $options=array()) {
        $tree = "";
        if ($pages->length) {
            
			foreach ($pages as $Page) {
			    if ($Page->nodeType != XML_ELEMENT_NODE) continue;
			    
			    $PageId   = $Page->attributes->getNamedItem('id')->nodeValue;
			    $PageName = $Page->attributes->getNamedItem('name')->nodeValue;
			    $PageUrl  = $Page->attributes->getNamedItem('url')->nodeValue;
			    
			    $class = "";
			    $classes = array();
			    if (! PageHelper::getPreviousPage($Page)) {
			        array_push($classes, 'firstChild');
			    }
			    else if (! PageHelper::getNextPage($Page)) {
			        array_push($classes, 'lastChild');
			    }
			    
				if ($li_class = Filter::get($options, 'li_class')) {
					array_push($classes, $li_class);
				}

			    if (count($classes)) {
			        $class = implode(" ", $classes);
			        $class = " class=\"{$class}\"";
			    }
			    
				$tree .= "<li{$class}>\n";
				$a_class = "";
				if ($a_class = Filter::get($options, 'a_class')) {
					$a_class = " class=\"{$a_class}\"";
				}
				$tree .= "<a href=\"{$PageUrl}\"{$a_class}>{$PageName}</a>\n";
				if ($Page->childNodes->length) {
					$tree .= PageHelper::buildSiteTree(
					    $Page->childNodes, array(
					        'ul_class'=>'submenu', 
					        'li_class'=>'', 
					        'a_class'=>''
					    )
					);
				}
				$tree .= "</li>\n";
			}
		}
		$ul_id = "";
		if ($ul_id = Filter::get($options, 'ul_id', '')) {
		    $ul_id = " id=\"{$ul_id}\"";
		}
		$ul_class = "";
		if ($ul_class = Filter::get($options, 'ul_class')) {
		    $ul_class = " class=\"{$ul_class}\"";
		}
        return (trim($tree) != "" ? "<ul{$ul_id}{$ul_class}>{$tree}</ul>" : $tree);
    }
    
    function getNavigation() {
        $navigation = null;
        try {
            $DaoClass = ucwords(DB_TYPE) . "DAO";
            $Dao = new $DaoClass(array(
				'type' => 'structure', 
				'bean_class' => 'Structure'
			));
            $Statement = $Dao->query("select structure from Structure where site_id = 'sbc'");
            if ($result = $Statement->fetch()) {
                $Dom = load_xml_string(Filter::getRaw($result, 'structure'));
                $xpath = new DOMXPath($Dom);
                $nodes = $xpath->query("//*[@show_in_navigation='0']");
                foreach ($nodes as $node) {
                    $Dom->documentElement->removeChild($node);
                }
                $nodes = $xpath->query("//*[@published='0']");
                foreach ($nodes as $node) {
                    $Dom->documentElement->removeChild($node);
                }
            }
            $navigation = $Dom->getElementsByTagName('site')->item(0)->childNodes;
        }
        catch (PDOException $e) {
			die($e->getMessage());
		}
		return $navigation;
    }
    
    function getElementById($doc, $id) {
		$xpath = new DOMXPath($doc);
		return $xpath->query("//*[@id='$id']")->item(0);
	}
    
    function parseStructureXml() {
        $Dom = null;
        try {
            $DaoClass = ucwords(DB_TYPE) . "DAO";
            $Dao = new $DaoClass(array(
				'type' => 'structure', 
				'bean_class' => 'Structure'
			));
            $Statement = $Dao->query("select structure from Structure where site_id = 'sbc'");
            if ($result = $Statement->fetch()) {
                $Dom = load_xml_string(Filter::getRaw($result, 'structure'));
            }
        }
        catch (PDOException $e) {
			die($e->getMessage());
		}
		return $Dom;
    }
    
    function saveStructureXml($xml) {
        try {
            $DaoClass = ucwords(DB_TYPE) . "DAO";
            $Dao = new $DaoClass(array(
				'type' => 'structure', 
				'bean_class' => 'Structure'
			));
            $Dao->exec("UPDATE Structure SET structure = '$xml' WHERE site_id = 'sbc'");
        }
        catch (PDOException $e) {
			die($e->getMessage());
		}
    }

    /**
     * Checks to make sure a permalink (Page alias) is uniqe. If not, appends the Page ID to the alias.
     * @param $Page          The Page object the alias is for
     * @param $permalink     The desired alias
     * @return String        The unique alias
     */
    function getUniquePermalink($Page, $permalink) {
        global $Router;
        $pages = PageHelper::getPages(true);
        $permalink = $Router->normalize($permalink);
        $count = 0;
        foreach ($pages as $p) {
            if (strcasecmp($p->getPermalink(), $permalink) === 0 
                && $p->getId() != $Page->getId()) {
                
                    $count++;
            }
        }
        if ($count > 0) {
            $count++;
            $permalink = "{$permalink}-{$Page->getId()}";
        }
        
        return $permalink;
    }
        
    /**
     * Gets or creates the story file name for the Page Editor
     * @param $Page
     * @return unknown_type
     */
    function getStoryFile(&$Page) {
        $storyFile = $Page->getStory();
        if (empty($storyFile)) {
            return "page.page.{$Page->getId()}.txt";
        }
        return $storyFile;
    }

    /**
     * Gets the meta  data for the current page
     * @param object  A reference to the Page object
     * @return array  The page meta data
     */
    function getMeta(&$Page) {

        static $meta;
        if (! is_array($meta)) {
            Loader::load("managers.meta.MetaHelper", true, _SBC_SYS_);
            $meta = MetaHelper::getMetaData();
        }
        
        $groups = array();
        $tmp = $Page->getMetagroup();
        if (!empty($tmp)) {
            $groups = explode(',', $tmp);
        }
        
        $meta = array();
        $count = count($groups);
        for ($i=0; $i<$count; $i++) {
            $these = Utils::findAllByKey(
                $meta, 
                'metagroups', 
                $groups[$i]
            );
            if (!count($these)) continue;
            foreach ($these as $m) {
                $meta[$m->name] = $m->content;
            }
        }
        
        $description = $Page->getMeta_description();
        if (!empty($description)) {
            $meta['description'] = $description;
        }
        
        if ($keywords = Filter::get($page, 'keywords')) {
            if (isset($meta['keywords']))  {
                $meta['keywords'] .= ", $keywords";
            }
            else {
                $meta['keywords'] = $keywords;
            }
        }
        return $meta;
    }
    
    /**
     * Gets the page type options
     * @return array  An array of page types
     */
    function getPageTypeOptions() {
        static $types;
        if (!is_array($types)) {
            $types = array();
            $files = FileSystem::list_files(ACTIVE_SKIN_DIR, 0);
            $count = count($files);
            for ($i=0; $i<$count; $i++) {
                $bits = explode('.', basename($files[$i]));
                if (count($bits) > 1 && $bits[0] == 'skin') {
                    array_push($types, $bits[1]);
                }
            }
        }
        return $types;
    }
    
    /**
     * Gets the menu options
     * @return array  An array of menu objects
     */
    function getMenuListOptions() {
        static $menus;
        if (! is_array($menus)) {
            Loader::load("managers.menus.MenusHelper", true, _SBC_SYS_);
            $menus = MenusHelper::getMenus();
        }
        return $menus;
    }
    
    /**
     * Gets the possible parents of the current page
     * @param reference  A reference to a Page object
     * @return array     An array of Parent Page options
     */
    function getParentPageOptions(&$Page) {
        global $pages;
        if (!is_array($pages)) {
            $pages = array();
            $PageModel = Singleton::getInstance('PageDAO');
            $tmp = $PageModel->index();
            foreach ($tmp as $thisPage) {
                if ($thisPage->getId() != $Page->getId()) {
                    array_push($pages, $thisPage);
                }
            }
        }
        return $pages;
    }
    
    function AclTypeSelector($selected='') {
        return HtmlUtils::selector(
            array(
                HtmlUtils::option(
                    __('MGR.ACL.TYPE.NONE', 'No ACLs', 1), 
                    'no_acls', 
                    $selected == 'no_acls' ? 1 : 0
                ),
                HtmlUtils::option(
                    __('MGR.ACL.TYPE.BLACK_LIST', 'Black List', 1), 
                    'blacklist', 
                    $selected == 'blacklist' ? 1 : 0
                ),
                HtmlUtils::option(
                    __('MGR.ACL.TYPE.WHITE_LIST', 'White List', 1), 
                    'whitelist', 
                    $selected == 'whitelist' ? 1 : 0
                )
            ),
            'acltype', 
            1, 
            array('id'=>'acltype')
        );
    }
    
    function getUserFullName($username) {
        $User = Utils::findObjByKey(UsersHelper::getUsers(), 'username', $username);
        if ($User) {
            return $User->getName();
        }
        return "";
    }
    
    function getAuthorSelector($author='') {
        global $Authenticate;
        
        if (trim($author) == "") {
            $currentUser = $Authenticate->user();
            $author = $currentUser->getUsername();
        }
        
        $users = UsersHelper::getUsers();
        $count = count($users);
        $html  = "<select name=\"author\" id=\"author\">\n";
        $html .= "<option value=\"\">" . __('GLOBAL.CHOOSE', 'Choose User', 1) . "</option>\n";
        for ($i=0; $i<$count; $i++) {
            $User = $users[$i];
            if ($User->getId() == 0) continue;
            $selected = "";
            if (strcasecmp($User->getUsername(), $author) == 0) {
                $selected = " selected=\true\"";
            }
            $html .= "<option value=\"{$User->getUsername()}\"{$selected}>{$User->getName()}</option>\n";
        }
        $html .= "</select>\n";
        return $html;
    }
    
    function UserSelector($users=null) {
        global $Core;
        
        if (!is_array($users)) {
            $users = explode(',', $users);
            for ($i=0; $i<count($users); $i++) {
                $users[$i] = trim($users[$i]);
            }
        }
      
        $objs = UsersHelper::getUsers();
        
        $selector = '<ul id="aclusers">'."\r\n";
        if (count($objs)) {
            foreach ($objs as $obj) {
                $selector .= '<li>'."\r\n";
                $selector .= '<input type="checkbox" ';
                $selector .= 'name="aclusers[]" ';
                $selector .= 'value="'.$obj->id.'" ';
                if (in_array($obj->id, $users)) {
                    $selector .= 'checked="checked" ';
                }
                $selector .= '/>&nbsp;';
                $selector .= !empty($obj->name) ? $obj->name : $obj->username."\r\n";
                $selector .= '</li>'."\r\n";
            }
        }
        else {
            $selector .= "<li>". __('MGR.ACL.NO_USERS_DEFINED') . "</li>\r\n";
        }
        $selector .= '</ul>'."\r\n";
        return  $selector;
    }

    function UserGroupsSelector($groups=null) {
        global $Core;
        
        if (!is_array($groups)) {
            $groups = explode(',', $groups);
            for ($i=0; $i<count($groups); $i++) {
                $groups[$i] = trim($groups[$i]);
            }
        }
      
        $objs = array();
        $file = SB_GROUPS_FILE;
        $objs = UsersHelper::getUsergroups();
        $selector = '<ul id="aclgroups">'."\r\n";
        if (count($objs)) {
            foreach ($objs as $obj) {
                $selector .= '<li>'."\r\n";
                $selector .= '<input type="checkbox" ';
                $selector .= 'name="aclgroups[]" ';
                $selector .= 'value="'.$obj->id.'" ';
                if (in_array($obj->id, $groups)) {
                    $selector .= 'checked="checked" ';
                }
                $selector .= '/>&nbsp;';
                $selector .= $obj->name."\r\n";
                $selector .= '</li>'."\r\n";
            }
        }
        else {
            $selector .= "<li>". __('ACL.NO_GROUPS_DEFINED') . "</li>\r\n";
        }
        $selector .= '</ul>'."\r\n";
        return  $selector;
    }

}