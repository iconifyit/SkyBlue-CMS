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

class MediaDAO extends XmlDAO {

    function __construct() {
        parent::__construct(array(
            'type' => 'media'
        ));
    }
    
    function index($dir=SB_MEDIA_DIR, $refresh=false) {
        static $media;
        if (! is_array($media) || $refresh) {
            $media = array();
            $files = FileSystem::list_files($dir, true);
            for ($i=0; $i<count($files); $i++) {
                array_push(
                    $media,
                    new Media(array(
                        'id'       => $i,
                        'name'     => basename($files[$i]),
                        'filepath' => $files[$i],
                        'filetype' => FileSystem::file_type($files[$i]),
                        'filesize' => FileSystem::file_size($files[$i])
                    ))
                );
            }
            
        }
        return $media;
    }
    
    function getItem($filepath) {
        if (! file_exists($filepath)) return null;
        new Media(array(
            'id'       => basename($filepath),
            'name'     => basename($filepath),
            'filepath' => $filepath,
            'filetype' => FileSystem::file_type($filepath),
            'filesize' => FileSystem::file_size($filepath),
            'type'     => 'media'
        ));
        return $Bean;
    }
    
    function save() { /* Currently has no save functionality */ }
    
    function upload($Bean, $options) {
        $Uploader = new Uploader(
            Filter::get($options, 'mimes'),
            Filter::get($options, 'targets')
        );
        return $Uploader->upload($Bean);
    }
    
    function delete($MediaBean, $options) {
        if (file_exists($MediaBean->getFilepath())) {
            return unlink($MediaBean->getFilepath());
        }
        return false;
    }
    
    function initDataSource() { return false; }
}