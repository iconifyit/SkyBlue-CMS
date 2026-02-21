<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2024-01-01 00:00:00 $
 * @package      SkyBlue CMS
 * @copyright    Copyright (C) 2005 - 2024 Scott Edwin Lewis. All rights reserved.
 * @license      GNU/GPL, see COPYING.txt
 *
 * AdminKit Sidebar Navigation Fragment
 */

global $Authenticate;

$username = Filter::getAlphaNumeric($params, 'username', '');
$currentCom = Filter::noInjection($_GET, 'com', 'console');

/**
 * Helper function to determine if a nav item is active
 *
 * @param string $com The component name to check
 * @param string $currentCom The current component
 * @return string Returns 'active' if current, empty string otherwise
 */
function isActiveNav($com, $currentCom) {
    return ($com === $currentCom) ? 'active' : '';
}

?>
<?php if (is_logged_in()) : ?>
<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="admin.php?com=console">
            <span class="align-middle"><?php __('SBC.ADMIN_SHORT', 'SkyBlue'); ?></span>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header">
                <?php __('SIDEBAR.CONTENT', 'Content'); ?>
            </li>

            <li class="sidebar-item <?php echo isActiveNav('console', $currentCom); ?>">
                <a class="sidebar-link" href="admin.php?com=console">
                    <i class="align-middle" data-feather="sliders"></i>
                    <span class="align-middle"><?php __('GLOBAL.DASHBOARD', 'Dashboard'); ?></span>
                </a>
            </li>

            <li class="sidebar-item <?php echo isActiveNav('page', $currentCom); ?>">
                <a class="sidebar-link" href="admin.php?com=page">
                    <i class="align-middle" data-feather="file-text"></i>
                    <span class="align-middle"><?php __('COM.PAGES', 'Pages'); ?></span>
                </a>
            </li>

            <li class="sidebar-item <?php echo isActiveNav('media', $currentCom); ?>">
                <a class="sidebar-link" href="admin.php?com=media">
                    <i class="align-middle" data-feather="image"></i>
                    <span class="align-middle"><?php __('COM.MEDIA', 'Media'); ?></span>
                </a>
            </li>

            <li class="sidebar-item <?php echo isActiveNav('collections', $currentCom); ?>">
                <a class="sidebar-link" href="admin.php?com=collections">
                    <i class="align-middle" data-feather="folder"></i>
                    <span class="align-middle"><?php __('COM.COLLECTIONS', 'Collections'); ?></span>
                </a>
            </li>

            <li class="sidebar-header">
                <?php __('SIDEBAR.APPEARANCE', 'Appearance'); ?>
            </li>

            <li class="sidebar-item <?php echo isActiveNav('skin', $currentCom); ?>">
                <a class="sidebar-link" href="admin.php?com=skin">
                    <i class="align-middle" data-feather="layout"></i>
                    <span class="align-middle"><?php __('COM.SKINS', 'Templates'); ?></span>
                </a>
            </li>

            <li class="sidebar-header">
                <?php __('SIDEBAR.SYSTEM', 'System'); ?>
            </li>

            <li class="sidebar-item <?php echo isActiveNav('settings', $currentCom); ?>">
                <a class="sidebar-link" href="admin.php?com=settings">
                    <i class="align-middle" data-feather="settings"></i>
                    <span class="align-middle"><?php __('COM.SETTINGS', 'Settings'); ?></span>
                </a>
            </li>

            <li class="sidebar-item <?php echo isActiveNav('users', $currentCom); ?>">
                <a class="sidebar-link" href="admin.php?com=users">
                    <i class="align-middle" data-feather="users"></i>
                    <span class="align-middle"><?php __('COM.USERS', 'Users'); ?></span>
                </a>
            </li>

            <li class="sidebar-item <?php echo isActiveNav('extensions', $currentCom); ?>">
                <a class="sidebar-link" href="admin.php?com=extensions">
                    <i class="align-middle" data-feather="package"></i>
                    <span class="align-middle"><?php __('COM.EXTENSIONS', 'Extensions'); ?></span>
                </a>
            </li>

            <li class="sidebar-item <?php echo isActiveNav('checkouts', $currentCom); ?>">
                <a class="sidebar-link" href="admin.php?com=checkouts">
                    <i class="align-middle" data-feather="lock"></i>
                    <span class="align-middle"><?php __('COM.CHECKOUTS', 'Checkouts'); ?></span>
                </a>
            </li>

            <li class="sidebar-header">
                <?php __('SIDEBAR.SUPPORT', 'Support'); ?>
            </li>

            <li class="sidebar-item <?php echo isActiveNav('help', $currentCom); ?>">
                <a class="sidebar-link" href="admin.php?com=help">
                    <i class="align-middle" data-feather="help-circle"></i>
                    <span class="align-middle"><?php __('COM.HELP', 'Help'); ?></span>
                </a>
            </li>
        </ul>
    </div>
</nav>
<?php endif; ?>
