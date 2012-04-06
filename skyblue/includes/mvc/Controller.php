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

Loader::load("managers.checkouts.CheckoutsHelper", true, _SBC_SYS_);

class Controller extends Publisher {

    /**
     * @var object  The current RequestObject
     */
    var $request;
    
    /**
     * @var object  The Model object
     */
    var $model;
    
    /**
     * @var object  The Data Access Object (DAO)
     */
    var $dao;
    
    /**
     * @var view    The view to use to display the results
     */
    var $view;
    
    /**
     * @var string  The current action being performed. This will typically 
     * be a Controller method name.
     */
    var $action;
    
    /**
     * @var string  The raw action name. This will typically be the URL action parameter.
     */
    var $rawAction;
    
    /**
     * @var array   An array of public methods
     */
    var $methods;
    
    /**
     * @var array   An array mapping actions to handlers
     */
    var $action_map;
    
    /**
     * @var array  An array of actions to which to control access
     */
    var $operations = array();
    
    /**
     * @var Object  The ACL object for this component
     */
    var $acl;
    
    /**
     * @var string  The name of the default view
     */
    var $defaultViewName;
    
    /**
     * @var string  The name of the current view
     */
    var $viewName;
    
    /**
     * @var string  The path where the views are stored
     */
    var $view_path;
    
    /**
     * @var string  The name of the default action
     */
    var $defaultAction;
    
    /**
     * @var string  The HTTP Response Code
     */
    var $httpResponse;
    
    /**
     * @var array  A component-specific configuration table
     */
    var $config;

    /**
     * @constructor
     */
    function __construct($Request) {
    
        /**
         * Set the initial HTTP Response
         */
        
        $this->setHttpResponse(HTTP_200_OK);    
    
        $this->_getMethods();
        $this->setRequest($Request);
        
        $this->setDao(MVC::getDAO($Request->get(VAR_COM)));
        
        $this->setConfig($this->parseConfigFile());
        
        defined('COM_PATH') or
            define('COM_PATH', SB_MANAGERS_DIR . $Request->get(VAR_COM) . '/');
        
        $com = $Request->get(VAR_COM);
        $ViewClass = "";
        if (! empty($com)) {
            $ViewClass = ucwords($com) . "View";
            if (! class_exists($ViewClass)) {
                $ViewClass = "View";
            }
            $Dao =& $this->getDao();
            $this->setView(new $ViewClass(
                $Dao, 
                $this->getViewPath()
            ));
        }

        $this->view->setIsAjax(Filter::getBoolean($Request, VAR_ISAJAX, false));
        $this->parseLanguageFile();
    }
    
    /**
     * Sets the component-specific config property
     * @param array $config  The key=>value paired config array
     * @return void
     */
    function setConfig($config) {
        $this->config = $config;
    }
    
    /**
     * Gets the component-specific config property
     * @return array  The key=>value paired config array
     */
    function getConfig($key=null) {
        if (empty($key)) {
            return $this->config;
        }
        else {
            return Filter::get($this->config, $key, "");
        }
    }
    
    /**
     * Checks for and parses the component-specific config file
     * @return array  The key=>value paired config array
     */
    function parseConfigFile() {
        $config = array();
        $com = $this->request->get(VAR_COM);
        if (file_exists(SB_MANAGERS_DIR . "{$com}/config.php")) {
            $config = FileSystem::read_config(
                SB_MANAGERS_DIR . "{$com}/config.php"
            );
        }
        return $config;
    }
    
    /**
     * Gets a reference to the current Model
     * 
     * Note:
     *
     *    When your code calls this method, you should also use 
     *    a reference indicator when you set your variable. In this 
     *    way, you are manipulating the same object, not a clone 
     *    of the object. So if you update a value on the Model, 
     *    it is updated for all other classes using the model.
     *
     * Example:
     * 
     *    $Dao =& $PageController->getDao();
     *
     * @return reference  A reference to the current Dao
     */
    function & getDao() {
        $dao =& $this->dao;
        return $dao;
    }
    
    /**
     * Sets the Dao for this Controller
     * @param object  A Dao object
     * @return void
     */
    function setDao(&$dao) {
        $this->dao =& $dao;
    }
    
