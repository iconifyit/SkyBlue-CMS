<?php defined('SKYBLUE') or die('Bad File Request');

/**
 * @version      2.0 2009-04-14 23:50:00 $
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
 * @date   June 22, 2009
 */

class Validator {

    /**
     * validates a URL
     * @param string   The string to validate
     * @return bool    Whether or not the string was a valid URL
     */
    
    function url($str) {
        return preg_match(SB_REGEX_URL, $str);
    }
    
    /**
     * validates that a string is not null/empty
     * @param string   The string to validate
     * @return bool    Whether or not the string was empty
     */
    
    function notnull($value) {
        if (is_string($value)) {
            $value = trim($value) == "" ? null : $value ;
        }
        return (! is_null($value));
    }
    
    /**
     * validates that a string is not null/empty
     * @param string   The string to validate
     * @return bool    Whether or not the string was empty
     */
    
    function notempty($str) {
       return Validator::notnull($str);
    }
    
    /**
     * validates that a string is a well-formed email address
     * @param string   The string to validate
     * @return bool    Whether or not the string was a well-formed email address
     */
    
    function email($str) {
        return eregi(SB_REGEX_EMAIL, $str);
    }
    
    /**
     * validates that a string is numeric
     * @param string   The string to validate
     * @return bool    Whether or not the string is numeric
     */
    
    function number($str) {
        return is_numeric($str);
    }
    
    /**
     * Validates a string as a legal password
     * @param string  The string to validate
     * @return bool   Whether or not the string is a legal password
     */
    
    function IsLegalPassword($str) {
        for ($i=0; $i<strlen($str); $i++) {
            if (strpos(PW_LEGAL_CHARS, $str{$i}) === false) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Validates a string as a legal username
     * @param string  The string to validate
     * @return bool   Whether or not the string is a legal username
     */
    
    function IsLegalUsername($str) {
        for ($i=0; $i<strlen($str); $i++) {
            if (strpos(PW_LEGAL_CHARS, $str{$i}) === false) {
                return array(0, $str{$i});
            }
        }
        return array(1, null);
    }

}