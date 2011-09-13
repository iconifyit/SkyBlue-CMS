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

class SnippetsController extends Controller {

    var $dao;
    var $action;
    var $viewName;
    var $view_path = 'managers/snippets/views/';
    var $defaultViewName = 'list';

    function __construct($Request) {
        parent::__construct($Request);
        $this->setHandler('index',      'doIndex',      true);
        $this->setHandler('list',       'doIndex',      true);
        $this->setHandler('edit',       'doEdit',       true);
        $this->setHandler('add',        'doSelectType', true);
        $this->setHandler('changetype', 'doSelectType', true);
        $this->setHandler('next',       'doNext',       true);
        $this->setHandler('save',       'doSave',       true);
        $this->setHandler('apply',      'doApply',      true);
        $this->setHandler('delete',     'doDelete',     true);
        $this->setHandler('cancel',     'doCancel',     true);
        $this->setViewPath(_SBC_APP_ . 'managers/snippets/views/');
    }

    function doIndex() {
        $itemsPerPage = 10;
        
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
    }

    function doEdit($Request) {
        $Bean = $this->dao->getItem($Request->get('id'));
        $snippetType = Filter::noInjection($_REQUEST, 'snippetType');
        if (!empty($snippetType)) {
            $Bean->setSnippetType($snippetType);
        }
        
        if ($this->checkOut($Bean) == 1) {
            $this->showEditForm($Bean);
        }
        else {
            $this->_setMessage(
                'warning',
                __('GLOBAL.WARNING', 'Warning', 1),
                __("GLOBAL.CURRENTLY_CHECKED_OUT", 'The item could not be checked out because it is currently checked out by another user.', 1)
            );
            Utils::redirect("admin.php?com=snippets");
        }
    }
    
    function doAdd($Request) {
        $nextId = Utils::getNextId($this->dao->index());
        $this->showEditForm(new Snippet(array(
            'id' => $nextId,
            'name' => 'Untitled Snippet',
            'snippetType' => $Request->get('snippetType'),
            'datafile' => "snippet.{$nextId}.datafile"
        )));
        $this->view->assign('is_new', "1");
    }
    
    function showEditForm($Bean) {
        $Bean->setContent(stripslashes($Bean->getContent()));
        $this->view->assign('the_action', __('GLOBAL.EDIT', 'Edit', 1));
        $this->setViewName('edit.php');
        $this->view->setData($Bean);
    }
    
    function doSelectType($Request) {
        
        $isNewItem = '0';
        
        $this->setViewName('type.php');
        
        $Bean = $this->dao->getItem(
            Filter::noInjection($_REQUEST, 'id')
        );
        
        if (! is_object($Bean)) {
            $isNewItem = '1';
            $Bean = new Snippet(array(
                'id' => Utils::getNextId($this->dao->index()),
                'name' => 'Untitled Snippet',
                'snippetType' => Filter::noInjection($_GET, 'snippetType')
            ));
        }

        $this->view->setData($Bean);
        $this->view->assign('new_item', $isNewItem);
        $this->view->assign('is_new', $isNewItem);
    }
    
    function doNext($Request) {
        $snippetType = $Request->get('snippetType');
        if (trim($snippetType) == "") {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('GLOBAL.PLEASE_SELECT_TYPE', 'Please select a type', 1)
            );
            $this->doSelectType($Request);
        }
        else if ($Request->get('new_item') == '1') {
            $this->doAdd($Request);
        }
        else {
            $this->doEdit($Request);
        }
    }
    
    function doCancel($Request) {
        $itemId = $Request->get('id');
        if (!is_null($itemId)) {
            $this->checkIn(
                $this->dao->getItem($itemId)
            );
        }
        parent::doCancel("admin.php?com=snippets");
    }
    
    function doDelete($Request) {
        $itemId = $Request->get('id');
        if (!is_null($itemId)) {
            $this->checkIn(
                $this->dao->getItem($itemId)
            );
        }
        parent::doDelete($Request, "admin.php?com=snippets");
    }
    
    function doSave($Request) {
        
        $this->addValidation('name', 'notnull', 'SNIPPETS.VALIDATE.NAME');
        $this->addValidation('snippetType', 'notnull', 'SNIPPETS.VALIDATE.NAME');
        $this->addValidation('content', 'notnull', 'SNIPPETS.VALIDATE.CONTENT');
        
        $Bean = new Snippet($Request);
        $Bean->setContent(stripslashes($Bean->getContent()));
        $Bean->setDatafile(basename($Bean->getDatafile()));
        
        if ($this->validate($Request)) {
            if (intval($Request->get('new_item')) === 1) {
                $success = $this->dao->update($Bean);
            }
            else {
                $success = $this->dao->insert($Bean);
            }

            if ($success) {
                $this->_setMessage(
                    'success',
                    __('GLOBAL.SUCCESS', 'Success', 1),
                    __('GLOBAL.SAVE_SUCCESS', 'Your changes were saved successfully.', 1)
                );
                Utils::redirect("admin.php?com=snippets");
            }
            else {
                $this->_setMessage(
                    'error',
                    __('GLOBAL.ERROR', 'Error', 1),
                    __('GLOBAL.CHANGES_NOT_SAVED', 'Your changes could not be saved.', 1)
                );
                $this->showEditForm(new Snippet($Request));
            }
        }
        else {
            $this->showEditForm(new Snippet($Request));
        }
    }
    
    function doApply($Request) {
        
        $this->addValidation('name', 'notnull', 'SNIPPETS.VALIDATE.NAME');
        $this->addValidation('snippetType', 'notnull', 'SNIPPETS.VALIDATE.NAME');
        $this->addValidation('content', 'notnull', 'SNIPPETS.VALIDATE.CONTENT');
        
        $Bean = new Snippet($Request);
        $Bean->setContent(stripslashes($Bean->getContent()));
        $Bean->setDatafile(basename($Bean->getDatafile()));
        
        #  Core::Dump(array('apply', $Request));
        
        if ($this->validate($Request)) {
            if (strcasecmp($Request->get('new_item'), "1") === 0) {
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
        }
        $this->showEditForm(new Snippet($Request));
    }

}