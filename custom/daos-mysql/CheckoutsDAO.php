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

class CheckoutsDAO extends MysqlDAO {

    function __construct() {
        parent::__construct(array(
            'type' => 'checkout',
            'bean_class' => 'Checkout'
        ));
    }

    function index() {
        return parent::index();
    }
    
    function deleteAll() {
        $result = false;
        if ($this->query("DELETE FROM {$this->getBeanClass()};")) {
            $result = true;
        }
        return $result;
    }
    
    function buildInsertQuery($fields, $Bean) {
        $query = "INSERT INTO {$this->getBeanClass()} ";
        $pairs = array();
        
        $fieldList = array();
        $valueList = array();
        foreach ($fields as $field) {
            $name  = Filter::get($field, 'field');
            $type  = $this->getFieldType($field);
            $value = Filter::get($Bean, $name);
            if (strncasecmp($name, 'type') === 0 || strcasecmp($name, 'objtype') === 0) {
                $value = $this->getType();
            }
            if (strncasecmp($type, 'INTEGER') !== 0) {
                $value = "'{$value}'";
            }
            else if (trim($value) == "") {
                $value = 0;
            }
            
            array_push($fieldList, $name);
            array_push($valueList, $value);
        }
        $query .= "(" . implode(",", $fieldList) . ") VALUES ";
        $query .= "(" . implode(",", $valueList) . ");";
        return $query;
    }
}