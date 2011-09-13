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

/**
 * TODO: Need to develop a way to install new plugins.
 */

class PluginController extends Controller {

    var $model;
    var $action;
    var $viewName;
    var $view_path;
    var $defaultViewName = 'list';

    function __construct($Request) {
        parent::__construct($Request);
        $this->setHandler('index',   'doIndex',   true);
        $this->setHandler('list',    'doIndex',   true);
        $this->setHandler('publish', 'doPublish', true);
        $this->setHandler('reorder', 'doReorder', true);
        $this->setViewPath(_SBC_SYS_ . "managers/plugin/views/");
    }

    function doIndex() {
        
        $itemsPerPage = Filter::getNumeric(
            $this->getConfig(), 'items_per_page', 10
        );
        
        $allItems = $this->dao->index();
        
        $itemCount = count($allItems);
        $pageNum = Filter::getNumeric($_GET, 'pageNum', 1);
        $pageCount = ceil($itemCount/$itemsPerPage);

        $this->view->setData(Utils::paginate(
            $allItems, 
            $itemsPerPage, 
            $pageNum
        ));
        
        $this->setViewName('list.php');
        $this->view->assign('pageNum', $pageNum);
        $this->view->assign('pageCount', $pageCount);
        $this->view->assign('items_per_page', $itemsPerPage);
    }
    
    function doAdd() {
        $this->setViewName('edit.php');
        $objects = $this->dao->index();
        $nextId = Utils::getNextId($objects);
        $this->dao->setData(new Plugin(array(
            'id'      => $nextId,
            'name'    => "plugin.untitled-id{$nextId}.php",
            'type'    => 'plugin',
            'objtype' => 'plugin',
            'published' => 1,
            'order'   => count($objects) + 1
        )));
        $this->view->assign('is_new', true);
    }

    function doEdit($Request) {
        $this->setViewName('edit.php');
        
        $Bean = $this->dao->getItem($Request->get('id'));
        
        if ($this->checkOut($Bean) == 1) {
            $this->showEditForm($Bean);
        }
        else {
            $this->_setMessage(
                'warning',
                __('GLOBAL.WARNING', 'Warning', 1),
                __("GLOBAL.CURRENTLY_CHECKED_OUT", 'The item could not be checked out because it is currently checked out by another user.', 1)
            );
            Utils::redirect("admin.php?com=plugin");
        }
    }
    
    function showEditForm($Bean) {
        $Bean->setContent(stripslashes($Bean->getContent()));
        $this->view->assign('the_action', __('GLOBAL.EDIT', 'Edit', 1));
        $this->setViewName('edit.php');
        $this->view->setData($Bean);
    }
    
    function doCancel($Request) {
        $itemId = $Request->get('id');
        if (!is_null($itemId)) {
            $Bean = $this->dao->getItem($Request->get('id'));
            $this->checkIn($Bean);
        }
        parent::doCancel("admin.php?com=plugin");
    }
    
    function doPublish($Request) {
        $direction = Filter::getAlphaNumeric($Request, 'direction', 'up');

        $Bean = $this->dao->getItem($Request->get('id'));
        
        $Bean->setPublished($direction == 'up' ? "1" : "0");
        
        if ($Request->get('is_new')) {
            $success = $this->dao->insert($Bean);
        }
        else {
            $success = $this->dao->update($Bean);
        }
        
        if ($success) {
            $this->_setMessage(
                'success',
                __('GLOBAL.SUCCESS', 'Success', 1),
                __('GLOBAL.SAVE_SUCCESS', 'Your changes were saved successfully.', 1)
            );
        }
        else {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('GLOBAL.CHANGES_NOT_SAVED', 'Your changes could not be saved.', 1)
            );
        }
        Utils::redirect("admin.php?com=plugin");
    }
    
    function doReorder($Request) {
        if ($this->dao->reorder($Request->get('id'), $Request->get('direction', 'down') )) {
            $this->_setMessage(
                'success',
                __('GLOBAL.SUCCESS', 'Success', 1),
                __('GLOBAL.SAVE_SUCCESS', 'Your changes were saved successfully.', 1)
            );
        }
        else {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('GLOBAL.CHANGES_NOT_SAVED', 'Your changes could not be saved.', 1)
            );
        }
        Utils::redirect("admin.php?com=plugin");
    }
    
    function doSave($Request) {

        $this->addValidation('name', 'notnull', 'PLUGIN.VALIDATE.NAME');
        $this->addValidation('content', 'notnull', 'PLUGIN.VALIDATE.CONTENT');
        
        $Bean = new Plugin($Request);
        $Bean->setContent(stripslashes($Bean->getContent()));
        $Bean->setDatafile(basename($Bean->getDatafile()));

        $XmlBean = PluginHelper::getBeanByKey($Request->get('id'));
        
        $Bean->setOrder($XmlBean->getOrder());
        $Bean->setIsCheckedOut("0");
        $Bean->setCheckedOutBy("");
        $Bean->setCheckedOutDate("");
        
        if ($this->validate($Request)) {
            if ($this->dao->insert($Bean)) {
                $this->checkIn($Bean);
                $this->_setMessage(
                    'success',
                    __('GLOBAL.SUCCESS', 'Success', 1),
                    __('GLOBAL.SAVE_SUCCESS', 'Your changes were saved successfully.', 1)
                );
                Utils::redirect("admin.php?com=plugin");
            }
            else {
                $this->_setMessage(
                    'error',
                    __('GLOBAL.ERROR', 'Error', 1),
                    __('GLOBAL.CHANGES_NOT_SAVED', 'Your changes could not be saved.', 1)
                );
                $this->showEditForm(new Plugin($Request));
            }
        }
        else {
            $this->showEditForm(new Plugin($Request));
        }
    }
    
    function doApply($Request) {

        $this->addValidation('name', 'notnull', 'PLUGIN.VALIDATE.NAME');
        $this->addValidation('content', 'notnull', 'PLUGIN.VALIDATE.CONTENT');
        
        $Bean = new Plugin($Request);
        $Bean->setContent(stripslashes($Bean->getContent()));
        $Bean->setDatafile(basename($Bean->getDatafile()));

        $XmlBean = PluginHelper::getBeanByKey($Request->get('id'));
        
        $Bean->setOrder($XmlBean->getOrder());
        $Bean->setIsCheckedOut("0");
        $Bean->setCheckedOutBy("");
        $Bean->setCheckedOutDate("");
        
        if ($this->validate($Request)) {
            if ($this->dao->insert($Bean)) {
                $this->checkIn($Bean);
                $this->_setMessage(
                    'success',
                    __('GLOBAL.SUCCESS', 'Success', 1),
                    __('GLOBAL.SAVE_SUCCESS', 'Your changes were saved successfully.', 1)
                );
            }
            else {
                $this->_setMessage(
                    'error',
                    __('GLOBAL.ERROR', 'Error', 1),
                    __('GLOBAL.CHANGES_NOT_SAVED', 'Your changes could not be saved.', 1)
                );
            }
        }
        $this->showEditForm(new Plugin($Request));
    }
    
    function doDelete($Request) {
        $itemId = $Request->get('id');
        if (!is_null($itemId)) {
            $Bean = $this->dao->getItem($Request->get('id'));
            $Bean->setId($Bean->getName());
            $this->checkIn($Bean);
        }
        parent::doDelete($Request, "admin.php?com=plugin");
    }

}