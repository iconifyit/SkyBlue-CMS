<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2009-06-22 17:37:00 $
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
 * @date   June 22, 2009
 */

class Adminacl extends TransferObject {
    
    /**
     * The object's unique ID
     * @var int
     */
    var $id;
    
    /**
     * The object type
     * @var string
     */
    var $type;
    
    /**
     * The object type
     * @var string
     */
    var $objtype;
    
    /**
     * The object name
     * @var string
     */
    var $name;

    /**
     * A list of authorized users
     * @var array
     */
    var $users;

    /**
     * A list of authorized groups
     * @var array
     */
    var $groups;
    
    /**
     * Get the unique ID
     * @return int  The object's unique ID
     */
    function getId() {
        return $this->id;
    }
    
    /**
     * Set the object's ID
     * @param $id
     * @return void
     */
    function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Get the object type
     * @return string  The object type
     */
    function getType() {
        return $this->type;
    }
    
    /**
     * Set the object type
     * @param string $type  The object type
     * @return void
     */
    function setType($type) {
        $this->type = $type;
    }
    
    /**
     * Get the object type
     * @return string  The object type
     */
    function getObjtype() {
        return $this->objtype;
    }
    
     /**
     * Set the object type
     * @param string $type  The object type
     * @return void
     */
    function setObjtype($objtype) {
        $this->objtype = $objtype;
    }
    
    /**
     * Get the object name
     * @return string  The object's name
     */
    function getName() {
        return $this->name;
    }
    
    /**
     * Set the object's name
     * @param string $name  The name of the object
     * @return void
     */
    function setName($name) {
        $this->name = $name;
    }
    
    /**
     * Get a list of Users authorized to view/edit this object
     * @return array
     */
    function getUsers() {
        $users = $this->users;
        if (is_string($users)) {
            $users = explode(',', $users);
        }
        return $users;
    }
    
    /**
     * Set the authorized Users
     * @param array $users  An array of Users authorized to view/edit this object
     * @return void
     */
    function setUsers($users) {
        if (is_array($users)) {
            $users = implode(',', $users);
        }
        $this->users = $users;
    }
    
    /**
     * Add a User authroized to view/edit this object
     * @param int $userid  The ID of the User to add
     * @return void
     */
    function addUser($userid) {
        $users = $this->getUsers();
        if (!is_array($users)) {
            $users = array();
        }
        array_push($users, $userid);
        $this->setUsers($users);
    }
    
    /**
     * Get a list of Groups authorized to view/edit this object
     * @return array
     */
    function getGroups() {
        $groups = $this->groups;
        if (is_string($groups)) {
            $groups = explode(',', $groups);
        }
        return $groups;
    }
    
    /**
     * Set the authorized Groups
     * @param array $groups  An array of Groups authorized to view/edit this object
     * @return void
     */
    function setGroups($groups) {
        if (is_array($groups)) {
            $groups = implode(',', $groups);
        }
        $this->groups = $groups;
    }
    
    /**
     * Add a Group authroized to view/edit this object
     * @param int $groupid  The ID of the Group to add
     * @return void
     */
    function addGroup($groupid) {
        $groups = $this->getGroups();
        if (!is_array($groups)) {
            $groups = array();
        }
        array_push($groups, $groupid);
        $this->setGroups($groups);
    }
}