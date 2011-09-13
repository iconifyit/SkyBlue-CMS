<?php defined('SKYBLUE') or die("Bad file request");

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
 * @date   October 27, 2010
 */

global $Core;

/**
 * Register the plugin event
 */

Event::register('Foo.bar', 'do_test_plugin');

function do_test_plugin($foo="") {
    return $foo;
}