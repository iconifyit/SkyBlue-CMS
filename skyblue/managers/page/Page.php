<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2009-06-22 17:37:00 $
 * @package      SkyBlueCanvas
 * @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license      GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

/**
 * @author Scott Lewis
 * @date   June 22, 2009
 */

class Page extends TransferObject {

    /**
     * @var int    The item's unique ID
     */

    var $id;
    
    /**
     * @var string  The object type
     */
    
    var $type;
    
    /**
     * @var string  The object type
     */
    
    var $objtype;
    
    /**
     * @var string  The page title
     */
    
    var $title; 
    
    /**
     * @var string  The page name
     */
    
    var $name;
    
    /**
     * @var int     The menu in which the page appears
     */
    
    var $menu;
    
    /**
     * @var string  The layout type of the page
     */
    
    var $pagetype;
    
    /**
     * @var boolean  Whether or not this is a 404 error page
     */
    
    var $is_error_page;
    
    /**
     * @var boolean  Whether or not to use the site name in the page's title bar
     */    
    
    var $usesitename;
    
    /**
     * @var int     The id of the parent page (if this is a child page)
     */
    
    var $parent;
    
    /**
     * @var string  The name of the story data file
     */
    
    var $story;
    
    /**
     * @var string  The story content
     */
    var $story_content;
    
    /**
     * @var int     The id of the meta data group to use for this page
     */
    
    var $metagroup;
    
    /**
     * @var boolean  Whether or not this is the default (home) page
     */
    
    var $isdefault;
    
    /**
     * @var boolean  Whether or not the page is published
     */
    
    var $published;
    
    /**
     * @var int      The position of the page within the page list
     */
    
    var $order;
    
    /**
     * @var boolean  Whether or not to add the page to the RSS feed
     */
    
    var $syndicate;
    
    /**
     * @var date    The date the page was last modified
     */
    
    var $modified;
    
    /**
     * @var string  The page's meta description
     */
    
    var $meta_description;
    
    /**
     * @var string  The type of ACL to use (whitelist, blacklist or none)
     */
    
    var $acltype;
    
    /**
     * @var string  A comma-separated list of User IDs authorized to view this page
     */
    
    var $aclusers;
    
    /**
     * @var string  A comma-separated list of Usergroup IDs authorized to view this page
     */
    
    var $aclgroups;
    
    /**
     * @var string  A custom alias (route) to this page
     */
    
    var $permalink;
    
    /**
     * @var string  Username of the page author
     */
    
    var $author;
    
    /**
     * @var int    Whether or not to include in navigation
     */
     
    var $show_in_navigation;
    
    /**
     * Getters/Setters
     */
    
    /**
     * Get the Page ID
     * @return int
     */
    function getId() {
        return $this->id;
    }
    
    /**
     * Set the Page ID
     * @param int $id  The unique Page ID
     * @return void
     */
    function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Get the object type
     * @return string
     */
    function getObjtype() {
        return $this->objtype;
    }
    
    /**
     * Set the object type
     * @param string $objtype  The object type
     * @return void
     */
    function setObjtype($objtype) {
        $this->objtype = $objtype;
    }
    
    /**
     * Get the page title
     * @return string
     */
    function getTitle() {
        return $this->title;
    }
    
    /**
     * Set the Page title
     * @param string $title  The Title of the Page (appears in TITLE tag)
     * @return void
     */
    function setTitle($title) {
        $this->title = $title;
    }
    
    /**
     * Get the Page name
     * @return string
     */
    function getName() {
        return $this->name;
    }
    
    /**
     * Set the Page name
     * @param string $name  The Page name (appears in links)
     * @return void
     */
    function setName($name) {
        $this->name = $name;
    }
    
    /**
     * Get the ID of the menu in which the Page appears
     * @return int
     */
    function getMenu() {
        return $this->menu;
    }
    
    /**
     * Set the ID of the menu in which the Page appears
     * @param int $menu  The id of the menu in which the Page appears
     * @return void
     */
    function setMenu($menu) {
        $this->menu = $menu;
    }
    
