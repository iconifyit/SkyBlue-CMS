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

/**
 * A static class for tracking performance of tasks in SkyBlueCanvas
 *
 * Usage:
 *
 *    Timer::start('myevent');
 *    
 *    // The body of your code
 *
 *    // Stop the timer
 *    // Use this if you want the timer to stop but do not need to capture the elapsed 
 *    // time yet.
 * 
 *    Timer::stop('myevent');
 *
 *    // If you called stop earlier in the script execution, the elapsed time is calculated by 
 *    // subtracting the start time from the microtime at the time stop was called. If you did 
 *    // not call stop, the elapsed time is calculated by subtracting the start time from the 
 *    // current microtime.
 *
 *    echo Timer::elapsed('myevent');
 *
 *    // If you are finished with this timer, call clear to clear its values
 *
 *    Timer::clear('myevent');
 *
 *    // Call destroy to completely remove the timer from memory.
 *
 *    Timer::destroy('myevent');
 *
 */

class Timer {
    
    /**
     * We cannot use the 'static' keyword with Class properties in PHP 4 so 
     * we use a function as a wrapper for the static timers array.
     * @param string  The event being timed
     * @param array   An array of key=>value pairs for the timers properties
     * @return array  The timers array
     */
    
    function & timers($event=null, $values=array()) {
        static $_timers;
        if (!is_array($_timers)) {
            $_timers = array();
        }
        if (!empty($event) && count($values)) {
            foreach ($values as $key=>$value) {
                $_timers[$event][$key] = $value;
            }
        }
        return $_timers;
    }
    
    /**
     * Start a new Timer
     * @param string   The event being timed
     * @return void
     */
    
    function start($event) {
        Timer::setStartTime($event, Timer::getMicroTime());
    }
    
    /**
     * Stops a timer
     * @param string   The event being timed
     * @return void
     */
    
    function stop($event) {
        Timer::setEndTime($event, Timer::getMicroTime());
    }
    
    /**
     * Clears a Timer
     * @param string   The event being timed
     * @return void
     */
    
    function clear($event) {
        $timer = Filter::get(Timer::timers(), $event);
        if (empty($timer)) return null;
        Timer::timers($event, array(
            'start' => -1,
            'stop'  => -1, 
            'stopped' => true
        ));
    }
    
    /**
     * Destroys a Timer by removing it from memory
     * @param string   The event being timed
     * @return void
     */
    
    function destroy($event) {
        $timers =& Timer::timers();
        unset($timers[$event]);
    }
    
    /**
     * Explicitly sets the start time for an event
     * @param string   The event being timed
     * @param float    The time in microseconds
     * @return void
     */
    
    function setStartTime($event, $time) {
        Timer::timers($event, array(
            'start' => $time,
            'stopped' => false
        ));
    }
    
    /**
     * Explicitly sets the end time for an event
     * @param string   The event being timed
     * @param float    The time in microseconds
     * @return void
     */
    
    function setEndTime($event, $time) {
        Timer::timers($event, array(
            'stop' => $time, 
            'stopped' => true
        ));
    }
    
    /**
     * Determines if a Timer is still running
     * @param string  The name of the event being timed
     * @return bool   Whether or not the timer has been stopped
     */
    
    function isStopped($event) {
        return Filter::get(
            Filter::get(Timer::timers(), $event, array()), 
            'stopped', 
            true
        );
    }
    
    /**
     * Gets the start time for an event
     * @param string   The event whose start time is requested
     * @return void
     */
    
    function getStartTime($event) {
        $timer = Filter::get(Timer::timers(), $event);
        if (!empty($timer)) {
            return Filter::get($timer, 'start');
        }
        return null;
    }
    
    /**
     * Gets the end time for an event
     * @param string   The event whose end time is requested
     * @return void
     */
    
    function getEndTime($event) {
        $timer = Filter::get(Timer::timers(), $event);
        if (!empty($timer)) {
            return Filter::get($timer, 'stop');
        }
        return null;
    }
    
    /**
     * Gets the total elapsed time between the event start and end times
     * @param string   The event whose start time is requested
     * @return void
     */
    
    function elapsed($event) {
        $endTime = Timer::getMicroTime();
        if (Timer::isStopped($event)) {
            $endTime = Timer::getEndTime($event);
        }
        return round($endTime - Timer::getStartTime($event), 4);
    }
    
    /**
     * Gets the current microtime
     * @return float  The current time in micro-seconds
     */
    
    function getMicroTime() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float) $usec + (float) $sec);
    }

}