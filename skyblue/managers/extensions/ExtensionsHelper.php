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
 * @date   November 22, 2009
 */

class ExtensionsHelper {

    function initialize() {
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
    
    function getComponentsFile() {
        return _SBC_APP_ . "config/components.xml";
    }
    
    function getBean($name) {
        $Dao = ExtensionsHelper::getDao(true);
        return $Dao->getItem($name);
    }
    
    function getDao($refresh=false) {
        static $Dao;
        if (! is_object($Dao) || $refresh) {
            if (! class_exists('ExtensionsDAO')) {
                ExtensionsHelper::initialize();
            }
            $Dao = new ExtensionsDAO();
        }
        return $Dao;
    }
    
    function manifestToExtensionBean($manifest) {
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
    
    function getManagersList($refresh=false) {
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
    
    function getManagers($refresh=false) {
        static $components;
        if (! is_array($components) || $refresh) {
            $components = parse_xml(ExtensionsHelper::getComponentsFile());
        }
        return $components;
    }
    
    function getFragmentsList() {
        return array_map("basename", FileSystem::list_dirs(SB_FRAGMENTS_DIR, false));
    }
    
    function getSkinsList() {
        return array_map("basename", FileSystem::list_dirs(SB_SKINS_DIR, false));
    }
    
    function getPluginsList() {
        return array_map("basename", FileSystem::list_files(SB_USER_PLUGINS_DIR, false));
    }
    
    function splitCopyPaths($arr) {
        $count = count($arr);
        for ($i=0; $i<$count; $i++) {
            list($from, $to) = explode(',', $arr[$i]);
            $arr[$i] = array('from' => trim($from), 'to' => trim($to));
        }
        return $arr;
    }
}