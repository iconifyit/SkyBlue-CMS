<?php defined('SKYBLUE') or die('Bad file request');

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
 * The Error class is used to created detailed custom errors in the 
 * Skin class. The Skin class uses an array to store errors so that
 * multiple errors can be returned should they be encountered.
 *
 * @package SkyBlue
 */

class Error {
    
    /**
     * The PHP error level (NOTICE, WARNING)
     * @var string
     */
    var $level;
    
    /**
     * The PHP Error code (E_ERROR, E_USER_ERROR, etc.)
     * @var int
     */
    var $number;
    
    /**
     * The error message
     * @var string
     */
    var $message;
    
    /**
     * The file in which the error occurred
     * @var string
     */
    var $file;
    
    /**
     * The line number on which the error occurred
     * @var int
     */
    var $line;
    
    /**
     * The full debug_backtrace array
     * @var array
     */
    var $backtrace;
    
    /**
     * The class constructor
     * @constructor
     * @param string  $level      The PHP error level
     * @param int     $number     The PHP error code
     * @param string  $message    The error string
     * @param string  $file       The file in which the error occurred
     * @param int     $line       The line number on which the error occurred
     * @param array   $errorBacktrace  The debug_backtrace array
     * @return void
     */
    function __construct($level, $number, $message, $file, $line, $errorBacktrace) {
        $this->setLevel($level);
        $this->setNumber($number);
        $this->setMessage($message);
        $this->setFile($file);
        $this->setLine($line);
        $this->setBacktrace($errorBacktrace);
    }
    
    /**
     * Gets the PHP error level (NOTICE, WARNIN, ERROR, etc.)
     * @return string
     */
    function getLevel() {
        return $this->level; 
    }
    
    /**
     * Sets the error level
     * @param string $level  The PHP error level (NOTICE, WARNING, ERROR, etc.)
     * @return void
     */
    function setLevel($level) {
        $this->level = $level;
    }
    
    
    /**
     * Gets the error number
     * @return int
     */
    function getNumber() {
        return $this->number;
    }
    
    /**
     * Sets the error number
     * @param int $number
     * @return void
     */
    function setNumber($number) {
        $this->number = $number;
    }
    
    /**
     * Gets the error message string
     * @return string
     */
    function getMessage() {
        return $this->message;
    }
    
    /**
     * Sets the error message string
     * @param string $message
     * @return void
     */
    function setMessage($message) {
        $this->message = $message;
    }
    
    /**
     * Gets the name of the file in which the error occurred
     * @return string
     */
    function getFile() {
        return $this->file;
    }
    
    /**
     * Sets the name of the file in which the error occurred
     * @param string $file
     * @return void
     */
    function setFile($file) {
        $this->file = $file;
    }
    
    /**
     * Gets the line number on which the error occurred
     * @return int
     */
    function getLine() {
        return $this->line;
    }
    
    /**
     * Sets the line number on which the error occurred
     * @param int $line
     * @return void
     */
    function setLine($line) {
        $this->line = $line;
    }
    
    /**
     * Gets the debug_backtrace array
     * @return array
     */
    function getBacktrace() {
        return $this->backtrace;
    }
    
    /**
     * Sets the debug_backtrace
     * @param array $errorBacktrace  The PHP debug_backtrace array
     * @return void
     */
    function setBacktrace($backtrace) {
        $this->backtrace = $backtrace;
    }
}