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

class Utils {

    /**
     * Redirects the browser to a page specified by the $url argument.
     * @param string  The URL to which to redirect the browser.
     */
    public static function redirect($url) {
        if (headers_sent()) {
            echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        } 
        else {
            header("Location: $url");
        }
        exit(0);
    }
    
    /**
     * Outputs a JSON Header
     */
    public static function httpHeaderJson() {
        if (! headers_sent()) {
            header("Cache-Control: no-cache, must-revalidate");
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Content-type: application/json");
        }
    }
    
    /**
     * Outputs a JavaScript Header
     */
    public static function httpHeaderJavascript() {
        if (! headers_sent()) {
            header("Cache-Control: no-cache, must-revalidate");
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Content-type: application/javascript");
        }
    }
    
    /**
     * Outputs an XML Header
     */
    public static function httpHeaderXml() {
        if (! headers_sent()) {
            header("Cache-Control: no-cache, must-revalidate");
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("content-type: text/xml");
        }
    }
    
    /**
     * Outputs an XML Header
     */
    public static function httpHeaderCss() {
        if (! headers_sent()) {
            header("Cache-Control: no-cache, must-revalidate");
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("content-type: text/css");
        }
    }

    /**
     * Adds a trailing slash to a path string when needed.
     * @param string   The path to which to add the trailing slash.
     * @return string  The path with trailing slash added
     */
    public static function addTrailingSlash($path) {
        if (!strlen($path)) return '/';
        return $path . ($path{strlen($path)-1} != '/' ? '/' : '') ;
    }
    
    /**
     * Alias of addTrailingSlash (or legacy support)
     * @param string   The path to which to add the trailing slash.
     * @return string  The path with trailing slash added
     */
    public static function checkTrailingSlash($path) {
        return Utils::addTrailingSlash($path);
    }
    
    /**
     * Checks if a value is empty
     * @param mixed  The value to check
     * @return bool  Whether or not the value is empty
     */
    public static function isEmpty($value) {
        if (is_array($value) && !count($value)) {
            return true;
        }
        return trim($value) == "";
    }
    
    
    /**
     * Conditionally prints a value if it is not empty
     * @param string  The value to print
     * @param string  The tag to pre-pend the value
     * @param string  The tag to post-pend the value
     * @return void
     */
    public static function echoValue($value, $before='', $after='') {
        if (Utils::isEmpty($value)) return;
        echo $before . $value . $after ;
    }
    
    /**
     * Used in pagination routines and other routines that need to capture
     * a subset of a set of data.
     * @param int - the offset within the data set.
     * @param int - number of items in the data set.
     * @param int - the minimum number of items to include in the subset.
     */
    public static function getStartOfRange($offset, $itemsPerPage, $itemCount, $min=0) {
        $start = ($offset * $itemsPerPage) - $itemsPerPage;
        return Utils::getNumInRange(
            $start, 
            $itemCount, 
            $min
        );
    }
    
    /**
     * Verifies that the specified number is within the range of
     * the minimum and maximum range specified.
     * @param int - the number to check.
     * @param int - the maximum number in the set.
     * @param int - the minimum number in the set.
     */
    public static function getNumInRange($num, $max, $min=1) {
        if ($num > $min) {
            return $num < $max ? $num : $max ;        
        } 
        return $min;
    }
    
    /**
     * Sorts an array of objects by comparing a member property.
     * @param array   The array of objects to sort
     * @param string  The name of the property to sort on
     * @return void
     */
    public static function sort(&$objects, $sort_field) {
        $sort = Core::LoadPlugin('quicksort');
        $sort->_sort($objects, $sort_field);
    }
    
    /**
     * Maps stripslashes to every item in an array
     * @return mixed
     */
    public static function stripslashes_deep($value) {
        if (is_array($value)) {
            return array_map('stripslashes_deep', $value);
        }
        return stripslashes($value);
    }
    
    /**
     * unpacks a zip archive located in $file. Be sure to 
     * move the archive to the location in which it is to be unpacked.
     * @return boolean  Whether or not the archive was un-zipped
     */
    public static function unzip($file, $destination) {
        if (!file_exists($file)) {
            return false;
        }

        if (class_exists("ZipArchive")) {
            $zip = new ZipArchive;
            if ($zip->open($file) === true) {
                $zip->extractTo($destination);
                $zip->close();
                return true;
            }
        }

        $disabled = ini_get('disabled_functions');
        $disarr = explode(',', $disabled);
        if (!in_array('exec', $disarr)) {
            exec(BIN_UNZIP.' -d'.$destination.' '.$file, $res);
            return $res;
        }
        return false;
    }
    
