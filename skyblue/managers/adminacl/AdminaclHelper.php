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

class AdminaclHelper {

    function initialize() {
    	if (file_exists(_SBC_APP_ . "daos/AdminaclDAO.php")) {
    		require_once(_SBC_APP_ . "daos/AdminaclDAO.php");
    	}
    	else {
    		require_once(SB_MANAGERS_DIR . "adminacl/AdminaclDAO.php");
    	}
    	require_once(SB_MANAGERS_DIR . "adminacl/Adminacl.php");
    	require_once(SB_MANAGERS_DIR . "adminacl/AdminaclController.php");
    }

    function hasAdmin($User, $Groups) {
        $AdminGroups = array();
        foreach ($Groups as $Group) {
            if (Filter::get($Group, 'siteadmin') == 1) {
                array_push($AdminGroups, $Group->id);
            }
        }
        $myGroups = Filter::get($User, 'groups', array());
        if (is_array($myGroups)) {
            $count = count($myGroups);
            for ($i=0; $i<$count; $i++) {
                if (in_array($myGroups[$i], $AdminGroups)) {
                    return true;
                }
            }
        }
        return false;
    }
    
    function prepareObjectsForSave($objects) {
        $clean = array();
        foreach ($objects as $object) {
            if (is_array($object->users)) {
                $object->users = implode(',', $object->users);
            }
            if (is_array($object->groups)) {
                $object->groups = implode(',', $object->groups);
            }
            array_push($clean, $object->getValueObject());
        }
        return $clean;
    }
    
    function getUsersList() {
        $Dao = AdminaclHelper::getUsersDao();
        return $Dao->index();
    }
    
    function getGroupsList() {
        $Dao = AdminaclHelper::getUsergroupsDao();
        return $Dao->index();
    }
    
    function getObjects() {
        $Dao = AdminaclHelper::getAdminaclDao();
        return $Dao->index();
    }
    
    function getAdminaclDao($refresh=false) {
        static $Dao;
        if (! is_object($Dao) || $refresh) {
            if (! class_exists('AdminacleDAO')) {
                AdminaclHelper::initialize();
            }
            $Dao = new AdminaclDAO();
        }
        return $Dao;
    }
    
    function getUsersDao($refresh=false) {
        static $Dao;
        if (! is_object($Dao) || $refresh) {
            if (! class_exists('UsersDAO')) {
                UsersHelper::initialize();
            }
            $Dao = new UsersDAO();
        }
        return $Dao;
    }
    
    function getUsergroupsDao($refresh=false) {
        static $Dao;
        if (!is_object($Dao) || $refresh) {
            if (! class_exists('UsergroupsDAO')) {
                UsergroupsHelper::initialize();
            }
            $Dao = new UsergroupsDAO();
        }
        return $Dao;
    }
}