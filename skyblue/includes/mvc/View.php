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

class View extends Publisher {
    
    var $data;
    var $tokens;
    var $vars;
    var $view;
    var $view_name;
    var $dao;
    var $message;
    var $isAjax = false;

    function __construct(&$Dao, $path=null) {
        $this->path = $path;
        $this->dao =& $Dao;
        $this->tokens = array();
    }
    
    function setView($name, $params=array()) {
        $this->view_name = $name;
        
        $this->assign('com', Filter::getAlphaNumeric($_REQUEST, 'com'));
        
        if (! is_array($this->tokens)) {
            $this->tokens = array();
        }
        $this->tokens = array_merge($this->tokens, $params);
        
        $this->view = FileSystem::buffer(
            "{$this->path}{$this->view_name}", 
            $this->tokens
        );

        if (! $this->isAjax && get_constant('_ADMIN_') === 1) {
            $this->assign('body', $this->view);
            $this->assign('dashboard', '');
            $this->view = FileSystem::buffer(
                SB_ADMIN_SKINS_DIR . 'skin.index.php', 
                $this->tokens
            );
        }
    }
    
    function setIsAjax($isAjax) {
        $this->isAjax = $isAjax;
    }
    
    function getData() {
        return $this->data;
    }
    
    function setData($data) {
        $this->data = $data;
    }
    
    function setDao(&$dao) {
        $this->dao =& $dao;
    }
    
    function getDao() {
        return $this->dao;
    }
    
    function getView() {
        return $this->render($this->view);
    }
    
    function setPath($path) {
        $this->path = $path;
    }
    
    function buffer_view($view_file) {
        ob_start();
        include($view_file);
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }
    
    function setMessage($message) {
        $this->message = $message;
    }
    
    function getMessage() {
        return $this->message;
    }
    
    function assign($token, $value) {
        $this->tokens[$token] = $value;
    }
    
    function render($html) {

        /**
         * Trigger in-line fragment calls.
         */
        
        $Fragmentor = new FragmentorPlugin($this);
        $Fragmentor->execute($html);
        $html = $Fragmentor->getHtml();

        /**
         * Execute callbacks for OnRenderPage
         */
        
        $html = Event::trigger('view.onRender', $html);
        
        /**
         * Parse in-line plugin calls
         */
        
        $html = parse_plugins($html);
        
        /**
         * Translate and print the current page
         */
        
        $html = translate($html);
        
        if (!count($this->tokens)) return $html;
        
        foreach ($this->tokens as $token=>$value) {
            if (is_array($value)) continue;
            $html = str_replace("[[$token]]", $value, $html);
        }
    
        return $html;
    }

    function getVar($key, $default=null) {
        if (isset($this->tokens[$key])) {
            return $this->tokens[$key];
        }
        return $default;
    }
    
}