    /**
     * Creates a zip archive of $file (a file or directory). 
     * @param String    The source file or directory for the archive.
     * @param String    The name of the new ZIP Archive.
     * @return String   The location of the newly-created ZIP Archive.
     */
    public static function zip($source, $target) {
                
        $result = array(
            'location'  => '',
            'numfiles'  => '',
            'errorCode' => 0,
            'errorInfo' => ''
        );
        
        if (!file_exists($source)) return false;
        
        $name = basename($target);
        $dirname  = SB_TMP_DIR . Utils::getHash('md5');
        $filename = "$dirname/$name";
        
        if (!is_dir($dirname)) {
            FileSystem::make_dir($dirname);
        }
        
        $Zip = new Archive_Zip($filename);
        
        if (is_dir($source)) {
            $files = FileSystem::list_files($source);
        }
        else {
            $files = array($source);
        }
        
        $Zip->create($files);
        
        $result['errorCode'] = $Zip->errorCode();
        $result['errorInfo'] = $Zip->errorInfo(true);
        
        if ($Zip->errorCode() === 0 || $Zip->errorCode() === 1) {
            $result['location'] = $filename;
            $result['numfiles'] = count($files);
        }

        return $result;
    }
    
    /**
     * Counts the number of objects in an array that have some property
     * value matching a test string.
     * @param array  The array of objects to test.
     * @param bool   The property to search on.
     * @param int    The value to search for.
     * @return int   The object count
     */
    public static function countMatching($objs, $key, $value) {
        $x=0;
        foreach ($objs as $obj) {
            if (Filter::get($obj, $key) == $value) {
                $x++;
            }
        }
        return $x;
    }
    
    /**
     * Select an object by the 'id' property from an array of objects.
     * @param array    The array of objects from which to select.
     * @param int      The id of the object to select.
     * @return object  The object matching $id
     */
    public static function selectObject($objs, $id) {
        if (count($objs)) {
            foreach ($objs as $obj) {
                if (Filter::get($obj, 'id') == $id) {
                    return $obj;
                }
            }
        }
        return false;
    }
    
    /**
     * Select an object by a specified property from an array of objects.
     * @param array    The array of objects from which to select.
     * @param string   The name of the property to search on.
     * @param string   The value to search for.
     * @return object  The object whose $key matches the $match value
     */
    public static function findObjByKey($objects, $key, $match) {
        foreach ($objects as $Object) {
            if (strcasecmp(Filter::get($Object, $key), $match) == 0) {    
                return $Object;
            }
        }
        return false;
    }
    
    /**
     * Selects all objects from an array where a named property
     * matches a specified value.
     * @param array    The array of objects.
     * @param string   The name of the property to search on.
     * @param string   The value to search for.
     * @return array   An array of objects whose $key matches $match
     */
    public static function findAllByKey($objs, $key, $match) {
        $rows = array();
        foreach ($objs as $Object) {
            if (strcasecmp(Filter::get($Object, $key), $match) == 0) {    
                array_push($rows, $Object);
            }
        }
        return $rows;
    }
    
    /**
     * @deprecated  Duplicate of Utils::findAllByKey()
     */
    public static function selectObjects($objs, $k, $v) {
        return Utils::findAllByKey($objs, $k, $v);
    }
    
