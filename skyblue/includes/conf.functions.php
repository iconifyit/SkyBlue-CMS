<?php defined('SKYBLUE') or die('Bad file request');

/**
* @version      2.0 2009-04-19 10:37:00 $
* @package        SkyBlueCanvas
* @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
* @license        GNU/GPL, see COPYING.txt
* SkyBlueCanvas is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYING.txt for copyright notices and details.
*/

function sb_conf($conf, $val) {
    $conf = strtoupper($conf);
    
    // Handle special case config settings
    
    switch ($conf) {
        case 'SITE':
            conf_site($val);
            break;
        default:
            sb_define($conf, $val);
            break;
    }
}

function sb_define($def, $val) {
    defined($def) or
    define($def, $val);
}

function conf_site($val) {
    if (defined('SITE')) return;
    define('SITE', $val);
}

function sb_isset($conf) {
    $consts = get_defined_constants();
    return isset($consts[strtoupper($conf)]);
}

function print_gzipped_page() {

    global $HTTP_ACCEPT_ENCODING;
    if (headers_sent()) {
        $encoding = false;
    }
    elseif (strpos($HTTP_ACCEPT_ENCODING, 'x-gzip') !== false) {
        $encoding = 'x-gzip';
    }
    elseif (strpos($HTTP_ACCEPT_ENCODING,'gzip') !== false) {
        $encoding = 'gzip';
    }
    else {
        $encoding = false;
    }

    if ($encoding) {
        $contents = ob_get_contents();
        ob_end_clean();
        header('Content-Encoding: '.$encoding);
        print("\x1f\x8b\x08\x00\x00\x00\x00\x00");
        $size = strlen($contents);
        $contents = gzcompress($contents, 9);
        $contents = substr($contents, 0, $size);
        print($contents);
        exit(0);
    }
    else {
        ob_end_flush();
        exit();
    }
}