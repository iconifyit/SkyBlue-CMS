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
 
class Uploader {

    /**
     * @var array An array of allowed file types
     */

    var $types;
    
    /**
     * @var array An array of valid target directories
     */
    
    var $targets;
    
    /**
     * @var int  The maximum upload size in bytes
     */
    
    var $max_size;
    
    /**
     * @var int  The available disk space in bytes
     */
    
    var $free_space;
    
    /**
     * @var int  A buffer for free disk space
     */ 
    
    var $buffer = 1024;
    
    /**
     * @constructor
     * The class constructor
     * @param array  The allowed file types that can be uploaded
     * @param array  An array of legal destination directories
     * @return void
     */
    
    function __construct($types, $targets) {
        $this->types   = $types;
        $this->targets = $targets;
        $this->set_max_size();
        $this->set_free_space();
    }
    
    /**
     * Performs the file upload
     * @param Media  The Media bean containing info about the file being uploaded.
     * @return array The exit code and the new file path
     */
    
    function upload($MediaBean) {

        $dest = dirname($MediaBean->getFilepath());
        
        if ($dest{strlen($dest)-1} != '/') $dest .= '/';
        
        $fname = $MediaBean->getName();
        $ftype = $MediaBean->getFiletype();
        $fsize = $MediaBean->getFilesize();
        
        $newfile = null;

        if ($fsize > $this->max_size) {
            $exitCode = 7;
        } 
        /*
         else if ($fsize > $this->free_space) {
            $exitCode = 8;
         }
         */
        else if (!in_array($ftype, $this->types)) {
            $exitCode = 4;
        }
        else if (!in_array($dest, $this->targets)) {
            $exitCode = 3;
        }
        else {
            $newfile = $dest.$fname;
                
            $max = 100;
            $ticker = 0;
            while (file_exists($newfile) && $ticker < $max) {
                $ticker++;
                $bits = explode('.', $fname);
                $ext = $bits[count($bits)-1];
                $base = implode('.', array_slice($bits, 0, -1));
                $newfile = $dest."$base.$ticker.$ext";
            }
            
            $exitCode = 0;
            if (is_uploaded_file($MediaBean->getTmp_name())) {
                $exitCode = move_uploaded_file($MediaBean->getTmp_name(), $newfile);
            }
        }
        return array($exitCode, $newfile);
    }
    
    /**
     * Sets the maximum file upload size
     * @return void
     */
    
    function set_max_size() {
        global $max_file_size;
        $ini = ini_get('upload_max_filesize');
        for ($i=0; $i<strlen($ini); $i++) {
            if (is_numeric($ini{$i})) {
                $max_file_size .= $ini{$i};
            }
        }
        $this->max_size = (intval($max_file_size) * 1024) * 1024;
    }
    
    /**
     * Sets the internal free disk space property
     * @return void
     */
    
    function set_free_space() {
        $this->free_space = disk_free_space(SB_SITE_DATA_DIR) - $this->buffer;
    }
   
}