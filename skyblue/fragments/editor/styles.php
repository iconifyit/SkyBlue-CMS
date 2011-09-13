<?php defined('SKYBLUE') or die('Bad file request'); 

global $Authenticate;

/**
 * @version      2.0 2010-01-01 00:00:00 $
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

$User = $Authenticate->user();

$ui_theme = Config::get('ui_theme', JQUERY_UI_THEME);

?>
<link type="text/css" href="<?php echo SB_UI_DIR; ?>js/<?php echo JQUERY_UI_VERSION; ?>/css/<?php echo $ui_theme; ?>/<?php echo JQUERY_UI_VERSION; ?>.custom.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?php echo SB_UI_DIR; ?>admin/css/editor.css" />

<?php if (get_context() == "admin") : ?>
<link rel="stylesheet" href="resources/ui/admin/css/main.css" type="text/css" media="screen" />
<link rel="stylesheet" href="resources/ui/admin/icons/<?php echo UI_ICON_SET; ?>/dashboard.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo SB_UI_DIR; ?>admin/css/toolbar.css" />
<?php endif; ?>