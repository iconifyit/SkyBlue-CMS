<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version    2.0 2010-07-09 19:39:00 $
 * @package    SkyBlueCanvas
 * @copyright  Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license    GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */
 
/**
 * @author Scott Lewis
 * @date   July 09, 2010
 */
 
class ConfigurationController extends Controller {

    var $model;
    var $action;
    var $viewName;
    var $view_path;
    var $defaultViewName = 'edit';

    function __construct($Request) {
        parent::__construct($Request);
        $this->setDefaultAction('edit');
        $this->setHandler('index',  'doEdit',    true);
        $this->setHandler('edit',   'doEdit',    true);
        $this->setHandler('save',   'doSave',    true);
        $this->setHandler('cancel', 'doCancel',  true);
        // $this->setHandler('delete', 'doDelete',  true);
        $this->setHandler('clear_cache', 'doClearCache', true);
        $this->setViewPath(_SBC_SYS_ . 'managers/configuration/views/');
    }
    
    function doClearCache($Request) {
        $Cache = new Cache("");
        if ($Cache->clearAll()) {
            $this->_setMessage(
                'success',
                __('GLOBAL.SUCCESS', 'Success', 1),
                __('CONFIG.CACHE_CLEARED', 'The cache has been cleared.', 1)
            );
        }
        else {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('CONFIG.CACHE_NOT_CLEARED', 'The cache could not be cleared.', 1)
            );
        }
        Utils::redirect("admin.php?com=configuration");
    }

    function doEdit($Request) {
        $this->setViewName('edit.php');
        $Bean = $this->dao->getItem();
        
        if ($this->checkOut($Bean) == 1) {
            $this->view->setData($Bean);
        }
        else {
            $this->_setMessage(
                'warning',
                __('GLOBAL.WARNING', 'Warning', 1),
                __("GLOBAL.CURRENTLY_CHECKED_OUT", 'The item could not be checked out because it is currently checked out by another user.', 1)
            );
            Utils::redirect("admin.php?com=page");
        }
    }
    
    function doCancel($Request) {
        $this->checkIn($this->dao->getItem(1));
        parent::doCancel("admin.php?com=settings");
    }
    
    /* Configuration cannot be deleted */
    function doDelete() { return false; }
    
    function doSave($Request) {
        $this->addValidation('site_url', 'notnull', 'CONFIG.VALIDATE.SITEURL');
        if (! $Request->get('use_cache')) {
            $Cache = new Cache("");
            $Cache->clearAll();
        }
        parent::doSave($Request, "admin.php?com=configuration");
    }

}