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

class MediaController extends Controller {

    var $model;
    var $action;
    var $viewName;
    var $view_path;
    var $defaultViewName = 'folders';

    function __construct($Request) {
        parent::__construct($Request);
        $this->setHandler('index',  'doIndex',   true);
        $this->setHandler('list',   'doList',    true);
        $this->setHandler('add',    'doAdd',     true);
        $this->setHandler('edit',   'doEdit',    true);
        $this->setHandler('cancel', 'doCancel',  true);
        $this->setHandler('upload', 'doUpload',  true);
        $this->setHandler('delete', 'doDelete',  true);
        $this->setHandler('save',   'doSave',    true);
        $this->setHandler('rename', 'doRename',  true);
        $this->setHandler('move',   'doMove',    true);
        $this->setHandler('copy',   'doCopy',    true);
        $this->setViewPath(_SBC_SYS_ . 'managers/media/views/');
    }
    
    function doIndex() {
        $this->setViewName('folders.php');
        $this->view->assign(
            'folderTree',
            MediaHelper::getFolderTree(SB_MEDIA_DIR, "folder-tree")
        );
    }

    function doList($Request) {
        
        $itemsPerPage = Filter::getNumeric(
            $this->getConfig(), 'items_per_page', 10
        );

        $dir = MediaHelper::getFolder();
        
        $allItems = $this->dao->index(SB_MEDIA_DIR . $dir);
        
        $itemCount = count($allItems);
        $pageNum = Filter::getNumeric($_GET, 'pageNum', 1);
        $pageCount = ceil($itemCount/$itemsPerPage);

        $this->view->setData(Utils::paginate(
            $allItems, 
            $itemsPerPage, 
            $pageNum
        ));
        
        $this->setViewName('list.php');
        $this->view->assign('folder', $dir);
        $this->view->assign('pageNum', $pageNum);
        $this->view->assign('pageCount', $pageCount);
        $this->view->assign('redirect', $Request->get('folder'));
    }
    
    function getBean($Request) {
        $filepath = SB_MEDIA_DIR . ( 
                $Request->get('folder') == '/' ? '' : $Request->get('folder') 
            ) . $Request->get('name');
       
        $Bean = $this->dao->getByKey(
            'filepath',
            $filepath
        );
        if (is_object($Bean)) {
            $Bean->setType('media');
            $Bean->setObjtype('media');
        }
        return $Bean;
    }

    function doEdit($Request) {
        $filepath = SB_MEDIA_DIR . (
                $Request->get('folder') == '/' ? '' : $Request->get('folder')
            ) . $Request->get('name');

        if (! file_exists($filepath)) {
            trigger_error(
                __('MEDIA.ERR.NO_SUCH_FILE ', "No such file", 1), 
                E_USER_ERROR
            );
        }
        else {
            $this->setViewName('edit.php');
            $this->view->assign('redirect', $Request->get('redirect'));
            $this->view->assign('folder',   $Request->get('folder'));
            
            $Bean = $this->getBean($Request);
            $Bean->setId(MediaHelper::getUniqueId($Bean));
            
            if ($this->checkOut($Bean) == 1) {
                $this->showEditForm($Bean);
            }
            else {
                $this->_setMessage(
                    'warning',
                    __('GLOBAL.WARNING', 'Warning', 1),
                    __("GLOBAL.CURRENTLY_CHECKED_OUT", 'The item could not be checked out because it is currently checked out by another user.', 1)
                );
                Utils::redirect($Request->get('redirect'));
            }
        }
    }
    
    function doAdd($Request) {
        $this->setViewName('upload.php');
        $this->view->assign(
            'folders',
            MediaHelper::getFolders()
        );
        $this->view->setData(new Media(array(
            'name'    => 'New Item',
            'type'    => 'media',
            'objtype' => 'media', 
            'id'      => 0
        )));
        $this->view->assign('is_new', true);
    }
    
    function doCancel($Request) {
        $Bean = $this->getBean($Request);
        if (is_object($Bean)) {
            $Bean->setId(MediaHelper::getUniqueId($Bean));
            $this->checkIn($Bean);
        }
        $redirect = "";
        if ($Request->get('redirect') != "") {
            $redirect = "&folder={$Request->get('redirect')}";
        }
        $pageNum = "";
        if ($Request->get('pageNum') != "") {
            $pageNum = "&pageNum={$Request->get('pageNum')}";
        }
        parent::doCancel("admin.php?com=media&action=list{$redirect}{$pageNum}");
    }
    
