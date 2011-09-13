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

class CheckoutsController extends Controller {

    var $model;
    var $action;
    var $viewName;
    var $view_path;
    var $defaultViewName = 'list';

    function __construct($Request) {
        parent::__construct($Request);
        $this->setHandler('index',       'doIndex',  true);
        $this->setHandler('checkin',     'doDelete', true);
        $this->setHandler('checkin_all', 'doCheckinAll', true);
        $this->setViewPath(_SBC_SYS_ . 'managers/checkouts/views/');
    }
    
    function doIndex($Request) {
        $this->setViewName('list.php');
        $this->view->setData($this->dao->index());
    }
    
    function doCheckinAll($Request) {
        if ($this->dao->deleteAll()) {
            $this->_setMessage(
                'success',
                __('GLOBAL.SUCCESS', 'Success', 1),
                __('GLOBAL.SAVE_SUCCESS', 'Your changes were successfully saved.', 1)
            );
        }
        else {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('CHECKOUTS.SOME_ITEMS_NOT_CHECKED_IN', 'Some items could not be checked in.', 1)
            );
        }
        Utils::redirect("admin.php?com=checkouts");
    }

    function doDelete($Request) {
        parent::doDelete($Request, "admin.php?com=checkouts");
    }
}