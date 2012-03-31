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
class UsersController extends Controller {

    var $model;
    var $action;
    var $viewName;
    var $view_path;
    var $defaultViewName = 'list';

    function __construct($Request) {
        parent::__construct($Request);
        $this->setHandler('index',       'doListUsers',  true);
        $this->setHandler('list',        'doListUsers',  true);
        $this->setHandler('add',         'doAdd',        true);
        $this->setHandler('edit',        'doEdit',       true);
        $this->setHandler('save',        'doSave',       true);
        $this->setHandler('cancel',      'doCancel',     true);
        $this->setHandler('delete',      'doDelete',     true);
        
        $this->setHandler('listgroups',  'doListGroups',  true);
        $this->setHandler('editgroup',   'doEditGroup',   true);
        $this->setHandler('addgroup',    'doAddGroup',    true);
        $this->setHandler('save_group',  'doSaveGroup',   true);
        $this->setHandler('deletegroup', 'doDeleteGroup', true);
        $this->setHandler('cancelgroup', 'doCancelGroup', true);
        
        $this->updateModelAndView();
        
        $this->setViewPath(_SBC_SYS_ . "managers/users/views/");
    }
    
    function updateModelAndView() {
        $_groupmethods = array(
            'dolistgroups',
            'doaddgroup',
            'doeditgroup',
            'dodeletegroup',
            'dosavegroup',
            'docancelgroup'
        );        
        if (in_array($this->_getMethod(), $_groupmethods)) {
            $this->setDao(MVC::getDao('usergroups'));
            $Dao =& $this->getDao();
            $this->view->setDao($Dao);
        }
    }
    
    function doListUsers() {
        $this->setViewName('list.php');
        $this->doIndex();
    }
    
    function doListGroups() {
        $this->setViewName('list.usergroups.php');
        $this->doIndex();
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
        
        $this->view->assign('pageNum', $pageNum);
        $this->view->assign('pageCount', $pageCount);
    }
 
    function doAdd() {
        $this->showEditForm(new User(array(
            'username' => 'new_user',
            'name'     => 'New User',
            'id'       => Utils::getNextId($this->dao->index()),
            'type'     => 'users',
            'objtype'  => 'users'
        )));
        $this->view->assign('is_new', true);
    }
    
    function doEdit() {
        $Bean = $this->dao->getItem(
            Filter::getAlphaNumeric($_GET, 'id')
        );
        if ($this->checkOut($Bean)) {
            $this->showEditForm($Bean);
        }
        else {
            $this->_setMessage(
                'warning',
                __('GLOBAL.WARNING', 'Warning', 1),
                __("GLOBAL.CURRENTLY_CHECKED_OUT", 'The item could not be checked out because it is currently checked out by another user.', 1)
            );
            Utils::redirect("admin.php?com=users");
        }
    }
    
    function showEditForm($Bean) {
        $this->setViewName('edit.php');
        $this->view->setData($Bean);
        $this->view->assign(
            'groups', 
            UsersHelper::getUsergroups(new UsergroupsDAO())
        );
    }
    
    function doAddGroup() {
        $this->showEditGroupForm(new Usergroup(array(
            'name'      => 'New Group',
            'id'        => Utils::getNextId($this->dao->index()),
            'siteadmin' => 0,
            'type'      => 'usergroups',
            'objtype'   => 'usergroups'
        )));
        $this->view->assign('is_new', true);
    }

    function doEditGroup() {
        $Bean = $this->dao->getItem(
            Filter::getAlphaNumeric($_GET, 'id')
        );
        if ($this->checkOut($Bean)) {
            $this->showEditGroupForm($Bean);
        }
        else {
            $this->_setMessage(
                'warning',
                __('GLOBAL.WARNING', 'Warning', 1),
                __("GLOBAL.CURRENTLY_CHECKED_OUT", 'The item could not be checked out because it is currently checked out by another user.', 1)
            );
            Utils::redirect("admin.php?com=users&action=listgroups");
        }
    }
    
    function showEditGroupForm($Bean) {
        $this->setViewName('edit.usergroups.php');
        $this->view->setData($Bean);
    }
    
    function doCancel($Request) {
        $itemId = $Request->get('id');
        if (! is_null($itemId)) {
            $this->checkIn(
                $this->dao->getItem($itemId)
            );
        }
        parent::doCancel("admin.php?com=users");
    }
    
    function doCancelGroup($Request) {
        $itemId = $Request->get('id');
        if (! is_null($itemId)) {
            $this->checkIn(
                $this->dao->getItem($itemId)
            );
        }
        parent::doCancel("admin.php?com=users&action=listgroups");
    }
    
