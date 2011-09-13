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

class MetaHelper {
    
    function initialize() {
    	if (file_exists(_SBC_APP_ . "daos/MetaDAO.php")) {
    		require_once(_SBC_APP_ . "daos/MetaDAO.php");
    	}
    	else {
    		require_once(SB_MANAGERS_DIR . "meta/MetaDAO.php");
    	}
    	if (file_exists(_SBC_APP_ . "daos/MetagroupsDAO.php")) {
    		require_once(_SBC_APP_ . "daos/MetagroupsDAO.php");
    	}
    	else {
    		require_once(SB_MANAGERS_DIR . "meta/MetagroupsDAO.php");
    	}
    	require_once(SB_MANAGERS_DIR . "meta/Meta.php");
    	require_once(SB_MANAGERS_DIR . "meta/Metagroup.php");
    	require_once(SB_MANAGERS_DIR . "meta/MetaController.php");
    }
    
    function getMetaData() {
        $Dao = MetaHelper::getMetaDao();
        return $Dao->index();
    }
    
    function getMetagroupsData() {
        $Dao = MetaHelper::getMetagroupsDao();
        return $Dao->index();
    }
    
    function getMetaDao($refresh=false) {
        static $Dao;
        if (! is_object($Dao) || $refresh) {
            if (! class_exists('MetaDAO')) {
                MetaHelper::initialize();
            }
            $Dao = new MetaDAO();
        }
        return $Dao;
    }
    
    function getMetagroupsDao($refresh=false) {
        static $Dao;
        if (! is_object($Dao) || $refresh) {
            if (! class_exists('MetagroupsDAO')) {
                MetaHelper::initialize();
            }
            $Dao = new MetagroupsDAO();
        }
        return $Dao;
    }
    
    function getGroups(&$DAO, $refresh=false) {
        global $Core;
        static $groups;
        if (!is_array($groups) || $refresh) {
            $groups = array();
            $items = $DAO->index();
            foreach ($items as $item) {
                array_push(
                    $groups,
                    Utils::bindTransferObject(
                        $item, new Metagroup
                    )
                );
            }
        }
        return $groups;
    }

    function hasGroup($Item, $Group) {
        return in_array($Group->getId(), $Item->getGroups());
    }
}