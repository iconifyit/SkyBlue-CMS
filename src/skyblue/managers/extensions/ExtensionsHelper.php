<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2009-06-20 21:41:00 $
 * @package      SkyBlue CMS
 * @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license      GNU/GPL, see COPYING.txt
 * SkyBlue CMS is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

/**
 * @author Scott Lewis
 * @date   November 22, 2009
 */

class ExtensionsHelper {

    // PHP 8.2: Made static to fix non-static method call errors
    static function initialize() {
        if (file_exists(_SBC_APP_ . "daos/ExtensionsDAO.php")) {
            require_once(_SBC_APP_ . "daos/ExtensionsDAO.php");
        }
        else {
            require_once(SB_MANAGERS_DIR . "extensions/ExtensionsDAO.php");
        }
        require_once(SB_MANAGERS_DIR . "extensions/Extension.php");
        require_once(SB_MANAGERS_DIR . "extensions/Package.php");
        require_once(SB_MANAGERS_DIR . "extensions/ExtensionsController.php");
    }

    // PHP 8.2: Made static to fix non-static method call errors
    static function getComponentsFile() {
        return _SBC_APP_ . "config/components.xml";
    }

    // PHP 8.2: Made static to fix non-static method call errors
    static function getBean($name) {
        $Dao = ExtensionsHelper::getDao(true);
        return $Dao->getItem($name);
    }

    // PHP 8.2: Made static to fix non-static method call errors
    static function getDao($refresh=false) {
        static $Dao;
        if (! is_object($Dao) || $refresh) {
            if (! class_exists('ExtensionsDAO')) {
                ExtensionsHelper::initialize();
            }
            $Dao = new ExtensionsDAO();
        }
        return $Dao;
    }

    // PHP 8.2: Made static to fix non-static method call errors
    static function manifestToExtensionBean($manifest) {
        return new Extension(array(
            'id'      => Utils::getNextId($coms),
            'name'    => Filter::get($manifest, 'name',    'unknown'),
            'type'    => Filter::get($manifest, 'type',    'unknown'),
            'author'  => Filter::get($manifest, 'author',  'unknown'),
            'version' => Filter::get($manifest, 'version', 'unknown'),
            'date'    => Filter::get($manifest, 'date',    'unknown'),
            'url'     => Filter::get($manifest, 'url',     'unknown'),
            'email'   => Filter::get($manifest, 'email',   'unknown'),
            'path'    => "managers/{$name}/"
        ));
    }

    // PHP 8.2: Made static to fix non-static method call errors
    static function getManagersList($refresh=false) {
        static $managers;
        if (! is_array($managers) || $refresh) {
            $managers = array();
            $components = ExtensionsHelper::getManagers(true);
            foreach ($components as $com) {
                array_push($managers, $com->name);
            }
        }
        return $managers;
    }

    // PHP 8.2: Made static to fix non-static method call errors
    static function getManagers($refresh=false) {
        static $components;
        if (! is_array($components) || $refresh) {
            $components = parse_xml(ExtensionsHelper::getComponentsFile());
        }
        return $components;
    }

    // PHP 8.2: Made static to fix non-static method call errors
    static function getFragmentsList() {
        return array_map("basename", FileSystem::list_dirs(SB_FRAGMENTS_DIR, false));
    }

    // PHP 8.2: Made static to fix non-static method call errors
    static function getSkinsList() {
        return array_map("basename", FileSystem::list_dirs(SB_SKINS_DIR, false));
    }

    // PHP 8.2: Made static to fix non-static method call errors
    static function getPluginsList() {
        return array_map("basename", FileSystem::list_files(SB_USER_PLUGINS_DIR, false));
    }

    // PHP 8.2: Made static to fix non-static method call errors
    static function splitCopyPaths($arr) {
        $count = count($arr);
        for ($i=0; $i<$count; $i++) {
            list($from, $to) = explode(',', $arr[$i]);
            $arr[$i] = array('from' => trim($from), 'to' => trim($to));
        }
        return $arr;
    }
}