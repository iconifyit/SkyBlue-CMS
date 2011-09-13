<?php 

if (function_exists('ob_start')) @ob_start("ob_gzhandler");

/**
 * @version        2.0 2009-12-26 12:48:00 $
 * @package        SkyBlueCanvas
 * @copyright      Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
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

define('BASE_PAGE', 'index.php');

/**
 * Include the SkyBlueCanvas system files
 */

require_once(_SBC_SYS_ . 'base.php');

/**
 * Start the timer
 */

Timer::start('frontend.load');

/**
 * Scrub the Super-global arrays to prevent injection/XSS attacks
 */

$_GET     = Filter::scrub($_GET);
$_POST    = Filter::scrub($_POST);
$_REQUEST = Filter::scrub($_REQUEST);

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
    'events'   => array(
        'OnBeforeInitPage',
        'OnBeforeShowPage',
        'OnAfterShowPage',
        'OnRenderPage',
        'OnAfterLoadStory',
        'OnBeforeUnload'
   )
));

/**
 * Load the site configuration
 */

Config::load();

/**
 * Start a new Session
 */

$Session = Singleton::getInstance('Session');

/**
 * Route the request
 */

$Router = new Router("");
$Router->route();

/**
 * Create a new RequestObject
 */

$Request = Singleton::getInstance('RequestObject');

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
    'aco'     => 'page'
));

/**
 * Load User-installed plugins
 */

$Core->LoadUserPlugins();

/**
 * Get the name of the current component
 */

$component = Filter::getAlphaNumeric($Request, VAR_COM, 'page');

/**
 * Load the language files
 */

Event::trigger('beforeLoadLanguage');

$LanguageHelper = Singleton::getInstance('LanguageHelper');

/**
 * Trigger BeforeInitPage event callbacks
 */

Event::trigger('beforeInitPage');

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
        'default'   => 'page',
        'redirect'  => "/",
        'component' => 'page'
    )
);

/*
 * Show the page
 */

$App->display();

/**
 * Execute any callbacks for OnAfterShowPage
 */

Event::trigger('afterShowPage');

/**
 * Just doing some performance measuring
 */

echo "\n<!-- <a href='http://skybluecanvas.com'>SkyBlueCanvas</a> (" . Timer::elapsed('frontend.load') . " seconds) -->" ;

if (function_exists('ob_flush')) @ob_flush();