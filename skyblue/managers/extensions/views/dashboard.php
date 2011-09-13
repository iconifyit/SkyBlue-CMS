<?php defined('SKYBLUE') or die(basename(__FILE__));

/**
 * @version      2.0 2008-12-12 19:47:43 $
 * @package      SkyBlueCanvas
 * @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license      GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

$Request = new RequestObject;
$com = 'extensions';

add_head_element('jquery.utils');
add_head_element('jquery.simplemodal');
add_head_element('jquery.ajaxuploader');
add_head_element('page.onload', TABS_JS);
add_head_element('page.style',  TABS_CSS);

add_stylesheet('extensions.css', SB_MANAGER_RESOURCES . 'extensions/css/extensions.css');
add_script('extensions.js',  SB_MANAGER_RESOURCES . 'extensions/js/extensions.js');

?>
<script type="text/javascript">
$(function() {
    /**
     * NOTE: This function over-rides the function with the same name 
     * in /ui/js/actions.js
     * Sets the action variable in Manager forms. The action variable 
     * is the event that is triggered in the $Manager->trigger() method 
     * in the Manger PHP class.
     * @param object  The button object of the clicked button
     * @param string  The action the button is to trigger
     * @return void
     */
    function set_action(button, action) {
        $("#install-form").find("input[@name='action']").val(action);
    };
});
</script>

<div class="jquery_tab">
    <div class="content">
        <h2>
            <a href="admin.php?com=console"><?php __('COM.CONSOLE', 'Dashboard'); ?></a> /
            <a href="admin.php?com=settings"><?php __('COM.SETTINGS', 'Settings'); ?></a> / 
            <?php __('COM.EXTENSIONS', 'Add-Ons'); ?>
        </h2>
        
        <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>
        
        <div id="tabs">
            <ul>
                <li><a href="admin.php?com=extensions&context=managers&action=list_managers&is_ajax=1#tab-manager" class="<?php echo $com == 'manager' ? 'active' : 'off'; ?>"><?php __('EXTENSIONS.MANAGERS', 'Managers'); ?></a></li>
                <li><a href="admin.php?com=extensions&context=fragments&action=list_fragments&is_ajax=1#tab-fragment" class="<?php echo $com == 'fragment' ? 'active' : 'off'; ?>"><?php __('EXTENSIONS.FRAGMENTS', 'Fragments'); ?></a></li>
                <li><a href="admin.php?com=extensions&context=skins&action=list_skins&is_ajax=1#tab-skin" class="<?php echo $com == 'skin' ? 'active' : 'off'; ?>"><?php __('EXTENSIONS.SKINS', 'Skins'); ?></a></li>
                <li><a href="admin.php?com=extensions&context=plugins&action=list_plugins&is_ajax=1#tab-plugin" class="<?php echo $com == 'plugin' ? 'active' : 'off'; ?>"><?php __('EXTENSIONS.PLUGINS', 'Plugins'); ?></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
    </div>
</div>