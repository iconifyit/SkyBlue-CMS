<?php defined('SKYBLUE') or die('Bad File Request');

/**
 * @version      2.0 2009-04-19 10:37:00 $
 * @package        SkyBlueCanvas
 * @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license        GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

/**
 * The Publisher class allows events to be attached to 
 * different events or state changes within classes that inherit from 
 * this class.
 */

/**
 * @deprecated Use the Event static class instead
 * This class has been kept here as a facade fro backward compatibility
 */

class Publisher extends SkyBlueObject {

    /**
     * Adds a custom event for later use
     * @param string The event name
     * @return void
     */
    
    function addEvent($event) {
        Event::addEvent($event);
    }
    
    /**
     * Checks to see if a particular event has any registered callbacks
     * @param string The name of the event
     * @param bool   Whether or not the event has any callbacks
     */
    
    function hasCallbacks($event) {
        return Event::hasCallbacks($event);
    }
    
    /**
     * Checks to see if an event has already been registered
     * @param string The name of the event
     * @return bool  Whether or not the event is registered
     */
    
    function isRegistered($event) {
        return Event::isRegistered($event);
    }
    
    /**
     * Registers a custom event
     * @param string The event name
     * @param mixed  A string name of a function or array of class, method to call
     * @param int    The priority of the callback (not implemented)
     * @return void
     */
    
    function register($event, $callback, $priority=0) {
        Event::register($event, $callback, $priortity);
    }
    
    /**
     * Removes a callback from an event
     * @param string The event name
     * @param string The callback name
     * @return void
     */
    
    function unregister($event, $callback) {
        Event::unregister($event, $callback);
    }
    
    /**
     * Fires all methods attached to a custom event
     * @param string The event name to fire
     * @param array  The data arguments on which to operate (if any)
     * @return void
     */
    
    function trigger($event, $data=null) {
        return Event::trigger($event, $data);
    }
    
    /**
     * Fires all methods attached to a custom event only once
     * @param string The event name to fire
     * @param array  The data arguments on which to operate (if any)
     * @return void
     */
    
    function triggerOnce($event, $data=null) {
        return Event::triggerOnce($event, $data);
    }
}