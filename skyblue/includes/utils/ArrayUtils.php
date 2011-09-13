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

class ArrayUtils {

    /**
     * Transforms a flat array into COLS X ROWS. 
     * This is very useful for building
     * tables of specified COLS X ROWS sizes.
     *
     * For example, if you have an array of 8 items that you
     * want returned as a 3 X 3 matrix, pass the array and 
     * $colcount = 3.
     *
     * Example:
     *
     * <pre>
     * $arr = array(0, 1, 2, 3, 4, 5, 6, 7);
     * ArrayToMatrix($arr, 3);
     *
     * Returns:
     *
     * array(
     *    [0]=> array(0, 1, 2)
     *    [1]=> array(3, 4, 5)
     *    [3]=> array(6, 7, 8)
     *  )
     *
     * Which is an array representation of:
     *
     *  0 | 1 | 2
     * -----------
     *  3 | 4 | 5
     * -----------
     *  6 | 7 | 8
     *
     * If the array is smaller than the matrix size, 
     * the remaining cells will be filled with null (n) values.
     *
     * Example 7 items in a 3 X 3 matrix:
     *
     * $arr = array(0, 1, 2, 3, 4, 5, 6);
     * ArrayToMatrix($arr, 3);
     * Returns:
     *
     * array(
     *    [0]=> array(0, 1, 2)
     *    [1]=> array(3, 4, 5)
     *    [3]=> array(6, , )
     *  )
     *
     * Which is an array representation of:
     *
     *  0 | 1 | 2
     * -----------
     *  3 | 4 | 5
     * -----------
     *  6 |   |  
     * </pre>
     *
     * @param array   $arr the flat array to convert to a multi-dim array.
     * @param integer $colcount the number of columns in the matrix.
     * @return array
     */
    
    function arrayToMatrix($arr, $colcount=3) {
        $matrix = array();
        $rowcount = ceil(count($arr)/$colcount);
        for ($i=0, $offset=0; $i<$rowcount; $i++, $offset+=$colcount) {
            for ($j=$offset; $j<($offset+$colcount); $j++) {
                $matrix[$i][$j] = isset($arr[$j]) ? $arr[$j] : null ;
            }
        }
       return $matrix;
    }
    
    /*
     * The difference between ArrayToGrid() and ArrayToMatrix() is that
     * ArrayToGrid() resests the cell indices at the beginning of each column, 
     * whereas ArrayToMatrix() numbers the cells sequentially regardless of the
     * column in which they fall.
     * 
     * Examples:
     *
     * ArrayToGrid()
     *
     *  0 | 1 | 2
     * -----------
     *  0 | 1 | 2
     * -----------
     *  0 | 1 | 2
     *
     *
     * ArrayToMatrix()
     *
     *  0 | 1 | 2
     * -----------
     *  3 | 4 | 5
     * -----------
     *  6 | 7 | 8
     *
     */
    
    function arrayToGrid($arr, $colcount=3) {
        $grid = array();
        $rowcount = ceil(count($arr)/$colcount);
        $trueindex = 0;
        for ($i=0; $i<$rowcount; $i++) {
            for ($j=0; $j<$colcount; $j++) {
                $grid[$i][$j] = isset($arr[$trueindex]) ? $arr[$trueindex] : null ;
                $trueindex++;
            }
        }
       return $grid;
    }

    /**
     * Binds an associative array of data to an object where the names 
     * of the object properties match the names of the array indices.
     *
     * Exmaple: Binding the values of a form POST data to an object.
     * 
     * @param object    The object to which to bind the array.
     * @param array     The associative array to bind to the object.
     * @return object   The object with array values bound to it
     */
    
    function bindArrayToObject($obj, $arr, $ignore=array('submit')) {
        foreach ($arr as $k=>$v) {
            if (in_array($k, $ignore)) continue;
            $obj->$k = is_array($v) ? implode(', ', $v) : $v ;
        }    
        return $obj;
    }
    
    /**
     * Converts an associative array to an object
     * @param array    The associative array
     * @return object  The new object
     */
    
    function toObject($arr) {
        if (!is_array($arr)) {
            return null;
        }
        $object = new stdClass;
        foreach ($arr as $key=>$value) {
            $object->$key = $value;
        }
        return $object;
    }
    
    /**
     * Converts an associative array to XML
     * @param array    The associative array
     * @return string  The XML representation of the array
     */
    
    function toXml($arr) {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<object>\n";
        foreach ($arr as $key=>$value) {
            $xml .= ArrayUtils::xmlNode($key, $value);
        }
        $xml .= "</object>\n\n";
        return $xml;
    }
    
