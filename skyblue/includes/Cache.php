<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version    2.0 2010-07-12 21:18:00 $
 * @package    SkyBlueCanvas
 * @copyright  Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license    GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */
 
/**
 * @author Scott Lewis
 * @date   July 12, 2010
 */

class Cache extends SkyBlueObject {

    /**
     * @var int  The cache lifetime in minutes
     */

    var $lifetime;
    
    /**
     * @var string  The name of the cache file
     */
    
    var $itemName;
    
    /**
     * @var array  An array of pages already checked by isCached.
     */
    
    var $_cached = array();
    
    /**
     * @var int   The age of the file in minutes
     */
    
    var $the_age;
    
    /**
     * @var bool  Does the file exist and is it not expired
     */
    
    var $is_cached;
    
    /**
     * @var bool  The raw cache of the page
     */
    
    var $the_cache;
    
    /**
     * @var object The CacheDAO object
     */
    var $Dao;
    
    /**
     * Class constructor
     * @param string  The cache file name
     * @param int     The cache lifetime in minutes
     */
    function __construct($itemName, $lifetime=60) {
        
        $this->itemName = $itemName;
        $this->lifetime = $lifetime;
        
        $this->Dao = $this->getDao();
        
        $this->set('file_time',  $this->getCacheTime());
        $this->set('the_age',    $this->getAge());
        $this->set('is_expired', $this->isExpired());
        $this->set('is_cached',  $this->cacheExists());
        $this->set('the_cache',  $this->getCacheContent());
        $this->clearExpiredCache();
    }
    
    function getDao() {
        static $Dao;
        if (! is_object($Dao)) {
            if (! class_exists('CacheDAO')) {
                Loader::load("daos.CacheDAO", true, _SBC_APP_);
            }
            $Dao = new CacheDAO;
        }
        $Dao->itemName = $this->itemName;
        return $Dao;
    }
    
    /**
     * Public method to get the cache contents
     * @return string The cache contents
     */
    function getCache() {
        return $this->the_cache;
    }
    
    /**
     * Private method to read the cache from disk
     * @return string The cache contents
     */
    function getCacheContent() {
        if (! empty($_POST)) return null;
        $content = "";
        if ($this->isCached()) {
            $content = Filter::getRaw($this->getItem($this->itemName), 'content');
            if (trim($content) != "") {
                $content = base64_decode($content);
            }
        }
        return $content;
    }
    
    /** 
     * Gets a cached item
     * @return array  An associative array of the cache item DB row
     */
    function getItem($name) {
        return $this->Dao->getItem($name);
    }
    
    /**
     * Get all cached items
     * @return array
     */
    function getAllItems() {
        return $this->Dao->getAllItems();
    }
    
    /**
     * Returns the previously stored boolean
     * @return bool   Whether or not a valid cache file exists
     */
    function isCached() {
        return $this->is_cached;
    }
    
    /**
     * Checks to see if a valid cache file exists. Valid 
     * means that no data is being posted in the HTTP Request, the file 
     * exists and is not older than the cache lifetime.
     * @return bool   Whether or not a valid cache file exists
     */
    function cacheExists() {
        if (! empty($_POST)) return null;
        if ($this->getItem($this->itemName) && ! $this->is_expired) {
            $this->addToCached($this->itemName, $this->file_time);
            return true;
        }
        return false;
    }
    
    /**
     * Deletes the cached page if expired
     * @return void
     */
    function clearExpiredCache() {
        if ($this->is_expired) {
            $this->clearItem($this->itemName);
            $this->removeFromCache($this->itemName);
        }
    }
    
    /**
     * Calculates the age of the cache file
     * @return int   The age of the cache file in minutes
     */
    function getAge() {
        return round(round(abs(
            time() - $this->file_time
        )) / 60);
    }
    
    /**
     * Gets the file_time by checking to see if a local value was previously stored. 
     * if not, the filectime is read via stat.
     * @return int   The time in seconds, that the file was last modified
     */
    function getCacheTime() {
        $file_time = 0;
        if ($item = $this->getItem($this->itemName)) {
            if (isset($this->_cached[$this->itemName])) {
                $file_time = $this->_cached[$this->itemName];
            }
            $file_time = Filter::get($item, 'time');
        }
        return $file_time;
    }
    
    /** 
     * Determines if a cache file has expired
     * @param string  The cache file path
     * @return bool   Whether or not the file has expired
     */
    function isExpired() {
        $item = $this->getItem($this->itemName);
        if (! $item) return true;
        return ($this->the_age > $this->lifetime);
    }
    
    /**
     * Saves an item to the cache
     * @param string    The content to cache
     * @return boolean  Whether or not the content was cached
     */
    function saveCache($content) {
        return $this->Dao->saveCache($content);
    }
    
    /**
     * Updates the cache file time to the current time
     * @return void
     */
    function touchCache() {
        return $this->Dao->touchCache();
    }
    
    /**
     * Deletes the cache file
     * @return void
     */
    function clearItem($id) {
        $result = false;
        if ($result = $this->Dao->clearItem($id)) {
            $this->removeFromCache($id);
        }
        return $result;
    }
    
    /**
     * Removes the file index from _cached array
     * @return void
     */
    function removeFromCache($itemName) {
        if ($itemName == "*") {
            $this->_cached = array();
        }
        else if (isset($this->_cached[$itemName])) {
            unset($this->_cached[$itemName]);
        }
    }
    
    /**
     * Adds the file index to _cached array
     * @return void
     */
    function addToCached($itemName, $filetime) {
        if (! isset($this->_cached[$itemName])) {
            $this->_cached[$itemName] = $filetime;
        }
    }
    
    /**
     * Deletes all cahced files
     * @return bool  Whether or not all cached files were deleted
     */
    function clearAll() {
        $result = false;
        if ($result = $this->Dao->clearAll()) {
            $this->removeFromCache("*");
        }
        return $result;
    }
}