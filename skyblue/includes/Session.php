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

class Session extends Publisher {

    var $id;
    var $name;
    var $starttime;
    var $endtime;
    var $User;

    function __construct($options=array()) {
        $this->start($options);
    }
    
    function getInstance($options=array(), $refresh=false) {
        $Session = Singleton::getInstance('Session');
        $Session->start($options);
        return $Session;
    }
    
    function start($options=array()) {
    
        $sessid      = Filter::get($options, 'sessid');
        $lifetime    = Filter::get($options, 'lifetime', SB_SESSION_LIFETIME);
        $cookie_path = Filter::get($options, 'cookie_path', SB_COOKIE_PATH);
        $name        = Filter::get($options, 'name');
    
        $this->trigger('session.beforeStart');
    
        if (is_callable('ini_set')) {
            ini_set('session.save_path', SB_SESSION_SAVE_PATH);
            ini_set('session.cookie_path', SB_COOKIE_PATH);
        }
    
        // FIXME: Setting a custom session_id breaks the session
        /*
        $phpsessid = Filter::get($_REQUEST, SB_PHPSESSID);
        if (empty($phpsessid)) {
            $bits = explode('.', Filter::get($_SERVER, 'REMOTE_ADDR'));

            $count = count($bits);
            for ($i=0; $i<$count; $i++) {
                if (strlen($bits[$i]) < 3) {
                    $n = 0;
                    while ((strlen($bits[$i]) < 3) && $n < 3) {
                        $bits[$i] = '0'.$bits[$i];
                        $n++;
                    }
                }
            }
            $sid  = implode('.', $bits);
            $sid .= 'xx'.time();
            $sid  = str_replace('.', 'x', $sid);
            $sid  = !empty($sessid) ? $sessid : $sid ;
            # session_id($sid);
            # die(session_id());
        }
        */
        
        session_set_cookie_params(
            $lifetime,
            $cookie_path
        );
        
        if (!empty($name)) {
            session_name($ses);
        }
        
        session_start();
        
        $visitorid = Filter::get($_SESSION, 'visitor.id');
        if (empty($visitorid)) {
            $_SESSION['visitor.id'] = session_id();
            $_SESSION['session.starttime'] = time();
        }
        
        $messages = Filter::get($_SESSION, 'messages');
        if (empty($messages)) {
            $_SESSION['messages'] = array();
        }
        
        $errors = Filter::get($_SESSION, 'messages.errors');
        if (empty($errors)) {
            $_SESSION['messages.errors'] = array();
        }
        
        $this->id = Filter::get($_SESSION, 'visitor.id');
        $this->starttime = Filter::get($_SESSION, 'session.starttime');
        
        $this->trigger('session.afterStart');
    }
    
    function addMessage($type, $title, $text) {
    
        $messages = Filter::get($_SESSION, 'messages', array());

        if (!is_array($messages)) {
            $messages = array($messages);
        }
        array_push($messages, array(
            'type'     => $type,
            'title'    => $title, 
            'message'  => $text    
        ));
        $_SESSION['messages'] = $messages;
    }
    
    function addError($msg) {
        
        $messages = Filter::get($_SESSION, 'messages.errors', array());
        
        if (!is_array($messages)) {
            $messages = array($messages);
        }
        array_push($messages, array(
            'text'  => $msg, 
            'type'  => 'error',
            'title' => '[TERM:GLOBAL.ERROR]'
        ));
        $_SESSION['messages.errors'] = $messages;
    }
    
    function getErrorMessages() {
        $messages = Filter::get($_SESSION, 'messages.errors', array());
        $_SESSION['messages.errors'] = array();
        return $messages;
    }
    
    function getMessages() {
        $messages = array_merge(
            $this->getErrorMessages(), 
            Filter::get($_SESSION, 'messages', array())
        );
        $_SESSION['messages'] = array();
        return $messages;
    }

    
    function elapsedTime($time=null) {
        return time() - $this->starttime;
    }
    
    /**
     * Get a session value
     * @param   string  The name of the variable to retrieve
     * @param   mixed   The default value to return if the variable is not set
     * @return  mixed   The session variable value
     */
    
    function get($key, $default=null) {
        return Filter::get($_SESSION, $key, $default);
    }
    
    /**
     * Get a session value then clear it.
     * @param   string  The name of the variable to retrieve
     * @param   mixed   The default value to return if the variable is not set
     * @return  mixed   The session variable value
     */
    
    function get_once($key, $default=null) {
        $value = $this->get($key, $default);
        $this->clear($key);
        return $value;
    }
    
    /**
     * Regenerates the global session id.
     * @return  void
     */
    
    function regenerate() {
        
        // Generate a new session id
        // Note: also sets a new session cookie with the updated id
        
        session_regenerate_id(true);

        // Update session with new id
        
        $_SESSION['session_id'] = session_id();

        // Get the session name
        
        $name = session_name();

        if (isset($_COOKIE[$name])) {
            // Change the cookie value to match the new session id to prevent "lag"
            $_COOKIE[$name] = $_SESSION['session_id'];
        }
    }
    
    /**
     * Runs the session.session_write event, then calls session_write_close.
     * @return  void
     */
    
    function write_close() {
        static $run;

        if ($run === null) {
            $run = null;

            // Run the events that depend on the session being open
            $this->trigger('session.session_write');

            // Close the session
            session_write_close();
        }
    }
    
    function getUser() {
        return new User($this->get('User'));
    }
    
    function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    function add($key, $value) {
        if (isset($_SESSION[$key]) && is_array($_SESSION[$key])) {
            array_push($_SESSION[$key], $value);
        }
        else {
            $_SESSION[$key] = $value;
        }
    }
    
    function reset() {
        $this->trigger('session.beforeReset');
        $this->destroy();
        $this->start();
        $this->trigger('session.afterReset');
    }
    
    function is_set($key) {
        if (isset($_SESSION, $key)) {
            return true;
        }
        return false;
    }
    
    function is_empty($key) {
        $value = Filter::get($_SESSION, $key);
        return empty($value);
    }
    
    function destroy($params=array()) {
        $this->trigger('session.beforeDestroy');
        $stats = array(
            'session.name'        => $this->name,
            'session.id'          => $this->id,
            'session.starttime'   => $this->starttime,
            'session.endtime'     => time(),
            'session.elapsedtime' => $this->elapsedTime(),
            'session.data'        => $_SESSION
        );
        if (count($params)) {
            foreach($params as $k=>$v) {
                $stats['session.' . $k] = $v;
            }
        }
        unset($_REQUEST[SB_PHPSESSID]);
        $_COOKIE = array();
        $_SESSION = array();
        session_destroy();
        $this->trigger('session.afterDestroy');
        return $stats;
    }
    
    function clear($key=null) {
        $this->trigger('session.beforeClear');
        if (!is_array($key)) $key = array($key);
        for ($i=0; $i<count($key); $i++) {
            if (isset($_SESSION[$key[$i]])) {
                unset($_SESSION[$key[$i]]);
            }
        }
        $this->trigger('session.afterClear');
    }
}