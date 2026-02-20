<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2010-01-01 00:00:00 $
 * @package      SkyBlue CMS
 * @copyright    Copyright (C) 2005 - 2024 Scott Edwin Lewis. All rights reserved.
 * @license      GNU/GPL, see COPYING.txt
 * SkyBlue CMS is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 *
 * Updated to use Bootstrap 5 cards with AdminKit theme
 */

/**
 * @author Scott Lewis
 * @date   June 22, 2009
 */

add_head_element('jquery.utils');
add_head_element('jquery.simplemodal');

/**
 * Dashboard items configuration
 * Each item has: com (component), icon (Feather icon name), title_key, title_default, desc_key, desc_default
 */
$dashboardItems = array(
    array(
        'com'          => 'page',
        'icon'         => 'file-text',
        'title_key'    => 'COM.PAGES',
        'title_default'=> 'Pages',
        'desc_key'     => 'CONSOLE.INFO.PAGES',
        'desc_default' => 'Create &amp; Edit Your Pages'
    ),
    array(
        'com'          => 'media',
        'icon'         => 'image',
        'title_key'    => 'COM.MEDIA',
        'title_default'=> 'Media',
        'desc_key'     => 'CONSOLE.INFO.MEDIA',
        'desc_default' => 'Manage your media files'
    ),
    array(
        'com'          => 'collections',
        'icon'         => 'folder',
        'title_key'    => 'COM.COLLECTIONS',
        'title_default'=> 'Collections',
        'desc_key'     => 'CONSOLE.INFO.COLLECTIONS',
        'desc_default' => 'Add and edit collections'
    ),
    array(
        'com'          => 'skin',
        'icon'         => 'layout',
        'title_key'    => 'COM.SKINS',
        'title_default'=> 'Templates',
        'desc_key'     => 'CONSOLE.INFO.SKINS',
        'desc_default' => 'Manage your page templates'
    ),
    array(
        'com'          => 'settings',
        'icon'         => 'settings',
        'title_key'    => 'COM.SETTINGS',
        'title_default'=> 'Settings',
        'desc_key'     => 'CONSOLE.INFO.SETTINGS',
        'desc_default' => 'Edit site settings and options'
    ),
    array(
        'com'          => 'users',
        'icon'         => 'users',
        'title_key'    => 'COM.USERS',
        'title_default'=> 'Users',
        'desc_key'     => 'CONSOLE.INFO.USERS',
        'desc_default' => 'Add and edit user settings'
    ),
    array(
        'com'          => 'checkouts',
        'icon'         => 'lock',
        'title_key'    => 'COM.CHECKOUTS',
        'title_default'=> 'Checkouts',
        'desc_key'     => 'COLLECTIONS.INFO.CHECKOUT',
        'desc_default' => 'Manage Checked Out Content'
    ),
    array(
        'com'          => 'help',
        'icon'         => 'help-circle',
        'title_key'    => 'COM.HELP',
        'title_default'=> 'Help',
        'desc_key'     => 'CONSOLE.INFO.HELP',
        'desc_default' => 'SkyBlue CMS Documentation &amp; Tutorials'
    )
);

?>
<h1 class="h3 mb-3"><?php __('GLOBAL.DASHBOARD', 'Dashboard'); ?></h1>

<?php echo HtmlUtils::formatMessage($this->getMessage()); ?>

<div class="row">
<?php foreach ($dashboardItems as $item) : ?>
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="row">
                    <div class="col mt-0">
                        <h5 class="card-title">
                            <a href="admin.php?com=<?php echo $item['com']; ?>" class="text-decoration-none">
                                <?php __($item['title_key'], $item['title_default']); ?>
                            </a>
                        </h5>
                    </div>
                    <div class="col-auto">
                        <div class="stat text-primary">
                            <i class="align-middle" data-feather="<?php echo $item['icon']; ?>"></i>
                        </div>
                    </div>
                </div>
                <p class="mb-0 text-muted">
                    <?php __($item['desc_key'], $item['desc_default']); ?>
                </p>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a href="admin.php?com=<?php echo $item['com']; ?>" class="btn btn-primary btn-sm">
                    <?php __('GLOBAL.MANAGE', 'Manage'); ?>
                </a>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><?php __('DASHBOARD.QUICK_ACTIONS', 'Quick Actions'); ?></h5>
            </div>
            <div class="card-body">
                <a href="admin.php?com=page&action=add" class="btn btn-success me-2 mb-2">
                    <i class="align-middle" data-feather="plus"></i>
                    <?php __('TOOLBAR.NEW_PAGE', 'Create New Page'); ?>
                </a>
                <a href="<?php echo Config::get('site_url', '/'); ?>" target="_blank" class="btn btn-outline-primary me-2 mb-2">
                    <i class="align-middle" data-feather="external-link"></i>
                    <?php __('DASHBOARD.VIEW_SITE', 'View Site'); ?>
                </a>
            </div>
        </div>
    </div>
</div>