    /**
     * Get the Page layout type (template name)
     * @return string
     */
    function getPagetype() {
        return $this->pagetype;
    }
    
    /**
     * Set the Page layout type (template name)
     * @param string $pagetype  The name of the template to use for the page
     * @return void
     */
    function setPagetype($pagetype) {
        $this->pagetype = $pagetype;
    }
    
    /**
     * Gets boolean whether or not this is the 404 Page
     * @return boolean
     */
    function getIs_error_page() {
        return $this->is_error_page;
    }
    
    /**
     * Sets boolean whether or not this is the 404 Page
     * @param boolean $is_error_page  Whether or not this is the 404 page
     * @return void
     */
    function setIs_error_page($is_error_page) {
        $this->is_error_page = $is_error_page;
    }
    
    /**
     * Gets boolean whether or not to use site name in TITLE tag
     * @return boolean
     */
    function getUsesitename() {
        return $this->usesitename;
    }
    
    /**
     * Sets boolean whether or not to use site name in TITLE tag
     * @param boolean $usesitename  Whether or not to use site name in TITLE tag
     * @return void
     */
    function setUsesitename($usesitename) {
        $this->usesitename = $usesitename;
    }
    
    /**
     * Get's the Parent Page ID
     * @return int
     */
    function getParent() {
        return $this->parent;
    }
    
    /**
     * Sets the Parent Page ID
     * @param int $parent  The ID of the Parent Page
     * @return void
     */
    function setParent($parent) {
        $this->parent = $parent;
    }
    
    /**
     * Get the name of the article content file
     * @return string  The name of the article content file
     */
    function getStory() {
        return $this->story;
    }
    
    /**
     * Set the name of the article content file
     * @param string $story  The name of the article content file
     * @return void
     */
    function setStory($story) {
        $this->story = $story;
    }
    
    /**
     * Get the name of the article content file
     * @return string  The name of the article content file
     */
    function getStory_content() {
        return $this->story_content;
    }
    
    /**
     * Set the name of the article content file
     * @param string $story  The name of the article content file
     * @return void
     */
    function setStory_content($story_content) {
        $this->story_content = $story_content;
    }
    
    /**
     * Get the Meta Group ID
     * @return int  The ID of the meta group for this page
     */
    function getMetagroup() {
        return $this->metagroup;
    }
    
    /**
     * Set the Meta Group ID
     * @param int $metagroup  The ID of the meta group for this page
     * @return void
     */
    function setMetagroup($metagroup) {
        $this->metagroup = $metagroup;
    }
    
    /**
     * Gets a boolean flag as to whether or not this is the default (home) page
     * @return boolean
     */
    function getIsdefault() {
        return $this->isdefault;
    }
    
    /**
     * Sets a boolean flag as to whether or not this is the default (home) page
     * @param boolean $isdefault  Whether or not this is the default (home) page
     * @return void
     */
    function setIsdefault($isdefault) {
        $this->isdefault = $isdefault;
    }
    
    /**
     * Gets a boolean flag as to whether or not the Page is published
     * @return boolean  Whether or not the Page is published
     */
    function getPublished() {
        return $this->published;
    }
    
    /**
     * Sets a boolean flag as to whether or not the Page is published
     * @param boolean $published  Whether or not the Page is published
     * @return void
     */
    function setPublished($published) {
        $this->published = $published;
    }
    
    /**
     * Gets the order (index) of the Page within the list of Pages
     * @return int  The index of the Page within the list of Pages
     */
    function getOrder() {
        return $this->order;
    }
    
    /**
     * Sets the order (index) of the Page within the list of Pages
     * @param int $order  The index of the Page within the list of Pages
     * @return void
     */
    function setOrder($order) {
        $this->order = $order;
    }
    
    /**
     * Gets a boolean flag as to whether or not the Page is syndicated (appears in RSS feed)
     * @return boolean $syndicate  Whether or not the Page is published (appears in RSS feed)
     */
    function getSyndicate() {
        return $this->syndicate;
    }
    
