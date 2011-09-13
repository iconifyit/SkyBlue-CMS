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

class User extends TransferObject {
     
    /**
     * @var string
     */
    var $username;
    
    /**
     * @var string
     */
    var $password;
    
    /**
     * @var string
     */
    var $email;
    
    /**
     * @var string
     */
    var $name;
    
    /**
     * @var string
     */
    var $groups;
    
    /**
     * @var int
     */
    var $block;
    
    /**
     * @var string
     */
    var $lastlogin;
    
    /**
     * @var string
     */
    var $tempkey;
    
    /**
     * @var int
     */
    var $tempkeyexpiration;
    
    /**
     * Gets the User's username
     * @return string  The username
     */
    function getUsername() {
        return $this->username;
    }
    
    /**
     * Sets the User's username
     * @param string $username  The username
     * @return void
     */
    function setUsername($username) {
        $this->username = $username;
    }
    
    /**
     * Gets the User's password
     * @return string  The password
     */
    function getPassword() {
        return $this->password;
    }
    
    /**
     * Sets the User's password
     * @param string $password  The password
     * @return void
     */
    function setPassword($password) {
        $this->password = $password;
    }
    
    /**
     * Gets the User's email
     * @return string  The email
     */
    function getEmail() {
        return $this->email;
    }
    
    /**
     * Sets the User's email
     * @param string $email  The email
     * @return void
     */
    function setEmail($email) {
        $this->email = $email;
    }
    
    /**
     * Gets the groups to which the User belongs
     * @return array
     */
    function getGroups() {
        $groups = $this->groups;
        if (!is_array($groups)) {
            $groups = explode(',', $groups);
        }
        return $groups;
    }
    
    /**
     * Sets the groups to which the User belongs
     * @param array  Array of the IDs of groups to which the item belongs
     * @return void
     */
    function setGroups($groups) {
        if (is_array($groups)) {
            $groups = implode(',', $groups);
        }
        $this->groups = $groups;
    }
    
    /**
     * Gets a boolean flag indicating whether or not the user is blocked
     * @return string  A boolean flag indicating whether or not the user is blocked
     */
    function getBlock() {
        return $this->block;
    }
    
    /**
     * Sets a boolean flag indicating whether or not the user is blocked
     * @param string $block  A boolean flag indicating whether or not the user is blocked
     * @return void
     */
    function setBlock($block) {
        $this->block = $block;
    }
    
    /**
     * Gets the date of the User's last login
     * @return String  The string date of the last login
     */
    function getLastlogin() {
        return $this->lastlogin;
    }
    
    /**
     * Sets the date of the User's last login
     * @param String $lastLogin   The string date of the last login
     * @return void
     */
    function setLastlogin($lastlogin) {
        $this->lastlogin = "".$lastlogin;
    }
    
    /**
     * Sets a temporary password reset key
     * @param string $tempKey  The temporary key
     * @return void
     */
    function setTempkey($tempkey) {
        $this->tempkey = $tempkey;
    }
    
    /**
     * Gets the temporary password reset key
     * @return string
     */
    function getTempkey() {
        return $this->tempkey;
    }
    
    /**
     * Sets a password expiration
     * @param int  The timestamp at which the password expires
     * @return void
     */
    function setTempkeyexpiration($tempkeyexpiration) {
        $this->tempkeyexpiration = $tempkeyexpiration;
    }
    
    /**
     * Gets the password expiration time
     * @return int
     */
    function getTempkeyexpiration() {
        return $this->tempkeyexpiration;
    }
}