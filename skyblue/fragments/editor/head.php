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
<script type="text/javascript" src="languages/<?php echo Config::get('site_lang', 'en'); ?>/terms.js"></script>

<link type="text/css" href="<?php echo SB_UI_DIR; ?>js/<?php echo JQUERY_UI_VERSION; ?>/css/<?php echo $ui_theme; ?>/<?php echo JQUERY_UI_VERSION; ?>.custom.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo SB_UI_DIR; ?>js/<?php echo JQUERY_UI_VERSION; ?>/js/<?php echo JQUERY_VERSION; ?>.min.js"></script>
<script type="text/javascript" src="<?php echo SB_UI_DIR; ?>js/<?php echo JQUERY_UI_VERSION; ?>/js/<?php echo JQUERY_UI_VERSION; ?>.custom.min.js"></script>
<script type="text/javascript" src="<?php echo SB_UI_DIR; ?>js/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo SB_UI_DIR; ?>js/jquery.dropshadow.js"></script>
<script type="text/javascript" src="<?php echo SB_UI_DIR; ?>js/jquery.reflect.js"></script>

<script type="text/javascript" src="<?php echo SB_UI_DIR; ?>js/actions.js"></script>
        
<link rel="stylesheet" type="text/css" href="<?php echo SB_UI_DIR; ?>admin/css/editor.css" />

<?php if (get_context() == "admin") : ?>
<link rel="stylesheet" href="resources/ui/admin/css/main.css" type="text/css" media="screen" />
<link rel="stylesheet" href="resources/ui/admin/icons/<?php echo UI_ICON_SET; ?>/dashboard.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo SB_UI_DIR; ?>admin/css/toolbar.css" />
<?php endif; ?>

<script type="text/javascript" src="<?php echo SB_UI_DIR; ?>js/jquery.simplemodal-1.3.min.js"></script>
<script type="text/javascript" src="<?php echo SB_UI_DIR; ?>js/jquery.hotkeys-0.7.8-packed"></script>
<script type="text/javascript" src="<?php echo SB_UI_DIR; ?>js/jquery.plugin.js"></script>
<script type="text/javascript" src="<?php echo SB_UI_DIR; ?>js/request.js"></script>
<script type="text/javascript" src="<?php echo SB_UI_DIR; ?>js/skybluecanvas.js"></script>
<script type="text/javascript" src="<?php echo SB_UI_DIR; ?>js/skybluecanvas-ui.js"></script>
<script type="text/javascript">
    sbc.init({
        context:   "<?php echo get_context(); ?>",
        logged_in: <?php echo is_logged_in() ? "true" : "false" ; ?>,
        user: {
            name:     "<?php echo $User->getName(); ?>",
            username: "<?php echo $User->getUsername(); ?>",
            isadmin:  <?php echo (is_admin() ? "true" : "false") ; ?>    
        },
        root_path: "<?php echo Config::get('site_url'); ?>",
        ui_path:   "<?php echo Config::get('site_url'); ?><?php echo SB_ADMIN_UI_DIR; ?>"
    });
</script>
<script type="text/javascript" src="<?php echo SB_UI_DIR; ?>js/editor.js"></script>