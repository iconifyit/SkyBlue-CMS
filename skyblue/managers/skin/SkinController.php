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

class SkinController extends Controller {

    var $model;
    var $action;
    var $activeskin;
    var $viewName;
    var $pages;
    var $view_path;
    var $defaultViewName = 'dashboard';

    function __construct($Request) {
        parent::__construct($Request);
        $this->setHandler('index',    'doIndex',    true);
        $this->setHandler('list',     'doIndex',    true);
        $this->setHandler('cancel',   'doIndex',   true);
        $this->setHandler('publish',  'doActivate', true);
        $this->setActiveSkin(SkinHelper::getActiveSkin());
        $this->setPages(SkinHelper::getPages());
        $this->setViewPath(_SBC_SYS_ . "managers/skin/views/");
    }
    
    function display() {
        $this->view->assign('activeskin', $this->getActiveSkin());
        parent::display();
    }

    function doIndex($Request) {
        
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
        
        $this->setViewName('dashboard.php');
        $this->view->assign('pageNum', $pageNum);
        $this->view->assign('pageCount', $pageCount);
    }
    
    /**
     * TODO: Update this method to work with DB implementations
     */
    function doActivate($Request) {
        if ($this->dao->setActiveSkin($Request->get('id'))) {
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
        Utils::redirect("admin.php?com=skin");
    }
    
    function doCancel($Request) {
        parent::doCancel("admin.php?com=skin");
    }
    
    function getActiveSkin() {
        return $this->activeskin;
    }

    function setActiveSkin($activeskin) {
        $this->view->assign('activeSkin', $activeskin);
        $this->activeskin = $activeskin;
    }
    
    function _showLayoutMap($skin_name, $old_diff, $new_diff) {
        $this->view->assign('old_layouts', $old_diff);
        $this->view->assign('new_layouts', $new_diff);
        $this->view->assign('skin_name',   $skin_name);
        $this->setViewName('layout.map');
    }
    
    function setPages($pages) {
        $this->pages = $pages;
    }
    
    function getPages() {
        return $this->pages;
    }
    
    function _getOldLayouts() {
        $pages = $this->getPages();
        $layouts = array();
        foreach ($pages as $page) {
            if (!in_array($page->pagetype, $layouts)) {
                array_push($layouts, $page->pagetype);
            }
        }
        return $layouts;
    }
    
    function _getNewLayouts($skin_name) {
        $layouts = array();
        $files = FileSystem::list_files(_SBC_WWW_ . SB_SKINS_DIR . "$skin_name/");
        for ($i=0; $i<count($files); $i++) {
            $file = basename($files[$i]);
            if (substr($file, 0, 4) == 'skin') {
                array_push($layouts, str_replace(array('skin.', '.html'), null, $file));
            }
        }
        return $layouts;
    }
    
    function setViewName($viewName) {
        $this->viewName = $viewName;
    }
    
    function getViewName() {
        return $this->viewName;
    }
    
    function _getLayoutTypesDiff($skin_name) {
        $old_layouts = $this->_getOldLayouts();
        $new_layouts = $this->_getNewLayouts($skin_name);
        
        $old_diff = array();
        $new_diff = array();
        
        for ($i=0; $i<count($old_layouts); $i++) {
            if (!in_array($old_layouts[$i], $new_layouts)) {
                array_push($old_diff, $old_layouts[$i]);
            }
        }
        
        for ($i=0; $i<count($new_layouts); $i++) {
            array_push($new_diff, $new_layouts[$i]);
        }
        return array($old_diff, $new_diff);
    }

}