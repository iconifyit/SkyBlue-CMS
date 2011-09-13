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

class Plugin extends TransferObject {
     
    /**
     * @var int  Integer flag indicating whether or not the plugin is published
     */
    var $published;
    
    /**
     * @var int  The load order of the plugin
     */
    var $ordinal;
    
    var $type = "plugin";
    var $objtype = "plugin";
    
    /**
     * Gets an integer flag indicating whether or not the plugin is published
     * @return int  Whether or not the plugin is published
     */
    function getPublished() {
        return $this->published;
    }
    
    /**
     * Sets an integer flag indicating whether or not the plugin is published
     * @parm int $published  Whether or not the plugin is published
     * @return void
     */
    function setPublished($published) {
        $this->published = $published;
    }
    
    /**
     * Gets the load order of the plugin. Load order does not necessarily correspond to 
     * execution order since execution can be attached to a system event.
     * @return int  The load order of the plugin.
     */
    function getOrdinal() {
        return $this->ordinal;
    }
    
    /**
     * Sets the load order of the plugin. Load order does not necessarily correspond to 
     * execution order since execution can be attached to a system event.
     * @param int $order  The load order of the plugin. 
     * @return void
     */
    function setOrdinal($ordinal) {
        $this->ordinal = $ordinal;
    }    
}