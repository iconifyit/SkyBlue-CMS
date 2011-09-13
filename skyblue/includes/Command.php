<?php defined('SKYBLUE') or die('Bad file request');

/**
* @version        v 1.2 2009-04-19 10:37:00 $
* @package        SkyBlueCanvas
* @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
* @license        GNU/GPL, see COPYING.txt
* SkyBlueCanvas is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYING.txt for copyright notices and details.
*/

class Command extends TransferObject {

    /**
     * The current RequestObject
     * @var RequestObject
     */
    var $request;
    
    /**
     * An array of Command parameters
     * @var array
     */
    var $params;
    
    /**
     * A callback to be executed on successful completion of the command
     * @var array
     */
    var $onSuccess;
    
    /**
     * A callback to be executed when an error is encountered
     * @var array
     */
    var $onError;
    
    /**
     * Gets the internal RequestObject
     * @return RequestObject
     */
    function getRequest() {
        return $this->request;
    }
    
    /**
     * Sets the internal RequestObject
     * @param RequestObject $request
     * @return void
     */
    function setRequest($request) {
        $this->request = $request;
    }
    
    /**
     * Gets the Command paramters array
     * @return array
     */
    function getParams() {
        return $this->params;
    }
    
    /**
     * Sets the Command parameters array
     * @param array $params
     * @return void
     */
    function setParams($params) {
        $this->params = $params;
    }
    
    /**
     * Gets a specific Command paramter
     * @param string $name
     * @return mixed
     */
    function getParam($name) {
        if (isset($this->params[$name])) {
            return $this->params[$name];
        }
        return null;
    }
    
    /**
     * Sets a specific Command paramter
     * @param string $name
     * @param mixed  $value
     * @return void
     */
    function setParam($name, $value) {
        $this->params[$name] = $value;
    }
    
    /**
     * Adds a value to an existing parameter
     * @param string $name   The parameter name
     * @param mixed  $value  The parameter value to add
     * @return void
     */
    function addParam($name, $value) {
        if (!isset($this->params[$name])) {
            $this->setParam($name, $value);
        }
        else {
            $oldParam = $this->getParam($name);
            if (!is_array($oldParam)) {
                $oldParam = array($oldParam);
            }
            array_push($oldParam, $value);
            $this->setParam($name, $oldParam);
        }
    }
    
    /**
     * Gets the onSuccess handler
     * @return array
     */
    function getOnSuccess() {
        return $this->onSuccess;
    }
    
    /**
     * Sets the onSuccess handler
     * @param array $onSuccess
     * @return void
     */
    function setOnSuccess($onSuccess) {
        $this->onSuccess = $onSuccess;
    }
    
    /**
     * Gets the onError handler
     * @return array
     */
    function getOnError() {
        return $this->onError;
    }
    
    /**
     * Sets the onError handler
     * @param array $onError
     * @return void
     */
    function setOnError($onError) {
        $this->onError = $onError;
    }

}

/*
new Command(array(
    'request' => $Request,
    'params' => array(
    'validations' => array(
        array('name', 'notnull', 'CONTACTS.VALIDATE.SITEURL'),
        array('email', 'email', 'CONTACTS.VALIDATE.EMAIL')
    ))
));
*/