    /**
     * Sets the view object
     * @param object  An instance of the View class
     * @return void
     */
    function setView(&$view) {
        $this->view =& $view;
    }
    
    /**
     * Returns a reference to the current view
     * @return reference  A reference to the current view
     */
    function & getView() {
        $view =& $this->view;
        return $view;
    }
    
    /**
     * Sets the name of the view to use to display the result of the current action.
     * @param string  The view name
     * @return void
     */
    function setViewName($viewName) {
        $this->viewName = $viewName;
    }
    
    /**
     * Gets the name of the current view
     * @return string  The name of the current view
     */
    function getViewName() {
        return $this->viewName;
    }
    
    /**
     * Gets the path where the views for this controller are stored
     * @return string  The path to the views
     */
    function getViewPath() {
        return $this->view_path;
    }
    
    /**
     * Sets the path to the view files
     * @param string  The path to the views
     * @return void 
     */
    function setViewPath($view_path) {
        $this->view_path = $view_path;
        $this->view->setPath($view_path);
    }
    
    /**
     * Sets a member property to a reference of the current RequestObject
     * @param reference  A reference to the current RequestObject
     */
    function setRequest(&$Request) {
        $this->request =& $Request;
    }
    
    /**
     * Since PHP4 does not support interfaces, we must use inheritance.
     * The index method is typically the default method that will be called 
     * if no other method is specified by the request.
     */
    function index() {
        /* Must be defined by child class */
    }
    
    /**
     * Displays the result (view) of the current action.
     * @param boolean  Whether or not to send the HTTP Headers (if false, will be sent by FrontController)
     */
    function display($sendHeaders=false) {
        
        /*
         * Add the component name to the View variables
         */
        $this->view->assign(
            'component',
            Filter::getAlphaNumeric($this->request, VAR_COM)
        );
        
        /*
         * Add the current action to the view
         */
        $this->view->assign(
            'action',
            $this->request->get('action')
        );
        
        $this->view->setView($this->getViewName());
        if (trim($this->getViewName()) == "") {
            $this->view->setView($this->getDefaultViewName());
        }

        /**
         * Give other code the opportunity to modify the Page before it is displayed
         */
    
        Event::trigger('system.beforeDisplay', $this);
    
        /**
         * Allow plugins or other code to over-ride the HTTP Response Code
         */
        
        Event::trigger('system.changeHttpResponse', $this);

        if ($sendHeaders) {
            header($this->getHttpResponse());
        }

        echo $this->view->getView();
    }
    
    /**
     * @deprecated  Use setHandler instead
     */
    function addActionHandler($action, $callback) {
        $this->setHandler($action, $callback);
    }
    
    /**
     * Adds an entry in the action_map to specify which member method to call 
     * to handle a particular action.
     * @param string  The name of the action
     * @param string  The name of the handler (method) to trigger when the $action occurs
     * @return void
     */
    function setHandler($action, $callback, $hasAcl=false) {
        $action = strtolower($action);
        $this->action_map[$action] = $callback;
        if ($hasAcl) {
            $this->addOperation($action);
        }
    }
    
    function addOperation($operation) {
        if (! in_array($operation, $this->getOperations())) {
            array_push($this->operations, $operation);
        }
    }
    
    function getOperations() {
        return $this->operations;
    }
    
    function setOperations($operations) {
        $this->operations = $operations;
    }
    
    function setAcl($acl) {
        $this->acl = $acl;
    }
    
    function getAcl() {
        return $this->acl;
    }
    
    /**
     * Gets the name of the handler (method) registered in action_map to handle the $action
     * @param string   The name of the action
     * @return string  The name of the handler registered for $action
     */
    function getHandler($action) {
        return Filter::get($this->action_map, strtolower($action));
    }

    /**
     * Adds a method to the current method list
     * @return void
     */
    function _addMethod($method) {
        array_push($this->methods, strtolower($method));
    }
    
    /**
      * Gets the action to be triggered
      * @return string  The name of the action
      */
    function getAction() {
        return $this->action;
    }
    
    /**
      * Sets the action to be triggered
      * @param string  The name of the action
      */
    function setAction($action) {
        $this->action = $action;
    }
    
