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

class MetaController extends Controller {

    var $model;
    var $action;
    var $viewName;
    var $view_path;
    var $defaultViewName = 'list';

    function __construct($Request) {
        parent::__construct($Request);
        $this->setHandler('index',  'doIndex',  true);
        $this->setHandler('add',    'doAdd',    true);
        $this->setHandler('edit',   'doEdit',   true);
        $this->setHandler('save',   'doSave',   true);
        $this->setHandler('cancel', 'doCancel', true);
        $this->setHandler('delete', 'doDelete', true);
        
        $this->setHandler('editgroup',   'doEditGroup',   true);
        $this->setHandler('addgroup',    'doAddGroup',    true);
        $this->setHandler('listgroups',  'doListGroups',  true);
        $this->setHandler('savegroup',   'doSaveGroup',   true);
        $this->setHandler('deletegroup', 'doDeleteGroup', true);
        $this->setHandler('cancelgroup', 'doCancelGroup', true);
        
        $this->setViewPath(_SBC_SYS_ . 'managers/meta/views/');

        $this->updateModelAndView();
    }
    
    function updateModelAndView() {
        $_groupmethods = array(
            'dolistgroups',
            'doaddgroup',
            'doeditgroup',
            'docancelgroup',
            'dodeletegroup',
            'dosavegroup'
        );
        if (in_array($this->_getMethod(), $_groupmethods)) {
            $this->setDao(MVC::getDAO('metagroups'));
            $Dao =& $this->getDao();
            $this->view->setDao($Dao);
        }
    }

    function doIndex() {
        $this->view->setData($this->dao->index());
        $this->setViewName('list.php');
    }
    
    function showEditForm($Meta) {
        $this->view->setData($Meta);
        $this->setViewName('edit.php');
        $this->view->assign(
            'groups', 
            MetaHelper::getGroups(new MetagroupsDAO)
        );
    }
    
    function showEditGroupForm($Metagroup) {
        $this->view->setData($Metagroup);
        $this->setViewName("edit.metagroups.php");
    }

    function doAdd() {
        $this->showEditForm(new Meta(array(
            'id'      => Utils::getNextId($this->dao->index()),
            'name'    => "Untitled Meta Tag",
            'type'    => 'meta',
            'objtype' => 'meta'
        )));
        $this->view->assign('is_new', true);
    }
    
    function doEdit($Request) {
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
            Utils::redirect("admin.php?com=meta");
        }
    }
    
    function doEditGroup($Request) {
        $Bean = $this->dao->getItem($Request->get('id'));
        if ($this->checkOut($Bean) == 1) {
            $this->showEditGroupForm($Bean);
        }
        else {
            $this->_setMessage(
                'warning',
                __('GLOBAL.WARNING', 'Warning', 1),
                __("GLOBAL.CURRENTLY_CHECKED_OUT", 'The item could not be checked out because it is currently checked out by another user.', 1)
            );
            Utils::redirect("admin.php?com=meta&action=listgroups");
        }
    }
    
    function doListGroups() {
        $this->setViewName('list.metagroups.php');
        $this->view->setData($this->dao->index());
    }
    
    function doAddGroup() {
        $this->showEditGroupForm(new Metagroup(array(
            'name'      => 'New Group',
            'id'        => Utils::getNextId($this->dao->index()),
            'type'      => 'metagroups',
            'objtype'   => 'metagroup'
        )));
        $this->view->assign('is_new', true);
    }
    
    function doCancel($Request) {
        $itemId = $Request->get('id');
        if (!is_null($itemId)) {
            $this->checkIn(
                $this->dao->getItem($itemId)
            );
        }
        parent::doCancel("admin.php?com=meta");
    }
    
    function doCancelGroup() {
        parent::doCancel("admin.php?com=meta&action=listgroups");
    }
    
    function doSave($Request) {
        $this->addValidation('name', 'notnull', 'META.VALIDATE.NAME');
        $this->addValidation('groups', 'notnull', 'META.VALIDATE.GROUPS');
        parent::doSave($Request, "admin.php?com=meta");
    }
    
    function doSaveGroup($Request) {
        $this->addValidation('name', 'notnull', 'META.VALIDATE.NAME');
        parent::doSave($Request, "admin.php?com=meta&action=listgroups");
    }
    
    function doDelete($Request) {
        $itemId = $Request->get('id');
        if (! is_null($itemId)) {
            $this->checkIn(
                $this->dao->getItem($itemId)
            );
        }
        parent::doDelete($Request, "admin.php?com=meta");
    }
    
    function doDeleteGroup($Request) {
        $itemId = $Request->get('id');
        if (! is_null($itemId)) {
            $this->checkIn(
                $this->dao->getItem($itemId)
            );
        }
        parent::doDelete($Request, "admin.php?com=meta&action=listgroups");
    }

}