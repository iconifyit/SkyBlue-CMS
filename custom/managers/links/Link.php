<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2009-06-20 21:41:00 $
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
 * @date   June 20, 2009
 */

class Link extends TransferObject {
     
    /**
     * The URL of the link
     * @var string
     */
    var $url;
    
    /**
     * Groups to which the link belongs
     * @var array
     */
    
    var $groups;
    
    /**
     * The link rel attribute
     * @var string
     */
     
    var $relationship;
    
    var $type = 'links';
    var $objtype = 'link';
    
    /**
     * Gets the URL
     * @return string  The URL
     */
    function getUrl() {
        return $this->url;
    }
    
    /**
     * Sets the URL
     * @param $url  The URL
     * @return void
     */
    function setUrl($url) {
        $this->url = $url;
    }
    
    /**
     * Gets the groups to which the link belongs
     * @return array
     */
    function getGroups() {
        $groups = $this->groups;
        if (!is_array($groups)) {
            $groups = explode(',', $groups);
        }
        return $groups;
    }
    
    /**
     * Sets the groups to which the link belongs
     * @param array  Array of the IDs of groups to which the link belongs
     * @return void
     */
    function setGroups($groups) {
        if (is_array($groups)) {
            $groups = implode(',', $groups);
        }
        $this->groups = $groups;
    }
    
    /**
     * Gets the link relationship
     * @return String  The relationship
     */
     
    function getRelationship() {
        return $this->relationship;
    }
    
    /**
     * Sets the link relationship
     * @param String $relationship  The link relationship
     * @return void
     */
     
    function setRelationship($relationship) {
        $this->relationship = $relationship;
    }
    
}