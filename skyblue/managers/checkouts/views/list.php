<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2009-06-20 21:41:00 $
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

$data = $this->getData();

?>
<div class="jquery_tab">
    <div class="content">
        <h2>
            <a href="admin.php?com=console"><?php __('COM.CONSOLE', 'Dashboard'); ?></a> /
            <?php __('COM.CHECKOUTS', 'Checked Out Content'); ?>
        </h2>
        
        <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>

        <p class="buttons-top">
            <?php if (count($data)) : ?>
            <?php 
                HtmlUtils::mgrActionLink(
                    'CHECKEDOUT.BTN.CHECKIN_ALL', 
                    'admin.php?com=checkouts&action=checkin_all',
                    array(
                        'id'      => "checkinAll",
                        'onclick' => "confirm_checkin(event, ' \'All Items\' ', 'admin.php?com=checkouts&action=checkin_all');"
                    )
                );
            ?>
            <?php endif; ?>
        </p>
        <table id="table_liquid" cellspacing="0">
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
            <?php if (!count($data)) : ?>
                <tr>
                    <td colspan="6"><?php __('GLOBAL.NO_ITEMS', 'No items to display'); ?></td>
                </tr>
            <?php else : ?>
                <?php $i=0; ?>
                <?php foreach ($data as $item) : ?>
                <tr class="<?php echo ($i % 2 == 0) ? 'even' : 'odd' ; ?>">
                    <td><?php echo $item->getName(); ?></td>
                    <td><?php echo $item->getItem_type(); ?></td>
                    <td><?php echo $item->getItem_id(); ?></td>
                    <td><?php echo $item->getChecked_out_by(); ?></td>
                    <td width="125">
                        <?php echo str_replace(' ', '&nbsp;', date("M j, Y - h:i:s A T", $item->getChecked_out_time())); ?>
                    </td>
                    <td width="125">
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
                    </td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
        <p class="buttons-bottom"></p>
    </div>
</div>