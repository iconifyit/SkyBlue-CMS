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
 * @date   June 27, 2009
 */

class SettingsDAO extends XmlDAO {

    function __construct() {
        parent::__construct(array(
            'type' => 'settings'
        ));
    }
    
    function index() {
        static $collections;
        if (!is_array($collections)) {
            $components = FileSystem::list_dirs(SB_MANAGERS_DIR, false);
            $collections = array();
            for ($i=0; $i<count($components); $i++) {
                $configFile = "{$components[$i]}/config.php";
                if (!file_exists($configFile)) continue;
                $comConfig = FileSystem::read_config($configFile);
                if (Filter::get($comConfig, 'show_in', '') == 'settings') {
                    array_push(
                        $collections, 
                        new Settings(array(
                            'name'      => ucwords(basename($components[$i])),
                            'icon'      => Filter::get($comConfig, 'icon'),
                            'nameToken' => Filter::get($comConfig, 'nameToken'),
                            'infoToken' => Filter::get($comConfig, 'infoToken')
                        ))
                    );
                }
            }
        }
        return $collections;
    }
    
    function initDataSource() { return false; }
}