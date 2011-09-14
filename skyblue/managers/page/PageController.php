<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2010-07-08 21:30:00 $
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

class PageController extends Controller {

    var $dao;
    var $action;
    var $viewName;
    var $view_path;
    var $defaultViewName = 'list';

    function __construct($Request) {

        parent::__construct($Request);
        
        $this->setDefaultAction($this->getDefaultAction());

        $this->setHandler('view',             'doView',          true);
        $this->setHandler('list',             'doList',          true);
        $this->setHandler('edit',             'doEdit',          true);
        $this->setHandler('add',              'doAdd',           true);
        $this->setHandler('delete',           'doDelete',        true);
        $this->setHandler('save',             'doSave',          true);
        $this->setHandler('apply',            'doApply',         true);
        $this->setHandler('publish',          'doPublish',       true);
        $this->setHandler('copy',             'doCopy',          true);
        $this->setHandler('cancel',           'doCancel',        true);
        $this->setHandler('reorder',          'doOrder',         true);
        $this->setHandler('sitemap',          'doSitemap',       true);
        $this->setHandler('ajax_create',      'doAjaxCreate',    true);
        $this->setHandler('ajax_rename',      'doAjaxRename',    true);
        $this->setHandler('ajax_delete',      'doAjaxDelete',    true);
        $this->setHandler('ajax_publish',     'doAjaxPublish',   true);
        $this->setHandler('get_scripts',      'doGetScripts',    true);
        $this->setHandler('ajax_update_tree', 'doUpdateTree',    true);
        $this->setHandler('ajax_change_nav',  'doAjaxChangeNav', true);
    }
    
    function getDefaultAction() {
        return get_constant('_ADMIN_') ? 'list' : 'view' ;
    }
    
    function doGetScripts($Request) {
        if (! is_admin_page()) Utils::redirect("admin.php?com=login&action=logout");
        $this->view->isAjax = true;
        $this->setViewName('response.php');
        $this->view->assign(
            'body', 
            FileSystem::buffer(SB_MANAGERS_DIR . 'page/views/scripts.php')
        );
    }
    
    function doView() {
    
        global $Core;
        global $Router;
        
        $Cache = null;
        $html  = null;
        
        $Core->DefineDefaultPage();
        
        /**
         * If the requested page is not found, change the HTTP Response Header to 404, 
         * and re-set the request 'pid' to display the 404 page.
         */
        
        if ($this->isNotFound()) {
            $this->setHttpResponse(HTTP_404_NOT_FOUND);
            $this->request->set('route', $this->getErrorPageRoute());
            $this->request->set('pid', $this->getPageId());
            $_GET['pid'] = $this->getPageId();
        }

        /**
         * If Caching is turned on, initialize the Cache
         */
        
        if (Config::get('use_cache', 0)) {
            $Cache = new Cache($Router->getFingerprint(), 60);
        }
        
        /**
         * If Caching is turned on and the page is cached 
         * set the Page->html to the Cached page content.
         */

        if (Config::get('use_cache', 0) && $Cache->isCached()) {
            echo $Cache->getCache();
            @ob_flush();
            die("\n<!-- Elapsed time: " . Timer::elapsed('frontend.load') . "-->");
        }
        else {
            $this->setViewName($this->getViewName());
        }

        /**
         * If Caching is enbaled, save the current page to Cache.
         */
        if (Config::get('use_cache', 0)) {
            ob_start();
            $this->display();
            $buffer = ob_get_contents();
            ob_end_clean();
            $Cache->saveCache($buffer . "\n<!--cached on: " . date(DATE_RFC822) . "-->");
        }
    }
    
    function doSitemap($Request) {
        $this->setDefaultAction("sitemap");
        $this->setViewName('sitemap');
        $this->view->setData($this->dao->index());
        $this->view->setView($this->getViewName());
    }
    
