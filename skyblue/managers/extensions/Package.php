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

class Package extends TransferObject {
      
    var $tmp_name;
    var $filepath;
    var $filetype;
    var $filesize;

    function getFilepath() {
        return $this->filepath;
    }
    
    function setFilepath($filepath) {
        $this->filepath = $filepath;
    }
    
    function getFiletype() {
        return $this->filetype;
    }
    
    function setFiletype($filetype) {
        $this->filetype = $filetype;
    }
    
    function getFilesize() {
        return $this->filesize;
    }
    
    function setFilesize($filesize) {
        $this->filesize = $filesize;
    }
    
    function getTmp_name() {
        return $this->tmp_name;
    }
    
    function setTmp_name($tmp_name) {
        $this->tmp_name = $tmp_name;
    }
    
}