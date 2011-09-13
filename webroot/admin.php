<?php 

if (function_exists('ob_start')) @ob_start("ob_gzhandler");

/**
 * @version        2.0 2009-12-26 12:48:00 $
 * @package        SkyBlueCanvas
 * @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license        http://opensource.org/licenses/gpl-license.php GNU Public License
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

/**
 * A security check to make sure any core files are 
 * being included locally.
 */

define('SKYBLUE', 1);

/**
 * Indicates to the system that the request came through admin.php
 */

define('_ADMIN_', 1);

/**
 * Defines the path to the root of the site
 */

define('_SBC_ROOT_', './');

$here = dirname(__FILE__);
$here_arr = explode("/", dirname(__FILE__));
$here = implode("/", array_slice($here_arr, 0, -1));
if ($here{strlen($here)-1} != "/") $here .= "/";

define('_SBC_', $here);

define('_SBC_SYS_',  _SBC_ . 'skyblue/');
define('_SBC_APP_',  _SBC_ . 'custom/');
define('_SBC_WWW_',  _SBC_ . 'webroot/');

/**
 * The base page through which the request came.
 */

define('BASE_PAGE', 'admin.php');

/**
 * Include the SkyBlueCanvas system files
 */

require_once(_SBC_SYS_ . 'base.php');

/**
 * Start the Timer to measure performance
 */

Timer::start('admin.load');

/**
 * Scrub the _GET Super-global array to prevent injection/XSS attacks
 *
 * NOTE:
 *
 *    We do not scrub the _POST or _REQUEST arrays in the Admin section 
 *    because posting script, applet, object, SQL or other types of code
 *    may be required in the normal operation/management of the system. 
 *    The basic assumption is that if you have been authenticated as a 
 *    site admin, you are authorized to submit code. If an un-authorized 
 *    party has gained access to the admin secion, your system has 
 *    already been compromised.
 */

$_GET = Filter::scrub($_GET);
/*
$_POST    = Filter::scrub($_POST);
$_REQUEST = Filter::scrub($_REQUEST);
*/

/**
 * Declare our variables
 */

$Core            = null;
$Router          = null;
$Cache           = null;
$Request         = null;
$Authenticate    = null;
$Authorize       = null;
$ErrorPage       = null;
$Page            = null;
$LanguageHelper  = null;
$comLanguageFile = null;
$component       = null;

/**
 * Create the Core object
 */

$Core = new Core(array(
    'path'     => _SBC_SYS_,
    'events' => array(
        'onAuthenticate',
        'onLoginSuccess',
        'onLoginFailed',
        'beforeCheckInstall',
        'beforeAuthenticate',
        'beforeLoadConfig',
        'beforeLoadLanguage',
        'beforeLoadAdminModules',
        'beforeLoadManager',
        'afterLoadManager'
    )
));

/**
 * Load the site configuration
 */

Event::trigger('admin.beforeLoadConfig');

Config::load();

Event::trigger('admin.beforeCheckInstall');

/**
 * Start a new Session
 */

$Session = Singleton::getInstance('Session');

/**
 * Route the request
 */

$Router = new Router;

/**
 * Don't call the route method in admin
 * $Router->route();
 */

/**
 * Create a new RequestObject
 */

$Request = Singleton::getInstance('RequestObject');

Event::trigger('admin.beforeAuthenticate');

/**
 * Authenticate the current User
 */

$Authenticate = Singleton::getInstance('Authenticate', array(
    'enabled' => true,
    'refresh' => true
));

/**
 * Authorize the current User
 */

$Authorize = Singleton::getInstance('Authorize', array(
    'enabled' => Filter::noInjection($_GET, VAR_COM) == 'login' ? false : true, 
    'User'    => $Session->getUser(),
    'aco'     => $Request->get(VAR_COM)
));

/**
 * Load User-installed plugins
 */

# $Core->LoadUserPlugins();


/**
 * Create a new RequestObject
 */

$Request = Singleton::getInstance('RequestObject');

/**
 * Get the name of the current component
 */

$component = Filter::getAlphaNumeric($Request, VAR_COM, 'console');

/**
 * Load the language files
 */

Event::trigger('admin.beforeLoadLanguage');

$LanguageHelper = Singleton::getInstance('LanguageHelper');

/**
 * Trigger BeforeInitPage event callbacks
 */

Event::trigger('admin.beforeInitApplication');

/**
 * Define the default page
 */

$Core->DefineDefaultPage();

/**
 * Create the App object 
 */
    
$App = new Application(
    $Request,
    array(
        'root'      => _SBC_ROOT_,
        'default'   => 'console',
        'redirect'  => "admin.php?com=console",
        'component' => $component
    )
);

/**
 * Show the out-put
 */

$App->display();

/**
 * Execute any callbacks for afterShowPage
 */

Event::trigger('admin.afterShowPage');

/**
 * Just doing some performance measuring
 */

# echo "\n<!-- Generated in " . Timer::elapsed('admin.load') . " seconds -->" ;

if (function_exists('ob_flush')) @ob_flush();