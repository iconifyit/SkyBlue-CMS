<?php defined('SKYBLUE') or die('Bad file request');

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

add_head_element('jquery.utils');
add_head_element('page.onload', TABS_JS);
add_head_element('page.style',  TABS_CSS);

add_onload_scriptlet('focus_login', '$("#username").focus();');

$theAction = $this->getVar('action', __('GLOBAL.LOGIN', 'Sign In', 1));

$Item = null;
$username = $this->getVar("username");

?>
<div class="jquery_tab">
    <div class="content">
        <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>
        <?php HtmlUtils::mgrFormOpen($this->getVar('com')); ?>

        <div id="tabs">
            <ul>
                <li><a href="#">Login</a></li>
            </ul>
            
            <!-- First Tab -->
            <div class="tab-body" id="tab-one">
                <fieldset class="three-column last">
                    <div class="column">
                        <label for="username"><?php __('GLOBAL.USERNAME', 'Username'); ?>:</label>
                        <input class="input-medium" type="text" name="username" value="<?php echo $username; ?>" id="username" />
                    </div>
                    <div class="clearfix"></div>
                </fieldset>
                <fieldset class="three-column last">
                    <div class="column">
                        <label for="password"><?php __('GLOBAL.PASSWORD', 'Password'); ?>:</label>
                        <input class="input-medium" type="password" name="password" value="" id="password" />
                    </div>
                    <div class="clearfix"></div>
                </fieldset>
                <fieldset class="three-column last">
                    <div class="column">
                        <p class="buttons-bottom">
                            <?php HtmlUtils::mgrButton('BTN.LOGIN', 'login'); ?>
                        </p>
                    </div>
                    <div class="clearfix"></div>
                </fieldset>
                <fieldset class="three-column last">
                    <div><a href="admin.php?com=login&action=lost_password"><?php __("LOGIN.LOST_PASSWORD", "Lost Password"); ?>?</a></div>
                </fieldset>
            </div>
        </div>
        <?php HtmlUtils::mgrFormClose(); ?>
    </div>
</div>