    /**
     * purpose:   To select an item from within an array of objects or arrays
     *            where the desired object may be stored in a property/key of 
     *            some other object/array.
     *
     * Note:      This public static function will work with an array of objects or
     *            An array of associative arrays. Each associative array
     *            is converted to an object on the fly and is converted back
     *            to an array so the return type matches the input type.
     *
     * Warning:   This public static function will NOT work on an array of scalar arrays.
     *
     * example:
     *
     * $item = SelectObjFromTree($myObjs, 'id', 2, 'children');
     *
     * Array
     * (
     *     [0] => stdClass Object
     *         (
     *             [id] => 1
     *             [title] => Parent Item 1
     *             [parent] => 
     *             [children] => Array
     *                 (
     *                     [0] => stdClass Object
     *                         (
     *                             [id] => 2
     *                             [title] => Child Item 1
     *                             [parent] => 1
     *                       )
     * 
     *               )
     * 
     *       )
     * 
     *     [1] => stdClass Object
     *         (
     *             [id] => 3
     *             [title] => Parent Item 2
     *             [parent] => 
     *       )
     * 
     *     [2] => stdClass Object
     *         (
     *             [id] => 4
     *             [title] => Parent Item 3
     *             [parent] => 
     *       )
     * 
     *)    
     *
     * The example above will return $objs[0]->children[0].
     *
     * @param array  - the array of objects.
     * @param string - the object property to search on.
     * @param string - the value for which to search.
     * @param string - the name of the property potentially holding the
     * nested objects.
     */
    public static function findItemFromTree($objs, $key, $match, $children) {
        $returnType = 'object';
        for ($i=0; $i<count($objs); $i++) {
            $parent = $objs[$i];
            
            if (is_array($parent)) {
                $returnType = 'array';
                $parent = (object) $parent;
            }
            if ($parent->$key == $match) {
                if ($returnType == 'array') {
                    $parent = (array) $parent;
                }
                return $parent;
            } 
            else if (isset($parent->$children)) {
                foreach ($parent->$children as $child) {
                    if (is_array($child)) {
                        $returnType = 'array';
                        $child = (object) $child;
                    }
                    if (isset($child->$key) && 
                        !empty($child->$key) && 
                        $child->$key == $match) {
                        
                        if ($returnType == 'array') {
                            $child = (array) $child;
                        }
                        return $child;
                    }
                }
            }
        }
        return false;
    }
    
    /**
     * Inserts an object into an array where the object property
     * matches the search property. If no match is found, the object
     * is inserted at the end of the array.
     * @param array    The array of objects to search.
     * @param object   The object to be inserted.
     * @param string   The object property to match on.
     * @return array   The updated array of objects
     */
    public static function insertObject($objs, $obj, $match) {
        $marker = 0;
        for ($i=0; $i<count($objs); $i++) {
            if (Filter::get($objs[$i], $match) == Filter::get($obj, $match)) {
                $objs[$i] = $obj;
                $marker = 1;    
            }
        }
        if ($marker === 0) {
            array_push($objs, $obj);
        }
        return $objs;
    }
    
    /**
     * Inserts an object into an array by matching a named property
     * with a specified value.
     *
     * @param object   The object to insert.
     * @param array    The array of objects.
     * @param string   The name of the property to search on.
     * @param string   The value to search for.
     * @return array   The array of objects
     */
    public static function insertObjByKey($obj, $objs, $key, $match) {
        for ($i=0; $i<count($objs); $i++) {
            if (Filter::get($objs[$i], $key) == $match) {
                $objs[$i] = $obj;
            }
        }
        return $objs;
    }
    
    /**
     * Deletes an object from an array.
     * @param array    The array of objects.
     * @param int      The id of the object to be deleted.
     * @return array   The updated array of objects
     */
    public static function deleteObject($objs, $id) {
        $newObjs = array();
        for ($i=0; $i<count($objs); $i++) {
            if (Filter::get($objs[$i], 'id') != $id) {
               array_push($newObjs, $objs[$i]);
            }
        }
        return $newObjs;
    }
    
    /**
     * Copies an object and updates the object name and ID
     * @param array    An array of all the objects in the set
     * @param int      The ID of the item to copy
     * @return object  The copied object
     */
    public static function copyObject($objs, $obj) {
        $newObj = Utils::cloneObject($obj);
        $newObj->setId(Utils::getNextId($objs));
        $newObj->setName('Copy of ' . $newObj->getName());
        return $newObj;
    }
    
    /**
     * Inserts an object into an array at a specified position.
     * @param array    The array of objects to search.
     * @param int      The id of the object to be re-positioned.
     * @param int      The position to which to move the object.
     * @return array   The re-ordered array of objects
     */
    public static function orderObjects($objs, $obj, $direction) {
        
        if (count($objs) < 2) return $objs;

        $offset = 0;
        $i = 0;
        foreach ($objs as $tmp) {
            if ($tmp->getId() == $obj->getId()) {
                $offset = $i;
            }
            $i++;
        }
        
        $last = count($objs) - 1;
        
        $objs = Utils::deleteObject($objs, $obj->getId());
        
        if ($direction == 'up') {
            $offset--;
        }
        else {
            $offset++;
        }
        
        if ($offset < 0) {
            $offset = 0;
        }
        else if ($offset > $last) {
            $offset = $last;
        }

        $neworder = array();

        switch ($offset) {
            case 0:
                array_push($neworder, $obj);
                $objs = array_merge($neworder, $objs);
                break;
            case $last:
                array_push($objs, $obj);
                break;
            default:
                $before = array_slice($objs, 0, $offset);
                $after = array_slice($objs, $offset);
                array_push($before, $obj);
                $objs = array_merge($before, $after);
                break;
        }
        
        $count = count($objs);
        for ($i=0; $i<$count; $i++) {
            if (isset($objs[$i]->order)) {
                $objs[$i]->order = $i+1;
            }
        }

        return $objs;
    }
    
