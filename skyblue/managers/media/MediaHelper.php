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

class MediaHelper {

    function initialize() {
        if (file_exists(_SBC_APP_ . "daos/MediaDAO.php")) {
            require_once(_SBC_APP_ . "daos/MediaDAO.php");
        }
        else {
            require_once(SB_MANAGERS_DIR . "media/MediaDAO.php");
        }
        require_once(SB_MANAGERS_DIR . "media/Media.php");
        require_once(SB_MANAGERS_DIR . "media/MediaController.php");
    }

    function getUniqueId($Bean) {
        return basename(dirname($Bean->getFilepath())) . "_" . $Bean->getName();
    }

    function countUploads($uploads) {
        if (!isset($uploads['name'][0])) return 0;
        if (trim($uploads['name'][0]) == "") return 0;
        return count($uploads['name']);
    }
    
    function getFolderTree($root, $treeId="") {
        $folders = FileSystem::list_dirs($root, false);
        $count = count($folders);
        if ($count == 0) return "";
        $id = empty($treeId) ? "" : " id=\"{$treeId}\"";
        $html = "<ul{$id}>\n";
        for ($i=0; $i<$count; $i++) {
            $folderName = basename($folders[$i]);
            $subFolder = str_replace(SB_MEDIA_DIR, "", $folders[$i]);
            $html .= "<li class=\"folder\">\n";
            $html .= '<span class="icon">';
            $html .= '<img src="' .  SB_UI_DIR . 'admin/icons/' . UI_ICON_SET . '/folder.png" />';
            $html .= '</span>';
            $html .= "<a href=\"admin.php?com=media&action=list&";
            $html .= "folder={$subFolder}\" class=\"folder-label\">{$folderName}</a>\n";
            $html .= MediaHelper::getFolderTree($folders[$i], "");
            $html .= '<div class="clearfix"></div>';
            $html .= "</li>\n";
        }
        $html .= "</ul>\n";
        return $html;
    }
    
    function getItemFolder($folder) {
        return Utils::checkTrailingSlash(str_replace(SB_MEDIA_DIR, '', $folder));
    }
    
    function fiterBySubDir($subDir, $files) {
        $filtered = array();
        $subDir = SB_MEDIA_DIR . $subDir;
        $count = count($files);
        for ($i=0; $i<$count; $i++) {
            $fileName = basename($files[$i]);
            $dir = dirname($files[$i]);
            if ($dir == $subDir) {
                array_push($filtered, $files[$i]);
            }
        }
        return $filtered;
    }
    
    function getFolder() {
        $folder = str_replace(
            '..', 
            '', 
            Utils::addTrailingSlash(
                Filter::get($_GET, 'folder')
            )
        );
        if (!is_dir(SB_MEDIA_DIR . $folder)) {
            $folder = "";
        }
        return $folder == '/' ? '' : $folder ;
    }
    
    function getFolders() {
        return FileSystem::list_dirs(SB_MEDIA_DIR, true);
    }
    
    function getActionOverrideSelector($selected='rename') {
        $options = array();
        array_push($options, HtmlUtils::option(
            __('MEDIA.RENAME', 'Rename', 1),
            'rename',
            $selected == 'rename' ? 1 : 0
        ));
        array_push($options, HtmlUtils::option(
            __('MEDIA.MOVE', 'Move', 1),
            'move',
            $selected == 'move' ? 1 : 0
        ));
        array_push($options, HtmlUtils::option(
            __('MEDIA.COPY', 'Copy', 1),
            'copy',
            $selected == 'copy' ? 1 : 0
        ));
        return HtmlUtils::selector(
            $options,
            'action_override',
            1,
            array('id' => 'action_override')
        );
    }
    
    function getFolderSelector($selected=null, $Bean) {
        $folders = MediaHelper::getFolders();
        $options = array(HtmlUtils::option(
            ' -- ' . __('GLOBAL.CHOOSE', 'Choose', 1) . ' -- ',
            ''
        ));
        $count = count($folders);
        for ($i=0; $i<$count; $i++) {
            $srcdir = Utils::addTrailingSlash(dirname($Bean->getFilepath()));
            $folders[$i] = str_replace(_SBC_WWW_,'',$folders[$i]);
            if ($srcdir == $folders[$i]) continue;
            array_push($options, HtmlUtils::option(
                $folders[$i],
                $folders[$i],
                $selected == $folders[$i] ? 1 : 0
            ));
        }
        return HtmlUtils::selector(
            $options,
            'destination',
            1,
            array('id'=>'destination')
        );
    }
    
    function formatFileSize($size) {
        $str = "";
        if (strlen($size) <= 9 && strlen($size) >= 7) {
            $size = number_format($size / 1048576,1);
            $str = "$size MB";
        } 
        elseif (strlen($size) >= 10) {
            $size = number_format($size / 1073741824,1);
            $str = "$size GB";
        } 
        else {
            $size = number_format($size / 1024,1);
            $str = "$size KB";
        }
        return $str;
    }
    
    function mediaType($file) {
        $type = FileSystem::file_type($file);
        if (strpos($type, "image") !== true) {
            return "image";
        }
        return "document";
    }
    
    function scaleHeight($file, $ratio) {
        $dims = MediaHelper::getDims($file);
        if (count($dims)) {
            return round(floor($dims[1] * $ratio), 0);
        }
        return 0;
    }
    
    function scaleWidth($file, $ratio) {
        $dims = MediaHelper::getDims($file);
        if (count($dims)) {
            return round(floor($dims[0] * $ratio), 0);
        }
        return 0;
    }
    
    function getDims($file, $refresh) {
        static $dims;
        if (!is_array($dims) || !isset($dims[$file])) {
            $dims[$file] = ImageUtils::dimensions($file);
        }
        return $dims[$file];
    }
}