    function doUpload() {
        $targets = $this->getTargets();
        $mimes   = Filter::get($this->getConfig(), 'mimes');
        $folders = Filter::get($_POST, 'folders', array());
        $uploads = Filter::get($_FILES, 'uploads', array());
        
        if (!is_array($folders)) $folders = array($folders);
        $count = count($folders);
        for ($i=0; $i<$count; $i++) {
            $folders[$i] = _SBC_WWW_ . Filter::noInjection($folders, $i);
        }
        if (!is_array($uploads)) $uploads = array('uploads'=>$uploads);
        
        if (MediaHelper::countUploads($uploads) == 0) {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('MEDIA.NO_UPLOADS', "No files were selected for upload", 1)
            );
            Utils::redirect("admin.php?com=media");
        }
        
        foreach ($uploads as $key=>$value) {
            if (is_array($value)) {
                $count = count($value);
                for ($i=0; $i<$count; $i++) {
                    $value[$i] = Filter::noInjection($value, $i);
                }
            }
            else if (is_string($value)) {
                $value = Filter::noInjection($value);
            }
        }
        $errors = array();
        $media = array();
        $count = count($uploads['name']);
        for ($i=0; $i<$count; $i++) {
            $name = @$uploads['name'][$i];
            $data = new Media(array(
                'id'       => $i+1,
                'objtype'  => 'media',
                'type'     => 'media',
                'name'     => @$uploads['name'][$i],
                'filetype' => @$uploads['type'][$i],
                'tmp_name' => @$uploads['tmp_name'][$i],
                'filesize' => @$uploads['size'][$i], 
                'filepath' => $folders[$i] . @$uploads['name'][$i]
            ));
            list($exitCode, $newFile) = $this->dao->upload($data, array(
                'targets' => $targets,
                'mimes'   => $mimes
            ));
            if ($exitCode != 1) {
                array_push(
                    $errors, __(
                        "MEDIA.ERROR.{$exitCode}", 
                        __('MEDIA.MSG.COULD_NOT_UPLOAD', "Files could not be uploaded", 1),
                        1
                    ). " [File Name: {$name}, Error Code: {$exitCode}]"
                );
            }
        }
        if (count($errors) == 0) {
            $this->_setMessage(
                'success',
                __('GLOBAL.SUCCESS', 'Success', 1),
                __('GLOBAL.UPLOAD_SUCCESS', 'Your files were uploaded successfully.', 1)
            );
        }
        else {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                $errors
            );
        }
        Utils::redirect("admin.php?com=media");
    }
    
    function getTargets() {
        $targets = Filter::get($this->getConfig(), 'targets');
        $count = count($targets);
        for ($i=0; $i<$count; $i++) {
            $targets[$i] = _SBC_WWW_ . $targets[$i];
        }
        return $targets;
    }
    
    function doDelete($Request) {
        $name = $Request->get('id');
        $filepath = SB_MEDIA_DIR . $Request->get('folder') . $name;
        $folder = $Request->get('folder');
        $Bean = new Media(array(
            'type'     => 'media',
            'objtype'  => 'media',
            'id'       => $name,
            'name'     => $name,
            'filepath' => $filepath
        ));
        $targets = $this->getTargets();
        $dir = Utils::addTrailingSlash(dirname($Bean->getFilepath()));
        if (!in_array($dir, $targets)) {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                $name . __('MEDIA.MSG.NOT_DELETED', ' could not be deleted.', 1)
            );
        }
        else if ($this->dao->delete($Bean)) {
            $this->checkIn($Bean);
            $this->_setMessage(
                'success',
                __('GLOBAL.SUCCESS', 'Success', 1),
                $name . __('MEDIA.MSG.DELETED', ' was deleted.', 1)
            );
        }
        else {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                $name . __('MEDIA.MSG.NOT_DELETED', ' could not be deleted.', 1)
            );
        }
        Utils::redirect("admin.php?com=media&action=list&folder={$folder}");
    }
    
    function doSave($Request) {

        $ResultObject = null;
    
        $method = $Request->get('action_override');
        if (trim($method) == "") {
            $method = $this->getDefaultAction();
        }
        $method = strtolower($method);
        if (isset($this->action_map[$method])) {
            $method = strtolower($this->action_map[$method]);
        }

        if ($this->_callable($method)) {
            if ($this->_authorize()) {
                $ResultObject = $this->_call($method, $this->request);
            }
            else {
                $this->_setMessage(
                    'error', 
                    __('CONTROLLER.LABEL.PERMISSION_DENIED', '', 1), 
                    __('CONTROLLER.MSG.NOT_ENOUGH_PRIVILEGES', '', 1)
                );
            }
            $this->view->setMessage($this->getMessage());
        }
        else {
            Utils::redirect(BASE_PAGE);
        }

        return $ResultObject;
    }
    
    function doRename($Request) {
        
        $Bean = new Media($Request);

        $oldName = basename($Bean->getFilepath());
        $newName = $Bean->getName();
        $dirName = dirname($Bean->getFilepath());
        
        if (strtolower($oldName) == strtolower($newName)) {
            $this->_setMessage(
                'error', 
                __('GLOBAL.ERROR', 'Error', 1), 
                __('MEDIA.ERR.SAME_NAME', 'You did not change the file name', 1)
            );
        }
        else if (file_exists("{$dirName}/{$newName}")) {
            $this->_setMessage(
                'error', 
                __('GLOBAL.ERROR', 'Error', 1), 
                __('MEDIA.ERR.FILE_EXISTS', 'A file by that name already exists in the target location.', 1)
            );
        }
        else if (FileSystem::move_file($Bean->getFilepath(), "{$dirName}/{$newName}")) {
            $this->_setMessage(
                'success',
                __('GLOBAL.SUCCESS', 'Success', 1),
                $oldName . __('MEDIA.MSG.RENAMED', ' was renamed to ', 1) . $newName
            );
        }
        else {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('GLOBAL.CHANGES_NOT_SAVED', 'Your changes could not be saved.', 1)
            );
        }
        $redirect = "";
        if ($Request->get('redirect') != "") {
            $redirect = "&folder={$Request->get('redirect')}";
        }
        $pageNum = "";
        if ($Request->get('pageNum') != "") {
            $pageNum = "&pageNum={$Request->get('pageNum')}";
        }
        Utils::redirect("admin.php?com=media&action=list{$redirect}{$pageNum}");
    }
    
    function doMove($Request) {
        
        $Bean = new Media($Request);
        
        $oldLocation = $Bean->getFilePath();
        $newLocation = $Request->get('destination') . $Request->get('name');
        
        if (file_exists($newLocation)) {
            $this->_setMessage(
                'error', 
                __('GLOBAL.ERROR', 'Error', 1), 
                __('MEDIA.ERR.FILE_EXISTS', 'A file by that name already exists in the target location.', 1)
            );
        }
        else if (FileSystem::move_file($oldLocation, $newLocation)) {
            $this->_setMessage(
                'success',
                __('GLOBAL.SUCCESS', 'Success', 1),
                $oldLocation . __('MEDIA.MSG.MOVED', ' was moved to ', 1) . $newLocation
            );
        }
        else {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('GLOBAL.CHANGES_NOT_SAVED', 'Your changes could not be saved.', 1)
            );
        }
        $redirect = "";
        if ($Request->get('redirect') != "") {
            $redirect = "&folder={$Request->get('redirect')}";
        }
        $pageNum = "";
        if ($Request->get('pageNum') != "") {
            $pageNum = "&pageNum={$Request->get('pageNum')}";
        }
        Utils::redirect("admin.php?com=media&action=list{$redirect}{$pageNum}");
    }
    
    function doCopy($Request) {
        
        $Bean = new Media($Request);
        
        $oldLocation = $Bean->getFilePath();
        $newLocation = $Request->get('destination') . $Request->get('name');
        
        $redirect = $Request->get('redirect');
        if (empty($redirect)) {
            $redirect = "&folder={$Request->get('folder')}";    
        }
        
        if (file_exists($newLocation)) {
            $this->_setMessage(
                'error', 
                __('GLOBAL.ERROR', 'Error', 1), 
                __('MEDIA.ERR.FILE_EXISTS', 'A file by that name already exists in the target location.', 1)
            );
            $base_url = "admin.php?com=media&action=edit&id={$Bean->getName()}{$redirect}";
        }
        else if (FileSystem::copy_file($oldLocation, $newLocation)) {
            $this->_setMessage(
                'success',
                __('GLOBAL.SUCCESS', 'Success', 1),
                $oldLocation . __('MEDIA.MSG.MOVED', ' was copied to ', 1) . $newLocation
            );
            $base_url = "admin.php?com=media&action=list";
        }
        else {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('GLOBAL.CHANGES_NOT_SAVED', 'Your changes could not be saved.', 1)
            );
            $base_url = "admin.php?com=media&action=list";
        }
        $pageNum = "";
        if ($Request->get('pageNum') != "") {
            $pageNum = "&pageNum={$Request->get('pageNum')}";
        }
        Utils::redirect("{$base_url}{$redirect}{$pageNum}");
    }

}