    /**
     * Get the next available ID for an object
     * @param array  An array of objects
     * @return int   The next availabel ID
     */
    public static function getNextId(&$objs) {
        $ids = array();
        $count = count($objs);
        for ($i=0; $i<$count; $i++) {
            if (is_object($objs[$i])) {
                array_push($ids, $objs[$i]->id);
            }
            else {
                array_push($ids, $i);
            }
        }
        sort($ids);
        $id = count($ids) > 0 ? end($ids) : 1 ;
        return ($id + 1);
    }
    
    /**
     * Creates a unique hash string
     * @return a unique string
     */
    public static function getHash($algo='md5', $str='') {
        $str = empty($str) ? microtime(true) : $str ;
        return hash($algo, $str);
    }
    
    /**
     * Binds an associative array to an object
     * @param array    The array to bind to the object
     * @param object   The object to be bound to
     * @return object  The new object
     */
    public static function bindTransferObject($source, $object) {
        foreach ($source as $key=>$value) {
            $method = 'set' . ucwords($key);
            $methods = get_class_methods(get_class($object));
            if (in_array($method, $methods)) {
                call_user_func(array($object, $method), $source->$key);
            }
        }
        foreach ($object as $key=>$value) {
            if ($key{0} == '_') {
                unset($object->$key);
            }
        }
        return $object;
    }
    
    /**
     * Backward compatible object cloning
     * @param object  The object to clone
     * @return object  The cloned object
     */
    public static function cloneObject($object) {
        if (version_compare(PHP_VERSION, '5.0.0', '<')) {
            return $object;
        }
        return clone($object);
    }
    
    /**
     * Returns a string in a format safe for CSS class and/or ID selectors
     * @param string   The string to re-format
     * @return string  The selector-safe string
     */
    public static function cssSafe($string) {
        if (empty($string)) return "";
        $new = "";
        $safe = "abcdefghijklmnopqrstuvwxyz_-0123456789";
        $string = strtolower($string);
        $firstChar = $string{0};
        if (is_numeric($firstChar)) {
            $new .= "x";
        }
        $length = strlen($string);
        for ($i=0; $i<$length; $i++) {
            if (stripos($safe, $string{$i}) === false) {
                $new .= "-";
            }
            else {
                $new .= $string{$i};
            }
        }
        return $new;
    }
    
    /**
     * Creates a new object if the class exists
     * @param  string  The name of the class
     * @param  mixed   The constructor arguments
     * @return mixed   The new object or null if the class does not exist
     */
    public static function getObject($Class, $args=null) {
        if (class_exists($Class)) {
            return new $Class($args);
        }
        return null;
    }
    
    /**
     * Encodes a PHP data structure to JSON (JavaScript Object Notation)
     * @return string  The JSON encoded string
     */
    public static function jsonEncode($input) {
        $JSON = Singleton::getInstance('Services_JSON');
        return $JSON->encode($input);
    }
    
    /**
     * Decodes a JSON (JavaScript Object Notation) string to a PHP data format
     * @return mixed  The PHP data structure
     */
    public static function jsonDecode($input) {
        $JSON = Singleton::getInstance('Services_JSON');
        return $JSON->decode($input);
    }
    
    /**
     * Sanitizes a string to strip code tags
     * @param string   The string to sanitize
     * @return string  The sanitized string
     */
    public static function sanitize($string) {
        $safe = "abcdefghijklmnopqrstuvwxyz_-0123456789.";
    }
    
    /**
     * Fingerprints a string
     * @param string   The string to fingerprint
     * @param string   The hash algorithm to use 
     * @param boolean  Whether or not to salt the fingerprint
     * @return string  A fingerprinted string
     */
    public static function fingerprint($str, $algorithm='sha1', $salt=false) {
        if ($salt) $str .= SB_PASS_SALT;
        switch ($algorithm) {
            case 'sha1':
                $fingerprint = sha1($str);
                break;
            case 'md5':
            default:
                $fingerprint = md5($str);
                break;
        }
        return $fingerprint;
    }
    
