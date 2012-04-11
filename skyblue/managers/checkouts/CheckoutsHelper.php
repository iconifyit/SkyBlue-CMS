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

class CheckoutsHelper {

    function initialize() {
        if (file_exists(_SBC_APP_ . "daos/CheckoutsDAO.php")) {
            require_once(_SBC_APP_ . "daos/CheckoutsDAO.php");
        }
        else {
            require_once(SB_MANAGERS_DIR . "checkouts/CheckoutsDAO.php");
        }
        require_once(SB_MANAGERS_DIR . "checkouts/Checkout.php");
        require_once(SB_MANAGERS_DIR . "checkouts/CheckoutsController.php");
    }

    function getDao($refresh=false) {
        if (! class_exists('CheckoutsDAO')) {
            CheckoutsHelper::initialize();
        }
        return new CheckoutsDAO();
    }
    
    function isMyCheckout($Object) {
        global $Authenticate;
        $isMine = true;
        if (is_callable(array($Object, 'getChecked_out_by'))) {
            $User = $Authenticate->user();
            $isMine = (strcasecmp($Object->getChecked_out_by(), $User->getUsername()) === 0);
        }
        return $isMine;
    }

    function getCheckoutId($Object) {
        $theId = "";
        if (is_object($Object) && !is_null($Object->type) && !is_null($Object->id)) {
            $theId = (strtolower($Object->getType() . "_" . $Object->getId()));
        }
        return $theId;
    }
    
    function checkOut($Object) {
        global $Authenticate;
        
        $Dao = CheckoutsHelper::getDao();

        $theCheckoutId = CheckoutsHelper::getCheckoutId($Object);

        if ($Checkout = $Dao->getItem($theCheckoutId)) {
            $theResult = CheckoutsHelper::isMyCheckout($Checkout);
        }
        else {
            $User = $Authenticate->user();
            $theResult = $Dao->insert(new Checkout(array(
                'item_id' => $Object->getId(),
                'item_type' => $Object->getObjtype(),
                'checked_out' => true,
                'checked_out_by' => $User->getUsername(),
                'checked_out_time' => time(), 
                'id' => $theCheckoutId,
                'name' => $Object->getName()
            )));
        }
        return $theResult; 
    }

    function checkIn($Object) {
        global $Authenticate;
        
        $Dao = CheckoutsHelper::getDao();
        $Checkout = $Dao->getItem(
            CheckoutsHelper::getCheckoutId($Object)
        );

        if (! is_object($Checkout)) return;
        if (CheckoutsHelper::isMyCheckout($Checkout)) {
            return $Dao->delete($Checkout->getId());
        }
    }

}