    function doList() {
        if (! is_admin_page()) Utils::redirect("admin.php?com=login&action=logout");
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
        $this->view->assign('itemsPerPage', $itemsPerPage);
        $this->view->setData(Utils::paginate(
            $allItems, 
            $itemsPerPage, 
            $pageNum
        ));
        add_script('page_actions', 'admin.php?com=page&action=get_scripts&ts=' . time());
    }
    
    function doAjaxCreate($Request) {
        $this->view->isAjax = true;
        $this->setViewName('response.php');
        
        $name      = Filter::get($Request, 'name');
        $nodeId    = Filter::get($Request, 'nodeId');
        
        $Page = new Page(array(
            'name' => trim($name)
        ));
        $Page->setModified(date(SB_DATE_MODIFIED_FORMAT,time()));
        $Page->setType('page');
        $Page->setObjtype('page');
        $Page->setPublished(0);
        
        $permalink = PageHelper::getUniquePermalink(
            $Page, PageHelper::getPermalink($Page)
        );
        if ($permalink{0} == "-") {
            $permalink = substr($permalink, 1);
        }
        if ($permalink{strlen($permalink)-1} == "-") {
            $permalink = substr($permalink, 0, -1);
        }
        $Page->setPermalink($permalink);
        
        $result    = $this->dao->insert($Page);
        $pageId    = $this->dao->lastInsertId();
        $permalink = $Page->getPermalink();
        
        $message = base64_encode(HtmlUtils::formatMessage(new Message(array(
            'type' => $result ? 'success' : 'error',
            'title' => $result ? 'Success' : 'Error',
            'message' => $result ? "The page '{$name}' has been created" : 'The page could not be created'
        ))));
        
        $this->view->assign('body', "{pageId:'$pageId', nodeId:'$nodeId', message:'$message', permalink:'$permalink'}");
    }
    
    function doAjaxRename($Request) {
        if (! is_admin_page()) Utils::redirect("admin.php?com=login&action=logout");
        $this->view->isAjax = true;
        $this->setViewName('response.php');

        $Page = $this->dao->getItem(Filter::getAlphaNumeric($Request, 'id'));
        
        if ($this->checkOut($Page) == 1) {
            
            if ($newName = Filter::get($Request, 'name')) {
                $Page->setName($newName);
            }
            
            if ($this->dao->update($Page) && $this->saveStructure($Request)) {
                $this->checkIn($Page);
                $result  = true;
                $message = __('GLOBAL.SAVE_SUCCESS', 'Your changes were saved successfully.', 1);
            }
            else {
                $result  = false;
                $message = __('GLOBAL.CHANGES_NOT_SAVED', 'Your changes could not be saved.', 1);
            }
        }
        else {
            $result  = false;
            $message = __("GLOBAL.CURRENTLY_CHECKED_OUT", 'The page could not be checked out because it is currently checked out by another user.', 1);
        }
        
        $message = base64_encode(HtmlUtils::formatMessage(new Message(array(
            'type' => $result ? 'success' : 'error',
            'title' => $result ? 'Success' : 'Error',
            'message' => $message
        ))));
        
        $this->view->assign('body', "{message:'$message'}");
    }
    
    function doSaveStructure($Request) {
        $this->view->isAjax = true;
        $this->setViewName('response.php');
        $this->view->assign('body', $this->saveStructure($Request));
    }
    
