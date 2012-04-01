<?php defined('SKYBLUE') or die('Bad File Request');

/**
 * @version        v 1.2 2009-05-18 08:58:00 $
 * @package        SkyBlueCanvas
 * @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license        GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

define('DS', DIRECTORY_SEPARATOR);

defined('_SBC_ROOT_') or die('_SBC_ROOT_ not defined');
defined('BASE_PAGE') or die('BASE_PAGE not defined');

if (function_exists('ini_set') && is_callable('ini_set')) {
    ini_set('display_errors', 'On');
    error_reporting(E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR);
    ini_set(
        'include_path', 
        ini_get('include_path') . ':' . dirname(_SBC_ROOT_) . ':'
    );
    session_set_cookie_params(86400, '/');
    session_start();
}

/**
 * I hate to have to do this, but PHP 5.1 + requires that the default timezone be explicitly set.
 */
if (function_exists('date_default_timezone_set') && function_exists('date_default_timezone_get')) {
    @date_default_timezone_set(@date_default_timezone_get());
}

defined('_INC_') or define('_INC_', _SBC_SYS_ . 'includes/');

/**
 * This file will allow you to include all the required SBC files. 
 * Before including this file, you must define _SBC_ROOT_ to the 
 * relative path to the SBC root directory from your file's location.
 * For instance, if your file is in /skyblue/mydir/myfile.php,
 * you will define _SBC_ROOT_ to '../'
 */

require_once(_INC_ . 'mvc/Loader.php');
require_once(_INC_ . 'SkyBlueObject.php');
require_once(_INC_ . 'Publisher.php');

require_once(_INC_ . 'Singleton.php');
require_once(_INC_ . 'utils/Utils.php');
require_once(_INC_ . 'utils/ArrayUtils.php');
require_once(_INC_ . 'utils/HtmlUtils.php');
require_once(_INC_ . 'utils/ImageUtils.php');
require_once(_INC_ . 'utils/Validator.php');
require_once(_INC_ . 'TransferObject.php');
require_once(_INC_ . 'LanguageHelper.php');
require_once(_INC_ . 'xml.parser.php');
require_once(_INC_ . 'Config.php');
require_once(_INC_ . 'Router.php');
require_once(_INC_ . 'SkyBluePlugin.php');
require_once(_INC_ . 'auth/Authenticate.php'); 
require_once(_INC_ . 'auth/Authorize.php');
require_once(_INC_ . 'auth/ACO.php');
require_once(_INC_ . 'auth/ACL.php');
require_once(_INC_ . 'Error.php');
require_once(_INC_ . 'conf.functions.php');
require_once(_INC_ . 'Core.php');
require_once(_INC_ . 'FrontController.php');
require_once(_INC_ . 'Application.php');
require_once(_INC_ . 'FileSystem.php');
require_once(_INC_ . 'Cache.php');
require_once(_INC_ . 'InputFilter.php');
require_once(_INC_ . 'Filter.php');
require_once(_INC_ . 'Request.php');
require_once(_INC_ . 'Uploader.php');
require_once(_INC_ . 'Downloader.php');
require_once(_INC_ . 'hooks.php');
require_once(_INC_ . 'Session.php');
require_once(_INC_ . 'PluginParser.php');
require_once(_INC_ . 'Fragment.php');
require_once(_INC_ . 'Fragmentor.php');
require_once(_INC_ . 'Timer.php');
require_once(_INC_ . 'Event.php');
require_once(_INC_ . 'Message.php');
require_once(_INC_ . 'JSON.php');
require_once(_INC_ . 'MailMessage.php');
require_once(_INC_ . 'Archive_Zip.php');
require_once(_INC_ . 'class.JavaScriptPacker.php');

/**
 * Load the MVC classes
 */
Loader::load('config.configuration', true, _SBC_APP_);
Loader::load('includes.mvc.MVC', true, _SBC_SYS_);