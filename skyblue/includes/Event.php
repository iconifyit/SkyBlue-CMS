<?php defined('SKYBLUE') or die('Bad File Request');

/**
 * @version      2.0 2009-06-06 23:50:00 $
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
 * @date   June 06, 2009
 */

class Event {
    
    /**
     * We cannot use the 'static' keyword with Class properties in PHP 4 so 
     * we use a function as a wrapper for the static events array.
     *
     * @param string  The event name
     * @param array   An array of key=>value pairs for the event properties
     * @return array  The timers array
     */
    
    function & events($event=null, $values=array()) {
        static $_events;
        if (!is_array($_events)) {
            $_events = array();
        }
        if (!is_array($_events[$event])) {
            $_events[$event] = array();
        }
        if (!empty($event) && count($values)) {
            foreach ($values as $key=>$value) {
                # $_events[$event][count($_events[$event])][$key] = $value;
                # $_events[$event][count($_events[$event])][$key] = $value;
                array_push($_events[$event], $value);
            }
        }
        return $_events;
    }
    
    /**
     * Adds a custom event for later use
     * @param string The event name
     * @return void
     */
    
    function addEvent($event) {
        if (Event::isRegistered($event)) return;
        Event::events($event, array());
    }
    
    /**
     * Checks to see if a particular event has any registered callbacks
     * @param string The name of the event
     * @param bool   Whether or not the event has any callbacks
     */
    
    function hasCallbacks($event) {
        if (Event::isRegistered($event)) {
            $values = Filter::get(Event::events(), $event);
            return (count($values) > 0);
        }
        return false;
    }
    
    /**
     * Checks to see if an event has already been registered
     * @param string The name of the event
     * @return bool  Whether or not the event is registered
     */
    
    function isRegistered($event) {
        return array_key_exists($event, Event::events());
    }
    
    /**
     * Registers a custom event
     * @param string The event name
     * @param mixed  A string name of a function or array of class, method to call
     * @param int    The priority of the callback (not implemented)
     * @return void
     */
    
    function register($event, $callback, $priority=0) {
        Event::addEvent($event);
        Event::events($event, array('callback'=>$callback));
    }
    
    /**
     * Removes a callback from an event
     * @param string The event name
     * @param string The callback name
     * @return void
     */
    
    function unregister($event, $callback) {
        if (!Event::isRegistered($event)) return;
        $_events =& Event::events();
        if (is_array($_events[$event])) {
            $filtered = array();
            for ($i=0; $i<$count; $i++) {
                if (strtolower($_events[$event][$i]) != strtolower($callback)) {
                    array_push($filtered, $_events[$event][$i]);
                }
            }
            $_events[$event] = $filtered;
        }
        else if (is_string($_events[$event])) {
            if ($_events[$event] == $callback) {
                unset($_events[$event]);
            }
        }
    }
    
    /**
     * Fires all methods attached to a custom event
     * @param string The event name to fire
     * @param array  The data arguments on which to operate (if any)
     * @return void
     */
    
    function trigger($event, $data=null) {
        if (Event::isRegistered($event)) {
            $_events =& Event::events();
            $callbacks =& $_events[$event];
            $count = count($callbacks);
            for ($i=0; $i<$count; $i++) {
                if (is_callable($callbacks[$i])) {
                    $callback = $callbacks[$i];
                    $data = call_user_func($callback, $data);
                }
            }
        }
        return $data;
    }
    
    /**
     * Fires all methods attached to a custom event only once
     * @param string The event name to fire
     * @param array  The data arguments on which to operate (if any)
     * @return void
     */
    
    function triggerOnce($event, $data=null) {
        if (Event::isRegistered($event)) {
            $_events =& Event::events();
            $count = count($_events[$event]);
            for ($i=0; $i<$count; $i++) {
                if (is_callable($_events[$event][$i])) {
                    $callback = $_events[$event][$i];
                    $_events[$event][$i] = null;
                    $data = call_user_func($callback, $data);
                }
            }
        }
        return $data;
    }
}