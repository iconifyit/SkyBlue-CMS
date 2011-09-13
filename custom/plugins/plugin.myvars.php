<?php defined('SKYBLUE') or die("Bad file request");

/**
 * @version        2.0 2010-07-08 21:30:00 $
 * @package        SkyBlueCanvas
 * @copyright      Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license        GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */
 
Event::register('OnRenderPage', 'doMyVarsPlugin');

function doMyVarsPlugin($html) {
    try {
        if (trim($html) == "") return $html;
    
		if (! class_exists('MyvarsHelper')) {
			Loader::load("managers.myvars.MyvarsHelper", true, _SBC_APP_);
		}
		
		if ($Dao = MyvarsHelper::getDAO()) {
			$myvars = $Dao->index();
			
			if (count($myvars) == 0) return $html;
			
			foreach ($myvars as $Bean) {
				$variable = $Bean->getName();
				$value    = $Bean->getValue();
				
				if (strcasecmp($Bean->getVartype(), "variable") == 0) {
					$html = str_replace("[[" . trim($variable) . "]]", $value, $html);
				}
				else if (strcasecmp($Bean->getVartype(), "string") == 0) {
					$html = str_replace($variable, $value, $html);
				}
				else if (strcasecmp($Bean->getVartype(), "regex") == 0) {
					$html = preg_replace("$variable", "$value", $html);
				}
			}
		}
    }
    catch (Exception $e) {
        /*Exit Gracefully*/
    }
    return $html;
}