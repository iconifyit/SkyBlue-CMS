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

class UsersHelper {
    
    function initialize() {
        if (file_exists(_SBC_APP_ . "daos/UsersDAO.php")) {
            require_once(_SBC_APP_ . "daos/UsersDAO.php");
        }
        else {
            require_once(SB_MANAGERS_DIR . "users/UsersDAO.php");
        }
        if (file_exists(_SBC_APP_ . "daos/UsergroupsDAO.php")) {
            require_once(_SBC_APP_ . "daos/UsergroupsDAO.php");
        }
        else {
            require_once(SB_MANAGERS_DIR . "users/UsergroupsDAO.php");
        }
        require_once(SB_MANAGERS_DIR . "users/User.php");
        require_once(SB_MANAGERS_DIR . "users/Usergroup.php");
        require_once(SB_MANAGERS_DIR . "users/UsersController.php");
    }

    function getUserBean() {
        $Dao = UsersHelper::getUsersDao();
        return new User();
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
        if (! is_object($Dao) || $refresh) {
            if (! class_exists('UsergroupsDAO')) {
                UsersHelper::initialize();
            }
            $Dao = new UsergroupsDAO();
        }
        return $Dao;
    }
    
    function getUsers($refresh=false) {
        static $users;
        if (! is_array($users) || $refresh) {
            $Dao = UsersHelper::getUsersDao();
            $users = $Dao->index(true);
        }
        return $users;
    }
    
    function getUsergroups($refresh=false) {
        static $groups;
        if (! is_array($groups) || $refresh) {
            $Dao = UsersHelper::getUsergroupsDao();
            $groups = $Dao->index(true);
        }
        return $groups;
    }

    function hasGroup($Item, $Group) {
        return in_array($Group->getId(), $Item->getGroups());
    }
    
    function validPassword($password) {
        $strlen = strlen($password);
        if ($strlen < 4) return false;
        for ($i=0; $i<$strlen; $i++) {
            if (strpos(SB_PASSWORD_CHARS, $password{$i}) === false) {
                return false;
            }
        }
        return true;
    }
}