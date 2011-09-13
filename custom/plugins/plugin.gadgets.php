<?php defined('SKYBLUE') or die(basename(__FILE__));

/**
* @version        RC 1.1 2008-08-14 18:12:00 $
* @package        SkyBlueCanvas
* @copyright      Copyright (C) 2005 - 2008 Scott Edwin Lewis. All rights reserved.
* @license        GNU/GPL, see COPYING.txt
* SkyBlueCanvas is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYING.txt for copyright notices and details.
*/

define('REGEX_GADGET_INLINE',  "/({gadget:([^}]*)})/i");
define('REGEX_GADGET_COMMENT', "/(<!--#gadget:(.*)-->)/i");
define('GADGETS_DIR',          SB_SITE_DATA_DIR . "gadgets/");

global $Core;

$Core->register('OnRenderPage', 'GoGoGadget');

function GoGoGadget($html) {
    $html = GadgetCommentToken($html);
    $html = GadgetInlineToken($html);
    return $html;
}

function GadgetCommentToken($html) {
    global $Core;
    preg_match_all(REGEX_GADGET_COMMENT, $html, $tokens);
    if (count($tokens) < 3) return $html;
    $tokens = $tokens[2];

    for ($i=0; $i<count($tokens); $i++) {
        $name = $tokens[$i];
        if (empty($name)) return $html;
        $id = GadgetIdFormat($name);
        
        $file = GADGETS_DIR . $name . 
            (strpos($name, ".js") === false ? ".js" : null);
        
        if (file_exists($file)) {
            $html = str_replace(
                "<!--#gadget:$name-->",
                "<div id=\"gadget-$id\">\n" .  FileSystem::read_file($file) . "\n</div>\n",
                $html
            );
        }
    }
    return $html;
}

function GadgetInlineToken($html) {
    global $Core;
    preg_match_all(REGEX_GADGET_INLINE, $html, $tokens);
    if (count($tokens) < 3) return $html;
    $tokens = $tokens[2];
    for ($i=0; $i<count($tokens); $i++) {
        $name = $tokens[$i];
        if (empty($name)) return $html;
        
        $id = GadgetIdFormat($name);
        
        $file = GADGETS_DIR . $name . 
            (strpos($name, ".js") === false ? ".js" : null);
        
        if (file_exists($file)) {
            $html = str_replace(
                "{gadget:$name}",
                "<div id=\"gadget-$id\">\n" .  $Core->SBReadFile($file) . "\n</div>\n",
                $html
            );
        }
    }
    
    return $html;
}

function GadgetIdFormat($str) {
    $id = null;
    $str = strtolower($str);
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789-_";
    for ($i=0; $i<strlen($str); $i++) {
        $id .= strpos($chars, $str{$i}) === false ? "-" : $str{$i} ;
    }
    return $id;
}

?>