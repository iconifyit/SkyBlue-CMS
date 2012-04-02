<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2008-12-12 23:50:00 $
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

class DAO extends PDO {

    /**
     * The name of the Bean class
     * @var string
     */
    var $beanClass;
    
    /**
     * The data source (file path, DSN, URI, etc.)
     * @var string
     */
    var $source;

    /**
     * Class constructor
     * @param string $dsn
     * @param string $user
     * @param string $pass
     * @throws Exception 
     */
    function __construct($dsn, $user, $pass) { 
        try {
            parent::__construct($dsn, $user, $pass);
        }
        catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
    
    /**
     * Gets a reference to the data source
     * @return string 
     */
    function getSource() {
        return $this->source;
    }
    
    function setSource($source) {
        $this->source = $source;
    }
    
    /**
     * Gets the name of the bean class
     * @return string 
     */
    function getBeanClass() {
        return $this->beanClass;
    }
    
    /**
     * Sets the name of the bean class
     * @param string $beanClass  The bean class
     */
    function setBeanClass($beanClass) {
        $this->beanClass = $beanClass;
    }
    
    /**
     * Counts the number of data objects
     * @return int 
     */
    function countItems() {
        return count($this->index());
    }
    
    /**
     * Gets a single Bean object
     * @param string $query    The query to get one object
     * @return TransferObject  The data transfer object
     * @throws Exception 
     */
    function getItem($query) {
        $result = null;
        try {
            $Statement = $this->query($query);
            if (is_callable(array($Statement, 'fetchObject'))) {
                $result = $Statement->fetchObject($this->getBeanClass());
            }
        }
        catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
        return $result;
    }
    
    /**
     * Lists all the data objects of the class
     * @param string $query  The query to list all data objects
     * @return array         An array of data transfer objects
     * @throws Exception 
     */
    function index($query) {
        $result = null;
        try {
            $Statement = $this->query($query);
            if (is_callable(array($Statement, 'fetchAll'))) {
                $result = $Statement->fetchALL(PDO::FETCH_CLASS, $this->getBeanClass());
            }
        }
        catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
        return $result;
    }
    
    /**
     * Deletes a data object
     * @param string $query  The delete query
     * @return int           The number of affected rows
     * @throws Exception 
     */
    function delete($query) {
        $result = false;
        try {
            $result = $this->exec($query);
        }
        catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
        return $result;
    }
    
    /**
     * Inserts a data object
     * @param string $query  The query to insert a data object
     * @return boolean
     * @throws Exception 
     */
    function insert($query) {
        $result = false;
        try {
            $result = $this->exec($query);
        }
        catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
        return $result;
    }
    
    /**
     * Updates a data object
     * @param string $query  The query to update a data object
     * @return int           The number of affected rows
     * @throws Exception 
     */
    function update($query) {
        $result = false;
        try {
            $result = $this->exec($query);
        }
        catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
        return $result;
    }

    function save() { return false; }
    function getById($id) { return false; }
    function exists($name) { return false; }
    function copy($TransferObject) { return false; }
}