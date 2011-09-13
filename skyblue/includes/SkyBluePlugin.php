<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version     2.0 2009-04-19 10:37:00 $
 * @package        SkyBlueCanvas
 * @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license        GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General License, and as distributed it includes or
 * is derivative of works licensed under the GNU General License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */
    
class SkyBluePlugin extends Publisher {

    var $html;
    var $config;
    var $filename;
    
    function __construct($html) {
        $this->getSettings();
        $this->setHtml($html);
    }
    
    function execute($html) {
        $this->setHtml(trim($html) != "" ? $html : $this->getHtml());
    }
    
    function getOption($name, $default='') {
        return Filter::getRaw($this->config, $name, $default);
    }
    
    function addOption($name, $value) {
        $this->config[$name] = $value;
    }
    
    function getSettings() {
        try {
            $config = array();
            $Dao = ExtensionsHelper::getDao(true);
            $Bean = $Dao->getItem($this->filename);
            if ($Bean && $content = $Bean->getContent()) {
                $lines = explode("\n", $content);
                foreach ($lines as $line) {
                    $bits = explode("=", $line);
                    if (count($bits) == 2) {
                        $this->addOption(trim($bits[0]), trim($bits[1]));
                    }
                }
            }
            return $config;
        }
        catch (Exception $e) {
            throw new Exception($e);
        }
    }
    
    function getHtml() {
        return $this->html;
    }
    
    function setHtml($html) {
        $this->html = $html;
    }
    
    function _parseParams($token) {
        $arr = explode('?', $token);
        $params = array();
        if (count($arr) > 1) {
            $params = $this->_parseQuery(Filter::get($arr, 1, null));
        }
        return array(
            'base'   => $arr[0],
            'params' => $params
        );
    }
    
    function _parseQuery($str) {
    
        $str = html_entity_decode($str);

        $arr = array();
        
        $pairs = explode('&', $str);
        
        foreach ($pairs as $i) {
            list($name,$value) = explode('=', $i, 2);

            if (isset($arr[$name])) {
                if (is_array($arr[$name])) {
                    $arr[$name][] = $value;
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
    
}