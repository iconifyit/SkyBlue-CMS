<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version        RC 1.0.3.2 2008-04-24 15:03:43 $
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
 * Plugin Function Example:
 * 
 * {plugin:my_plugin(const:const_name,var:var_name,literal_str)}
 * 
 * type:name | type:value
 * 
 * str:"my string"
 * const:const_name
 * var:var_name
 * arr:[one,two,three] or [key1=val1,key2=val2]
 * class:class.prop
 * 
 * Will support classes. Indicate that a plugin is a class by:
 * 
 * {plugin:my_class.my_func(comma,delimited,args)}
 * 
 * Syntax:
 * 
 *     In-line text:
 *     
 *     {plugin:func_name(comma,delimited,args)}
 *     
 *     HTML:
 *     
 *     <!--#plugin func_name(comma,delimited,args) -->
 * 
 */

define('REGEX_PLUGIN_FUNCTION', "/([a-zA-Z_]+[^\(]*)\(([^\)]*)\)/i");
define('REGEX_INLINE_PLUGIN',   "/({plugin:[^}]*})/i");
define('REGEX_COMMENT_PLUGIN',  "/(<!--#plugin(.*)-->)/i");

define('TOKEN_ARR_DELIM',           ',');
define('TOKEN_ARR_LEFT',            '[');
define('TOKEN_ARR_RIGHT',           ']');
define('TOKEN_SEQ_POINT',           ';');
define('TOKEN_DIVIDER',             ':');
define('TOKEN_MEMBER_DIVIDER',      '.');
define('TOKEN_INLINE_PLUGIN_LEFT',  '{plugin:');
define('TOKEN_INLINE_PLUGIN_RIGHT',  '}');
define('TOKEN_COMMENT_PLUGIN_LEFT',  '<!--#plugin');
define('TOKEN_COMMENT_PLUGIN_RIGHT', '-->');
define('TOKEN_ASSIGN_OP',            '=');

define('PP_CLASS',    'class');
define('PP_FUNCTION', 'func');

class PluginParser extends SkyBlueObject {

    var $classInstances = array();
    var $result;
    
    function execute($html) {
        $this->result = $this->parseAndReplace($html);
    }
    
    function getHtml() {
        return $this->result;
    }
        
    function find($text) { 
        preg_match_all(REGEX_INLINE_PLUGIN,  $text, $tokens1);
        preg_match_all(REGEX_COMMENT_PLUGIN, $text, $tokens2);
        return array_merge($tokens1[0], $tokens2[0]);
    }
    
    function parse($token) {
    
        preg_match(
            REGEX_PLUGIN_FUNCTION, 
            str_replace(
                array(
                    TOKEN_INLINE_PLUGIN_LEFT, 
                    TOKEN_INLINE_PLUGIN_RIGHT, 
                    TOKEN_COMMENT_PLUGIN_LEFT,
                    TOKEN_COMMENT_PLUGIN_RIGHT
                ), 
                null, 
                $token
            ),
            $plugins
        );
        
        $plugin = null;
        if (isset($plugins[1]) && trim($plugins[1] != '')) {
            $plugin = $plugins[1];
        }
        $plugin = $this->_getFunction($plugin);
        
        $class = $plugin[PP_CLASS];
        $func  = $plugin[PP_FUNCTION];
        
        $call = $func;
        if (!empty($class) && class_exists($class)) {
            if (!isset($this->classInstances[$class])) {
                $this->classInstances[$class] = new $class;
            }
            $call = array($this->classInstances[$class], $func);
        }
        
        $args = array();
        if (isset($plugins[2]) && trim($plugins[2]) != '') {
            $args = $this->_parse($plugins[2]);
        }
        return $this->_call($call, $args);
    }
    
    function parseAndReplace($text) {
        $tokens = $this->find($text);
        for ($i=0; $i<count($tokens); $i++) {
            $replace = $this->parse($tokens[$i]);
            if (!empty($replace)) {
                $replace = $this->parseAndReplace($replace);
            }
            $text = str_replace($tokens[$i], $replace, $text);
        }

        return $text;
    }

