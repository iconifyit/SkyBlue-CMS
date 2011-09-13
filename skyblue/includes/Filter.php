<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2009-06-16 23:50:00 $
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
 * @date   December 12, 2008
 */

/**
 * The Filter class uses the InputFilter class by Daniel Morris, per the terms 
 * of the GNU/GPL under which it is licensed. See includes/InputFilter.php for 
 * copyright and additional details.
 */

class Filter {
    
    /**
     * Get a value from an object or array
     * @param mixed    The object or array from which to get the value
     * @param string   The key or property for which to get the value
     * @param mixed    The default value to return if the key/property is not found
     * @param boolean  Whether or not to scrub the value
     * @return mixed   The value of the key/property
     */
    public static function get($subject, $key, $default=null, $scrub=true) {
        if (is_array($subject) && isset($subject[$key])) {
            return $scrub ? Filter::scrub($subject[$key]) : $subject[$key];
        }
        else if (is_object($subject) && isset($subject->$key)) {
            return $subject->$key;
        }
        return $default;
    }
    
    /**
     * Gets a raw (un-sanitized) value from an object or array
     * @param mixed    The object or array from which to get the value
     * @param string   The key or property for which to get the value
     * @param mixed    The default value to return if the key/property is not found
     * @param boolean  Whether or not to scrub the value
     * @return mixed   The value of the key/property
     */
    public static function getRaw($subject, $key, $default=null) {
        if (is_array($subject) && isset($subject[$key])) {
            return $subject[$key];
        }
        else if (is_object($subject) && isset($subject->$key)) {
            return $subject->$key;
        }
        return $default;
    }
    
    /**
     * Returns the value only if it is alpha-numeric. Otherwise returns default. 
     * found or if it is empty.
     * @param mixed    The object or array from which to get the value
     * @param string   The key or property for which to get the value
     * @param mixed    The default value to return if the key/property is not found
     * @param boolean  Whether or not to scrub the value
     * @return mixed   The value of the key/property
     */
    public static function getAlphaNumeric($subject, $key, $default=null, $scrub=true) {
        $value = Filter::get($subject, $key, $default, $scrub);
        return ctype_alnum($value) ? $value : $default ;
    }
    
    /**
     * Returns the value only if it is numeric. Otherwise returns default. 
     * found or if it is empty.
     * @param mixed    The object or array from which to get the value
     * @param string   The key or property for which to get the value
     * @param mixed    The default value to return if the key/property is not found
     * @param boolean  Whether or not to scrub the value
     * @return mixed   The value of the key/property
     */
    public static function getNumeric($subject, $key, $default=null, $scrub=true) {
        $value = Filter::get($subject, $key, $default, $scrub);
        return is_numeric($value) ? $value : $default ;
    }
    
    /**
     * Returns the value as a boolean only. See Utils::toBoolean for conversion rules. 
     * found or if it is empty.
     * @param mixed    The object or array from which to get the value
     * @param string   The key or property for which to get the value
     * @param mixed    The default value to return if the key/property is not found
     * @param boolean  Whether or not to scrub the value
     * @return mixed   The value of the key/property
     */
    public static function getBoolean($subject, $key, $default=false) {
        return Utils::toBoolean(Filter::get($subject, $key, $default, true));
    }
    
    /**
     * Works the same ast Filter::get except that $default is returned if the key is not 
     * found or if it is empty.
     * @param mixed    The object or array from which to get the value
     * @param string   The key or property for which to get the value
     * @param mixed    The default value to return if the key/property is not found
     * @param boolean  Whether or not to scrub the value
     * @return mixed   The value of the key/property
     */
    public static function getNonEmpty($subject, $key, $default=null, $scrub=true) {
        $value = Filter::get($subject, $key, $default, $scrub);
        if (empty($value)) {
            $value = $default;
        }
        return $value;
    }
    
    /**
     * Get a value from an object or array - look for injection
     * @param mixed    The object or array from which to get the value
     * @param string   The key or property for which to get the value
     * @param mixed    The default value to return if the key/property is not found
     * @param boolean  Whether or not to scrub the value
     * @return mixed   The value of the key/property
     */
    public static function noInjection($subject, $key, $default=null, $scrub=true) {
        if (is_array($subject)) {
            $value = Filter::get($subject, $key, $default, $scrub);
        }
        else {
            $value = $subject;
        }
        if (! is_string($value)) return $value;
        if (strpos($value, '<') !== false || strpos($value, '>') !== false) {
            return $default;
        }
        return $value;
    }
    
    /**
     * Scrubs the value using the InputFilter class
     * @param mixed   The value to scrub
     * @return mixed  The scrubbed value
     */
    public static function scrub($data) {
       $InputFilter = Filter::getInputFilter();
       return $InputFilter->process($data);
    }
    
    /**
     * Get an instance of the InputFilter class
     * @return object  An instance of the InputFilter class
     */
    public static function getInputFilter() {
        static $InputFilter;
        if (!is_object($InputFilter)) {
            $InputFilter = new InputFilter;
        }
        return $InputFilter;
    }
}