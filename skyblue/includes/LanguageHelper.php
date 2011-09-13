<?php defined('SKYBLUE') or die('Bad file request');

/*
 * REPLACEMENT_CHAR is substituted anywhere a string is normalized to remove illegal characters.
 * An example of its usage is in the Router::normalize() method where special characters will 
 * be replaced with REPLACEMENT_CHAR.
 */

define('REPLACEMENT_CHAR', "-");

/*
 * URL_SAFE_CHARS is a string of characters that can safely be used in a URL. These characters 
 * are used in conjunction with REPLACEMENT_CHAR. For instance, in Router::normalize()
 */

define('URL_SAFE_CHARS', "abcdefghijklmnopqrstuvwxyz0123456789_-");

/**
 * LanguageHelper handles all translations for i18n support. Terms are defined in a collection of ini files 
 * stored in ~/sbc/language/language_name/
 */
class LanguageHelper {

    /**
     * map is a multi-dimensional associative array of term constants and values for the current language.
     * @var Array
     */
    var $map;
    
    /**
     * Constructor
     * @constructor
     * @return void
     */
    function __construct() {
        global $Core;
        
        $this->map = array();

        if (file_exists(_SBC_ROOT_ . "languages/system.php")) {
            require_once(_SBC_ROOT_ . "languages/system.php");
            if (isset($chars)) {
                $this->map['CHARS'] = $chars;
            }
            if (isset($entities)) {
                $this->map['ENTITIES'] = $entities;
            }
            if (isset($TERMS)) {
                $this->map['TERMS'] = $TERMS;
            }
        }
        
        $lang = Config::get('site_lang');
        
        if (file_exists(SB_LANG_DIR . "{$lang}/terms.ini")) {
            $user_ini = parse_ini_file(SB_LANG_DIR . "{$lang}/terms.ini", true);
            $this->_add($user_ini);
        }

        if (file_exists(ACTIVE_SKIN_DIR . "language/theme.ini")) {
            $this->_add(
                parse_ini_file(ACTIVE_SKIN_DIR . "language/theme.ini", true)
            );
        }
        
        Event::trigger("LanguageHelper.append", $this);
    }
    
    /**
     * Parses an ini format language file
     * @param String $file  The fully-qualified file path of the language file
     * @return void
     */
    function parse_file($file) {
        if (file_exists($file)) {
            $this->_add(
                parse_ini_file($file, true)
            );
        }
    }
    
    /**
     * Adds a group of terms to the current language collection.
     * @param Array $ini  An array of key=>vaue pairs of terms
     * @return void
     */
    function _add($ini) {
        if (!empty($ini)) {
            foreach ($ini as $section=>$values) {
                if (isset($this->map[$section])) {
                    $this->map[$section] = array_merge(
                        $this->map[$section], 
                        $ini[$section]
                    );
                }
                else {
                    $this->map[$section] = $ini[$section];
                }
            }
        }
    }
    
    /**
     * Parses a string for terms tokens to be replaced by terms in the language table.
     * @param String $str  The string to parse and translate.
     * @return String  The translated string
     */
    function terms($str) {
        $tokens = $this->getTermTokens($str);
        foreach($tokens as $k=>$v) {
            $term = str_replace(array('[TERM:', ']'), null, $v);
            $str = str_replace($v, Filter::get($this->map['TERMS'], $term, $term), $str);
        }
        return $str;
    }
    
    /**
     * Parses a string shred for terms tokens.
     * @param String $str  The shred to parse
     * @return Array       The parsed terms tokens
     */
    function getTermTokens($str) {
          preg_match_all(
              "/\[TERM:[a-zA-Z0-9_\.]+\]/", $str, $tokens, PREG_SPLIT_DELIM_CAPTURE
          );
          $result = array();
          for ($i=0; $i<count($tokens); $i++) {
              if (!in_array($tokens[$i][0], $result)) {
                  array_push($result, $tokens[$i][0]);
              }
          }
          return $result;
    }
    
    /**
     * I have no idea what this does.
     * @return Array
     */
    function getLegalChars() {
        $chars = array();
        foreach ($this->chars as $key=>$value) {
            array_push($chars, $value);
        }
        return $chars;
    }
    
    /**
     * Gets the ENTITIES section of the language table.
     * @return Array  The entities
     */
    function getEntityMap() {
        return $this->map['ENTITIES'];
    }
    
    /**
     * Finds a term in the language table
     * @param String $str  The terms to find
     * @return String      The term value
     */
    function _find($str) {
        if (!isset($this->map) || empty($this->map)) return $str;
        $map = $this->map;
        if (isset($map[$str])) {
            return $map[$str];
        }
        foreach ($map as $k=>$v) {
            if (is_array($v)) {
                if (isset($v[$str])) {
                    return $v[$str];
                }
            }
        }
        return $str;
    }
    
    /**
     * Translates a character to a web-safe value. This is a more user-friendly alias 
     * for normalize($char)
     * @param String $char   The character to normalize
     * @return String        The normalized character
     */
    function getChar($char) {
        return $this->normalize($char);
    }
    
    /**
     * An alias for _find($term)
     * @param String $term   The term to find
     * @return String        The term value
     */
    function getTerm($term) {
        return $this->_find($term);
    }
    
    /**
     * Translates a character to a web-safe value.
     * @param String $char   The character to normalize
     * @return String        The normalized character
     */
    function normalize($str) {
        $len = strlen($str);
        $out = "";
        $curr_char = "";
        for ($i=0; $i < $len; $i++) {
            $curr_char .= $str[$i];
            if (( ord($str[$i]) & (128+64) ) == 128) {
                
                /*
                 * character end found
                 */
                
                if (strlen($curr_char) == 2) {
                
                    /*
                     * 2-byte character check for it is greek one and convert
                     */
                    
                    if (ord($curr_char[0]) == 205) {
                        $out .= chr(ord($curr_char[1]) + 16);
                    }
                    else if (ord($curr_char[0]) == 206) {
                        $out .= chr(ord($curr_char[1]) + 48);
                    }
                    else if (ord($curr_char[0]) == 207) {
                        $out .= chr(ord($curr_char[1]) + 112);
                    }
                    else {
                        /*
                         * non greek 2-byte character, discard character
                         */
                    }
                } 
                else {
                    /*
                     * n-byte character, n > 2, discard character
                     */
                }
                $curr_char = "";
            } 
            else if (ord($str[$i]) < 128) {
            
                /*
                 * character is one byte (ascii)
                 */
                
                $out .= $curr_char;
                $curr_char = "";
            }
        }
        return $out;
    }

}