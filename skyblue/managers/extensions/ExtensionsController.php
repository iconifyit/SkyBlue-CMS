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

sb_conf('EXT_CONTEXT_SKINS',     'skins');
sb_conf('EXT_CONTEXT_FRAGMENTS', 'fragments');
sb_conf('EXT_CONTEXT_MANAGERS',  'managers');
sb_conf('EXT_CONTEXT_PLUGINS',   'plugins');

class ExtensionsController extends Controller {

    var $model;
    var $action;
    var $viewName;
    var $view_path;
    var $defaultViewName = 'dashboard';

    function __construct($Request) {
        parent::__construct($Request);
        
        $this->setDefaultAction('index');
        $this->setHandler('index', 'doIndex', true);
       
        $this->mimes = $this->getConfig('mimes');
        $this->targets = $this->getConfig('targets');
        
        $this->setHandler('cancel', 'doCancel', true);
        
        $this->setHandler('list_managers',   'doListManagers',   true);
        $this->setHandler('list_fragments',  'doListFragments',  true);
        $this->setHandler('list_skins',      'doListSkins',      true);
        $this->setHandler('list_plugins',    'doListPlugins',    true);
        
        $this->setHandler('edit_manager',    'doEdit', true);
        $this->setHandler('edit_fragment',   'doEdit', true);
        $this->setHandler('edit_skin',       'doEdit', true);
        $this->setHandler('edit_plugin',     'doEdit', true);
        
        $this->setHandler('save_managers_config',  'doSave', true);
        $this->setHandler('save_fragments_config', 'doSave', true);
        $this->setHandler('save_skins_config',     'doSave', true);
        $this->setHandler('save_plugins_config',   'doSave', true);
    }
    
    function doIndex($Request) {
        $this->view->isAjax = false;
        $this->setViewPath(_SBC_SYS_ . "managers/extensions/views/");
        $this->setViewName("dashboard.php");
    }
    
    function doCancel($Request) {
        
        $name = $Request->get('item_name');
        $context = $Request->get('context');

        if (trim($name) != "") {
            $Bean = ExtensionsHelper::getBean('extension', "{$context}_{$name}");
            $this->checkIn($Bean);
        }
        
        parent::doCancel("admin.php?com=extensions&context={$context}");
    }
    
    function initListView() {
        $this->setViewPath(_SBC_SYS_ . "managers/extensions/views/");
        $this->setViewName("list.php");
    }
    
    function initEditView() {
        $this->view->isAjax = false;
        $this->setViewPath(_SBC_SYS_ . "managers/extensions/views/");
        $this->setViewName("edit.php");
    }
    
    function doEdit($Request) {
    
        $this->initEditView();
        $this->view->assign('request', $Request);
        
        $Bean = $this->dao->getItem($Request->get('name'));
        
        if (! $Bean) {
            $Bean = new Extension($Request);
            $this->view->assign('is_new', '1');
        }
        
        if ($this->checkOut($Bean)) {
            $this->view->setData($Bean);
        }    
        else {
            $this->_setMessage(
                'warning',
                __('GLOBAL.WARNING', 'Warning', 1),
                __("GLOBAL.CURRENTLY_CHECKED_OUT", 'The item could not be checked out because it is currently checked out by another user.', 1)
            );
            Utils::redirect("admin.php?com=extensions&context={$context}");    
        }
    }
    
    function doSave($Request) {
        
        $Bean = new Extension($Request);
        
        $success = false;
        
        if (intval($Request->get('is_new')) == 1) {
            $success = $this->dao->insert($Bean);
        }
        else {
            $success = $this->dao->update($Bean);
        }
        
        if ($success) {
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
        Utils::redirect("admin.php?com=extensions&context={$context}");
    }
    
    function doListManagers($Request) {
        $this->initListView();
        $this->view->assign('context', EXT_CONTEXT_MANAGERS);
        $this->view->assign('task_edit_options', array('context'=> EXT_CONTEXT_MANAGERS, 'action'=>'edit_manager'));
        $this->view->setData(ExtensionsHelper::getManagersList());
    }
        
    function doListFragments($Request) {
        $this->initListView();
        $this->view->assign('context', EXT_CONTEXT_FRAGMENTS);
        $this->view->assign('task_edit_options', array('context'=> EXT_CONTEXT_FRAGMENTS, 'action'=>'edit_fragment'));
        $this->view->setData(ExtensionsHelper::getFragmentsList());
    }

    function doListSkins($Request) {
        $this->initListView();
        $this->view->assign('context', EXT_CONTEXT_SKINS);
        $this->view->assign('task_edit_options', array('context'=> EXT_CONTEXT_SKINS, 'action'=>'edit_skin'));
        $this->view->setData(ExtensionsHelper::getSkinsList());
    }
    
    function doListPlugins($Request) {
        $this->initListView();
        $this->view->assign('context', EXT_CONTEXT_PLUGINS);
        $this->view->assign('task_edit_options', array('context'=> EXT_CONTEXT_PLUGINS, 'action'=>'edit_plugin'));
        $this->view->setData(ExtensionsHelper::getPluginsList());
    }
    
}