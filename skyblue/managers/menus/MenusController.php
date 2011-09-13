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
 
class MenusController extends Controller {

    var $dao;
    var $action;
    var $viewName;
    var $view_path;
    var $defaultViewName = 'list';

    function __construct($Request) {
        parent::__construct($Request);
        $this->setHandler('index',  'doIndex',   true);
        $this->setHandler('list',   'doIndex',   true);
        $this->setHandler('add',    'doAdd',     true);
        $this->setHandler('edit',   'doEdit',    true);
        $this->setHandler('save',   'doSave',    true);
        $this->setHandler('cancel', 'doCancel',  true);
        $this->setHandler('delete', 'doDelete',  true);
        $this->setViewPath(_SBC_SYS_ . 'managers/menus/views/');
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
    }
    
    function doAdd() {
        $this->showEditForm(new Menu(array(
            'id'      => Utils::getNextId($this->dao->index()),
            'title'   => "Untitled Menu",
            'type'    => 'menus',
            'objtype' => 'menus'
        )));
        $this->view->assign('is_new', true);
    }

    function doEdit() {
        $Bean = $this->dao->getItem(
            Filter::getNumeric($_GET, 'id')
        );
        
        if ($this->checkOut($Bean) == 1) {
            $this->showEditForm($Bean);
        }
        else {
            $this->_setMessage(
                'warning',
                __('GLOBAL.WARNING', 'Warning', 1),
                __("GLOBAL.CURRENTLY_CHECKED_OUT", 'The item could not be checked out because it is currently checked out by another user.', 1)
            );
            Utils::redirect("admin.php?com=menus");
        }
    }
    
    function showEditForm($Menu) {
        $this->setViewName('edit.php');
        $this->view->setData($Menu);
    }
    
    function doCancel($Request) {
        $itemId = $Request->get('id');
        if (!is_null($itemId)) {
            $this->checkIn(
                $this->dao->getItem($itemId)
            );
        }
        parent::doCancel("admin.php?com=menus");
    }
    
    function doDelete($Request) {
        $itemId = $Request->get('id');
        if (!is_null($itemId)) {
            $this->checkIn(
                $this->dao->getItem($itemId)
            );
        }
        parent::doDelete($Request, 'admin.php?com=menus');
    }
    
    function doSave($Request) {
        $this->addValidation('title', 'notnull', 'MENUS.VALIDATE.NAME');
        parent::doSave($Request, "admin.php?com=menus");
    }

}