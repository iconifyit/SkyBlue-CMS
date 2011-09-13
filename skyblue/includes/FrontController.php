<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version        2.0 2008-12-12 23:50:00 $
 * @package        SkyBlueCanvas
 * @copyright      Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license        GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

class FrontController extends Publisher {

    var $com;
    var $action;
    var $valid_coms;
    var $request;
    var $result;
    var $Controller;
    
    /**
     * @param array Array of options
     * <pre>
     * array(
     *     'component'  => $componentName,
     *     'root'       => $pathToRoot,
     *     'default'    => $defaultComponent,
     *     'valid_coms' => $validComponents,
     *     'redirect'   => $redirectUrl
     * )
     * </pre>
     */

    function __construct(&$Request, $options=array()) {
        
        set_error_handler(array('FrontController', 'handleError'), E_ALL);

        $this->trigger('system.beforeInit');
        
        $this->setRequest($Request);
        
        $class     = Filter::get($options, 'component');
        $root      = Filter::get($options, 'root');
        $default   = Filter::get($options, 'default');
        $redirect  = Filter::get($options, 'redirect');
        $component = get_component($class);
        $rootpath  = Filter::get($component, 'type');
        
        if (trim($rootpath) != "") {
            $rootpath = get_constant($rootpath);
        }
        else {
            $rootpath = _SBC_SYS_;
        }

        $this->valid_coms = Filter::get($options, 'valid_coms', array());
        
        $this->trigger('system.afterInit');

        if (empty($class)) {
            trigger_error(
                __('FC.NO_COMPONENT_SPECIFIED', 'Class option not defined in FrontController', 1), 
                E_USER_ERROR
            );
        }
        else if (empty($root)) {
            trigger_error(
                __('FC.ROOT_NOT_SPECIFIED', 'Root option not defined in FrontController', 1), 
                E_USER_ERROR
            );
        }
        else if (empty($default)) {
            trigger_error(
                __('FC.DEFAULT_COMPONENT_NOT_SET', 
                   'Default Component option not defined in FrontController', 1), 
                E_USER_ERROR
            );
        }
        else if (!in_array($class, $this->valid_coms)) {
            trigger_error(
                __('FC.INVALID_COMPONENT_NAME', 'Invalid Component name', 1),
                E_USER_ERROR
            );
        }
        
        $this->trigger('system.beforeLoadAssets');
        
        MVC::loadHelperClass($class, $rootpath);
        MVC::loadViewClass($class, $rootpath);

        $this->trigger('system.afterLoadAssets');
        
        $this->com = $class;
        $this->request->com = $class;
        
        if (empty($this->com)) {
            $this->com = $default;
            $this->request->com = $default;
        }

        $this->Controller = MVC::getController($this->request);
        
        $this->trigger('system.beforeExecute');
        
        $this->Controller->execute();
        
        $this->trigger('system.afterExecute');

        ob_start();
        $this->Controller->display();
        $buffer = ob_get_contents();
        ob_end_clean();
        
        $this->trigger('system.afterBuffer');
        
        $this->setResult($buffer);
    }
    
    function setRequest(&$Request) {
        $this->request =& $Request;
    }
    
    function setResult(&$result) {
        $this->result =& $result;
    }
    
    function getResult() {
        return $this->result;
    }
    
    function display($sendHeaders=true) {
        Event::trigger('system.beforeDisplay', $this);
        if ($sendHeaders) {
            header($this->Controller->getHttpResponse());
        }
        echo Event::trigger('system.display', $this->getResult());
    }
    
    /**
     * Custom Error Handler
     * @param int       The PHP error number
     * @param string    The Error Message string
     * @param string    The file where the error occurred
     * @param int       The line number where the error occurred
     * @return boolean  Whether or not the error was handled
     */
    function handleError($errno, $errstr, $errfile, $errline) {
        $level = 0;
        $fatal = false;
        switch ($errno) {
            case E_NOTICE:
            case E_USER_NOTICE:
                $level = 1;
                $type = __('ERROR.NOTICE', 'Notice', 1);
                break;
            case E_WARNING:
            case E_USER_WARNING:
                $level = 2;
                $type = __('ERROR.WARNING', 'Warning', 1);
                break;
            case E_ERROR:
            case E_USER_ERROR:
                $fatal = true;
                $level = 3;
                $type = __('ERROR.FATAL', 'Fatal Error', 1);
                break;
            default:
                $level = 3;
                $type = __('ERROR.UNKNOWN', 'Unknown', 1);
                break;
        }
    
        if ($fatal && Config::get("display_errors")) {
            $Model = new Model("");
            $Model->setData(new Error(
                $type,
                $errno,
                $errstr,
                $errfile,
                $errline,
                debug_backtrace()    
            ));
            $View = new View($Model, "resources/ui/admin/html/");
            $View->setView("error.php");
            echo $View->getView();
        }
        if ($level >= Config::get('log_level') && Config::get('log_errors')) {
            error_log(sprintf(
                __('ERROR.LOG_STR', "PHP %s:  %s in %s on line %d", 1), 
                $type, $errstr, $errfile, $errline
            ));
        }
        if ($fatal && Config::get('display_errors')) {
            exit(1);
        }
        if ($fatal && !Config::get("display_errors")) {
            Utils::redirect("admin.php");
        }
        return true;
    }
    
}