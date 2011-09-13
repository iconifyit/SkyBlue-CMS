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
 * @date   November 7, 2010
 */

class MyvarsHelper {

    function initialize() {
    	if (file_exists(_SBC_APP_ . "daos/MyvarsDAO.php")) {
    		require_once(_SBC_APP_ . "daos/MyvarsDAO.php");
    	}
    	else {
    		require_once(SB_APP_MANAGERS_DIR . "myvars/MyvarsDAO.php");
    	}
    	require_once(SB_APP_MANAGERS_DIR . "myvars/Myvar.php");
    	require_once(SB_APP_MANAGERS_DIR . "myvars/MyvarsController.php");
    }

    function getDAO($refresh=false) {
        static $Dao;
        if (! is_object($Dao) || $refresh) {
            if (! class_exists('MyvarsDAO')) {
                MyvarsHelper::initialize();
            }
            $Dao = new MyvarsDAO();
        }
        return $Dao;
    }
    
    function getVartypeSelector($selected="") {
        $values = array(
            'string',
            'variable',
            'regex'
        );
        $options = array();
        $count = count($values);
        for ($i=0; $i<$count; $i++) {
            $attrs = array('value' => $values[$i]);
            if (strcasecmp($values[$i], $selected) == 0) {
                $attrs['selected'] = 'selected';
            }
            array_push(
                $options,
                HtmlUtils::tag(
                    'option',
                    $attrs,
                    $values[$i]
                )
            );
        }
        return implode("\n", $options);
    }
}