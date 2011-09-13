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

class Contact extends TransferObject {

    /**
     * @var string
     */
    var $name;
    
    /**
     * @var string
     */
    var $title;
    
    /**
     * @var string
     */
    var $email;
    
    /**
     * @var string
     */
    var $phone;
    
    /**
     * @var string
     */
    var $fax;
    
    /**
     * @var string
     */
    var $address;
    
    /**
     * @var string
     */
    var $city;
    
    /**
     * @var string
     */
    var $state;
    
    /**
     * @var number
     */
    var $zip;
    
    /**
     * Gets the contact name
     * @return string  The contact name
     */
    function getName() {
        return $this->name;
    }
    
    /**
     * Sets the contact name
     * @param string $name  The contact's name
     * @return void
     */
    function setName($name) {
        $this->name = $name;
    }
    
    /**
     * Gets the contact title
     * @return string  The contact title
     */
    function getTitle() {
        return $this->title;
    }

    /**
     * Sets the contact title
     * @param string $title  The contact's title
     * @return void
     */
    function setTitle($title) {
        $this->title = $title;
    }
    
    /**
     * Gets the contact email
     * @return string  The contact email
     */
    function getEmail() {
        return $this->email;
    }
    
    /**
     * Sets the contact email
     * @param string $email  The contact's email
     * @return void
     */
    function setEmail($email) {
        $this->email = $email;
    }
    
    /**
     * Gets the contact phone
     * @return string  The contact phone
     */
    function getPhone() {
        return $this->phone;
    }
    
    /**
     * Sets the contact phone
     * @param string $phone  The contact's phone
     * @return void
     */
    function setPhone($phone) {
        $this->phone = $phone;
    }
    
    /**
     * Gets the contact fax
     * @return string  The contact fax
     */
    function getFax() {
        return $this->fax;
    }
    
    /**
     * Sets the contact fax
     * @param string $fax  The contact's fax
     * @return void
     */
    function setFax($fax) {
        $this->fax = $fax;
    }
    
    /**
     * Gets the contact address
     * @return string  The contact address
     */
    function getAddress() {
        return $this->address;
    }
    
    /**
     * Sets the contact address
     * @param string $address  The contact's address
     * @return void
     */
    function setAddress($address) {
        $this->address = $address;
    }
    
    /**
     * Gets the contact city
     * @return string  The contact city
     */
    function getCity() {
        return $this->city;
    }
    
    /**
     * Sets the contact city
     * @param string $city  The contact's city
     * @return void
     */
    function setCity($city) {
        $this->city = $city;
    }
    
    /**
     * Gets the contact state
     * @return string  The contact state
     */
    function getState() {
        return $this->state;
    }
    
    /**
     * Sets the contact state
     * @param string $state  The contact's state
     * @return void
     */
    function setState($state) {
        $this->state = $state;
    }
    
    /**
     * Gets the contact zip
     * @return number  The contact zip
     */
    function getZip() {
        return $this->zip;
    }
    
    /**
     * Sets the contact zip
     * @param string $zip  The contact's zip
     * @return void
     */
    function setZip($zip) {
        $this->zip = $zip;
    }
    
}