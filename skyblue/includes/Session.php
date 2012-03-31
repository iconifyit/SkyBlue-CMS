<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2009-04-19 10:37:00 $
 * @package      SkyBlueCanvas
 * @copyright    Copyright (C) 2005 - 2012 Scott Edwin Lewis. All rights reserved.
 * @license      GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General License, and as distributed it includes or
 * is derivative of works licensed under the GNU General License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

class Session extends Publisher {

    /**
     * The session id
     * @var string 
     */
    var $id;
    
    /**
     * The session name
     * @var type 
     */
    var $name;
    
    /**
     * The time the session started
     * @var int 
     */
    var $starttime;
    
    /**
     * The time the session ended
     * @var int 
     */
    var $endtime;
    
    /**
     * The currently logged-in user
     * @var User 
     */
    var $User;

    /**
     * Constructor
     * @param array $options    The session options
     */
    function __construct($options=array()) {
        $this->start($options);
    }
    
    /**
     * Statically called method to get a singleton of the session object
     * @param array $options      The session options
     * @param boolean $refresh    Whether or not to reset the session before returning the object
     * @return object
     */
    function getInstance($options=array(), $refresh=false) {
        $Session = Singleton::getInstance('Session');
        $Session->start($options);
        return $Session;
    }
    
    /**
     * Starts a new session
     * @param array $options    The session options
     */
    function start($options=array()) {
    
        $sessid      = Filter::get($options, 'sessid');
        $lifetime    = Filter::get($options, 'lifetime', SB_SESSION_LIFETIME);
        $cookie_path = Filter::get($options, 'cookie_path', SB_COOKIE_PATH);
        $name        = Filter::get($options, 'name');
    
        $this->trigger('session.beforeStart');
    
        if (is_callable('ini_set')) {
            ini_set('session.save_path', SB_SESSION_SAVE_PATH);
            ini_set('session.cookie_path', SB_COOKIE_PATH);
            ini_set('session.gc_maxlifetime', 86400);
        }
        
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
    
    /**
     * Adds a message to the session for display on a later page
     * @param string $type     The type of message - Info | Error | Warning
     * @param string $title    The message title heading to appear in the H2 tag
     * @param string $text     The message body
     */
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
    
    /**
     * Adds an error message. This is a short-hand for calling the addMessage method with an error type.
     * @param string $msg    The message body
     */
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
    
    /**
     * Returns all of the messages of type error currently in the session
     * @return array    An array of error messages 
     */
    function getErrorMessages() {
        $messages = Filter::get($_SESSION, 'messages.errors', array());
        $_SESSION['messages.errors'] = array();
        return $messages;
    }
    
    /**
     * Returns all messages currently in the session
     * @return array    An array of messages
     */
    function getMessages() {
        $messages = array_merge(
            $this->getErrorMessages(), 
            Filter::get($_SESSION, 'messages', array())
        );
        $_SESSION['messages'] = array();
        return $messages;
    }

    /**
     * Gets the elapsed time since the start of the session
     * @param int $time    A specific time to check since the start of the session
     * @return int 
     */
    function elapsedTime($time=null) {
        if (! empty($time) && is_numeric($time) && $time > $this->starttime) {
            $elapsed = $time - $this->starttime;
        }
        else {
            $elapsed = time() - $this->starttime;
        }
        return $elapsed;
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
    
    /**
     * Gets the User object corresponding to the currently logged-in user.
     * @return \User 
     */
    function getUser() {
        return new User($this->get('User'));
    }
    
    /**
     * Sets a session key
     * @param string $key     The session key to set
     * @param mixed $value    The value to store in $key
     */
    function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    /**
     * Adds a session key
     * @param string $key     The session key to set
     * @param mixed $value    The value to store in $key
     */
    function add($key, $value) {
        if (isset($_SESSION[$key]) && is_array($_SESSION[$key])) {
            array_push($_SESSION[$key], $value);
        }
        else {
            $_SESSION[$key] = $value;
        }
    }
    
    /**
     * Resets the session by destroying the current session and creating a new one. 
     */
    function reset() {
        $this->trigger('session.beforeReset');
        $this->destroy();
        $this->start();
        $this->trigger('session.afterReset');
    }
    
    /**
     * Checks to see if a session key is set
     * @param string $key    The session key to check
     * @return boolean 
     */
    function is_set($key) {
        if (isset($_SESSION, $key)) {
            return true;
        }
        return false;
    }
    
    /**
     * Checks to see if the value of a session key is empty
     * @param string $key    The session key to check
     * @return type 
     */
    function is_empty($key) {
        $value = Filter::get($_SESSION, $key);
        return empty($value);
    }
    
    /**
     * Destroys the current session
     * @param array $params    The session parameters (properties)
     * @return array 
     */
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
    
    /**
     * Clears the value of a session key
     * @param string $key 
     */
    function clear($key=null) {
        $this->trigger('session.beforeClear');
        if (! is_array($key)) $key = array($key);
        for ($i=0; $i<count($key); $i++) {
            if (isset($_SESSION[$key[$i]])) {
                unset($_SESSION[$key[$i]]);
            }
        }
        $this->trigger('session.afterClear');
    }
}