    /**
     * Sets a boolean flag as to whether or not the Page is syndicated (appears in RSS feed)
     * @param boolean $syndicate  Whether or not the Page is published (appears in RSS feed)
     * @return void
     */
    function setSyndicate($syndicate) {
        $this->syndicate = $syndicate;
    }
    
    /**
     * Gets the date the Page was last modified
     * @return string  The date the Page was last modified
     */
    function getModified() {
        return $this->modified;
    }
    
    /**
     * Gets the date the Page was last modified
     * @param string $modified The date the Page was last modified
     * @return void
     */
    function setModified($modified) {
        $this->modified = $modified;
    }
    
    /**
     * Gets the Page's meta desicription text
     * @return string
     */
    function getMeta_description() {
        return $this->meta_description;
    }
    
    /**
     * Sets the Page's meta desicription text
     * @param string $meta_desription  The Page's meta description text
     * @return void
     */
    function setMeta_description($meta_description) {
        $this->meta_description = $meta_description;
    }
    
    /**
     * Gets the Page's ACL type (whitelist, blacklist, none)
     * @return string
     */
    function getAcltype() {
        return $this->acltype;
    }
    
    /**
     * Sets the Page's ACL type (whitelist, blacklist, none)
     * @param string  $acltype  The Page's ACL type
     * @return void
     */
    function setAcltype($acltype) {
        $this->acltype = $acltype;
    }
    
    /**
     * Gets the Page's ACL Users
     * @return string  A comma-separated list of Users authorized to view/edit the Page
     */
    function getAclusers() {
        $aclusers = $this->aclusers;
        if (!is_array($aclusers)) {
            $aclusers = explode(',', $aclusers);
        }
        return $aclusers;
    }
    
    /**
     * Sets the Page's ACL Users
     * @param string $acusers  A comma-separated list of Users authorized to view/edit the Page
     * @return void
     */
    function setAclusers($aclusers) {
        if (is_array($aclusers)) {
            $aclusers = implode(',', $aclusers);
        }
        $this->aclusers = $aclusers;
    }
    
    /**
     * Gets the Page's ACL Groups
     * @return string  A comma-separated list of Groups authorized to view/edit the Page
     */
    function getAclgroups() {
        $aclgroups = $this->aclgroups;
        if (!is_array($aclgroups)) {
            $aclgroups = explode(',', $aclgroups);
        }
        return $aclgroups;
    }
    
    /**
     * Sets the Page's ACL Groups
     * @param string $aclgroups  A comma-separated list of Groups authorized to view/edit the Page
     * @return void
     */
    function setAclgroups($aclgroups) {
        if (is_array($aclgroups)) {
            $aclgroups = implode(',', $aclgroups);
        }
        $this->aclgroups = $aclgroups;
    }
    
    /**
     * Gets the Page's permalink
     * @return string
     */
    function getPermalink() {
        return $this->permalink;
    }
    
    /**
     * Sets the Page's permalink
     * @param string $permalink  The permalink to the Page
     * @return void
     */
    function setPermalink($permalink) {
        $this->permalink = $permalink;
    }
    
    /**
     * Get the Page meta data array
     * @return array
     */
    function getMeta() {
        return $this->meta;
    }
    
    /**
     * Sets the Page meta data array
     * @param array $meta
     * @return void
     */
    function setMeta($meta) {
        $this->meta = $meta;
    }
    
    /**
     * Get the Username of the page author
     * @return array
     */
    function getAuthor() {
        return $this->author;
    }
    
    /**
     * Sets the Username of the page author
     * @param string $author_username
     * @return void
     */
    function setAuthor($author) {
        $this->author = $author;
    }
    
    /**
     * Gets show_in_navigation
     * @return int
     */
    function getShow_in_navigation() {
        return $this->show_in_navigation;
    }
    
   /**
    * Sets show_in_navigation
    * @param int $show_in_navigation
    * @return int
    */
   function setShow_in_navigation($show_in_navigation) {
       $this->show_in_navigation = $show_in_navigation;
   }

}