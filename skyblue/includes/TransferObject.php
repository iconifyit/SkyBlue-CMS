<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version     2.0 2009-04-19 10:37:00 $
 * @package        SkyBlueCanvas
 * @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license        GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General License, and as distributed it includes or
 * is derivative of works licensed under the GNU General License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

class TransferObject {

    var $_methods    = array();
    var $_properties = array();
    var $objtype;
    var $type;
    var $id;
    var $name;

    function __construct($options=array()) {

        $properties = array_keys(get_class_vars(get_class($this)));
        $methods    = get_class_methods(get_class($this));
        
        for ($i=0; $i<count($properties); $i++) {
            $property = $properties[$i];
            if ($property{0} != "_") {
                array_push($this->_properties, $property);
            }
        }
        
        for ($i=0; $i<count($methods); $i++) {
            $method = $methods[$i];
            if ($method{0} != "_") {
                array_push($this->_methods, $method);
            }
        }
        
        if (count($options)) {
            foreach ($options as $key=>$value) {
                if (in_array($key, $properties)) {
                    $method = 'set' . ucwords($key);
                    if (in_array($method, $this->_methods)) {
                        $this->$method($value);
                    }
                }
            }
        }
    }
    
    function getId() {
        return $this->id;
    }
    
    function setId($id) {
        $this->id = $id;
    }
    
    function getName() {
        return $this->name;
    }
    
    function setName($name) {
        $this->name = $name;
    }
    
    function setType($type) {
        $this->type = $type;
    }
    
    function getType() {
        return $this->type;
    }
    
    function setObjtype($objtype) {
        $this->objtype = $objtype;
    }
    
    function getObjtype() {
        return $this->objtype;
    }
    
    function getValueArray() {
        $arr = array();
        for ($i=0; $i<count($this->_properties); $i++) {
            $key = $this->_properties[$i];
            $method = 'get' . ucwords($key);
            if (in_array($method, $this->_methods)) {
                $arr[$key] = $this->$method();
            }
        }
        return $arr;
    }
    
    function & getValueObject() {
        $object = Utils::cloneObject($this);
        foreach ($object as $key=>$value) {
            if ($key{0} == "_") {
                unset($object->$key);
            }
        }
        return $object;
    }
    
    /**
     * Converts the current object to XML format
     * @return string  The XML representation of the object
     */
    
    function toXml() {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<object>\n";
        foreach ($this as $key=>$value) {
            $xml .= TransferObject::xmlNode($key, $value);
        }
        $xml .= "</object>\n\n";
        return $xml;
    }
    
    function xmlNode($key, $value) {
        $xml = "";
        if (is_array($value) || is_object($value)) {
            $xml .= "<{$key}>\n";
            foreach ($value as $key2=>$value2) {
                $xml .= TransferObject::xmlNode($key2, $value2);
            }
            $xml .= "</{$key}>\n";
        }
        else {
            if (!is_numeric($value)) {
                $value = "<![CDATA[{$value}]]>";
            }
            $xml .= "<{$key}>{$value}</{$key}>\n";
        }
        return $xml;
    }
    
    /**
     * Converts the current object to JSON format
     * @return string  The JSON representation of the object
     */
    
    function toJson() {
        return Utils::jsonEncode($this->getValueObject());
    }
    
    /**
     * Converts the current object to PHP serialized string format
     * @return string  The PHP serialized string
     */
    
    function toSerialized() {
        return serialize($this->getValueObject());
    }

}