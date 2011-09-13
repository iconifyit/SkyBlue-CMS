<?php

/**
 * @version        1.1 RC1 2008-11-20 21:18:00 $
 * @package        SkyBlueCanvas
 * @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license        GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

// Exit Codes ( Used in core.class.php )

$TERMS['EXITCODE_0']['str']     = 'Your changes could not be saved';
$TERMS['EXITCODE_0']['class']   = 'error';

$TERMS['EXITCODE_1']['str']     = 'Your changes have been saved';
$TERMS['EXITCODE_1']['class']   = 'confirm';

$TERMS['EXITCODE_2']['str']     = 'User Cancelled - No changes were saved.';
$TERMS['EXITCODE_2']['class']   = 'info';

$TERMS['EXITCODE_3']['str']     = 'Invalid Item ID';
$TERMS['EXITCODE_3']['class']   = 'error';

$TERMS['EXITCODE_4']['str']     = 'The file is not an accepted type';
$TERMS['EXITCODE_4']['class']   = 'error';

$TERMS['EXITCODE_5']['str']     = 'The uploaded file does not match the type you specified';
$TERMS['EXITCODE_5']['class']   = 'error';

$TERMS['EXITCODE_7']['str']     = 'File is too large to upload';
$TERMS['EXITCODE_7']['class']   = 'error';

$TERMS['EXITCODE_8']['str']     = 'File could not be created.';
$TERMS['EXITCODE_8']['class']   = 'error';

$TERMS['EXITCODE_9']['str']     = 'The form has been reset.';
$TERMS['EXITCODE_9']['class']   = 'warning';


// Terms for the Meta Data Manager

$TERMS['CONTENT'] = 'Content';
$TERMS['ITEMID']  = 'Item ID';
$TERMS['NAME']    = 'Name';

// Translation Test

$TERMS['Internationalization'] = 'Iñtërnâtiônàlizætiøn';

$chars = array();
$chars["ñ"] = "n";
$chars["ë"] = "e";
$chars["â"] = "a";
$chars["ô"] = "o";
$chars["à"] = "a";
$chars["æ"] = "a";
$chars["ø"] = "o";

$specialChars = "ñ";
$regularChars = "n";

# Iñtërnâtiônàlizætiøn

$entities = array();
$entities['&auml;'] = 'ae';
$entities['&ouml;'] = 'oe';
$entities['&uuml;'] = 'ue';
$entities['&szlg;'] = 'ss';
$entities['&amp;']  = '-and-';
$entities[' & ']    = '-and-';
$entities['&']      = '-and-';
$entities[' - ']    = '-';
$entities['/']      = '-';
$entities[' / ']    = '-';
$entities[' ']      = '-';
$entities['&lt;']      = '-lt-';
$entities['&gt;']   = '-gt-';