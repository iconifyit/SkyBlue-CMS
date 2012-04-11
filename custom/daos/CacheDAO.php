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

class CacheDAO extends SqliteDAO {
    
    /**
     * Class constructor
     * @param string  The cache file name
     * @param int     The cache lifetime in minutes
     */
    function __construct() {
        parent::__construct(array(
            'type' => 'cache', 
            'bean_class' => 'Cache'
        ));
        if (! $this->tableExists("Cache")) {
            try {
                $this->query(
                    "BEGIN; " . 
                    "CREATE TABLE Cache (" . 
                        "id CHAR(255) PRIMARY KEY, " . 
                        "content CHAR(255), " . 
                        "time CHAR(255)" . 
                    ");" . 
                    " COMMIT;"
                );
            }
            catch (PDOException $e) {
                die($e->getMessage());
            }
        }
    }
    
    /** 
     * Gets a cached item
     * @return array  An associative array of the cache item DB row
     */
    function getItem($id) {
        $Statement = $this->query("SELECT * FROM Cache WHERE id = '{$id}' LIMIT 1"); 
        if ($result = $Statement->fetch(PDO::FETCH_ASSOC)) {
            return $result;
        }
        return null;
    }
    
    /**
     * Get all cached items
     * @return array
     */
    function getAllItems($refresh=false) {
        $Statement = $this->query("SELECT * FROM Cache"); 
        if ($result = $Statement->fetchAll(PDO::FETCH_ASSOC)) {
            return $result;
        }
        return null;
    }

    /**
     * Saves an item to the cache
     * @param string    The content to cache
     * @return boolean  Whether or not the content was cached
     */
    function saveCache($content) {
        $result = false;
        $id     = $this->itemName;
        $time   = time();
        if (! $this->getItem($id)) {
            $content = base64_encode($content);
            try {
                $this->query(
                    "INSERT INTO Cache (id, content, time) "
                    . " VALUES ('{$id}', '{$content}', '{$time}');"
                );
            }
            catch (PDOException $e) {
                die($e->getMessage());
            }
        }
        else {
            $result = $this->touchCache();
        }
        return $result;
    }
    
    /**
     * Updates the cache file time to the current time
     * @return void
     */
    function touchCache() {
        $result = false;
        try {
            $result = $this->query(
                "UPDATE Cache SET time = '{$time}' WHERE id = '{$id}';" 
            );
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
        return $result;
    }
    
    /**
     * Deletes the cache file
     * @return void
     */
    function clearItem($id) {
        $result = false;
        try {
            $result = $this->query(
                "DELETE FROM Cache WHERE id = '{$id}'"
            );
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
        return $result;
    }
    
    /**
     * Deletes all cahced files
     * @return bool  Whether or not all cached files were deleted
     */
    function clearAll() {
       $result = false;
        try {
            $result = $this->query(
                "DELETE FROM Cache"
            );
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
        return $result;
    }
}