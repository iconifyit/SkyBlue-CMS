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
 * @date   June 20, 2009
 */

class LoginHelper {

    static function initialize() {
        if (file_exists(_SBC_APP_ . "daos/LoginDAO.php")) {
            require_once(_SBC_APP_ . "daos/LoginDAO.php");
        }
        else {
            require_once(SB_MANAGERS_DIR . "login/LoginDAO.php");
        }
        require_once(SB_MANAGERS_DIR . "login/Login.php");
        require_once(SB_MANAGERS_DIR . "login/LoginController.php");
    }

    static function updateLastLogin($User) {
        $Dao = LoginHelper::getUsersDao();
        $Dao->updateLastLogin($User);
    }

    static function fingerprint($password) {
        return Utils::fingerprint($password);
    }

    static function verifyPassword($storedPassword, $suppliedPassword) {
        return $storedPassword === $suppliedPassword;
    }

    static function getUser($username, $refresh=false) {
        $Dao = LoginHelper::getUsersDao();
        return $Dao->getByKey('username', $username);
    }

    static function getUserByKey($key, $match) {
        $Dao = LoginHelper::getUsersDao(true);
        return $Dao->getByKey($key, $match);
    }

    static function getUserByFingerprint($fingerprint) {
        $Dao = LoginHelper::getUsersDao(true);
        $Users = $Dao->index(true);
        foreach ($Users as $User) {
            if (Utils::fingerprint($User->getUsername(),"md5",true) == $fingerprint) {
                return $User;
            }
        }
        return null;
    }

    static function getUsersDao($refresh=false) {
        if (! class_exists('UsersDAO')) {
            Loader::load("managers.users.User", true, _SBC_APP_);
            Loader::load("daos.UsersDAO", true, _SBC_APP_);
        }
        return new UsersDAO();
    }

    static function getConfigDao($refresh=false) {
        if (! class_exists('ConfigurationDAO')) {
            Loader::load("managers.configuration.Configuration", true, _SBC_APP_);
            Loader::load("daos.ConfigurationDAO", true, _SBC_APP_);
        }
        return new ConfigurationDAO();
    }

    static function getAdminContact() {
        $Dao = LoginHelper::getConfigDao();
        $Config = $Dao->getItem(1);
    }
}