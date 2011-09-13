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

class Checkout extends TransferObject {
    
    var $type    = 'checkout';
    var $objtype = 'checkouts';
    var $file_lock;
    var $checked_out;
    var $checked_out_by;
    var $checked_out_time;
    var $item_id;
    var $item_type;
    var $id;
    
    function getId() {
        return $this->id;
    }
    
    function setId($id) {
        $this->id = $id;
    }
    
    function getFile_lock() {
        return $this->file_lock;
    }
    
    function setFile_lock($file_lock) {
        $this->file_lock = $file_lock;
    }
    
    function getChecked_out() {
        return $this->checked_out();
    }
    
    function setChecked_out($checked_out) {
        $this->checked_out = $checked_out;
    }
    
    function getChecked_out_by() {
        return $this->checked_out_by;
    }
    
    function setChecked_out_by($checked_out_by) {
        $this->checked_out_by = $checked_out_by;
    }
    
    function getChecked_out_time() {
        return $this->checked_out_time;
    }
    
    function setChecked_out_time($checked_out_time) {
        $this->checked_out_time = $checked_out_time;
    }
    
    function getItem_id() {
        return $this->item_id;
    }
    
    function setItem_id($item_id) {
        $this->item_id = $item_id;
    }
    
    function getItem_type() {
        return $this->item_type;
    }
    
    function setItem_type($item_type) {
        $this->item_type = $item_type;
    }
    
    function getObjtype() {
        return $this->objtype;
    }
    
    function setObjtype($objtype) {
        $this->objtype = $objtype;
    }
    
    function getType() {
        return $this->type;
    }
    
    function setType($type) {
        $this->type = $type;
    }        
}