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

class SnippetsHelper {

    function initialize() {
    	if (file_exists(_SBC_APP_ . "daos/SnippetsDAO.php")) {
    		require_once(_SBC_APP_ . "daos/SnippetsDAO.php");
    	}
    	else {
    		require_once(SB_APP_MANAGERS_DIR . "snippets/SnippetsDAO.php");
    	}
    	require_once(SB_APP_MANAGERS_DIR . "snippets/Snippet.php");
    	require_once(SB_APP_MANAGERS_DIR . "snippets/SnippetsController.php");
    }

    function getDao($refresh=false) {
        static $Dao;
        if (! is_object($Dao) || $refresh) {
            if (! class_exists('SnippetsDAO')) {
            	SnippetsHelper::initialize();
            }
            # require_once(_SBC_APP_ . "managers/snippets/beans/snippet.php");
            # require_once(_SBC_APP_ . "managers/snippets/daos/snippets.php");
            $Dao = new SnippetsDAO();
        }
        return $Dao;
    }
    
    function getDataFile($Bean) {
        $dataFile = $Bean->getDatafile();
    }
    
    function getBeanByKey($key, $value, $refresh=false) {
        static $Bean;
        if (! is_object($Bean) || $refresh) {
            $Dao = SnippetsHelper::getDao();
            $Bean = $Dao->getByKey($key, $value);
        }
        return $Bean;
    }
    
    function getTypeSelector($type) {
        $types = array(
            Snippet::TYPE_WYSIWYG, 
            Snippet::TYPE_PHP, 
            Snippet::TYPE_TEXT, 
            Snippet::TYPE_XML
        );
        $options = array(HtmlUtils::option(
            ' -- ' . __('GLOBAL.CHOOSE', 'Choose', 1) . ' -- ',
            '',
            0
        ));
        $count = count($types);
        for ($i=0; $i<$count; $i++) {
            $ucType = $types[$i];
            array_push($options, HtmlUtils::option(
                __('SNIPPETS.TYPES.{$ucType}', $ucType, 1),
                $types[$i],
                $type == $types[$i] ? 1 : 0
            ));
        }
        return HtmlUtils::selector(
            $options,
            'snippetType',
            1,
            array('id'=>'snippetType')
        );
    }
}