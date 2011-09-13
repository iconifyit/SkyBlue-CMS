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

class HelpController extends Controller {

    var $model;
    var $action;
    var $viewName;
    var $view_path;
    var $defaultViewName = 'list';

    function __construct($Request) {
        parent::__construct($Request);
        $this->setHandler('index',  'doIndex',  true);
        $this->setHandler('edit',   'doEdit',   true);
        $this->setHandler('save',   'doSave',   true);
        $this->setHandler('cancel', 'doCancel', true);
        $this->setViewPath(_SBC_SYS_ . 'managers/help/views/');
    }

    function doIndex() {
        $this->setViewName('list.php');
    }

    function doEdit() {
        $this->setViewName('edit.php');
        $this->dao->getItem(
            Filter::getNumeric($_GET, 'id')
        );
    }
    
    function doCancel() {
        parent::doCancel("admin.php?com=help");
    }
    
    function doSave($Request) {
        global $Core;
        
        if ($this->dao->save()) {
            $this->_setMessage(
                'info',
                __('GLOBAL.NOTE', 'Note', 1),
                __('GLOBAL.USER_CANCELLED', 'User canceled. No changes were saved.', 1)
            );
        }
        else {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('GLOBAL.CHANGES_NOT_SAVED', 'Your changes could not be saved.', 1)
            );
        }
        Utils::redirect("admin.php?com=help");
    }

}