    function _argval($str) {
        global $Core;
        if (!$this->_has($str, TOKEN_DIVIDER) || 
            !$this->_has($str, TOKEN_ASSIGN_OP)) {
            
            return $this->_convert($str);
        }
        list($type, $arg) = $this->_typeAndVal($str);
        switch ($type) {
            case 'const':
                $val = @constant($arg);
                break;
            case 'var':
                $val = @$$arg;
                break;
            case 'arr':
                $bits = explode(TOKEN_ARR_DELIM, $arg);
                for ($i=0; $i<count($bits); $i++) {
                    $val[] = $this->_argval($bits[$i]);
                }
                break;
            case 'class':
                $val = $this->_parseClassProp($arg);
                break;
            case 'string':
            default:
                $val = $arg;
                break;
        }
        return $val;
    }
    
    function _typeAndVal($arg) {
        $type = null;
        $i=0;
        $max = 255;
        if (strpos($arg, TOKEN_DIVIDER) !== false) {
            while ($arg{$i} != TOKEN_DIVIDER && $i<$max) {
                $type .= $arg{$i};
                $i++;
            }
            return array($type, substr($arg, $i+1));
        }
        else if (strpos($arg, TOKEN_ASSIGN_OP) !== false) {
            return $this->_assign($arg);
        }
        return null;
    }
    
    function _assign($arg) {
        $key = null;
        if (strpos($arg, TOKEN_ASSIGN_OP) !== false) {
            $i=0; 
            $max=255;
            while ($arg{$i} != TOKEN_ASSIGN_OP && $i<$max) {
                $key .= $arg{$i};
                $i++;
            }
            $val = $this->_convert(substr($arg, $i+1));
            return array($key=>$val);
        }
    }
    
    function _convert($arg, $delim=',') {
        if (strpos($arg, $delim) === false) return $arg;
        $args = explode($delim, $arg);
        
        $assign = $this->_assign($arg);
        if (!empty($assign)) return $assign;
        
        $res = array();
        for ($i=0; $i<count($args); $i++) {
            $tmp = trim($args[$i]);
            if (strpos($tmp, ':') !== false) {
                list($key, $value) = explode(':', $tmp);
                $res[$key] = $value;
            }
            else {
                $res[$i] = $tmp;
            }
        }
        return $res;
    }
    
    function _parseClassProp($str) {
        if (!$this->_has($str, TOKEN_MEMBER_DIVIDER)) {
            return null;
        }
        $bits = explode(TOKEN_MEMBER_DIVIDER, $str);
        if (!isset($bits[0]->$bits[1])) {
            if (!class_exists($bits[0])) {
                return null;
            }
            $class = new $bits[0];
        }
        return $class->$bits[1];
    }
    
    function _parse($str) {
        if (!$this->_has($str, TOKEN_ARR_DELIM)) {
            return array($this->_argval($str));
        }
    
        $args = array();
    
        $n = 0;
        $max = 1000;
        
        while (strlen($str) > 0 && $n < $max) {
            $n++;
            $i=0;
            $arg = null;
            $delim = false;
            $inBlock = false;
            while (!$delim && strlen($str) && $i < $max) {
                if ($i >= strlen($str)) break;
                if ($str{$i} == TOKEN_ARR_LEFT) {
                    $inBlock = true;
                    $delim = false;
                }
                else if ($str{$i} == TOKEN_ARR_RIGHT) {
                    $inBlock = false;
                    $delim = false;
                }
                else if ($str{$i} == TOKEN_ARR_DELIM && !$inBlock) {
                    $delim = true;
                }
                if (!$delim && !in_array($str{$i}, array(TOKEN_ARR_LEFT, TOKEN_ARR_RIGHT))) {
                    $arg .= $str{$i};
                }
                $i++;
            }
            array_push($args, $this->_argval($arg));
            $str = trim(substr($str, $i));
        }
        return $args;
    }
    
    function _has($haystack, $needle) {
        if (is_array($haystack)) {
            return in_array($needle, $haystack);
        }
        else if (is_string($haystack)) {
            return strpos($haystack, $needle) !== false;
        }
        return false;
    }
    
    function _call($call, $args) {
        if (is_callable($call, false)) {
            if (count($call) > 1) {
                return $call[0]->$call[1]($args);
            }
            return $call($args);
        }
        return null;
    }
    
    function _getFunction($plugin) {
        $class = null;
        $func  = null;
        
        $bits = explode(TOKEN_MEMBER_DIVIDER, $plugin);
        if (count($bits) == 1) {
            $func = $bits[0];
        }
        else {
            $class = $bits[0];
            $func  = $bits[1];
        }
        return array(PP_CLASS=>$class, PP_FUNCTION=>$func);
    }

}