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

class LinksController extends Controller {

    var $dao;
    var $action;
    var $viewName;
    var $view_path = 'managers/links/views/';
    var $defaultViewName = 'list';

    function __construct($Request) {
        parent::__construct($Request);
        $this->setHandler('index',       'doIndex',         true);
        $this->setHandler('list',        'doIndex',         true);
        $this->setHandler('add',         'doAdd',           true);
        $this->setHandler('edit',        'doEdit',          true);
        $this->setHandler('delete',      'doDelete',        true);
        $this->setHandler('save',        'doSave',          true);
        $this->setHandler('cancel',      'doCancel',        true);
        
        $this->setHandler('editgroup',    'doEditGroup',    true);
        $this->setHandler('addgroup',     'doAddGroup',     true);
        $this->setHandler('listgroups',   'doListGroups',   true);
        $this->setHandler('deletegroup',  'doDeleteGroup',  true);
        $this->setHandler('cancelgroups', 'doCancelGroups', true);
        $this->setHandler('save_group',   'doSaveGroup',    true);
        $this->updateModelAndView();
        $this->setViewPath(_SBC_APP_ . 'managers/links/views/');
    }
    
    function updateModelAndView() {
        $_groupmethods = array(
            'dolistgroups',
            'doaddgroup',
            'doeditgroup',
            'dosavegroup',
            'dodeletegroup',
            'docancelgroups'
        );        
        if (in_array($this->_getMethod(), $_groupmethods)) {
            $this->setDao(MVC::getDAO('linksgroups'));
            $Dao =& $this->getDao();
            $this->view->setDao($Dao);
        }
    }

    function doIndex() {
        
        $itemsPerPage = Filter::getNumeric(
            $this->getConfig(), 'items_per_page', 10
        );
        
        $allItems = $this->dao->index();
        
        $itemCount = count($allItems);
        $pageNum = Filter::getNumeric($_GET, 'pageNum', 1);
        $pageCount = ceil($itemCount/$itemsPerPage);
        
        $this->setViewName('list.php');
        $this->view->assign('pageNum', $pageNum);
        $this->view->assign('pageCount', $pageCount);
        $this->view->setData(Utils::paginate(
            $allItems, 
            $itemsPerPage, 
            $pageNum
        ));
    }

    function doAdd() {
        $this->showEditForm(new Link(array(
            'id'      => Utils::getNextId($this->dao->index()),
            'name'    => "Untitled Link",
            'type'    => 'links',
            'objtype' => 'links'
        )));
        $this->view->assign('is_new', true);
    }
    
    function doEdit() {
        $Bean = $this->dao->getItem(Filter::getNumeric($_GET, 'id'));
        if ($this->checkOut($Bean) == 1) {
            $this->showEditForm($Bean);
        }
        else {
            $this->_setMessage(
                'warning',
                __('GLOBAL.WARNING', 'Warning', 1),
                __("GLOBAL.CURRENTLY_CHECKED_OUT", 'The item could not be checked out because it is currently checked out by another user.', 1)
            );
            Utils::redirect("admin.php?com=links");
        }
    }
    
    function showEditForm($Link) {
        $this->setViewName('edit.php');
        $this->view->assign(
            'groups', 
            LinksHelper::getGroups(new LinksgroupsDAO)
        );
        $this->view->setData($Link);
    }
    
    function doEditGroup() {
        $Bean = $this->dao->getItem(Filter::getNumeric($_GET, 'id'));
        if ($this->checkOut($Bean) == 1) {
            $this->showEditGroupForm($Bean);
        }
        else {
            $this->_setMessage(
                'warning',
                __('GLOBAL.WARNING', 'Warning', 1),
                __("GLOBAL.CURRENTLY_CHECKED_OUT", 'The item could not be checked out because it is currently checked out by another user.', 1)
            );
            Utils::redirect("admin.php?com=links&action=listgroups");
        }
    }
    
    function doAddGroup() {
        $this->setViewName('edit.linksgroups.php');
        $this->showEditGroupForm(new Linksgroup(array(
            'name'      => 'New Group',
            'id'        => Utils::getNextId($this->dao->index()),
            'type'      => 'linksgroups',
            'objtype'   => 'linksgroups'
        )));
        $this->view->assign('is_new', true);
    }
    
    function showEditGroupForm($Linksgroup) {
        $this->setViewName('edit.linksgroups.php');
        $this->view->setData($Linksgroup);
    }
    
    function doListGroups() {
        
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
        
        $this->setViewName('list.linksgroups.php');
        $this->view->assign('pageNum', $pageNum);
        $this->view->assign('pageCount', $pageCount);
    }
    
    function doCancel($Request) {
        $itemId = $Request->get('id');
        if (!is_null($itemId)) {
            $this->checkIn(
                $this->dao->getItem($itemId)
            );
        }
        parent::doCancel("admin.php?com=links");
    }
    
    function doCancelGroups($Request) {
        $this->updateModelAndView();
        $itemId = $Request->get('id');
        if (!is_null($itemId)) {
            $this->checkIn(
                $this->dao->getItem($itemId)
            );
        }
        parent::doCancel("admin.php?com=links&action=listgroups");
    }
    
    function doSave($Request) {
        $this->addValidation('name', 'notnull', 'LINKS.VALIDATE.NAME');
        $this->addValidation('url', 'notnull', 'LINKS.VALIDATE.URL');
        $this->addValidation('groups', 'notnull', 'LINKS.VALIDATE.GROUPS');
        parent::doSave($Request, "admin.php?com=links");
    }
    
    function doSaveGroup($Request) {
        $this->addValidation('name', 'notnull', 'LINKS.VALIDATE.NAME');
        parent::doSave($Request, "admin.php?com=links&action=listgroups");
    }

    function doDelete($Request) {
        $itemId = $Request->get('id');
        if (!is_null($itemId)) {
            $this->checkIn(
                $this->dao->getItem($itemId)
            );
        }
        parent::doDelete($Request, "admin.php?com=links");
    }
    
    function doDeleteGroup($Request) {
        $itemId = $Request->get('id');
        if (!is_null($itemId)) {
            $this->checkIn(
                $this->dao->getItem($itemId)
            );
        }
        parent::doDelete($Request, "admin.php?com=links&action=listgroups");
    }
}