<?php defined('SKYBLUE') or die('Bad File Request');

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

class SkinHelper {

    function initialize() {
    	if (file_exists(_SBC_APP_ . "daos/SkinDAO.php")) {
    		require_once(_SBC_APP_ . "daos/SkinDAO.php");
    	}
    	else {
    		require_once(SB_MANAGERS_DIR . "skin/SkinDAO.php");
    	}
    	require_once(SB_MANAGERS_DIR . "skin/Skin.php");
    	require_once(SB_MANAGERS_DIR . "skin/SkinController.php");
    }
    
    function getPages() {
        $Dao = SkinHelper::getPageDao();
        return $Dao->index();
    }
    
    function getPageDao($refresh=false) {
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
    
    function getDao($refresh=false) {
        static $Dao;
        if (! is_object($Dao) || $refresh) {
            if (! class_exists('SkinDAO')) {
                SkinHelper::initialize();
            }
            $Dao = new SkinDAO();
        }
        return $Dao;
    }
    
    function getActiveSkin() {
        $Dao = SkinHelper::getDao();
        $skins = $Dao->index();
        # Core::Dump($skins);
        foreach ($skins as $Skin) {
            if ($Skin->getPublished()) {
                return $Skin;
            }
        }
        return null;
    }
}