    function validateUser($Request) {
        if (! parent::validate($Request)) return false;

        $errors = array();
        
        if ($Request->get('password') != $Request->get('confirm-password')) {
            array_push(
                $errors, 
                __('USERS.VALIDATE.MISMATCH', 'Your password and password confirmation do not match', 1)
            );
        }
        
        if (count($errors) == 0) return true;
        $this->_setMessage(
            'error',
            __('GLOBAL.ERROR', 'Error', 1),
            $errors
        );
        return false;
    }
    
    function doSave($Request) {
        
        $User = $this->dao->getByKey('username', $Request->get('username'));
        
        $this->addValidation('username', 'notnull', 'USERS.VALIDATE.USERNAME');
        $this->addValidation('email', 'email', 'USERS.VALIDATE.EMAIL');
        
        /**
         * If this is a new User, make sure we have a password
         */
        if (empty($User)) {
            $this->addValidation('password', 'notnull', 'USERS.VALIDATE.PASSWORD');
        }
        
        $this->addValidation('groups', 'notnull', 'USERS.VALIDATE.GROUPS');

        if ($this->validateUser($Request)) {
            
            Utils::fingerprint($Request->get('password'));
            
            $password = $Request->get('password');
            if (empty($password)) {
                $password = $User->getPassword();
            }
            else {
                if (! UsersHelper::validPassword($password)) {
                    $this->_setMessage(
                        'error',
                        __('GLOBAL.ERROR', 'Error', 1),
                        __('USERS.MSG.PASSWORD_RULES', 'Your password must contain XXX and must be X-XX characters long.', 1)
                    );
                    $this->showEditForm(new User($Request));
                }
                $password = Utils::fingerprint($password);
            }
            
            $newUser = new User($Request);
            $newUser->setPassword($password);
            
            if ($Request->get('is_new')) {
                $success = $this->dao->insert($newUser);
            }
            else {
                $success = $this->dao->update($newUser);
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
            Utils::redirect("admin.php?com=users");
        }
        else {
            $this->showEditForm(new User($Request));
        }
    }
    
    function doSaveGroup($Request) {
        $this->addValidation('name', 'notnull', 'USERS.VALIDATE.NAME');
        parent::doSave($Request, "admin.php?com=users&action=listgroups");
    }
    
    function doDelete($Request) {
//        if (intval($Request->get('id')) === 0) {
//            $this->_setMessage(
//                'error',
//                __('GLOBAL.ERROR', 'Error', 1),
//                __('USERS.DEFAULTUSERREQUIRED', 'You cannot delete the default user', 1)
//            );
//            Utils::redirect("admin.php?com=users");
//        }
//        else if (intval($Request->get('id')) === 1) {
//            $this->_setMessage(
//                'error',
//                __('GLOBAL.ERROR', 'Error', 1),
//                __('USERS.ADMINUSERREQUIRED', 'You cannot delete the Admin user', 1)
//            );
//            Utils::redirect("admin.php?com=users");
//        }
//        else if (count($this->dao->index()) == 1) {
//            $this->_setMessage(
//                'error',
//                __('GLOBAL.ERROR', 'Error', 1),
//                __('USERS.ONEUSERREQUIRED', 'You must have at least one User', 1)
//            );
//            Utils::redirect("admin.php?com=users");
//        }
//        else {
//            parent::doDelete($Request, "admin.php?com=users");
//        }
        if (intval($Request->get('id')) === 1) {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('USERS.DEFAULTUSERREQUIRED', 'The Anonymous User cannot be deleted', 1)
            );
            Utils::redirect("admin.php?com=users");
        }
        else if (intval($Request->get('id')) === 2) {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('USERS.ADMINUSERREQUIRED', 'The Admin User cannot be deleted', 1)
            );
            Utils::redirect("admin.php?com=users");
        }
        else if (count($this->dao->index()) === 1) {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('USERS.ONEUSERREQUIRED', 'You must have at least one User', 1)
            );
            Utils::redirect("admin.php?com=users");
        }
        else {
            parent::doDelete($Request, "admin.php?com=users");
        }
    }
    
    function doDeleteGroup($Request) {
        if (count($this->dao->index()) > 1) {
            parent::doDelete($Request, "admin.php?com=users&action=listgroups");
        }
        else {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('USERS.ONEGROUPREQUIRED', 'You must have at least one Usergroup', 1)
            );
            Utils::redirect("admin.php?com=users&action=listgroups");
        }
    }

}