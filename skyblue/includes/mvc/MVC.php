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

Loader::load('includes.mvc.view',       true, _SBC_SYS_);
Loader::load('includes.mvc.controller', true, _SBC_SYS_);
Loader::load('includes.mvc.dao',        true, _SBC_SYS_);
Loader::load('includes.mvc.XmlDAO',     true, _SBC_SYS_);

defined('DB_TYPE') or define('DB_TYPE', 'sqlite');
$DaoClass = ucwords(DB_TYPE) . "DAO";
Loader::load("daos.{$DaoClass}", true, _SBC_APP_);

# Loader::load('includes.mvc.SqliteDAO',  true, _SBC_SYS_);
# Loader::load('includes.mvc.MysqlDAO',   true, _SBC_SYS_);

class MVC {

    function getController($Request) {
        return Utils::getObject(ucwords($Request->get('com'))."Controller", $Request);
    }
    
    function getDAO($type) {
        return Utils::getObject(ucwords($type)."DAO");
    }
    
    function getView($type) {
        return Utils::getObject(ucwords($type)."View");
    }
    
    function loadHelperClass($className, $rootpath) {
        $ucClassName = ucwords($className) . "Helper";
        if (file_exists($rootpath . "managers/{$className}/{$ucClassName}.php")) {
        	Loader::load("managers.{$className}.{$ucClassName}", false, $rootpath);
        }
        else if (is_dir($rootpath . "managers/{$className}/helpers")) {
        	Loader::load("managers.{$className}.helpers.*", false, $rootpath);
        }
        if (is_callable(array($ucClassName, 'initialize'))) {
        	# $ucClassName::initialize();
        	call_user_func(array($ucClassName, 'initialize'));
        }
    }
    
    function loadBeanClass($className, $rootpath) {
    	$ucClassName = ucwords($className);
        if (file_exists($rootpath . "managers/{$className}/{$ucClassName}.php")) {
        	Loader::load("managers.{$className}.{$ucClassName}", false, $rootpath);
        }
        else {
        	Loader::load("managers.{$className}.beans.*", false, $rootpath);
        }
    }
    
    function loadControllerClass($className, $rootpath) {
    	$ucClassName = ucwords($className) . "Controller";
        if (file_exists($rootpath . "managers/{$className}/{$ucClassName}.php")) {
        	Loader::load("managers.{$className}.{$ucClassName}", false, $rootpath);
        }
        else {
        	Loader::load("managers.{$className}.controllers.*", false, $rootpath);
        }
    }
    
    function loadDaoClass($className, $rootpath) {
        $ucClassName = ucwords($className) . "DAO";
        if (file_exists(_SBC_APP_ . "daos/{$ucClassName}.php")) {
    		Loader::load("daos.{$ucClassName}", false, _SBC_APP_);
    	}
        else if (file_exists($rootpath . "managers/{$className}/{$ucClassName}.php")) {
        	Loader::load("managers.{$className}.{$ucClassName}", false, $rootpath);
        }
        else {
        	Loader::load("managers.{$className}.daos.*", false, $rootpath);
        }
    }
    
    function loadViewClass($className, $rootpath) {
    	$ucClassName = ucwords($className) . "View";
        if (file_exists($rootpath . "managers/{$className}/{$ucClassName}.php")) {
        	Loader::load("managers.{$className}.{$ucClassName}", false, $rootpath);
        }
        else {
        	Loader::load("managers.{$className}.views", false, $rootpath);
        }
    }
}