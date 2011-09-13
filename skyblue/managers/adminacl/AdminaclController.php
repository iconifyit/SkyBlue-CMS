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

class AdminaclController extends Controller {

    var $model;
    var $action;
    var $viewName;
    var $view_path;
    var $defaultViewName = 'list';

    function __construct($Request) {
        parent::__construct($Request);
        $this->setHandler('index',  'doIndex',  true);
        $this->setHandler('list',   'doIndex',  true);
        $this->setHandler('edit',   'doEdit',   true);
        $this->setHandler('save',   'doSave',   true);
        $this->setHandler('apply',  'doApply',  true);
        $this->setHandler('cancel', 'doCancel', true);
        $this->setViewPath(_SBC_SYS_ . 'managers/adminacl/views/');
    }

    function doIndex($Request) {
        $itemsPerPage = 10;
        
        $allItems = $this->dao->index();
        
        $itemCount = count($allItems);
        $pageNum = Filter::getNumeric($Request, 'pageNum', 1);
        $pageCount = ceil($itemCount/$itemsPerPage);

        $this->view->setData(Utils::paginate(
            $allItems, 
            $itemsPerPage, 
            $pageNum
        ));
        
        $this->setViewName('list.php');
        $this->view->assign('pageNum', $pageNum);
        $this->view->assign('pageCount', $pageCount);
        $this->view->assign('com', 'adminacl');
    }
    
    function doEdit($Request) {
        $this->setViewName('edit.php');
        $this->view->assign('usersList',  AdminaclHelper::getUsersList());
        $this->view->assign('groupsList', AdminaclHelper::getGroupsList());
        $this->view->assign('pageNum', Filter::getNumeric($Request, 'pageNum', 1));
        
        $Aco = $this->dao->getItem($Request->get('id'));
        $com = $Aco->getName();
        $ControllerName = ucwords($com) . "Controller";
        
        $component = get_component($com);
        $rootpath = _SBC_SYS_;
        if (trim(Filter::get($component, 'type')) != "") {
            $rootpath = get_constant(Filter::get($component, 'type'));
        }
        
        Loader::load("managers.{$com}.{$ControllerName}", false, $rootpath);
        if (class_exists($ControllerName)) {
            $Controller = new $ControllerName(new RequestObject());
            $this->view->assign('acl', $Controller->getOperations());
        }
        $this->view->setData($Aco);
    }
    
    function doCancel($Request) {
        parent::doCancel("admin.php?com=adminacl&pageNum=" . $Request->get('pageNum'));
    }
    
    function doSave($Request) {
        
        $Request->set(
            'acl', 
            str_replace('"', '', Utils::jsonEncode($Request->get('acl')))
        );

        $Aco = new ACO($Request);
        
        if ($this->dao->update($Aco->getValueObject())) {
            $this->_setMessage(
                'success',
                __('GLOBAL.SUCCESS', 'Success', 1),
                __('GLOBAL.SAVE_SUCCESS', 'Your changes were successfully saved.', 1)
            );
        }
        else {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('GLOBAL.CHANGES_NOT_SAVED', 'Your changes could not be saved.', 1)
            );
        }
        Utils::redirect("admin.php?com=adminacl");
    }
    
    function doApply($Request) {
        
        $Request->set(
            'acl', 
            str_replace('"', '', Utils::jsonEncode($Request->get('acl')))
        );

        $Aco = new ACO($Request);
        
        if ($this->dao->update($Aco->getValueObject())) {
            $this->_setMessage(
                'success',
                __('GLOBAL.SUCCESS', 'Success', 1),
                __('GLOBAL.SAVE_SUCCESS', 'Your changes were successfully saved.', 1)
            );
        }
        else {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('GLOBAL.CHANGES_NOT_SAVED', 'Your changes could not be saved.', 1)
            );
        }
        Utils::redirect(
            "admin.php?com=adminacl&action=edit" 
            . "&id={$Request->get('id')}&pageNum={$Request->get('pageNum')}"
        );
    }

}