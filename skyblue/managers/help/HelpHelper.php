<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2009-06-20 21:41:00 $
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
 * @date   June 20, 2009
 */

class HelpHelper {
    function initialize() {
        if (file_exists(_SBC_APP_ . "daos/HelpDAO.php")) {
            require_once(_SBC_APP_ . "daos/HelpDAO.php");
        }
        else {
            require_once(SB_MANAGERS_DIR . "help/HelpDAO.php");
        }
        require_once(SB_MANAGERS_DIR . "help/Help.php");
        require_once(SB_MANAGERS_DIR . "help/HelpController.php");
    }
}