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

class ContactsController extends Controller {

    var $dao;
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
        $this->setViewPath(_SBC_SYS_ . 'managers/contacts/views/');
    }

    function doIndex($Request) {
        $this->setViewName('list.php');
        $this->view->setData($this->dao->index());
    }
    
    function doAdd($Request) {
        $this->showEditForm(new Contact(array(
            'id'      => Utils::getNextId($this->dao->index()),
            'name'    => "Untitled Contact",
            'type'    => 'contacts',
            'objtype' => 'contact'
        )));
        $this->view->assign('is_new', true);
    }

    function doEdit($Request) {
        $Bean = $this->dao->getItem(Filter::getNumeric($_GET, 'id'));
        if ($this->checkOut($Bean)) {
            $this->showEditForm($Bean);
        }
        else {
            $this->_setMessage(
                'warning',
                __('GLOBAL.WARNING', 'Warning', 1),
                __("GLOBAL.CURRENTLY_CHECKED_OUT", 'The item could not be checked out because it is currently checked out by another user.', 1)
            );
            Utils::redirect("admin.php?com=contacts");
        }
    }
    
    function doCancel($Request) {
        $itemId = $Request->get('id');
        if (!is_null($itemId)) {
            $this->checkIn(
                $this->dao->getItem($itemId)
            );
        }
        parent::doCancel("admin.php?com=contacts");
    }
    
    function doSave($Request) {
        $this->addValidation('name', 'notnull', 'CONTACTS.VALIDATE.SITEURL');
        $this->addValidation('email', 'email', 'CONTACTS.VALIDATE.EMAIL');
        parent::doSave($Request, "admin.php?com=contacts");
    }
    
    function showEditForm($Contact) {
        $this->setViewName('edit.php');
        $this->view->assign(
            'states', 
            Filter::get(
                $this->config,
                'states'
        ));
        $this->view->setData($Contact);
    }
    
    function doDelete($Request) {
        $itemId = $Request->get('id');
        if (! is_null($itemId)) {
            $this->checkIn(
                $this->dao->getItem($itemId)
            );
        }
        parent::doDelete($Request, "admin.php?com=contacts");
    }
}