    function doAjaxDelete($Request) {
        if (! is_admin_page()) Utils::redirect("admin.php?com=login&action=logout");
        
        $this->view->isAjax = true;
        $this->setViewName('response.php');
        
        $itemId = $Request->get('id');
        $nodeId = $Request->get('nodeId');
        $Page   = $this->dao->getItem($itemId);
        
        if ($this->checkOut($Page) == 1) {
            if ($Page->getIsdefault()) {
                $this->checkIn($Page);
                $message = base64_encode(HtmlUtils::formatMessage(new Message(array(
                    'type'    => 'error',
                    'title'   => __('GLOBAL.ERROR', 'Error', 1),
                    'message' => __('PAGE.NO_DELETE_DEFAULT_PAGE', 'The page you tried to delete is set as your home (default) page. The home page cannot be deleted. If you want to delete this page you must first set another page as your default page, then delete this one.', 1)
                ))));
                $this->view->assign('body', "{message:'$message',result: false}");
            }
            else {
                $result = $this->dao->delete($itemId);
                $message = base64_encode(HtmlUtils::formatMessage(new Message(array(
                    'type'    => $result ? 'success' : 'error',
                    'title'   => $result ? 'Success' : 'Error',
                    'message' => $result ? 'The page has been deleted' : 'The page could not be deleted'
                ))));
                $result ? "true" : "false" ;
                $this->view->assign('body', "{message:'$message',result: $result, nodeId:'$nodeId'}");
            }
        }
    }
    
    function doAjaxPublish($Request) {
        if (! is_admin_page()) Utils::redirect("admin.php?com=login&action=logout");
        $this->view->isAjax = true;
        $this->setViewName('response.php');

        $Page = $this->dao->getItem(Filter::getAlphaNumeric($Request, 'id'));

        if ($this->checkOut($Page) == 1) {
        
            $direction = Filter::getAlphaNumeric($Request, 'direction', 'up');
            
            if ($direction == 'down' && ($Page->getIsdefault() || $this->dao->countItems() == 1)) {
                $result  = false;
                $message = __("GLOBAL.CANNOT_UNPUBLISH_HOMEPAGE", 'The Home Page cannot be un-published.', 1);
            }
            else {
                $Page->setPublished($direction == 'up' ? 1 : 0);
                if ($this->dao->publish($Page) && $this->saveStructure($Request)) {
                    $this->checkIn($Page);
                    $result  = true;
                    $message = __('GLOBAL.SAVE_SUCCESS', 'Your changes were saved successfully.', 1);
                }
                else {
                    $result  = false;
                    $message = __('GLOBAL.CHANGES_NOT_SAVED', 'Your changes could not be saved.', 1);
                }
            }
        }
        else {
            $result  = false;
            $message = __("GLOBAL.CURRENTLY_CHECKED_OUT", 'The page could not be checked out because it is currently checked out by another user.', 1);
        }
        $this->view->assign('body', HtmlUtils::formatMessage(new Message(array(
            'type' => $result ? 'success' : 'error',
            'title' => $result ? 'Success' : 'Error',
            'message' => $message
        ))));
    }
    
    function doAjaxChangeNav($Request) {
        if (! is_admin_page()) Utils::redirect("admin.php?com=login&action=logout");
        $this->view->isAjax = true;
        $this->setViewName('response.php');

        $Page = $this->dao->getItem(Filter::getAlphaNumeric($Request, 'id'));

        if ($this->checkOut($Page) == 1) {
            $Page->setShow_in_navigation(Filter::getAlphaNumeric($Request, 'show_in_navigation', 1));
            if ($this->dao->updateShowInNavigation($Page) && $this->saveStructure($Request)) {
                $this->checkIn($Page);
                $result  = true;
                $message = __('GLOBAL.SAVE_SUCCESS', 'Your changes were saved successfully.', 1);
            }
            else {
                $result  = false;
                $message = __('GLOBAL.CHANGES_NOT_SAVED', 'Your changes could not be saved.', 1);
            }
        }
        else {
            $result  = false;
            $message = __("GLOBAL.CURRENTLY_CHECKED_OUT", 'The page could not be checked out because it is currently checked out by another user.', 1);
        }
        $this->view->assign('body', HtmlUtils::formatMessage(new Message(array(
            'type' => $result ? 'success' : 'error',
            'title' => $result ? 'Success' : 'Error',
            'message' => $message
        ))));
    }
    
