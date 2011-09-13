<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2008-08-08 23:50:00 $
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
 * @author Todd Crowe
 * @date   August 08, 2008
 */

class Authorize extends Publisher {

    var $enabled = false;
    var $acos;
    var $acl;
    
    /**
      * Constructor/Destructor methods
      */

    function __construct($config=array()) {
        /* ACLs are disabled by default */
        $this->enabled = Filter::get($config, 'enabled', false);
        $this->user = Filter::get($config, 'User');
        $this->_declareEvents($config);
        $this->_init();
    }
    
    function _findAco($ControllerName) {
        $name = strtolower(str_replace('Controller', '', $ControllerName));
        return Utils::findObjByKey($this->getAcos(), 'name', $name);
    }

    /**
     * Public methods
     */

    /**
     * Checks the ACLs for a given object. Returns true if the ACLs pass.
     *
     * The general priority of ACLs is as follows:
     *  1) If ACLs are globally disabled, pass.
     *  2) If ACLs are locally disabled, pass.
     *  3) If the user is blocked or the ACL is invalid, fail.
     *  4a) Blacklist: if the user or one of the user's groups is blacklisted,
     *      fail; otherwise, pass.
     *  4b) Whitelist: if the user or one of the user's groups is whitelisted,
     *      pass; otherwise, fail.
     *  5) Parent/child filtering at the module level (see FilterChildObjects())
     */
     
    function check(&$Controller, $action) {
        
        $User = $this->user;
        
        $Aco = $this->_findAco(get_class($Controller));
        
        if (!is_object($Aco)) return false;
        
        $this->acl = new ACL(
            Utils::jsonDecode($Aco->getAcl()),
            $Aco->getName(),
            $User
        );

        // Pass if ACLs are globally disabled
        
        if (!$this->enabled) {
            return true;
        }
        
        // Fail if ACLs are enabled and no user is logged in
        
        if ($this->enabled) {
            if (empty($User)) return false;
            if (trim($User->getUsername()) == "") return false;
        }

        // Fail if the user is blocked.
        
        if ($User->getBlock()) {
            return false;
        }
        
        // Fail if the Aro does not have the User in its permissions table

        if (!$this->acl->check($Aco->getName(), $action, $User)) {
            return false;
        }
        
        // If all tests passed to this point, authorize access to the 
        // component and requested action
        
        return true;
    }
    
    function checkDataAccess($Object) {
        
        $User = $this->user;
        
        // Pass if the current data is not an object.
        
        if (!is_object($Object)) {
            return true;
        }
        
        // Pass if ACLs are disabled for this object
        
        $acltype = Filter::get($Object, 'acltype');
        if (empty($acltype) || $acltype == 'no_acls') {
            return true;
        }
        
        // Fail if the object has invalid ACLs
        
        if (!in_array($acltype, array('whitelist', 'blacklist'))) {
            return false;
        }

        /**
         * Blacklists/Whitelists:
         */
         
        $aclusers  = explode(",", Filter::get($Object, 'aclusers'));
        $aclgroups = explode(",", Filter::get($Object, 'aclgroups'));

        $uid = $User->getId();
        
        if ($acltype == 'blacklist') {
            
            // Pass if the blacklist is empty.
            
            if (empty($aclusers) && empty($aclgroups)) {
                return true;
            }

            // Fail if the user is blacklisted.
            
            if (in_array($uid, $aclusers)) {
                return false;
            }

            // Fail if one of the user's groups is blacklisted.
            
            $gids = $User->getGroups();
            
            foreach ($gids as $gid) {
                if (in_array($gid, $aclgroups)) {
                    return false;
                }
            }
            return true;
        }
        else {
        
            // Pass if the whitelist is empty.
            
            if (empty($aclusers) && empty($aclgroups)) {
                return false;
            }

            // Pass if the user is whitelisted.
            
            if (in_array($uid, $aclusers)) {
                return true;
            }

            // Pass if one of the user's groups is whitelisted.
            
            $gids = $User->getGroups();
            foreach ($gids as $gid) {
                if (in_array($gid, $aclgroups)) {
                    return true;
                }
            }
            return false;
        }
    }

    function EnableACLs($enable=true) {
        if ($enable && !$this->enabled) {
            $this->_init();
        }
        $this->enabled = $enable;
    }
    
    function canAccess($AcoName) {
        if ($AcoName == 'login') return true;
        global $Authenticate;
        $user = $Authenticate->user();
        $Aco = Utils::findObjByKey($this->acos, 'name', $AcoName);
        if (!is_object($Aco)) return true;
        $gids = explode(",", Filter::get($user, 'groups'));
        if (in_array($user->id, $Aco->getUsers())) {
            return true;
        }
        $count = count($gids);
        for ($i=0; $i<$count; $i++) {
            if (in_array($gids[$i],$Aco->getGroups())) {
                return true;
            }
        }
        return false;
    }

    /** 
     * Given two lists of objects and the names of elements that identify a
     * parent-child relationship betwen the two sets of objects, returns the
     * list of non-orphan child objects.
     *
     * This is used to filter out child objects that should be ACLs protected
     * by virtue of their parents' ACLs.
     *
     *   @parents   - list of parent objects.
     *   @children  - list of child objects.
     *   @parent_el - name of the parent object element that contains the id
     *                of the parent.
     *   @child_el  - name of the child object element that contains the id
     *                of the parent. May be scalar or a list.
     *   @orphans   - if false (default), orphans (child objects with no defined parents)
     *                are filtered out.
     */
     
    function FilterChildObjects($parents, $children, $parent_el, $child_el, $orphans=false) {
        
        $parentids = array();
        foreach ($parents as $parent) {
            array_push($parentids, $parent->$parent_el);
        }

        $filtered = array();
        foreach ($children as $child) {
            if (isset($child->$child_el) && !empty($child->$child_el)) {
                $childids = explode(",", $child->$child_el);
                foreach ($childids as $childid) {
                    if (in_array($childid, $parentids)) {
                        array_push($filtered, $child);
                        break;
                    }
                }
            }
            else if ($orphans) {
                array_push($filtered, $child);
            }
        }
        return $filtered;
    }

    /**
     * Private methods
     */

    function _declareEvents($config) {
        if (isset($config['events'])) {
            $events = $config['events'];
            for ($i=0; $i<count($events); $i++) {
                $this->addEvent($events[$i]);
            }
        }
    }
    
    function _loadAccessControlObjects() {
        global $Core;
        $Acos = array();
        $data = AdminaclHelper::getObjects();
        foreach ($data as $Aco) {
            array_push($Acos, new ACO($Aco));
        }
        $this->setAcos($Acos);
    }
    
    function getAcos() {
        return $this->acos;
    }
    
    function setAcos($Acos) {
        $this->acos = $Acos;
    }
    
    function _init() {
        Loader::load("managers.adminacl.AdminaclHelper", true, _SBC_SYS_);
        $this->_loadAccessControlObjects();
    }
}