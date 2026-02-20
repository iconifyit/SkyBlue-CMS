<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

define('SKYBLUE', 1);
define('_SBC_ROOT_', './');

$here = dirname(__FILE__);
$here_arr = explode("/", dirname(__FILE__));
$here = implode("/", array_slice($here_arr, 0, -1));
if ($here[strlen($here)-1] != "/") $here .= "/";

define('_SBC_', $here);
define('_SBC_SYS_',  _SBC_ . 'skyblue/');
define('_SBC_APP_',  _SBC_ . 'custom/');
define('_SBC_WWW_',  _SBC_ . 'webroot/');
define('BASE_PAGE', 'errors.php');

echo "About to load base.php...\n";
require_once(_SBC_SYS_ . 'base.php');
echo "base.php loaded successfully!\n";
?>
