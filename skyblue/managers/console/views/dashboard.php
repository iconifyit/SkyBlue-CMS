<?php defined('SKYBLUE') or die('Bad file request');

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
 * @date   June 22, 2009
 */

add_head_element('jquery.utils');
add_head_element('jquery.simplemodal');

?>
<div class="console">
    <h2><?php __('GLOBAL.DASHBOARD', 'Dashboard'); ?></h2>
    <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>
    <ul class="dashboard-controls">
        <li class="pages">
            <span class="icon">
                <img src="<?php echo SB_UI_DIR; ?>admin/icons/<?php echo UI_ICON_SET; ?>/pages.png" class="reflect" />
            </span>
            <h2><a href="admin.php?com=page"><span class="heading"><?php __('COM.PAGES', 'Pages'); ?></span></a></h2>
            <p><?php __('CONSOLE.INFO.PAGES', 'Create &amp; Edit Your Pages'); ?></p>
        </li>
        <li class="media">
            <span class="icon">
                <img src="<?php echo SB_UI_DIR; ?>admin/icons/<?php echo UI_ICON_SET; ?>/media.png" class="reflect" />
            </span>
            <h2><a href="admin.php?com=media"><span class="heading"><?php __('COM.MEDIA', 'Media'); ?></span></a></h2>
            <p><?php __('CONSOLE.INFO.MEDIA', 'Manage your media files'); ?></p>
        <li class="collections">
            <span class="icon">
                <img src="<?php echo SB_UI_DIR; ?>admin/icons/<?php echo UI_ICON_SET; ?>/collections.png" class="reflect" />
            </span>
            <h2><a href="admin.php?com=collections"><span class="heading"><?php __('COM.COLLECTIONS', 'Collections'); ?></span></a></h2>
            <p><?php __('CONSOLE.INFO.COLLECTIONS', 'Add and edit collections')?></p>
        </li>
        <li class="skins">
            <span class="icon">
                <img src="<?php echo SB_UI_DIR; ?>admin/icons/<?php echo UI_ICON_SET; ?>/templates.png" class="reflect" />
            </span>
            <h2><a href="admin.php?com=skin"><span class="heading"><?php __('COM.SKINS', 'Template Manager'); ?></span></a></h2>
            <p><?php __('CONSOLE.INFO.SKINS', 'Manage your page templates'); ?></p>
        </li>
        <li class="settings">
            <span class="icon">
                <img src="<?php echo SB_UI_DIR; ?>admin/icons/<?php echo UI_ICON_SET; ?>/settings.png" class="reflect" />
            </span>
            <h2><a href="admin.php?com=settings"><span class="heading"><?php __('COM.SETTINGS', 'Settings'); ?></span></a></h2>
            <p><?php __('CONSOLE.INFO.SETTINGS', 'Edit site settings and Options'); ?></p>
        </li>
        <li class="users">
            <span class="icon">
                <img src="<?php echo SB_UI_DIR; ?>admin/icons/<?php echo UI_ICON_SET; ?>/users.png" class="reflect" />
            </span>
            <h2><a href="admin.php?com=users"><span class="heading"><?php __('COM.USERS', 'User Manager'); ?></span></a></h2>
            <p><?php __('CONSOLE.INFO.USERS', 'Add and edit user settings'); ?></p>
        </li>
        <li class="checkouts">
            <span class="icon">
                <img src="<?php echo SB_UI_DIR; ?>admin/icons/<?php echo UI_ICON_SET; ?>/checkouts.png" class="reflect" />
            </span>
            <h2><a href="admin.php?com=checkouts"><span class="heading"><?php __('COM.CHECKOUTS', 'Checked Out Content'); ?></span></a></h2>
            <p><?php __('COLLECTIONS.INFO.CHECKOUT', 'Manage Checked Out Content'); ?></p>
        </li>
        <li class="help">
            <span class="icon">
                <img src="<?php echo SB_UI_DIR; ?>admin/icons/<?php echo UI_ICON_SET; ?>/help.png" class="reflect" />
            </span>
            <h2><a href="admin.php?com=help"><span class="heading"><?php __('COM.HELP', 'Help'); ?></span></a></h2>
            <p><?php __('CONSOLE.INFO.HELP', 'SkyBlueCanvas Documentation &amp; Tutorials'); ?></p>
        </li>
    </ul>
    <div style="clear: both; height: 20px;"></div>
</div>