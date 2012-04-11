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

class PluginDAO extends SqliteDAO {

    function __construct() {
        parent::__construct(array(
            'type' => 'plugin',
            'bean_class' => 'Plugin'
        ));
    }
    
    function getFileList() {
        $items = array();
        $files = FileSystem::list_files(SB_USER_PLUGINS_DIR, 0);
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
                $Bean = new Plugin(array(
                    'id'        => basename($files[$i]),
                    'name'      => basename($files[$i]),
                    'type'      => 'plugin',
                    'objtype'   => 'plugin',
                    'published' => 0,
                    'ordinal'   => count($synched) + 1
                ));
                $this->insert($Bean);
                array_push($synched, $Bean);
            }
        }
        Utils::sort($synched, 'ordinal');
        return $synched;
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
            if (strcasecmp($name, 'id') === 0) continue;
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
        $query .= implode(',', $pairs) . " WHERE id = '{$Bean->getId()}';";
        return $query;
    }

    function insert($Bean) {
        $result = false;
        if (! $this->getItem($Bean->getId())) {
            if ($info = $this->getColumns()) {
                $result = $this->query(
                    $this->buildInsertQuery($info, $Bean)
                );
            }
        }
        return $result;
    }    
    
    function reorder($id, $direction) {
        $result = false;
        if ($Plugin = $this->getItem($id)) {
            $plugins   = $this->index();
            $count     = count($pugins);
            $ordinal   = $Plugin->getOrdinal();
            $hold      = $ordinal;
            
            $index = $this->getOffset($Plugin, $plugins);

            if ($count == 1) return false;
            
            if (strcasecmp($direction, 'up') == 0) {
            
                // If this is the first item in the list 
                // it can't move up any more.
                
                if ($index == 0) return false;
                
                $Plugin1 = null;
                if (isset($plugins[$index-1])) {
                    $Plugin1 = $plugins[$index-1];
                }
                
                $Plugin2 = null;
                if (isset($plugins[$index-2])) {
                    $Plugin2 = $plugins[$index-2];
                }
                
                /*
                  When determining the order of items, it is not important 
                  whether or not there are actually items to move the item between.
                  It is sufficient to guess where items would likely be. So we guess the 
                  most likely position of items to move between. If no items are found, 
                  then we can determine the true 'between' spot. Otherwise, we move between 
                  2 virtual items.
                */
                
                // $index1 defaults to one ordinal lower than 
                // the item being moved down
                
                $index1 = $ordinal - 1;
                
                // $index2 defaults to one position lower than $index1
                
                $index2 = $index1 - 1;
            }
            else {
                
                // If this is the last item in the list
                // it can't move down any more
            
                if ($index == $count-1) return false;
                
                $Plugin1 = null;
                if (isset($plugins[$index+1])) {
                    $Plugin1 = $plugins[$index+1];
                }
                
                $Plugin2 = null;
                if (isset($plugins[$index+2])) {
                    $Plugin2 = $plugins[$index+2];
                }
                
                // $index1 defaults to one ordinal higher than 
                // the item being moved down
                
                $index1 = $ordinal + 1;
                
                // $index2 defaults to one position higher than $index1
                
                $index2 = $index1 + 1;
            }
            
            if ($Plugin1 && $Plugin2) {
                $index1 = $Plugin1->getOrdinal();
                $index2 = $Plugin2->getOrdinal();
            }
            $Plugin->setOrdinal(($index1 + $index2)/2);
            $result = $this->update($Plugin);
        }
        return $result;
    }
    
    function getOffset($Plugin, $plugins) {
        $index = -1;
        $count = count($plugins);
        for ($i=0; $i<$count; $i++) {
            if ($Plugin->getId() == $plugins[$i]->getId()) {
                $index = $i;
            }
        }
        return $index;
    }
    
    function delete($id) { return false; }
}