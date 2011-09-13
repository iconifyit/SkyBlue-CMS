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

class ConsoleController extends Controller {

    var $model;
    var $action;
    var $viewName;
    var $view_path;
    var $defaultViewName = 'dashboard';

    function __construct($Request) {
        parent::__construct($Request);
        $this->setDefaultAction('index');
        $this->setHandler('index',       'doIndex',      true);
    }
    
    function doIndex() {
        global $Authenticate;
        
        $name = $Authenticate->user('name');
        if (trim($name) == "") $name = 'User';
        $this->view->assign('user.firstname', $name);
        $this->view->assign('user.username', $name);
        $this->setViewPath(_SBC_SYS_ . "managers/console/views/");
        $this->setViewName("dashboard.php");
    }
}