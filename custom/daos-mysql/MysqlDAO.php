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

class MysqlDAO extends DAO {

    var $type;
    var $dataSubPath;
    var $source;

    function __construct($options) {
        $this->setType(Filter::getAlphaNumeric($options, 'type'));
        $this->setBeanClass(Filter::get($options, 'bean_class'));
        parent::__construct(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, 
            DB_USER,
            DB_PASS
        );
    }
    
    function getType() {
        return $this->type;
    }
    
    function setType($type) {
        $this->type = $type;
    }
    
    function setSource($source) {
        $this->source = $source;
    }
    
    function getSource() {
        return $this->source;
    }

    function index($refresh=false) {
        return parent::index("SELECT * FROM {$this->getBeanClass()}");
    }
    
    function getQueryResult($query) {
        $Statement = $this->query($query);
        return $Statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function doQuery($query) {
        return $this->query($query);
    }
    
    function getFieldType($field) {
        $fieldType = "";
        $type = Filter::get($field, 'type');
        if (strncasecmp(substr($type, 0, 3), 'INT')) {
            $fieldType = "INTEGER";
        }
        else if (strncasecmp(substr($type, 0, 4), 'CHAR')) {
            $fieldType = "CHAR";
        }
        else if (strncasecmp(substr($type, 0, 4), 'TEXT')) {
            $fieldType = "TEXT";
        }
        return $fieldType;
    }
    
    function buildUpdateQuery($fields, $Bean) {
        $query = "UPDATE {$this->getBeanClass()} SET ";
        $pairs = array();
        foreach ($fields as $field) {
            $name  = Filter::get($field, 'field');
            if (strcasecmp($name, 'id') === 0) continue;

            $type  = $this->getFieldType($field);
            $value = Filter::get($Bean, $name);
            if (strcasecmp($name, 'type') === 0 || strcasecmp($name, 'objtype') === 0) {
                $value = $this->getType();
            }
            if (strncasecmp($type, 'INTEGER') !== 0) {
                $value = "'{$value}'";
            }
            else if (trim($value) == "") {
                $value = 0;
            }
            array_push($pairs, " {$name} = {$value}");
        }
        $query .= implode(',', $pairs) . " WHERE id = {$Bean->getId()};";
        return $query;
    }
    
    function buildInsertQuery($fields, $Bean) {
        $query = "INSERT INTO {$this->getBeanClass()} ";
        $pairs = array();
        
        $fieldList = array();
        $valueList = array();
        foreach ($fields as $field) {
            $name  = Filter::get($field, 'field');
            if (strcasecmp($name, 'id') === 0) continue;
            
            $type  = $this->getFieldType($field);
            $value = Filter::get($Bean, $name);
            if (strcasecmp($name, 'type') === 0 || strcasecmp($name, 'objtype') === 0) {
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
    
    function getColumns() {
        $columns = array();
        $Statement = $this->query(
			"SHOW COLUMNS FROM {$this->getBeanClass()}"
		);
		$rows = $Statement->fetchAll(PDO::FETCH_ASSOC);
		$count = count($rows);
		for ($i=0; $i<$count; $i++) {
			$row = $rows[$i];
			foreach ($row as $key=>$value) {
				unset($row[$key]);
				$row[strtolower($key)] = $value;
			}
			array_push($columns, $row);
		}
        return $columns;
    }
    
    function getColumnNames() {
        $names = array();
        $fields = $this->getColumns();
        for ($i=0; $i<count($fields); $i++) {
            $name = Filter::get($fields[$i], 'field');
            if (! in_array($name, $names)) {
                array_push($names, $name);
            }
        }
        return $names;
    }

    function insert($Bean) {
        return parent::insert(
            $this->buildInsertQuery($this->getColumns(), $Bean)
        );
    }

    function update($Bean) {
        return parent::update(
            $this->buildUpdateQuery($this->getColumns(), $Bean)
        );
    }
    
    /**
     * Programming a generic copy/clone method is problematic from a usability stand-point. 
     * An end user is likely to look at a field like 'name' to differentiate between items 
     * in a list. However, we have no guarantee that a name field exists - or any other field 
     * for that matter. The ID field is the primary key and is, therefore, the only field 
     * that must be unique.
     * 
     * The best solution for this situation is to write an object-specific copy method.
     * 
     * That being said, here is a generic copy method where the ID is the only unique field.
     *
     * @param  int   $id  The id of the item to copy
     * @return bool
     */
        
    function copy($id) {
        $result = false;
        if ($Bean = $this->getItem($id)) {
            $result = $this->insert($Bean);
        }
        return $result;
    }
    
    function getItem($id) {
        $query = "SELECT * FROM {$this->getBeanClass()} WHERE id = {$id} LIMIT 1";
		if (! is_numeric($id)) {
			$query = "SELECT * FROM {$this->getBeanClass()} WHERE id = '{$id}' LIMIT 1";
		}
		return parent::getItem($query);
    }
    
    function getByKey($key, $value) {
        $result = null;
        if ($fields = $this->getColumns()) {
			$count = count($fields);
			$query = "SELECT * FROM {$this->getBeanClass()} WHERE {$key} = '{$value}'";
			for ($i=0; $i<$count; $i++) {
				if (strcasecmp(Filter::get($fields[$i], 'name'), $key) === 0) {
					if (strcasecmp(Filter::get($fields[$i], 'type'), 'INTEGER') === 0) {
						$query = "SELECT * FROM {$this->getBeanClass()} WHERE {$key} = {$value}";
					}
				}
			}
			$result = parent::getItem($query);
        }
		return $result;
    }
    
    function getValue($table, $column, $key, $value) {
        $result = "";
		try {
			$query = "SELECT {$column} FROM {$table} WHERE {$key} = {$value}";
			$Statement = $this->query($query);
			if ($result = $Statement->fetch()) {
			    $result = is_array($result) ? $result[0] : $result ;
			}
		}
		catch (PDOException $e) {
			die($e->getMessage());
		}
		return $result;
    }
    
    function countItems() {
        return count($this->index());
    }
    
    function getByName($name) {
        return parent::getItem(
            "SELECT * FROM {$this->getBeanClass()} WHERE name = '{$name}' LIMIT 1"
        );
    }
    
    function delete($id) {
        $query = "DELETE FROM {$this->getBeanClass()} WHERE id = {$id};";
		if (! is_numeric($id)) {
			$query = "DELETE FROM {$this->getBeanClass()} WHERE id = '{$id}';";
		}
        return parent::delete($query);
    }
    
    function getLastInsertId() {
        $result = "";
		try {
			$query = "SELECT from {$this->getBeanClass()} LAST_INSERT_ID()";
			$Statement = $this->query($query);
			if ($result = $Statement->fetch()) {
			    $result = is_array($result) ? $result[0] : $result ;
			}
		}
		catch (PDOException $e) {
			die($e->getMessage());
		}
		return $result;
    }
    
    function tableExists($table) {
        $isFound = false;
        $tables = array();
        
        if ($Statement = $this->query("SHOW TABLES from " . DB_NAME)) {
			if ($rows = $Statement->fetchAll()) {
				$count = count($rows);
				for ($i=0; $i<$count; $i++) {
					array_push($tables, $rows[$i][0]);
				}
				$isFound = in_array($table, $tables); 
			}
		}
		return $isFound;   
    }

    function save($data=null) { return false; }
    function deleteAll() { return false; }
    function exists($name) { return false; }
}