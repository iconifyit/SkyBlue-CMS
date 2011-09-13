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
 
class ConfigurationHelper {

    function initialize() {
    	if (file_exists(_SBC_APP_ . "daos/ConfigurationDAO.php")) {
    		require_once(_SBC_APP_ . "daos/ConfigurationDAO.php");
    	}
    	else {
    		require_once(SB_MANAGERS_DIR . "configuration/ConfigurationDAO.php");
    	}
    	require_once(SB_MANAGERS_DIR . "configuration/Configuration.php");
    	require_once(SB_MANAGERS_DIR . "configuration/ConfigurationController.php");
    }

    function getDao($refresh=false) {
        static $Dao;
        if (! is_object($Dao) || $refresh) {
            if (! class_exists('ConfigurationDAO')) {
                ConfigurationHelper::initialize();
            }
            $Dao = new ConfigurationDAO();
        }
        return $Dao;
    }
    
    function getConfiguration() {
        $Dao = ConfigurationHelper::getDao();
        return $Dao->index();
    }

    function getLanguageSelector($selectedOption) {
        $langs = FileSystem::list_dirs(SB_LANG_DIR, false);
        $options = array(
            HtmlUtils::option(' -- ' . __('GLOBAL.CHOOSE', 'Choose', 1) . ' -- ', '')
        );
        $count = count($langs);
        for ($i=0; $i<$count; $i++) {
            $thisOption = strtolower(basename($langs[$i]));
            array_push(
                $options,
                HtmlUtils::option(
                    $thisOption,
                    $thisOption,
                    $selectedOption == $thisOption ? true : false
                )
            );
        }
        return HtmlUtils::selector(
            $options,
            'site_lang',
            1,
            array('id' => 'site_lang')
        );
    }
    
    function getEditorSelector($selectedOption) {
        $editors = FileSystem::list_dirs(_SBC_WWW_ . "resources/editors/", false);
        $options = array(
            HtmlUtils::option(' -- ' . __('GLOBAL.CHOOSE', 'Choose', 1) . ' -- ', '')
        );
        $count = count($editors);
        for ($i=0; $i<$count; $i++) {
            $thisOption = strtolower(basename($editors[$i]));
            array_push(
                $options,
                HtmlUtils::option(
                    $thisOption,
                    $thisOption,
                    $selectedOption == $thisOption ? true : false
                )
            );
        }
        return HtmlUtils::selector(
            $options,
            'site_editor',
            1,
            array('id' => 'site_editor')
        );
    }
    
    function getUiThemeSelector($selectedOption) {
        $themes = FileSystem::list_dirs(SB_UI_DIR . "js/" . JQUERY_UI_VERSION . "/css/", false);
        $options = array(
            HtmlUtils::option(' -- ' . __('GLOBAL.CHOOSE', 'Choose', 1) . ' -- ', '')
        );
        $count = count($themes);
        for ($i=0; $i<$count; $i++) {
            $thisOption = strtolower(basename($themes[$i]));
            array_push(
                $options,
                HtmlUtils::option(
                    $thisOption,
                    $thisOption,
                    $selectedOption == $thisOption ? true : false
                )
            );
        }
        return HtmlUtils::selector(
            $options,
            'ui_theme',
            1,
            array('id' => 'ui_theme')
        );
    }
    
}