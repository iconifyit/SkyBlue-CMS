<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2009-09-07 09:08:00 $
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
 * @date   September 7, 2009
 */

class ACL {
    
    var $acl;
    var $aco;
    var $aro;
    
    function __construct($acl, $aco, $aro) {
        $this->acl = $acl;
        $this->aco = $aco;
        $this->aro = $aro;
    }
    
    function check($AcoName, $action, $User) {
        if (!$this->hasAction($action)) {
            return false;
        }
        if ($this->hasUser($AcoName, $action, $User->getId())) {
            return true;
        }
        if ($this->hasGroup($AcoName, $action, $User->getGroups())) {
            return true;
        }
        return false;
    }
    
    function hasAction($action) {
        return isset($this->acl->$action);
    }
    
    function hasUser($AcoName, $action, $uid) {
        return in_array($uid, Filter::get(
            Filter::get($this->getAcl(), $action), 'users', array()
        ));
    }
    
    function hasGroup($AcoName, $action, $gids) {
        $groups = Filter::get(Filter::get($this->getAcl(), $action), 'groups');
        if (!is_array($gids)) $gids = array($gids);
        $count = count($gids);
        for ($i=0; $i<$count; $i++) {
            if (in_array($gids[$i], $groups)) {
                return true;
            }
        }
        return false;
    }
    
    function getAcl() {
        return $this->acl;
    }
    
    function setAcl($acl) {
        $this->acl = $acl;
    }
    
    function getAco() {
        return $this->aco;
    }
    
    function setAco($aco) {
        $this->aco = $aco;
    }
    
    function getAro() {
        return $this->aro;
    }
    
    function setAro($aro) {
        $this->aro = $aro;
    }
}