    /**
     * Gets the raw action.
     * @return string
     */
    function getRawAction() {
        return $this->rawAction;
    }
    
    /**
     * Sets the raw_action
     * @return void
     */
    function setRawAction($rawAction) {
        $this->rawAction = $rawAction;
    }
        
    /**
     * Gets the handler for the current action
     * @return string  The name of the handler for the current action
     */
    function _getMethod() {
        $method = $this->request->get('action');
        if (trim($method) == "") {
            $method = $this->getDefaultAction();
        }
        $method = strtolower($method);
        $this->setRawAction($method);
        if (isset($this->action_map[$method])) {
            $method = strtolower($this->action_map[$method]);
        }
        $this->setAction($method);
        return $method;
    }
    
    /**
     * Builds a map of public actions => handlers that 
     * can be triggered by the UI.
     * @return void
     */
    function _getMethods() {
        $this->methods = array();
        $methods = get_class_methods(get_class($this));
        foreach ($methods as $method) {
            if (substr($method, 0, 1) != '_') {
                $this->_addMethod($method);
                $this->addActionHandler($method, $method);
            }
        }
    }
    
    /**
     * Sets the default action to be triggered if no action is specified 
     * in the Request ($_GET or $_POST).
     * @param string  The name of the action
     * @return void
     */
    function setDefaultAction($action) {
        $this->defaultAction = $action;
    }
    
    /**
     * Gets the default action
     * @return string  The default action
     */
    function getDefaultAction() {
        if (isset($this->defaultAction) && !empty($this->defaultAction)) {
            return $this->defaultAction;
        }
        return 'index';
    }
    
    /**
     * Gets the default view name
     * @return string  The default view name
     */
    function getDefaultViewName() {
        return $this->defaultViewName;
    }
    
    /**
     * Sets the default view name
     * @param string $defaultViewName  The default view name
     * @return void
     */
    function setDefaultViewName($defaultViewName) {
        $this->defaultViewName = $defaultViewName;
    }
    
    /**
     * Set the HTTP Response header for the current page
     *
     * @param string  The HTTP Response header
     * @return void
     */
    function setHttpResponse($httpResponse) {
        $this->httpResponse = $httpResponse;
    }
    
    /**
     * Get the HTTP Response header for the current page
     * @param string  The HTTP Response header
     */
    function getHttpResponse() {
        return $this->httpResponse;
    }

    /**
     * Executes the handler registered for the current action
     * @return object  A ResultObject (not yet implemented)
     */
    function execute() {
    
        $ResultObject = null;
    
        $method = $this->_getMethod();

        if ($this->_callable($this->action)) {
            if ($this->_authorize()) {
                $ResultObject = $this->_call($method, $this->request);
            }
            else {
                $this->_setMessage(
                    'error', 
                    __('CONTROLLER.LABEL.PERMISSION_DENIED', '', 1), 
                    __('CONTROLLER.MSG.NOT_ENOUGH_PRIVILEGES', '', 1)
                );
                if (is_logged_in()) {
                    Utils::redirect(get_referrer("admin.php?com=login"));
                }
                else {
                    Utils::redirect("admin.php?com=login");
                }
            }
            $this->view->setMessage($this->getMessage());
        }
        else {
            Utils::redirect(BASE_PAGE);
        }
        return $ResultObject;
    }
    
    /**
     * Determines if the current User is authorized to view/manipulate the current ACO 
     * (access request object).
     * @param object  The Access Request Object begin manipulated.
     */
    function _authorize() {
        global $Authorize;
        return $Authorize->check($this, $this->getRawAction());
    }
    
    /**
     * Determines if the $method is callable (i.e., exists and is not private)
     * @param string    The name of the method
     * @return boolean  Whether or not the method is callable
     */
    function _callable($method) {
        if (in_array($method, $this->methods) && 
            is_callable(array($this, $method))) {
            return true;
        }
        return false;
    }
    
    /**
     * Calls the method, passing the $args to it
     * @param string  The method to call
     * @param array   The method arguments
     * @return void (should this be a boolean?)
     */
    function _call($method, $args) {
        $this->$method($args);
    }
    
    /**
     * @deprecated  Use getMessage instead
     */
    function _getMessage() {
        return $this->getMessage();
    }
    
