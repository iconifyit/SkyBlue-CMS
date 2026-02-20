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
    <?php __('COM.CHECKOUTS', 'Checked Out Content'); ?>
</h1>

<?php echo HtmlUtils::formatMessage($this->getMessage()); ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><?php __('COM.CHECKOUTS', 'Checked Out Content'); ?></h5>
        <?php if (count($data)) : ?>
        <div>
            <?php
                HtmlUtils::mgrActionLink(
                    'CHECKEDOUT.BTN.CHECKIN_ALL',
                    'admin.php?com=checkouts&action=checkin_all',
                    array(
                        'id'      => "checkinAll",
                        'onclick' => "confirm_checkin(event, ' \'All Items\' ', 'admin.php?com=checkouts&action=checkin_all');",
                        'class'   => 'btn btn-warning'
                    )
                );
            ?>
        </div>
        <?php endif; ?>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <?php
                    HtmlUtils::mgrThead(array(
                        __('GLOBAL.NAME', 'Name', 1),
                        __('GLOBAL.TYPE', 'Type', 1),
                        __('GLOBAL.ID', 'Item&nbsp;ID', 1),
                        __('CHECKOUTS.CHECKED_OUT_BY', 'Checked&nbsp;Out&nbsp;By', 1),
                        __('CHECKOUTS.CHECKED_OUT_TIME', 'Date/Time', 1),
                        __('GLOBAL.TASKS', 'Tasks', 1)
                    ));
                ?>
                <tbody>
                <?php if (!count($data)) : ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <?php __('GLOBAL.NO_ITEMS', 'No items to display'); ?>
                        </td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($data as $item) : ?>
                    <tr>
                        <td><?php echo $item->getName(); ?></td>
                        <td><?php echo $item->getItem_type(); ?></td>
                        <td><?php echo $item->getItem_id(); ?></td>
                        <td><?php echo $item->getChecked_out_by(); ?></td>
                        <td>
                            <?php echo date("M j, Y - h:i:s A T", $item->getChecked_out_time()); ?>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                            <?php
                                $customAttrs = array(
                                    'onclick' => "confirm_checkin(event, ' \'{$item->getName()}\' ', 'admin.php?com=checkouts&action=checkin&id={$item->getId()}');"
                                );
                            ?>
                            <?php
                                HtmlUtils::mgrTask(
                                    'checkouts',
                                    'checkin',
                                    $item->getId(),
                                    $item->getId(),
                                    'play',
                                    $customAttrs
                                );
                            ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
