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

class SkinDAO extends SqliteDAO {

    var $data;
    var $source;

    function __construct() {
        parent::__construct(array(
            'type' => 'skin',
            'bean_class' => 'Skin'
        ));
    }
        
    function setActiveSkin($id) {
        $result = false;
        if ($Skin = $this->getItem($id)) {
            $result = $this->query(
                "UPDATE {$this->getBeanClass()} SET published = 0 WHERE published <> 0"
            );
            if ($result) {
                $result = $this->query(
                    "UPDATE {$this->getBeanClass()} SET published = 1 WHERE id = '{$id}'"
                );
            }
        }
        return $result;
    }
    
    function insert($Bean) {
        $result = false;
        if (! $this->getItem($Bean->getId())) {
            if ($info = $this->getColumns()) {
                $result = $this->exec(
                    $this->buildInsertQuery($info, $Bean)
                );
            }
        }
        return $result;
    }
    
    function buildInsertQuery($fields, $Bean) {
        $query = "INSERT INTO {$this->getBeanClass()} ";
        $pairs = array();
        
        $fieldList = array();
        $valueList = array();
        foreach ($fields as $field) {
            $name  = Filter::get($field, 'name');
            $type  = Filter::get($field, 'type');
            $value = Filter::get($Bean, $name);
            if (strcasecmp($name, 'type') === 0 || strcasecmp($name, 'objtype') === 0) {
                $value = $this->getType();
            }
            if (strcasecmp($type, 'INTEGER') !== 0) {
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
    
    function buildUpdateQuery($fields, $Bean) {
        $query = "UPDATE {$this->getBeanClass()} SET ";
        $pairs = array();
        foreach ($fields as $field) {
            $name  = Filter::get($field, 'name');
            $type  = Filter::get($field, 'type');
            $value = Filter::get($Bean, $name);
            if (strcasecmp($name, 'type') === 0 || strcasecmp($name, 'objtype') === 0) {
                $value = $this->getType();
            }
            if (strcasecmp($type, 'INTEGER') !== 0) {
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
        
    function getFileList() {
        $items = array();
        $files = FileSystem::list_dirs(_SBC_WWW_ . SB_SKINS_DIR, 0);
        $count = count($files);
        for ($i=0; $i<$count; $i++) {
            $name = strtolower(basename($files[$i]));
            array_push($items, $name);
        }
        return $items;
    }
    
    function index($refresh=false) {
        $synched = array();
        $files = $this->getFileList();
        $Objects  = parent::index();
        foreach ($Objects as $Object) {
            if (in_array(strtolower(Filter::get($Object, 'name')), $files)) {
                array_push($synched, $Object);
            }
        }
        $count = count($files);
        $skinCount = count($synched) + $count;
        for ($i=0; $i<$count; $i++) {
            if (! Utils::findObjByKey($synched, 'name', $files[$i])) {
                $Skin = new Skin(array(
                    'id'      => basename($files[$i]), 
                    'name'    => basename($files[$i]), 
                    'type'    => 'skin', 
                    'objtype' => 'skin',
                    'published' => $skinCount > 1 ? 0 : 1
                ));
                $this->insert($Skin);
                array_push($synched, $Skin);
            }
        }
        return $synched;
    }
    
    function save() { return false; }
    function delete() { return false; }
    function copy($TransferObject) { return false; }
    
}