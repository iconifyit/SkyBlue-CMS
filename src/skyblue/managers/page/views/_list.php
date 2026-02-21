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

add_head_element('jquery.ui', '', 'include');
add_head_element(
    'com.css',
    '<link rel="stylesheet" type="text/css" href="managers/page/views/css/styles.css" />'
);

add_head_element('jquery.cookie');

$data = $this->getData();

$pageCount    = $this->getVar('pageCount');
$pageNum      = $this->getVar('pageNum');
$itemsPerPage = $this->getVar('itemsPerPage');

?>
<h1 class="h3 mb-3">
    <a href="admin.php?com=console" class="text-muted text-decoration-none"><?php __('COM.CONSOLE', 'Dashboard'); ?></a>
    <span class="text-muted">/</span>
    <?php __('COM.PAGES', 'Pages'); ?>
</h1>

<?php echo HtmlUtils::formatMessage($this->getMessage()); ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><?php __('PAGE.TREE', 'Page Tree'); ?></h5>
        <a href="admin.php?com=page&action=add" class="btn btn-primary btn-sm">
            <i data-feather="plus"></i> <?php __('BTN.ADD', 'Add Page'); ?>
        </a>
    </div>
    <div class="card-body">
        <?php PageHelper::getTreeView($data, array('block_id' => 'page-tree')); ?>
    </div>
</div>
