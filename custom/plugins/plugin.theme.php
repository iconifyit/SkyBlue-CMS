<?php defined('SKYBLUE') or die("Bad file request");

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


global $Core;

/**
 * Register the plugin event
 */

Event::register('LanguageHelper.append', 'add_theme_lang');

function add_theme_lang(&$helper) {
    if (file_exists(ACTIVE_SKIN_DIR . "language/theme.ini")) {
        $helper->_add(
            parse_ini_file(ACTIVE_SKIN_DIR . "language/theme.ini", true)
        );
    }
}