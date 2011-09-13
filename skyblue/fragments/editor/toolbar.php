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

$username = Filter::getAlphaNumeric($params, 'username', '');
$lastlogin = Filter::get($params, 'lastlogin', '');

?>
<?php if (is_logged_in()) : ?>
<div id="editorToolbar">
    <div id="editorToolbarControls">
        <?php if (Filter::noInjection($_GET, 'com') != 'console') : ?>
        <ul>
            <li><a href="admin.php?com=console" class="action tooltip console" title="<?php __('TOOLBAR.HOME', 'Admin Home'); ?>"></a></li>
            <li><a href="admin.php?com=page" class="action tooltip pages" title="<?php __('TOOLBAR.PAGES', 'Manage Pages'); ?>"></a></li>
            <li><a href="admin.php?com=skin" class="action tooltip layout" title="<?php __('TOOLBAR.SKINS', 'Manage Templates'); ?>"></a></li>
            <li><a href="admin.php?com=users" class="action tooltip users" title="<?php __('TOOLBAR.USERS', 'Manage Users'); ?>"></a></li>
            <li><a href="admin.php?com=collections" class="action tooltip collections" title="<?php __('TOOLBAR.COLLECTIONS', 'Manage Collections'); ?>"></a></li>
            <li><a href="admin.php?com=media" class="action tooltip media" title="<?php __('TOOLBAR.MEDIA', 'Manage Media'); ?>"></a></li>
            <li><a href="admin.php?com=settings" class="action tooltip settings" title="<?php __('TOOLBAR.SETTINGS', 'Manage Settings'); ?>"></a></li>
            <li class="last"><a href="admin.php?com=extensions" class="action tooltip extensions" title="<?php __('TOOLBAR.EXTENSIONS', 'Manage Add-Ons'); ?>"></a></li>
        </ul>
        <?php endif; ?>
        <ul>
            <li><a href="admin.php?com=page&action=add" class="action tooltip addpage" title="<?php __('TOOLBAR.NEW_PAGE', 'Create New Page'); ?>"></a></li>
            <li><a href="admin.php?com=help" class="action tooltip help" title="<?php __('TOOLBAR.HELP', 'SkyBlueCanvas Help'); ?>"></a></li>
        </ul>
    </div>
    <span class="user"><?php __('GLOBAL.WELCOME', 'Welcome'); ?> <?php echo $username; ?></span>&nbsp;|&nbsp;
    <span class="signout"><a href="admin.php?com=login&action=logout" class="logout"><?php __('GLOBAL.LOGOUT', 'Sign Out'); ?></a></span>
    &nbsp;&nbsp;
</div>
<?php endif; ?>