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

class ExtensionsDAO extends MysqlDAO {

    function __construct() {
        parent::__construct(array(
            'type'       => 'extensions',
            'bean_class' => 'Extension'
        ));
    }
    
    function getItem($name) {
        return parent::getByName($name);
    }
    
    /* Nothing to see here */
    function index() {}
    function delete() {}
    function reorder() {}
    function copy() {}
    function save() {}
}