    /**
     * Formats a file size as B, KB, MB, GB or TB
     * @param int $bytes      The file size in bytes
     * @param int $precision  The precision to which to round the size
     * @return string  The formatted file size string
     */
    public static function formatBytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
      
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
      
        $bytes /= pow(1024, $pow);
      
        return round($bytes, $precision) . ' ' . $units[$pow];
    } 
    
    /**
     * Paginates a set of items.
     * @param array $items         An array of the items to paginate
     * @param int   $itemsPerPage  The number of items per page
     * @param int   $pageNum       The current page number
     * @return array  The paginated (sub-set) items
     */
    public static function paginate($items, $itemsPerPage, $pageNum) {
        
        $subset = array();
        $itemCount = count($items);
        $pageCount = ceil($itemCount/$itemsPerPage);
        
        /*
         * By default we return the full set of items
         */
        
        $subset = $items;
        
        /*
         * If there are more items in the set than the max number 
         * of items per page, we want to get sub-set
         */
        
        if ($itemCount > $itemsPerPage) {
            $start = Utils::getStartOfRange($pageNum, $itemsPerPage, $itemCount, 0);
            $subset = array_slice($items, $start, $itemsPerPage);
        }
        
        return $subset;
    }
    
    /**
     * Returns 1 or 0 as Yes or No
     * @param int $int
     * @return string Yes or No
     */
    public static function intToYesNo($int) {
        return Utils::chooseBooleanOption($int, 'GLOBAL.YES', 'GLOBAL.NO');
    }
    
    /**
     * Switches between two strings depending on whether or not $bool is true
     * @param boolean  $bool          The boolean/integer value
     * @param string   $trueValue     The string to return if $bool is true
     * @param string   $falseValue    The string to return if $bool is false
     * @return string  The string value
     */
    public static function chooseBooleanOption($bool, $trueValue, $falseValue) {
        if (!is_numeric($bool)) return "";
        return intval($bool) >= 1 ? __($trueValue, $trueValue, 1) : __($falseValue, $falseValue, 1) ;
    }
    
    /**
     * Parses the states config file
     * @return array  An array of states
     */
    public static function getStates() {
        static $states;
        if (!is_array($states)) {
            if (file_exists(SB_LIB_DIR . "states.php")) {
                $config = FileSystem::read_config(
                    SB_LIB_DIR . "states.php"
                );
                $states = Filter::get($config, 'states');
            }
        }
        return $states;
    }
    
    /**
     * Determines if a method both exists and is_callable
     * @param $method
     * @return boolean
     */
    public static function callable($method) {
        return function_exists($method) && is_callable($method);
    }
    
    /**
     * Coerces a value to a boolean
     * 
     *   Coercion rules:
     *       Literal string "true"  = true
     *       Literal string "false" = false
     *       Any other string       = false
     *       1 or greater integer   = true
     *       0 or less integer      = false
     *      
     * @param mixed $value  A string or integer value
     * @return boolean  The boolean value of $value
     */
    public static function toBoolean($value) {
        if (strtolower($value) == "true") return true;
        if (strtolower($value) == "false") return false;
        return intval($value) > 0 ? true : false ;
    }
    
    /**
     * Parses a URL-style query string into an array
     * @param String $query   The key=value pair query string
     * @return Array  The keyed array of the query values
     */
    public static function parseQuery($str) {
    
        $str = html_entity_decode($str);

        $arr = array();
        
        $pairs = explode('&', $str);
        
        foreach ($pairs as $i) {
            list($name,$value) = explode('=', $i, 2);

            if (isset($arr[$name])) {
                if (is_array($arr[$name])) {
                    array_push($arr[$name], $value);
                }
                else {
                    $arr[$name] = array($arr[$name], $value);
                }
            }
            else {
                $arr[$name] = $value;
            }
        }
        return $arr;
    }
    
    /**
     * Builds a URL-style query string from a keyed array
     * @param Array    The keyed array of the query values
     * String $query   The key=value pair query string
     */
    public static function buildQuery($options) {
        if (! is_array($options) || count($options) == 0) return null;
        $tmp = array();
        foreach ($options as $key=>$value) {
            array_push($tmp, "{$key}={$value}");
        }
        return implode("&", $tmp);
    }
    
    /**
     * Writes an entry to a log file
     * @param String $logFile  The fully qualified path to the log file
     * @param String $entry    The string to write to the log
     * @return void
     */
    public static function log($logFile, $entry) {
        FileSystem::write_file(
            $logFile,
            $entry,
            file_exists($logFile) ? 'a' : 'w+'
        );
    }
    
    /**
     * Removes whitespace from CSS/JS files.
     * @param String $buffer  The buffered CSS/JS output.
     * @return String         The minified CSS/JS output.
     */
    public static function minify($buffer) {
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
        $buffer = str_replace(array("\r\n", "\r", "\n", "\t", ' ', ' ', ' '), '', $buffer);
        return $buffer;
    }
    
    /**
     * Gets a date in the future
     * @param Array $add  An array of times to add: array(month,day,year).
     * @return String     Date formatted as m/d/Y T
     */
    public static function getFutureDate($add) {
        $m = count($add) >= 1 ? $add[0] : 0 ;
        $d = count($add) >= 2 ? $add[1] : 0 ;
        $y = count($add) >= 3 ? $add[2] : 0 ;
        date_default_timezone_set('Europe/London'); 
        $futureDate = gmdate("m/d/Y T", mktime(0,0,0,date("m")+$m,date("d")+$d,date("Y")+$y));
        return $futureDate;
    }
     
    /**
     * Send a MailMessage
     * @param  Array  $options       The MailMessage options
     * @param  String $recepient     The email address to send mail to
     * @param  String $from          The name of the sender
     * @param  String $replyto       The email address of the sender
     * @param  String $cc            Email addresses of additional recepients (comma-delimited)
     * @param  String $bc            Email addresses of blind copies (comma-delimited)
     * @param  String $subject       Mail subject
     * @param  String $message       Mail message body
     * @param  String $attachment    Path to any attachments
     * @return bool                  Whether or not the message was sent
     */
    public static function mail($options) {
        if (! Utils::isDisabled('exec')) {
            $msg = Filter::get($options, 'message');
            $sbj = Filter::get($options, 'subject');
            $to  = Filter::get($options, 'recepient');
            $bc  = Filter::get($options, 'bc');
            $cc  = Filter::get($options, 'cc');
            
            $cmd = 'echo "'.$msg.'" | mail -s "'.$sbj.'" '.$to ;
            
            if (trim($cc) != "") {
                $cmd .= " -c {$cc}";
            }
            
            if (trim($bc) != "") {
                $cmd .= " -b {$bc}";
            }

            exec($cmd, $errors);
            $result = count($errors) == 0 ? true : false ;
        }
        else {
            $Mail = new MailMessage($options);
            $result = $Mail->send() == 1 ? true : false;
        }
        return $result;
    }
    
    /**
     * Determines is a public static function has been disabled in php.ini
     * @parma String $public static function  The name of the function
     * @return bool  Whether or not $public static function is disabled
     */
    public static function isDisabled($function) {
        $disabled = array_map('trim', explode(',', ini_get('disable_functions')));
        return in_array($function, $disabled);
    }
    
    /**
     * Detects if the current User Agent is a mobile device
     * @return boolean
     */
    public static function isMobile() {
        $uaRegex   = '/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone)/i';
        $accept    = 'application/vnd.wap.xhtml+xml';
        $userAgent = strtolower(Filter::get($_SERVER, 'HTTP_USER_AGENT'));
        
        $isMobile = false;
     
        if (preg_match($uaRegex, strtolower($userAgent))) {
            $isMobile = true;
        }
         
        if (strpos(strtolower($_SERVER['HTTP_ACCEPT']), $accept) !== false) {
            $isMobile = true;
        }   
        
        if (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])) {
            $isMobile = true;
        }
         
        $mobileUserAgent = substr($userAgent,0,4);
        $mobileAgents = array(
            'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
            'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
            'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
            'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
            'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
            'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
            'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
            'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
            'wapr','webc','winw','winw','xda','xda-'
        );
         
        if (in_array($mobileUserAgent, $mobileAgents)) {
            $isMobile = true;
        }
         
        if (strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false) {
            $isMobile = true;
        }
         
        if (strpos($userAgent,'windows') !== false) {
            $isMobile = true;
        }
        
        return $isMobile;
    }
}