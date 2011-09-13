<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2009-05-16 23:50:00 $
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

class ACO extends TransferObject {

    var $id;
    var $name;
    var $users;
    var $groups;
    var $acl;
    
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
    
    function getUsers() {
        return explode(",", $this->users);
    }
    
    function setUsers($users) {
        if (!is_array($users)) {
            $users = array($users);
        }
        $this->users = implode(",", $users);
    }
    
    function getGroups() {
        return explode(",", $this->groups);
    }
    
    function setGroups($groups) {
        if (!is_array($groups)) {
            $groups = array($groups);
        }
        $this->groups = implode(",", $groups);
    }
    
    function getAcl() {
        return $this->acl;
    }
    
    function setAcl($acl) {
        $this->acl = $acl;
    }
    
    function has($User) {
        if (in_array($User->getId(), $this->getUsers())) {
            return true;
        }
        else {
            $acogroups = $this->getGroups();
            $usergroups = $User->getGroups();
            $count = count($usergroups);
            for ($i=0; $i<$count; $i++) {
                if (in_array($usergroups[$i], $acogroups)) {
                    return true;
                }
            }
            return false;
        }
        return false;
    }
    
    function hasUser($action, $uid) {
        $acl = Utils::jsonDecode($this->getAcl());
        if (isset($acl->$action)) {
            if (isset($acl->$action->users)) {
                return in_array($uid, $acl->$action->users);
            }
            return false;
        }
        return false;
    }
    
    function hasGroup($action, $gid) {
        $acl = Utils::jsonDecode($this->getAcl());
        if (isset($acl->$action)) {
            if (isset($acl->$action->groups)) {
                return in_array($gid, $acl->$action->groups);
            }
            return false;
        }
        return false;
    }

}

