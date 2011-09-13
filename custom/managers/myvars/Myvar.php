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
* @date   Novermber 7, 2010
*/

class Myvar extends TransferObject {

    /**
     * @var string
     */
    var $name;
    
    /**
     * @var string
     */
    var $vartype;
    
    /**
     * @var string
     */
    var $value;
    
    /**
     * Gets the myvar name
     * @return string  The name
     */
    function getName() {
        return $this->name;
    }
    
    /**
     * Sets the myvar name
     * @param string $name  The name
     * @return void
     */
    function setName($name) {
        $this->name = $name;
    }
    
    /**
     * Gets the vartype
     * @return string  The vartype
     */
    function getVartype() {
        return $this->vartype;
    }
    
    /**
     * Sets the vartype
     * @param string $vartype  The myvar's type
     * @return void
     */
    function setVartype($vartype) {
        $this->vartype = $vartype;
    }
    
    /**
     * Gets the vartype
     * @return string
     */
    function getValue() {
        return $this->value;
    }
    
    /**
     * Sets the value
     * @param string $value  The myvar's value
     * @return void
     */
    function setValue($value) {
        $this->value = $value;
    }
    
}