    function xmlNode($key, $value) {
        $xml = "";
        if (is_array($value)) {
            $xml .= "    <{$key}>\n";
            foreach ($value as $key2=>$value2) {
                $xml .= "    " . ArrayUtils::xmlNode($key2, $value2);
            }
            $xml .= "    </{$key}>\n";
        }
        else {
            if (!is_numeric($value)) {
                $value = "<![CDATA[{$value}]]>";
            }
            $xml .= "    <{$key}>{$value}</{$key}>\n";
        }
        return $xml;
    }
    
    function toLegacyXml($arr) {
        $id = Filter::get($arr, 'id');
        $ignore_attrs = array('description', 'id', 'type');
        $xml = "<item " . (isset($id) ? "id=\"{$id}\"\n" : "") ;
        $count = 0;
        $minus = 0;
        foreach ($arr as $k=>$v) {
            if (!in_array($k, $ignore_attrs) && trim($k) != '') {
                $count++;
            }
        }
        $i = 0;
        foreach ($arr as $k=>$v) {
            if (!in_array($k, $ignore_attrs) && trim($k) != '') {
                $indent = '';
                if (isset($id) || $i != 0) {
                    $indent = str_repeat(' ', 6);
                }
                $xml .= "{$indent}{$k}=\"{$v}\"" . (($i + 1) != $count ? "\n" : '');
                $i++;
            }
        }
        $xml .= ">\n";
        $description = Filter::get($arr, 'description');
        if (isset($description) && trim($description) != '') {
            $description = trim($description);
            $xml .= str_repeat(' ', 6);
            $xml .= "<description><![CDATA[{$description}]]></description>\n";
        }
        $xml .= "</item>\n\n";
        return $xml;
    }
    
    /**
     * Converts an array of objects or arrays to XML
     * @param array    An array of objects or keyed arrays
     * @return string  The XML string
     */
    
    function objectsToXml($rows, $type) {
        $xml = "";
        $count = count($rows);
        for ($i=0; $i<$count; $i++) {
            $xml .= ArrayUtils::toLegacyXml($rows[$i]->getValueObject());
        }
        return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
            . "<root>\n<obj type=\"{$type}\">\n{$xml}\n</obj>\n</root>\n";
    }
    
    /**
     * Updates the property values of an object with the values of
     * an associateve array. If no new values are passed in the array, 
     * the existing object properties will be maintained.
     * @param object   The object to which to bind the array.
     * @param array    The associative array to bind to the object.
     * @return object  The object with updated values
     */
    
    function updateObjectFromArray($obj, $arr) {
        $obj2 = new stdClass;
        foreach ($obj as $k=>$v) {
            if (trim($k) != '') {
                $obj2->$k = $v;
            }
        }
        foreach($arr as $k=>$v) {
            if (trim($k) != '') {
                $obj2->$k = $v;
            }
        }
        foreach($obj2 as $k=>$v) {
            if (!isset($arr[$k])) {
                unset($obj2->$k);
            }
        }
        return $obj2;
    }
    
    /**
     * Tests whether or not an object with a named property matching
     * a specified value is in an array. Similar to PHP's in_arry().
     *
     * @param object    The object to search for.
     * @param array     The array of objects.
     * @param string    The name of the property to match on.
     * @return boolean  Whether or not the object is in the array
     */
    
    function objectInArray($obj, $array, $key) {
        foreach ($array as $a) {
            if ($a->$key == $obj->$key) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Replaces needle in haystack. The difference between this function
     * and PHP's str_replace() is that this function will replace needle
     * in an array of haystacks.
     * @param mixed    The subject of the replacement.
     * @param string   The string to be replaced.
     * @param string   The string with which to replace the needle.
     * @return array   The updated array
     */
    
    function stringReplace($haystack, $needle, $replace) {
        if (is_array($haystack)) {
            for ($i=0; $i<count($haystack); $i++) {
                $pile = $haystack[$i];
                if (strpos($pile, $needle) !== false) {
                    $pile = str_replace($needle, $replace, $pile);
                    $haystack[$i] = $pile;
                }
            }
        } 
        else {
            $haystack = str_replace($needle, $replace, $haystack);
        }
        return $haystack;
    }
    
    /**
     * Trim all the strings in the array
     * @param array   The array of strings to trim
     * @return array  The array of trimmed strings
     */
    
    function trimItems($arr) {
        for ($i=0; $i<count($arr); $i++) {
            if (is_string($arr[$i])) {
                $arr[$i] = trim($arr[$i]);
            }
        }
        return $arr;
    }
    
    /**
     * Get the offset of a specific item within an array
     * @param array   The array of items
     * @param mixed   The value to match on
     * @return int    The offset of the matched item within the array
     */
    
    function getItemOffset($arr, $match) {
        for ($i=0; $i<count($arr); $i++) {
            if ($arr[$i] == $match) {
                return $i;
            }
        }
        return -1;
    }
    
}