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

class Skin extends TransferObject {
     
    /**
     * Whether or not the skins is published (the active skin)
     * @var int (must be 0 or 1)
     */
    var $published;
    
    /**
     * Gets the published value of the skin
     * @return int (1 = published, 0 = un-published)
     */
    function getPublished() {
        return $this->published;
    }
    
    /**
     * Sets the published value of the skin
     * @param int $published (1 = published, 0 = un-published)
     * @return void
     */
    function setPublished($published) {
        $this->published = $published;
    }
}