    /**
     * Adds a message to the Session so it can be displayed by the subsequent view
     * @param string  The message type (e.g., error, note, success)
     * @param string  The title (heading) for the message
     * @param string  The message body
     */
    function _setMessage($type, $title, $message) {
        $Session = Singleton::getInstance('Session');
        $Session->set(
            __CLASS__.'.message',
            array(
                'type'    => $type, 
                'title'   => $title,
                'message' => $message
            )
        );
    }
    
    /**
     * Retrieves the result message of the previous action from the Session.
     * @return array  An array of messages
     */
    function getMessage() {
        $Session = Singleton::getInstance('Session');
        $_message = $Session->get_once(__CLASS__.'.message');
        $_sess_messages = $Session->getMessages();
        if (! empty($_message) && Filter::get($_message, 'type')) {
            return new Message(array(
                'type'    => Filter::get($_message, 'type'),
                'title'   => Filter::get($_message, 'title'),
                'message' => Filter::get($_message, 'message')
            ));
        }
        else if (count($_sess_messages)) {
            $msgArray = array();
            $count = count($_sess_messages);
            for ($i=0; $i<$count; $i++) {
                $_message = $_sess_messages[$i];
                array_push($msgArray, new Message(array(
                    'type'    => Filter::get($_message, 'type'),
                    'title'   => Filter::get($_message, 'title'),
                    'message' => Filter::get($_message, 'message')
                )));
            }
            return $msgArray;
        }
        return null;
    }
    
    /**
     * Tells the LanguageHelper to parse the component-specific language file if it exists.
     * @return void
     */
    function parseLanguageFile() {
        $component = Filter::getAlphaNumeric($_GET, VAR_COM);
        if (empty($component)) return;
        $comLanguageFile = SB_LANG_DIR . Config::get('site_lang') . "/{$component}.ini";
        if (file_exists($comLanguageFile)) {
            $LanguageHelper = Singleton::getInstance('LanguageHelper');
            $LanguageHelper->parse_file($comLanguageFile);
        }
    }
    
    /**
     * A basic Save method
     * @param RequestObject  $Request
     * @param TransferObject $Bean
     * @param string $redirect
     * @return void
     */
    function doSave($Request, $redirect) {
        $BeanClass = $this->dao->getBeanClass();
        if (! class_exists($BeanClass)) {
            trigger_error(
                'SYSTEM.NO_SUCH_CLASS',
                E_USER_ERROR
            );
        }
        if ($this->validate($Request)) {

            $Bean = new $BeanClass($Request);
            
            if ($Request->get('is_new') == 1) {
                $success = $this->dao->insert($Bean);
            }
            else {
                $success = $this->dao->update($Bean);
            }
            
            if ($success) {
                $this->checkIn($Bean);
                $this->_setMessage(
                    'success',
                    __('GLOBAL.SUCCESS', 'Success', 1),
                    __('GLOBAL.SAVE_SUCCESS', 'Your changes were saved successfully.', 1)
                );
                Utils::redirect($redirect);
            }
            else {
                $this->_setMessage(
                    'error',
                    __('GLOBAL.ERROR', 'Error', 1),
                    __('GLOBAL.CHANGES_NOT_SAVED', 'Your changes could not be saved.', 1)
                );
                $this->view->assign('is_new', $Request->get('is_new'));
                $this->showEditForm(new $BeanClass($Request));
            }
        }
        else {
            $this->view->assign('is_new', $Request->get('is_new'));
            $this->showEditForm(new $BeanClass($Request));
        }
    }
    
    /**
     * Displays the Edit form (assumes name is edit.php)
     * @param $Bean  A Bean object of the type being edited
     * @return void
     */
    function showEditForm($Bean) {
        $this->setViewName('edit.php');
        $this->view->setData($Bean);
    }
    
    /**
     * Cancels the current action and redirects to $redirect
     * @param $redirect  The URL to which to redirect
     * @return void
     */
    function doCancel($redirect) {
        $this->_setMessage(
            'tip',
            __('GLOBAL.NOTE', 'Note', 1),
            __('GLOBAL.USER_CANCELLED', 'User canceled. No changes were saved.', 1)
        );
        Utils::redirect($redirect);
    }
    