    function saveStructure($Request) {
        $tree = Filter::getRaw($Request, 'tree');
        $xml = PageHelper::html2xml($tree);
        try {
            $DaoClass = ucwords(DB_TYPE) . "DAO";
            $Dao = new $DaoClass(array(
                'type' => 'structure', 
                'bean_class' => 'Structure'
            ));
            if ($Dao->query("UPDATE Structure set structure='{$xml}' where site_id = 'sbc'")) {
                $result = 1;
            }
            else {
                $result = 0;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
        return $result;
    }
    
    function doUpdateTree($Request) {
        $this->view->isAjax = true;
        $this->setViewName('response.php');
        
        $result = $this->saveStructure($Request);

        $this->view->assign('body', HtmlUtils::formatMessage(new Message(array(
            'type' => $result ? 'success' : 'error',
            'title' => $result ? 'Success' : 'Error',
            'message' => $result ? "The page has been created" : 'The page could not be created'
        ))));
    }
    
    function doAdd() {
        if (! is_admin_page()) Utils::redirect("admin.php?com=login&action=logout");
        $newPageId = Utils::getNextId($this->dao->index());
        $this->showEditForm(new Page(array(
            'id'   => $newPageId,
            'name' => 'Untitled Page',
            'story' => "page.page.{$newPageId}.txt"
        )));
        $this->view->assign('is_new', true);
    }
    
    function doEdit($Request) {
        global $Authenticate;
        if (! is_admin_page()) Utils::redirect("admin.php?com=login&action=logout");
        $User = $Authenticate->user();
        $Page = $this->dao->getItem(Filter::get($_GET, 'id'));
        $this->view->assign('is_new', false);
        if ($this->checkOut($Page) == 1) {
            $this->showEditForm($Page);
        }
        else {
            $this->_setMessage(
                'warning',
                __('GLOBAL.WARNING', 'Warning', 1),
                __("GLOBAL.CURRENTLY_CHECKED_OUT", 'The page could not be checked out because it is currently checked out by another user.', 1)
            );
            Utils::redirect("admin.php?com=page&pageNum={$Request->get('pageNum', 1)}");
        }
    }
    
    function showEditForm($Page) {
        $this->setViewName("edit.php");
        $this->view->assign('layouts', PageHelper::getPageTypeOptions());
        $this->view->assign('menus',   PageHelper::getMenuListOptions());
        $this->view->assign('parents', PageHelper::getParentPageOptions($Page));
        $this->view->setData($Page);
    }
    
    function doPublish($Request) {
        if (! is_admin_page()) Utils::redirect("admin.php?com=login&action=logout");
        $Page = $this->dao->getItem(Filter::get($_GET, 'id'));
        if ($this->checkOut($Page) == 1) {
        
            $direction = Filter::getAlphaNumeric($Request, 'direction', 'up');
            
            if ($direction == 'down' && ($Page->getIsdefault() || $this->dao->countItems() == 1)) {
                $this->_setMessage(
                    'error',
                    __('GLOBAL.ERROR', 'Error', 1),
                    __("GLOBAL.CANNOT_UNPUBLISH_HOMEPAGE", 'The Home Page cannot be un-published.', 1)
                );
                Utils::redirect("admin.php?com=page&pageNum={$Request->get('pageNum', 1)}");
            }
            
            $Page->setPublished($direction == 'up' ? 1 : 0);

            if ($this->dao->publish($Page)) {
                $this->checkIn($Page);
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
        else {
            $this->_setMessage(
                'warning',
                __('GLOBAL.WARNING', 'Warning', 1),
                __("GLOBAL.CURRENTLY_CHECKED_OUT", 'The page could not be checked out because it is currently checked out by another user.', 1)
            );
        }
        Utils::redirect("admin.php?com=page&pageNum={$Request->get('pageNum', 1)}");
    }
    
    /**
     * TODO: This needs to be updated to work with DB storage 
     */
    function doOrder($Request) {
        if (! is_admin_page()) Utils::redirect("admin.php?com=login&action=logout");
        if ($this->dao->reorder($Request->get('id'), $Request->get('direction', 'down'))) {
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
        Utils::redirect("admin.php?com=page&pageNum={$Request->get('pageNum', 1)}");
    }
    
    function doDelete($Request) {
        if (! is_admin_page()) Utils::redirect("admin.php?com=login&action=logout");
        $Page = $this->dao->getItem($Request->get('id'));
        
        if ($Page->getIsdefault()) {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('PAGE.NO_DELETE_DEFAULT_PAGE', 'The page you tried to delete is set as your home (default) page. The home page cannot be deleted. If you want to delete this page you must first set another page as your default page, then delete this one.', 1)
            );
            Utils::redirect("admin.php?com=page");
        }
        else {
            $itemId = $Request->get('id');
            if (! is_null($itemId)) {
                $this->checkIn(
                    $this->dao->getItem($itemId)
                );
            }

            if ($this->dao->delete($itemId)) {
            
                $dom  = PageHelper::parseStructureXml();
                $doc  = $dom->documentElement;
                $node = PageHelper::getElementById($dom, $Page->getId());
                $doc->removeChild($node);
                PageHelper::saveStructureXml($dom->saveXml());
            
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
            Utils::redirect("admin.php?com=page&pageNum={$Request->get('pageNum', 1)}");
        }
    }
    
    /**
     * @param RequestObject  The request object
     * @see includes/mvc/Controller#doSave($Request, $redirect)
     */
    function doSave($Request) {
    
        if (! is_admin_page()) Utils::redirect("admin.php?com=login&action=logout");
        $this->addValidation('name', 'notnull', 'PAGE.VALIDATE.NAME');
        $this->addValidation('pagetype', 'notnull', 'PAGE.VALIDATE.LAYOUT');

        if ($this->validate($Request)) {
            
            $Page = new Page($Request);
            $Page->setModified(date(SB_DATE_MODIFIED_FORMAT,time()));
            $Page->setType('page');
            $Page->setObjtype('page');
            $Page->setPermalink(PageHelper::getUniquePermalink(
                $Page, PageHelper::getPermalink($Page)
            ));
            
            if ($Request->get('is_new') == 1) {
                $success = $this->dao->insert($Page);
            }
            else {
                $success = $this->dao->update($Page);
            }

            if ($success) {
            
                $doc  = PageHelper::parseStructureXml();
                $node = PageHelper::getElementById($doc, $Page->getId());
                $node->setAttribute('name',      $Page->getName());
                $node->setAttribute('published', $Page->getPublished());
                $node->setAttribute('url',       $Page->getPermalink());
                $node->setAttribute('show_in_navigation', $Page->getShow_in_navigation());
                PageHelper::saveStructureXml($doc->saveXml());
            
                $this->checkIn($Page);
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
            Utils::redirect("admin.php?com=page&pageNum={$Request->get('pageNum', 1)}");
        }
        else {
            $this->showEditForm(new Page($Request));
        }
    }
    
    /**
     * @param RequestObject  The request object
     * @see includes/mvc/Controller#doApply($Request, $redirect)
     */
    function doApply($Request) {
        if (! is_admin_page()) Utils::redirect("admin.php?com=login&action=logout");
        $this->addValidation('name', 'notnull', 'PAGE.VALIDATE.NAME');
        $this->addValidation('pagetype', 'notnull', 'PAGE.VALIDATE.LAYOUT');

        if ($this->validate($Request)) {
            
            $Page = new Page($Request);
            $Page->setModified(date(SB_DATE_MODIFIED_FORMAT,time()));
            $Page->setType('page');
            $Page->setObjtype('page');
            $Page->setPermalink(PageHelper::getUniquePermalink(
                $Page, PageHelper::getPermalink($Page)
            ));
            
            if ($Request->get('is_new') == 1) {
                $success = $this->dao->insert($Page);
            }
            else {
                $success = $this->dao->update($Page);
            }
            
            if ($success) {
            
                $doc  = PageHelper::parseStructureXml();
                $node = PageHelper::getElementById($doc, $Page->getId());
                $node->setAttribute('name',      $Page->getName());
                $node->setAttribute('published', $Page->getPublished());
                $node->setAttribute('url',       $Page->getPermalink());
                $node->setAttribute('show_in_navigation', $Page->getShow_in_navigation());
                PageHelper::saveStructureXml($doc->saveXml());
            
                $this->checkIn($Page);
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
            # Utils::redirect("admin.php?com=page&pageNum={$Request->get('pageNum', 1)}");
        }
        $this->showEditForm(new Page($Request));
    }
    /**
     * Copies the page object.
     * @param RequestObject $Request  The request object.
     * @return void
     */
    function doCopy($Request) {
        if (! is_admin_page()) Utils::redirect("admin.php?com=login&action=logout");
        if ($this->dao->copy($Request->get('id'))) {
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
        Utils::redirect("admin.php?com=page&pageNum={$Request->get('pageNum', 1)}");
    }
    
    /**
     * Cancels the current action
     * @param RequestObject $Request  The request object.
     * @see includes/mvc/Controller#doCancel($redirect)
     */
    function doCancel($Request) {
        $itemId = $Request->get('id');
        if (! is_null($itemId)) {
            $this->checkIn(
                $this->dao->getItem($itemId)
            );
        }
        parent::doCancel("admin.php?com=page&action=list&pageNum={$Request->get('pageNum', 1)}");
    }
    
    function getViewPath() {
        $this->view_path = _SBC_SYS_ . "managers/page/views/";
        if (get_constant('_ADMIN_', 0) !== 1) {
            $this->view_path = ACTIVE_SKIN_DIR;
        }
        return $this->view_path;
    }

    function getViewName() {
        if (get_constant('_ADMIN_', 0) !== 1) {
            $this->view_path = ACTIVE_SKIN_DIR;
            $this->defaultViewName = 'default';
            $viewName = $this->viewName;
            $pageId = Filter::get($_GET, 'pid', DEFAULT_PAGE);
            if (Filter::getNumeric($_GET, 'pid', 0) != 0) {
                $pageId = Filter::get($_GET, 'pid', DEFAULT_PAGE);
                if (trim($pageId) == "") $pageId = DEFAULT_PAGE;
                $viewName = Filter::get(
                    $this->dao->getItem($pageId), 
                    'pagetype',
                    $this->defaultViewName
                );
            }
            return "skin.{$viewName}.php";
        }
        else {
            $this->defaultViewName = 'list.php';
            $this->view_path = _SBC_SYS_ . "managers/page/views/";
            return $this->viewName;
        }
    }
        
    /**
     * Determine if the current page is found or not
     * @return bool Whether or not the object exists
     */
    function isNotFound() {
        static $pages;
        if (!is_array($pages)) {
            $pages = $this->dao->index();
        }
        $Page = Utils::selectObject(
            $pages, 
            Filter::get($_GET, 'pid', null)
        );
        if (Filter::get($Page, 'id', null) == null 
            || Filter::get($Page, 'id', NOT_FOUND) == NOT_FOUND 
            || $Page->getPublished() != 1) {

            return true;
        }
        return false;
    }
    
    /**
     * Replaces the current request ID with the 404 page id if the request item is not found
     * @return int  The page ID
     */
    function getPageId() {
        global $Router;
        if ($this->isNotFound()) {
            return Filter::get($Router->getErrorPage(), 'id');
        }
        return Filter::get($_GET, 'pid', DEFAULT_PAGE);
    }
    
    /**
     * Gets the appropriate page route
     * @return String  The page route
     */
    function getErrorPageRoute() {
         global $Router;
         return $Router->normalize(
             Filter::get($Router->getErrorPage(), 'name')
         );
    }

}
