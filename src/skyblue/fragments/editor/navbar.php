<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2024-01-01 00:00:00 $
 * @package      SkyBlue CMS
 * @copyright    Copyright (C) 2005 - 2024 Scott Edwin Lewis. All rights reserved.
 * @license      GNU/GPL, see COPYING.txt
 *
 * AdminKit Top Navbar Fragment
 */

global $Authenticate;

$username  = Filter::getAlphaNumeric($params, 'username', '');
$firstname = Filter::get($params, 'firstname', '');
$lastlogin = Filter::get($params, 'lastlogin', '');

$displayName = !empty($firstname) ? $firstname : $username;

?>
<?php if (is_logged_in()) : ?>
<nav class="navbar navbar-expand navbar-light navbar-bg">
    <a class="sidebar-toggle js-sidebar-toggle">
        <i class="hamburger align-self-center"></i>
    </a>

    <div class="navbar-collapse collapse">
        <ul class="navbar-nav navbar-align">
            <!-- Quick Actions -->
            <li class="nav-item">
                <a class="nav-link" href="admin.php?com=page&action=add" title="<?php __('TOOLBAR.NEW_PAGE', 'Create New Page'); ?>">
                    <i class="align-middle" data-feather="plus-circle"></i>
                </a>
            </li>

            <!-- View Site -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo Config::get('site_url', '/'); ?>" target="_blank" title="<?php __('TOOLBAR.VIEW_SITE', 'View Site'); ?>">
                    <i class="align-middle" data-feather="external-link"></i>
                </a>
            </li>

            <!-- User Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                    <span class="text-dark"><?php echo htmlspecialchars($displayName); ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="admin.php?com=users&action=edit&id=<?php echo Filter::get($params, 'userid', ''); ?>">
                        <i class="align-middle me-1" data-feather="user"></i>
                        <?php __('NAVBAR.PROFILE', 'My Profile'); ?>
                    </a>
                    <a class="dropdown-item" href="admin.php?com=settings">
                        <i class="align-middle me-1" data-feather="settings"></i>
                        <?php __('COM.SETTINGS', 'Settings'); ?>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="admin.php?com=help">
                        <i class="align-middle me-1" data-feather="help-circle"></i>
                        <?php __('COM.HELP', 'Help'); ?>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="admin.php?com=login&action=logout">
                        <i class="align-middle me-1" data-feather="log-out"></i>
                        <?php __('GLOBAL.LOGOUT', 'Sign Out'); ?>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>
<?php endif; ?>
