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
            <a href="admin.php?com=collections"><?php __('COM.COLLECTIONS', 'Collections'); ?></a> / 
            <?php __('COM.CONTACTS', 'Contacts'); ?>
        </h2>
        
        <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>

        <p class="buttons-top">
            <?php HtmlUtils::mgrActionLink('CONTACTS.BTN.NEW', 'admin.php?com=contacts&action=add'); ?>
        </p>
        <table id="table_liquid" cellspacing="0">
            <?php 
                HtmlUtils::mgrThead(array(
                    __('GLOBAL.NAME', 'Name', 1),
                    __('GLOBAL.TITLE', 'Title', 1),
                    __('GLOBAL.EMAIL', 'Email', 1),
                    __('GLOBAL.TASKS', 'Tasks', 1)
                )); 
            ?>
            <?php if (!count($data)) : ?>
                <tr>
                    <td colspan="4"><?php __('GLOBAL.NO_ITEMS', 'No items to display'); ?></td>
                </tr>
            <?php else : ?>
                <?php $i=0; ?>
                <?php foreach ($data as $item) : ?>
                <tr class="<?php echo ($i % 2 == 0) ? 'even' : 'odd' ; ?>">
                    <td><?php echo $item->getName(); ?></td>
                    <td><?php echo $item->getTitle(); ?></td>
                    <td><?php echo $item->getEmail(); ?></td>
                    <td width="125">
                        <?php HtmlUtils::mgrTasks($i, count($data), $item, array('edit','delete')); ?>
                    </td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
        <p class="buttons-bottom">
            <?php HtmlUtils::mgrActionLink('CONTACTS.BTN.NEW', 'admin.php?com=contacts&action=add'); ?>
        </p>
    </div>
</div>