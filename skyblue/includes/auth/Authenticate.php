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

define('SESS_IS_TIMED_OUT', 1);

class Authenticate extends Publisher {

    var $enabled       = true;      /* False => no one can be authenticated. */
    var $refresh       = true;      /* True => timeout updates on page load  */
    var $cookies       = false;
    var $js            = false;
    var $message       = null;
    var $users         = array();
    var $groups        = array();
    var $form_err      = USER_OK;

    /**
     * Constructor/Destructor methods
     */

    function __construct($config=array()) {
    
        require_once(SB_MANAGERS_DIR . "users/UsersHelper.php");
        # require_once(SB_MANAGERS_DIR . "users/helpers/users.php");

        $Session = Singleton::getInstance('Session');
        
        $this->enabled = Filter::get($config, 'enabled', true);
        $this->refresh = Filter::get($config, 'refresh', true);
        
        $this->_load();
        
        /** 
        * Make sure the user authentication is still valid.
        * (e.g. it may have timed out)
        */
        
        if (! $this->isValidSession()) {
            $this->InvalidateUser();
            $Session->set('User', $this->getAnonymousUser());
        }
        else if ($this->refresh) {
            $this->_refreshUser();
        }
    }

    /*
     * Public methods
     */

    /* Returns either the complete current validated user object or just one field
     * from the object.  The user object will be filled with empty fields if the user
     * is not validated.
     */
    function user($field="") {
        $User = $this->_getuser();
        $method = "get" . ucwords($field);
        if (is_callable($User, $method)) {
            return $User->$method();
        }
        return $User;
    }

    /**
     * TODO: CheckBrowserConfig should not be in this class
     */
    function CheckBrowserConfig() {
        global $Core;

        $this->js = Filter::get($_GET, 'js', 0, 1, 0);
        if (!$this->js) {
            $redirect = Filter::get($_GET, 'redir', NULL);
            $script = str_replace(
                '{redirect}', 
                $redirect != NULL ? '&redir='.urlencode($redirect) : '', 
                USER_JS_TEST_SCRIPT
            );
            echo $script;
        }

        if (!isset($_COOKIE) || empty($_COOKIE)) {
            $this->cookies = 0;
        }
        else {
            $this->cookies = 1;
        }

        if (!$this->js && !$this->cookies) {
            $this->message  = USER_MUST_HAVE_JS_AND_COOKIES;
        }
        else if (!$this->js) {
            $this->message  = USER_MUST_HAVE_JS;
        }
        else if (!$this->cookies) {
            $this->message  = USER_MUST_HAVE_COOKIES;
        }
    }

    function IsAdmin() {
        if (!$this->enabled) return false;
        $gids = $this->user('groups');
        return $this->_containsAdmin($gids);
    }

    function IsValidUser() {
        if (!$this->enabled) return false;
        return $this->isValidSession();
    }

    function InvalidateUser($redirect=null) {
        $Session = Singleton::getInstance('Session');
        $Session->clear('TIMEOUT');
        $Session->clear('User');
        $redirect = Filter::get($_GET, 'redir', $redirect);
        if (!empty($redirect) && Filter::get($_GET, 'action', null) == 'logout') {
            Utils::redirect($redirect);
        }
    }

    function Refresh() {
        $this->_loadUsers();
        $this->_loadUserGroups();
    }

    function ValidateUser($username, $password) {
    
        /**
        * Find the user object for the current login.
        */
        $User = Utils::findObjByKey($this->users, 'username', $username);
        if (!$User) {
            $this->InvalidateUser();
            return false;
        }

        /**
        * Check the password.
        */
        if ($User->getPassword() != Utils::fingerprint($password)) {
            $this->InvalidateUser();
            return false;
        }

        $gids = $User->getGroups();
        
        $Session = Singleton::getInstance('Session');
        $Session->set('TIMEOUT', time() + (
            $this->_containsAdmin($gids) ? SB_ADMIN_TIMEOUT : SB_USER_TIMEOUT
        ));
        $Session->set('User', $User);
        return true;
    }

    /*
     * Private methods
     */

    function _containsAdmin($gids) {
        foreach ($gids as $gid) {
            $Group = $this->_getGroup($gid);
            if ($Group && $Group->siteadmin) {
                return true;
            }
        }
        return false;
    }

    function _getGroup($gid) {
        foreach ($this->groups as $Group) {
            if ($Group->getId() == $gid)
                return $Group;
        }
        return null;
    }

    function _getuser() {
        $Session =& Singleton::getInstance('Session');
        if ($Session->is_set('User')) {
            $User = $Session->getUser();
        }
        else {
            $User = $this->getAnonymousUser();
        }
        return $User;
    }
    
    /**
     * Gets an anonymous (not logged-in) User
     * @return User
     */
    
    function getAnonymousUser() {
        static $User;
        if (!is_object($User)) {
            $User = UsersHelper::getUserBean();
            $User->setId(0);
            $User->setUsername('anonymous');
            $User->setName("Visitor");
            $User->setBlock(0);
            $User->setGroups(3);
        }
        return $User;
    }
    
    function _load() {
        $this->_loadUsers();
        $this->_loadUserGroups();
    }

    function isValidSession() {
        $Session = Singleton::getInstance('Session');
        $User = $Session->getUser('User');
        
        if (!$Session->is_empty('User') &&
            !$User->getBlock() && 
            !$Session->is_empty('TIMEOUT') && 
            $Session->get('TIMEOUT') > time()) {
                
            return true;
        }
        else if (! $Session->is_empty('TIMEOUT') && 
                   $Session->get('TIMEOUT') < time()) {
            
            $Session->addMessage(
                'warning',
                'Warning',
                __('GLOBAL.SESSION_TIMED_OUT', 'Your Session has timed out', 1)
            );
            return false;
        }
        return false;
    }

    function _loadUsers() {
        $this->users = UsersHelper::getUsers();
    }

    function _loadUserGroups() {
        $this->groups = UsersHelper::getUsergroups();
    }

    function _refreshUser() {
        $User = $this->user();
        $gids = $User->getGroups();
        $timeout = $this->_containsAdmin($gids) ? SB_ADMIN_TIMEOUT : SB_USER_TIMEOUT ;
        $Session =& Singleton::getInstance('Session');
        $Session->set('TIMEOUT', time() + $timeout);
    }
}