<?php defined('SKYBLUE') or die('Bad file request');

/**
* @version        RC 1.0.3.2 2008-04-24 15:03:43 $
* @package        SkyBlueCanvas
* @copyright    Copyright (C) 2005 - 2008 Scott Edwin Lewis. All rights reserved.
* @license        GNU/GPL, see COPYING.txt
* SkyBlueCanvas is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYING.txt for copyright notices and details.
*/

global $Core;

Event::register('OnRenderPage', 'plgPreloader');

function plgPreloader($html) {
    $preloader = new Preloader($html);
    return $preloader->get('result');
}

class Preloader extends SkyBlueObject
{

    var $images     = array();
    var $javascript = null;
    var $html       = null;
    
    function __construct($html) 
    {
        $this->html = $html;
        $this->InitSessionVar();
        $this->getImageTags();
        $this->initSkinImages();
        $this->buildPreloadJS();
        $this->result = $this->html;
    }
    
    function initSkinImages()
    {
        global $Core;
        $dir = ACTIVE_SKIN_DIR.'images/';
        $images = FileSystem::list_files($dir);
        for ($i=0; $i<count($images); $i++) {
            $arr = $_SESSION['preloaded'];
            if (! in_array($images[$i], $arr)) {
                $this->images[] = $images[$i];
                $_SESSION['preloaded'][] = $images[$i];
            }
        }
    }
    
    function buildPreloadJS()
    {
        global $Core; 
        
        if (count($this->images) > 0)
        {
            $cdata  = 'if (document.images)'."\r\n";
            $cdata .= str_repeat(' ', 8).'{'."\r\n";
            
            for ($i=0; $i<count($this->images); $i++)
            {
                $cdata .= str_repeat(' ', 12).'image'.$i.' = new Image();'."\r\n";
            }
            
            for ($i=0; $i<count($this->images); $i++)
            {
                $src = $this->images[$i];
                $cdata .= str_repeat(' ', 12).'image'.$i.'.src = "'.$src.'";'."\r\n";
            }
            
            $cdata .= str_repeat(' ', 8).'}';
            $attrs = array();
            $attrs['type'] = 'text/javascript';
            $this->javascript = HtmlUtils::tag('script', $attrs, $cdata, 1);
        }
        $this->html = str_replace('<!--#preloader-->', $this->javascript, $this->html);
    }
    
    function InitSessionVar()
    {
        if (!array_key_exists('preloaded', $_SESSION) ||
             !is_array($_SESSION['preloaded']))
        {
            session_register('preloaded');
            $_SESSION['preloaded'] = array();
        }
    }
    
    function getImageTags()
    {
        global $Core;
        
        $regex = "'src=[\"|\']([^\"\']+.[gif|png|jpg|jpeg])+[\"|\']'i";
        preg_match_all($regex, $this->html, $matches);
        $matches = $matches[1];
        for ($i=0; $i<count($matches); $i++)
        {
            $image = $matches[$i];
            $arr   = $_SESSION['preloaded'];
            if (!in_array($image, $this->images) &&
                 !in_array($image, $arr))
            {
                $this->images[] = $image;
                $_SESSION['preloaded'][] = $image;
            }
        }
    }
}