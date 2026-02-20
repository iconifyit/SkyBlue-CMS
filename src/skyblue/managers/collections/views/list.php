<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2009-06-20 21:41:00 $
 * @package      SkyBlue CMS
 * @copyright    Copyright (C) 2005 - 2024 Scott Edwin Lewis. All rights reserved.
 * @license      GNU/GPL, see COPYING.txt
 * SkyBlue CMS is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 *
 * Updated to use Bootstrap 5 styling
 */

/**
 * @author Scott Lewis
 * @date   June 20, 2009
 */

$components = $this->getData();

/**
 * Map collection names to Feather icons
 */
$iconMap = array(
    'links'    => 'link',
    'contacts' => 'users',
    'meta'     => 'tag',
    'menus'    => 'menu',
    'snippets' => 'code',
    'myvars'   => 'hash',
    'news'     => 'file-text',
    'gallery'  => 'image',
    'default'  => 'folder'
);

?>
<h1 class="h3 mb-3">
    <a href="admin.php?com=console" class="text-muted text-decoration-none"><?php __('COM.CONSOLE', 'Dashboard'); ?></a>
    <span class="text-muted">/</span>
    <?php __('COM.COLLECTIONS', 'Collections'); ?>
</h1>

<?php echo HtmlUtils::formatMessage($this->getMessage()); ?>

<div class="row">
    <?php for ($i=0; $i<count($components); $i++) : ?>
    <?php
        $c = $components[$i];
        $name = strtolower($c->getName());
        $icon = isset($iconMap[$name]) ? $iconMap[$name] : $iconMap['default'];
    ?>
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="row">
                    <div class="col mt-0">
                        <h5 class="card-title">
                            <a href="admin.php?com=<?php echo $name; ?>" class="text-decoration-none">
                                <?php __($c->getNameToken(), $c->getName()); ?>
                            </a>
                        </h5>
                    </div>
                    <div class="col-auto">
                        <div class="stat text-primary">
                            <i class="align-middle" data-feather="<?php echo $icon; ?>"></i>
                        </div>
                    </div>
                </div>
                <p class="mb-0 text-muted">
                    <?php __($c->getInfoToken(), "Manage your " . $c->getName()); ?>
                </p>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a href="admin.php?com=<?php echo $name; ?>" class="btn btn-primary btn-sm">
                    <?php __('GLOBAL.MANAGE', 'Manage'); ?>
                </a>
            </div>
        </div>
    </div>
    <?php endfor; ?>
</div>
