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

class Meta extends TransferObject {
       
    /**
     * @var string  The name of the meta field
     */
    var $name;
    
    /**
     * @var string  The content (value) of the meta field
     */
    var $content;
    
    /**
     * Groups to which the item belongs
     * @var array
     */
    var $groups;
    
    /**
     * The type of the object
     */
    var $type = 'meta';
    var $objtype = 'meta';
    
    /**
     * Gets the name of the meta field
     * @return string  The name of the meta field
     */
    function getName() {
        return $this->name;
    }
    
    /**
     * Sets the name of the meta field
     * @param string $name  The name of the meta field
     * @return void
     */
    function setName($name) {
        $this->name = $name;
    }
    
    /**
     * Gets the content of the meta field
     * @return string  The content of the meta field
     */
    function getContent() {
        return $this->content;
    }
    
    /**
     * Sets the content of the meta field
     * @param string $content  The content of the meta field
     * @return void
     */
    function setContent($content) {
        $this->content = $content;
    }
    
    /**
     * Gets the groups to which the item belongs
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
     * Sets the groups to which the item belongs
     * @param array  Array of the IDs of groups to which the item belongs
     * @return void
     */
    function setGroups($groups) {
        if (is_array($groups)) {
            $groups = implode(',', $groups);
        }
        $this->groups = $groups;
    }
    
}