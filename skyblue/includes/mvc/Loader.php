<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2008-12-12 23:50:00 $
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
 * @date   December 12, 2008
 */

class Loader {
    function load($resource, $required=true, $root="") {
        if ($resource{strlen($resource)-1} == "*") {
            $path = str_replace('*', '', $resource);
            $path = str_replace('.', '/', $path);
            if (substr($path, -1) == "/") $path = substr($path, 0, -1);
            $files = FileSystem::list_files($root . $path);
            for ($i=0; $i<count($files); $i++) {
                if ($required) {
                    require_once($files[$i]);
                }
                else {
                    include_once($files[$i]);
                }
            }
        }
        else {
            $file = $root . str_replace('.', '/', $resource) . '.php';
            if ($required) {
                if (file_exists($file)) {
                    require_once($file);
                }
                else {
                    die('No such file ' . $file);
                }
            }
            else {
                if (file_exists($file)) {
                    include_once($file);
                }
            }
        }
    }
}