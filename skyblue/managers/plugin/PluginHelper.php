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

/*
- Plugins can only be added and deleted manually
- User installs new plugins by adding the PHP file to the plugins dir, 
	then adding the plugin name to the plugins config file
- System reads in plugins config file
- Compare to Plugins table and update as needed
- Compare Plugins table to plugins structure XML and update as needed
- When a user changes the order of the plugins via the UI, update the plugins structure XML
- When a user changes the publish state of a plugin via the UI, update the plugins structure XML
*/

class PluginHelper {
    
    function initialize() {
    	if (file_exists(_SBC_APP_ . "daos/PluginDAO.php")) {
    		require_once(_SBC_APP_ . "daos/PluginDAO.php");
    	}
    	else {
    		require_once(SB_MANAGERS_DIR . "plugin/PluginDAO.php");
    	}
    	require_once(SB_MANAGERS_DIR . "plugin/Plugin.php");
    	require_once(SB_MANAGERS_DIR . "plugin/PluginController.php");
    }
    
    function getPluginsXml() {
        return load_xml_file(_SBC_APP_ . "config/plugins.xml");
    }
    
    function getElementById($doc, $id) {
        $xpath = new DOMXPath($doc);
		return $xpath->query("//*[@id='$id']")->item(0);
	}
	
	function findElement($doc, $key, $value) {
	    $node = null;
        $xpath = new DOMXPath($doc);
		try {
		    $node = $xpath->query("//*[@{$key}='$value']")->item(0);
		}
		catch (Exception $e) {
		    $node = null;
		}
		return $node;
	}
    
    function parseStructure() {
        $Dom = null;
        try {
            $DaoClass = ucwords(DB_TYPE) . "DAO";
            $Dao = new $DaoClass(array(
				'type' => 'structure', 
				'bean_class' => 'Structure'
			));
            $Statement = $Dao->query("select structure from Structure where site_id = 'plugins'");
            if ($result = $Statement->fetch()) {
                $Dom = new DOMDocument("1.0", "UTF-8");
                $Dom->loadXML(Filter::getRaw($result, 'structure'));
            }
        }
        catch (PDOException $e) {
			die($e->getMessage());
		}
		return $Dom;
    }
    
    function saveStructure($xml) {
        try {
            $DaoClass = ucwords(DB_TYPE) . "DAO";
            $Dao = new $DaoClass(array(
				'type' => 'structure', 
				'bean_class' => 'Structure'
			));
            $Dao->exec("UPDATE Structure SET structure = '$xml' WHERE site_id = 'plugins'");
        }
        catch (PDOException $e) {
			die($e->getMessage());
		}
    }
    
    function getDao($refresh=false) {
        static $Dao;
        if (! is_object($Dao) || $refresh) {
            if (! class_exists('PluginDAO')) {
                PluginHelper::initialize();
            }
            $Dao = new PluginDAO();
        }
        return $Dao;
    }
    
    function getPlugins() {
        $Dao = PluginHelper::getDao();
        return $Dao->index();
    }
    
    function getDataFile($Bean) {
        $dataFile = $Bean->getDatafile();
    }
    
    function getBeanByKey($key, $value, $refresh=false) {
        static $Bean;
        if (! is_object($Bean) || $refresh) {
            $Dao = PluginHelper::getDao();
            $Bean = $Dao->getByKey($key, $value);
        }
        return $Bean;
    }
}