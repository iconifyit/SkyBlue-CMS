<?php defined('SKYBLUE') or die('Bad File Request');

/**
 * @version      2.0 2009-06-20 21:41:00 $
 * @package      SkyBlue CMS
 * @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license      GNU/GPL, see COPYING.txt
 * SkyBlue CMS is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

/**
 * @author Scott Lewis
 * @date   June 20, 2009
 */

class SkinHelper {

    static function initialize() {
        file_put_contents('/tmp/frontend-debug.log', "SkinHelper::initialize() - starting\n", FILE_APPEND);

        // Load SqliteDAO first if it exists
        if (!class_exists('SqliteDAO') && file_exists(_SBC_APP_ . "daos/SqliteDAO.php")) {
            file_put_contents('/tmp/frontend-debug.log', "SkinHelper::initialize() - loading SqliteDAO\n", FILE_APPEND);
            require_once(_SBC_APP_ . "daos/SqliteDAO.php");
        }

        if (file_exists(_SBC_APP_ . "daos/SkinDAO.php")) {
            file_put_contents('/tmp/frontend-debug.log', "SkinHelper::initialize() - loading custom SkinDAO\n", FILE_APPEND);
            require_once(_SBC_APP_ . "daos/SkinDAO.php");
        }
        else {
            file_put_contents('/tmp/frontend-debug.log', "SkinHelper::initialize() - loading system SkinDAO\n", FILE_APPEND);
            require_once(SB_MANAGERS_DIR . "skin/SkinDAO.php");
        }
        file_put_contents('/tmp/frontend-debug.log', "SkinHelper::initialize() - loading Skin.php\n", FILE_APPEND);
        require_once(SB_MANAGERS_DIR . "skin/Skin.php");
        file_put_contents('/tmp/frontend-debug.log', "SkinHelper::initialize() - loading SkinController.php\n", FILE_APPEND);
        require_once(SB_MANAGERS_DIR . "skin/SkinController.php");
        file_put_contents('/tmp/frontend-debug.log', "SkinHelper::initialize() - completed\n", FILE_APPEND);
    }
    
    static function getPages() {
        $Dao = SkinHelper::getPageDao();
        return $Dao->index();
    }
    
    static function getPageDao($refresh=false) {
        static $Dao;
        if (! is_object($Dao) || $refresh) {
            if (! class_exists('Page')) {
                require_once(SB_MANAGERS_DIR . "page/Page.php");
                # Loader::load("managers.page.Page", true, _SBC_SYS_);
            }
            if (file_exists(_SBC_APP_ . "daos/PageDAO.php")) {
                if (! class_exists('PageDAO')) {
                    require_once(_SBC_APP_ . "daos/PageDAO.php");
                }
            }
            else {
                # require_once(SB_APP_MANAGERS_DIR . "page/PageDAO.php");
                require_once(SB_MANAGERS_DIR . "page/daos/page.php");
            }
            $Dao = new PageDAO();
        }
        return $Dao;
    }
    
    static function getDao($refresh=false) {
        file_put_contents('/tmp/frontend-debug.log', "SkinHelper::getDao() - starting\n", FILE_APPEND);
        static $Dao;
        if (! is_object($Dao) || $refresh) {
            file_put_contents('/tmp/frontend-debug.log', "SkinHelper::getDao() - need to create DAO\n", FILE_APPEND);
            if (! class_exists('SkinDAO')) {
                file_put_contents('/tmp/frontend-debug.log', "SkinHelper::getDao() - initializing SkinDAO class\n", FILE_APPEND);
                SkinHelper::initialize();
            }
            file_put_contents('/tmp/frontend-debug.log', "SkinHelper::getDao() - creating new SkinDAO\n", FILE_APPEND);
            $Dao = new SkinDAO();
            file_put_contents('/tmp/frontend-debug.log', "SkinHelper::getDao() - SkinDAO created\n", FILE_APPEND);
        }
        file_put_contents('/tmp/frontend-debug.log', "SkinHelper::getDao() - returning DAO\n", FILE_APPEND);
        return $Dao;
    }
    
    static function getActiveSkin() {
        file_put_contents('/tmp/frontend-debug.log', "SkinHelper::getActiveSkin() - starting\n", FILE_APPEND);
        $Dao = SkinHelper::getDao();
        file_put_contents('/tmp/frontend-debug.log', "SkinHelper::getActiveSkin() - got DAO\n", FILE_APPEND);
        $skins = $Dao->index();
        file_put_contents('/tmp/frontend-debug.log', "SkinHelper::getActiveSkin() - got " . count($skins) . " skins\n", FILE_APPEND);
        # Core::Dump($skins);
        foreach ($skins as $Skin) {
            if ($Skin->getPublished()) {
                file_put_contents('/tmp/frontend-debug.log', "SkinHelper::getActiveSkin() - found published skin\n", FILE_APPEND);
                return $Skin;
            }
        }
        file_put_contents('/tmp/frontend-debug.log', "SkinHelper::getActiveSkin() - no published skin found, returning null\n", FILE_APPEND);
        return null;
    }
}