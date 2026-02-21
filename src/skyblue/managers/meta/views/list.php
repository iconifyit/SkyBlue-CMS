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

$data = $this->getData();

?>
<h1 class="h3 mb-3">
    <a href="admin.php?com=console" class="text-muted text-decoration-none"><?php __('COM.CONSOLE', 'Dashboard'); ?></a>
    <span class="text-muted">/</span>
    <a href="admin.php?com=collections" class="text-muted text-decoration-none"><?php __('COM.COLLECTIONS', 'Collections'); ?></a>
    <span class="text-muted">/</span>
    <?php __('COM.META', 'Meta Tags'); ?>
</h1>

<?php echo HtmlUtils::formatMessage($this->getMessage()); ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><?php __('COM.META', 'Meta Tags'); ?></h5>
        <div>
            <?php HtmlUtils::mgrActionLink('META.BTN.VIEWGROUPS', 'admin.php?com=meta&action=listgroups', array('class' => 'btn btn-outline-secondary me-2')); ?>
            <?php HtmlUtils::mgrActionLink('META.BTN.NEW', 'admin.php?com=meta&action=add'); ?>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <?php
                    HtmlUtils::mgrThead(array(
                        __('GLOBAL.TITLE', 'Title', 1),
                        __('GLOBAL.TASKS', 'Tasks', 1)
                    ));
                ?>
                <tbody>
                <?php if (!count($data)) : ?>
                    <tr>
                        <td colspan="2" class="text-center text-muted py-4">
                            <?php __('GLOBAL.NO_ITEMS', 'No items to display'); ?>
                        </td>
                    </tr>
                <?php else : ?>
                    <?php $i=0; ?>
                    <?php foreach ($data as $item) : ?>
                    <tr>
                        <td>
                            <a href="admin.php?com=meta&action=edit&id=<?php echo $item->getId(); ?>">
                                <?php echo $item->getName(); ?>
                            </a>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <?php HtmlUtils::mgrTasks($i, count($data), $item, array('edit','delete')); ?>
                            </div>
                        </td>
                    </tr>
                    <?php $i++; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
