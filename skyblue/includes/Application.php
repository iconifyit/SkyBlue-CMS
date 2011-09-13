<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version        2.0 2009-04-19 10:37:00 $
 * @package        SkyBlueCanvas
 * @copyright      Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license        GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General License, and as distributed it includes or
 * is derivative of works licensed under the GNU General License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

define('APP_URL', 'admin.php');
define('APP_DIR', dirname(__FILE__));

class Application extends FrontController {

    var $html;
    var $components;
    
    function __construct($Request, $options=array()) {
        parent::__construct(
            $Request,
            array(
                'component'  => Filter::get($options, 'component'),
                'valid_coms' => Filter::get($options, 'valid_coms', $this->getValidComponents()),
                'default'    => Filter::get($options, 'default'),
                'root'       => Filter::get($options, 'root'),
                'redirect'   => Filter::get($options, 'redirect')
            )
        );
        $this->setHtml($this->getResult());
    }
    
    function setHtml($html) {
        $this->html = $html;
    }
    
    function getHtml() {
        return $this->html;
    }
    
    function getValidComponents() {
        global $Core;
        $this->components = get_components();
        
        $managers = array();
        $count = count($this->components);
        
        foreach ($this->components as $com) {
            array_push($managers, $com->name);
        }
        return $managers;
    }
    
}