    /**
     * The default Delete behavior
     * @param reference  $Request   A reference to the RequestObject
     * @param string     $redirect  The page to which to redirect after the action
     * @return void
     */
    function doDelete(&$Request, $redirect) {
        $id = $Request->get('id');
        if (empty($id)) {
            trigger_error(
                __('SYSTEM.UNKOWN_IDENTIFIER', 'An unknown, illegal or empty object identifier was encountered', 1),
                E_USER_ERROR
            );
        }
        if ($this->dao->delete($id)) {
            $this->_setMessage(
                'success',
                __('GLOBAL.SUCCESS', 'Success', 1),
                __('GLOBAL.SAVE_SUCCESS', 'Your changes were saved successfully.', 1)
            );
        }
        else {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('GLOBAL.CHANGES_NOT_SAVED', 'Your changes could not be saved.', 1)
            );
        }
        Utils::redirect($redirect);
    }
    
    /**
     * Adds a validation rule for a field
     * @param string $field         The field to validate
     * @param string $validation    The validation type
     * @param string $errorMessage  The error message
     * @return void
     */
    function addValidation($field, $validation, $errorMessage) {
        if (! isset($this->config)) {
            $this->config = array();
        }
        if (! isset($this->config['validations']) || !is_array($this->config['validations'])) {
            $this->config['validations'] = array();
        }
        if (! isset($this->config['validations'][$field]) || !is_array($this->config['validations'][$field])) {
            $this->config['validations'][$field] = array();
        }
        array_push(
            $this->config['validations'][$field], 
            array('method' => $validation, 'message' => $errorMessage)
        );
    }
    
    /**
     * Used to validate a Request object when a form is submitted
     * @param object    The RequestObject
     * @return boolean  Whether or not the request validates
     * 
     * Add validations to a method {e.g, YourController::doSave()}
     * with the syntax below.
     * 
     * Example:
     * 
     * <code>
     *    $this->addValidation('field1', 'notnull', 'Field1 cannot be empty');
     * </code>
     * 
     * You can also have multiple validations per field:
     * 
     * Example:
     * 
     * <code>
     *    $this->addValidation('field1', 'notnull', 'Field1 cannot be empty');
     *    $this->addValidation('field1', 'email', 'Field1 must be an email address');
     * </code>
     * 
     * You can also integrate the validation configuration with i18n.
     * 
     * Example:
     * 
     * <code>
     *    $this->addValidation('field1', 'notnull', 'COMNAME.VALIDATE.TOKEN1');
     *    $this->addValidation('field1', 'email', 'COMNAME.VALIDATE.TOKEN2');
     * </code>
     */
    function validate($Request) {
        
        /*
         * Get the validations config from the config array
         */
        $validations = Filter::get($this->getConfig(), 'validations');
        
        /*
         * If the array is empty, no validations have been specified
         */
        if (empty($validations)) return true;
        
        /*
         * Get a new Validator Object
         */
        $Validator = Singleton::getInstance('Validator');
        
        /*
         * Create an array to hold our error messages
         */
        $errors = array();
        
        /*
         * Loop through each validation setting
         */
        foreach ($validations as $field=>$validations) {
            
            if (! is_array($validations)) {
                $validations = array($validations);
            }

            $count = count($validations);
            for ($i=0; $i<$count; $i++) {

                $method = Filter::get($validations[$i], 'method');
                $message = Filter::get($validations[$i], 'message');
                
                /*
                 * Make sure Validator::$method() is callable
                 */
                if (is_callable(array($Validator, $method))) {
                    /*
                     * Execute the validation method
                     */
                    if (! $Validator->$method($Request->get($field))) {
                        array_push(
                            $errors, 
                            __($message, $message, 1)
                        );
                    }
                }
            }
        }

        if (count($errors) == 0) return true;
        
        $this->_setMessage(
            'error',
            __('GLOBAL.ERROR', 'Error', 1),
            $errors
        );
        return false;
    }
    
    function checkOut($Object) {
        return CheckoutsHelper::checkOut($Object);
    }
    
    function checkIn($Object) {
        return CheckoutsHelper::checkIn($Object);
    }
}