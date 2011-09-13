<?php

define('DS', DIRECTORY_SEPARATOR);
define('SKYBLUE', 1);
define('_ADMIN_', 1);
define('DEMO_MODE', 0);
define('BASE_PAGE', 'admin.php');

define('_SBC_ROOT_', '../../../../../');

$here = dirname(__FILE__);

$offset = count(explode('/', _SBC_ROOT_))-1;
$bits = explode("/", $here);

$path_to_root = implode("/", array_slice($bits, 0, count($bits)-$offset));

define('_SBC_',      $path_to_root . "/");
define('_SBC_SYS_',  _SBC_ . 'skyblue/');
define('_SBC_APP_',  _SBC_ . 'custom/');
define('_SBC_WWW_',  _SBC_ . 'webroot/');

require_once(_SBC_SYS_ . 'base.php');

$Core = new Core(array(
    'path'     => _SBC_ROOT_,
    'lifetime' => 3600,
    'events'   => array(
        'OnBeforeInitPage',
        'OnBeforeShowPage',
        'OnAfterShowPage',
        'OnRenderPage',
        'OnAfterLoadStory',
        'OnBeforeUnload'
   )
));

Config::load();

$Authenticate = new Authenticate(array(
    'enabled' => true,
    'refresh' => true
));