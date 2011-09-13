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

    function __construct($dsn, $user, $pass) { 
        try {
            parent::__construct($dsn, $user, $pass);
        }
        catch (PDOException $e) {
			die($e->getMessage());
		}
    }
    
    function getSource() {
        return $this->source;
    }
    
    function setSource($source) {
        $this->source = $source;
    }
    
    function getBeanClass() {
        return $this->beanClass;
    }
    
    function setBeanClass($beanClass) {
        $this->beanClass = $beanClass;
    }
    
    function countItems() {
        return count($this->index());
    }
    
    function getItem($query) {
        $result = null;
        try {
            $Statement = $this->query($query);
            if (is_callable(array($Statement, 'fetchObject'))) {
                $result = $Statement->fetchObject($this->getBeanClass());
            }
        }
        catch (PDOException $e) {
			die($e->getMessage());
		}
        return $result;
    }
    
    function index($query) {
        $result = null;
        try {
            $Statement = $this->query($query);
            if (is_callable(array($Statement, 'fetchAll'))) {
                $result = $Statement->fetchALL(PDO::FETCH_CLASS, $this->getBeanClass());
            }
        }
        catch (PDOException $e) {
			die($e->getMessage());
		}
        return $result;
    }
    
    function delete($query) {
        $result = false;
        try {
            $result = $this->exec($query);
        }
        catch (PDOException $e) {
			die($e->getMessage());
		}
		return $result;
    }
    
    function insert($query) {
        $result = false;
        try {
            $result = $this->exec($query);
        }
        catch (PDOException $e) {
			die($e->getMessage());
		}
		return $result;
    }
    
    function update($query) {
        $result = false;
        try {
            $result = $this->exec($query);
        }
        catch (PDOException $e) {
			die($e->getMessage());
		}
		return $result;
    }

    function save() { return false; }
    function getById($id) { return false; }
    function exists($name) { return false; }
    function copy($TransferObject) { return false; }
}