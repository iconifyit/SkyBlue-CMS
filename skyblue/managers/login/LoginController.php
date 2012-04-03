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

class LoginController extends Controller {

    var $model;
    var $action;
    var $viewName;
    var $view_path = 'managers/login/views/';
    var $defaultViewName = 'login_form';

    function __construct($Request) {
    
        parent::__construct($Request);
        $this->setHandler('index',           'doIndex',            true);
        $this->setHandler('lost_password',   'doLostPassword',     true);
        $this->setHandler('reset_password',  'doResetPassword',    true);
        $this->setHandler('update_password', 'doUpdatePassword',  true);
        $this->setHandler('retrieve',        'doRetrievePassword', true);
        $this->setHandler('login',           'doLogin',            true);
        $this->setHandler('logout',          'doLogout',           true);
        $this->setHandler('cancel',          'doCancel',           true);
        $this->setViewPath(_SBC_SYS_ . 'managers/login/views/');
    }

    function doLogin($Request) {
        
        $username = $Request->get('username');
        $password = $Request->get('password');
        
        if (trim($username) == "") {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('LOGIN.USERNAME_REQUIRED', 'You did not enter your username', 1)
            );
            Utils::redirect("admin.php?com=login");
        }
        else if (trim($password) == "") {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('LOGIN.PASSWORD_REQUIRED', 'You did not enter your password', 1)
            );
            Utils::redirect("admin.php?com=login");
        }
        
        $password = LoginHelper::fingerprint($password);
        
        $User = LoginHelper::getUser($username);
        
        if (empty($User) || trim($User->getUsername()) == "") {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('GLOBAL.LOGIN_FAILED', 'Login Failed', 1)
            );
            Utils::redirect("admin.php?com=login");
        }
        else if (! LoginHelper::verifyPassword($User->getPassword(), $password)) {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('GLOBAL.LOGIN_FAILED', 'Login Failed', 1)
            );
            Utils::redirect("admin.php?com=login");
        }
        else {
            $User->setLastLogin(time());
            $Session = Singleton::getInstance('Session');
            $Session->regenerate();
            $Session->set('User', $User);
            $Session->set('user.username', $User->getUsername());
            $Session->set('TIMEOUT', time() + SB_ADMIN_TIMEOUT);
            LoginHelper::updateLastLogin($User);
            Utils::redirect("admin.php?com=console");        
        }
    }

    function doIndex($Request) {
        $this->setViewName('login_form.php');
    }
    
    function doCancel($Request) {
        parent::doCancel("index.php");
    }
    
    function doLostPassword($Request) {
        $this->setViewName("lost_password.php");
        $this->view->assign("username", $Request->get('username'));
        $this->view->assign("email", $Request->get('email'));
    }
    
    function doRetrievePassword($Request) {
        $username = $Request->get('username');
        $email    = $Request->get('email');
        $User = LoginHelper::getUser($username);
        
        $params = "";
        if (trim($username) != "") {
            $params .= "&username={$username}";
        }
        if (trim($email) != "") {
            $params .= "&email={$email}";
        }

        if (empty($User) || strcasecmp($User->getEmail(), $email) !== 0) {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('LOGIN.USER_NOT_FOUND', 'User not found', 1)
            );
            Utils::redirect("admin.php?com=login&action=lost_password" . $params);
        }
        else if (trim($User->getTempkey()) != "" && $User->getTempkeyexpiration() > time()) {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('LOGIN.RESET_ACTIVE_REQUEST', 'There is already an active request', 1)
            );
            Utils::redirect("admin.php?com=login&action=lost_password" . $params);
        }
        else {
            
            $username = Utils::fingerprint($User->getUsername(), "md5", true);
            $tempKey  = Utils::fingerprint($User->getPassword(), "md5", true);
            
            $tempKeyLink = Config::get('site_url') . "admin.php?com=login&action=reset_password&username={$username}&key={$tempKey}";
            
            $MailMessage = new MailMessage(array(
                'recepient' => $User->getEmail(),
                'replyto'   => Config::get('contact_email'),
                'from'      => Config::get('contact_email'),
                'message'   => __('LOGIN.PASSWORD_RESET_REQUEST', 'Click the link below to reset your password. ', 1) . $tempKeyLink,
                'subject'   => __('LOGIN.PASSWORD_RESET_SUBJECT', 'Lost Password', 1)
            ));
            if ($MailMessage->send()) {
                $User->setTempKey($tempKey);
                $User->setTempKeyExpiration(time() + 360);
                $Dao = LoginHelper::getUsersDao();
                $Dao->update($User);
                $this->_setMessage(
                    'success',
                    __('GLOBAL.SUCCESS', 'Success', 1),
                    __('LOGIN.RESET_MESSAGE_SENT', 'Instructions have been sent to your email address', 1)
                );
            }
            else {
                $this->_setMessage(
                    'error',
                    __('GLOBAL.ERROR', 'Error', 1),
                    __('LOGIN.RESET_MESSAGE_NOT_SENT', 'The password reset instructions could not be sent', 1)
                );
            }
            Utils::redirect("admin.php?com=login");
        }
    }
    
    function doResetPassword($Request) {
        
        $username = Filter::getAlphaNumeric($Request, 'username', '');
        $tempkey  = Filter::getAlphaNumeric($Request, 'key', '');
        
        $User = LoginHelper::getUserByKey('tempkey', $tempkey);

        if (null == $User) {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('LOGIN.RESET_BAD_REQUEST', 'Illegal request.', 1)
            );
            Utils::redirect("admin.php?com=login");
        }
        else if (trim($username) == "" || 
            Utils::fingerprint($User->getUsername(), "md5", true) != $username) {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('LOGIN.RESET_BAD_USERNAME', 'Invalid username provided.', 1)
            );
            Utils::redirect("admin.php?com=login");
        }
        else if (trim($tempkey) == "" || $User->getTempkey() != $tempkey) {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('LOGIN.RESET_BAD_KEY', 'Invalid key provided.', 1)
            );
            Utils::redirect("admin.php?com=login");
        }
        else if ($tempKeyExpires > time()) {
            $this->_setMessage(
                'error',
                __('GLOBAL.ERROR', 'Error', 1),
                __('LOGIN.RESET_TMP_KEY_EXPIRED', 'Temporary key has expired.', 1)
            );
            Utils::redirect("admin.php?com=login");
        }
        else {
            $this->view->assign('username', $username);
            $this->view->assign('tempkey', $tempkey);
            $this->setViewName("reset_password.php");
        }
    }
    
    function doUpdatePassword($Request) {
        
        $fingerprint = Filter::get($Request, 'username');
        $tempkey = Filter::get($Request, 'tempkey');
        
        $this->setViewName("reset_password.php");
        $this->view->assign('username', $fingerprint);
        $this->view->assign('tempkey',  $tempkey);
                
        if (count($_POST)) {
            $User = LoginHelper::getUserByFingerprint($fingerprint);
            $password = Filter::get($Request, 'password');
            $confirm_password = Filter::get($Request, 'confirm_password');
            
            if (trim($password) == "") {
                $this->_setMessage(
                    'error',
                    __('GLOBAL.ERROR', 'Error', 1),
                    __('LOGIN.PLEASE_ENTER_PASSWORD', 'Please enter a password', 1)
                );
                Utils::redirect("admin.php?com=login&action=reset_password&key={$tempkey}&username={$fingerprint}");
            }
            else if (trim($password) !== trim($confirm_password)) {
                $this->_setMessage(
                    'error',
                    __('GLOBAL.ERROR', 'Error', 1),
                    __('LOGIN.PASSWORD_NOT_CONFIRMED', 'Your password confirmation does not match', 1)
                );
                Utils::redirect("admin.php?com=login&action=reset_password&key={$tempkey}&username={$fingerprint}");
            }
            else if (null == $User) {
                $this->_setMessage(
                    'error',
                    __('GLOBAL.ERROR', 'Error', 1),
                    __('LOGIN.USER_NOT_FOUND', 'User was not found', 1)
                );
                Utils::redirect("admin.php?com=login&action=reset_password&key={$tempkey}&username={$fingerprint}");
            }
            else {
                $User->setPassword(Utils::fingerprint($password));
                $User->setTempkey("");
                $User->setTempkeyexpiration("");
                $Dao = LoginHelper::getUsersDao(true);
                if (! $Dao->update($User)) {
                    $this->_setMessage(
                        'error',
                        __('GLOBAL.ERROR', 'Error', 1),
                        __('LOGIN.PASSWORD_NOT_RESET', 'Your password could not be reset', 1)
                    );
                    Utils::redirect("admin.php?com=login&action=update_password&key={$tempKey}&username={$fingerprint}");
                }
                else {
                    $this->_setMessage(
                        'success',
                        __('GLOBAL.SUCCESS', 'Success', 1),
                        __('LOGIN.PASSWORD_RESET', 'Your password has been reset', 1)
                    );
                    Utils::redirect("admin.php?com=login");
                }
            }
        }
    }
    
    function doLogout($Request=null) {
        $Session = Singleton::getInstance('Session');
        $Session->destroy();
        $this->_setMessage(
            'success',
            __('GLOBAL.SUCCESS', 'Success', 1),
            __('LOGIN.LOGOUT_SUCCESS', 'You Have Successfully Logged Out', 1)
        );
        Utils::redirect("admin.php?com=login");
    } 
}