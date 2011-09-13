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
 
class ConfigurationDAO extends SqliteDAO {

    function __construct() {
        parent::__construct(array(
            'type'       => 'configuration',
            'bean_class' => 'Configuration'
        ));
    }
    
    function getConfigBean() {
        $Bean = new Configuration();
        if ($Statement = $this->query("SELECT * FROM {$this->getBeanClass()}")) {
        	if ($result = $Statement->fetchAll(PDO::FETCH_ASSOC)) {
        	    foreach ($result as $field) {
					$key = Filter::get($field, 'name');
					$key = ucwords(strtolower($key));
					$method = "set{$key}";
					if (is_callable(array($Bean, $method))) {
						$Bean->$method(Filter::get($field, 'value'));
					}
				}
				$Bean->setName("configuration");
				$Bean->setId(1);
				$Bean->setType("configuration");
				$Bean->setObjtype("configuration");
        	}
        }
        return $Bean;
    }
    
    function getAllowedFields() {
        return array(
            'site_name',
            'site_slogan',
            'site_url',
            'site_editor',
            'site_lang',
            'sef_urls',
            'use_cache',
            'contact_name',
            'contact_title',
            'contact_address',
            'contact_address_2',
            'contact_city',
            'contact_state',
            'contact_zip',
            'contact_email',
            'contact_phone',
            'contact_fax',
            'ui_theme',
            'objtype',
            'name',
            'active_skin'
        );
    }
    
    function update($Bean) {
        $queries = array();
        $props  = Filter::getRaw($Bean, '_properties');
        foreach ($props as $prop) {
            if (strcasecmp($prop, 'id') === 0) continue;
            $method = "get" . ucwords(strtolower($prop));
            if (in_array($prop, $this->getAllowedFields()) && is_callable(array($Bean, $method))) {
                array_push($queries, "UPDATE {$this->getBeanClass()} set value='{$Bean->$method()}' WHERE name='{$prop}';\n");
            }
        }
        $result = false;
        $effected_rows = 0;
        $count = count($queries);
        for ($i=0; $i<$count; $i++) {
            if ($this->exec($queries[$i])) {
                $effected_rows++;
            }
        }
        return ($effected_rows == ($count-1));
    }

    function getItem($id) {
        return $this->getConfigBean();
    }
    
    function index($refresh=false) {
        return array($this->getConfigBean());
    }
    
    function insert($Bean) { return false; }
}