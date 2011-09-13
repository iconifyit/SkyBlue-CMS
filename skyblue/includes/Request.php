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
 * The RequestObject encapsulates requests so that specific parameters 
 * do not need to be known by the various classes in the request chain.
 */

class RequestObject extends SkyBlueObject {
    
    function __construct() {
    
        global $Authenticate;
    
        /**
         * We always scrub the _GET array because there is /never/ a 
         * reason to pass code through this array.
         */
    
        $_GET = Filter::scrub($_GET);
        
        foreach ($_GET as $k=>$v) {
            $_GET[$k] = Filter::noInjection($_GET, $k);
        }
        
        /**
         * We always scrub the _COOKIE array because there is /never/ a 
         * reason to pass code through this array.
         */
        
        $_COOKIE = Filter::scrub($_COOKIE);
        
        /**
         * Re-build the _REQUEST array with the scrubbed arrays
         */
        
        /**
         * Start with the keys=>values from the _COOKIE array
         */
        
        $_REQUEST = $_COOKIE;
        
        /**
         * Over-write with the keys=>values from the _GET array
         */
        
        foreach ($_GET as $k=>$v) {
            $_REQUEST[$k] = $v;
        }
        
        /**
         * Over-write with the keys=>values from the _POST array
         */
        
        foreach ($_POST as $k=>$v) {
            $_REQUEST[$k] = $v;
        }
        
        /**
         * Now set the RequestObject properties to the _REQUEST
         * key=>value pairs.
         */
        
        foreach ($_REQUEST as $k=>$v) {
            $this->set($k, $v);
        }
    }
}