<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version        v 1.1 2009-04-19 10:37:00 $
 * @package        SkyBlueCanvas
 * @copyright      Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license        GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

$data = $this->getData();
$count = count($data);

$pageCount = $this->getVar('pageCount');
$pageNum   = $this->getVar('pageNum');

?>
<style type="text/css">
    table td img, table td a img {border: none !important;}
    .buttons-top {text-align: right;}
</style>

<div class="jquery_tab">
    <div class="content">
        <h2>
            <a href="admin.php?com=console"><?php __('COM.CONSOLE', 'Dashboard'); ?></a> /
            <a href="admin.php?com=settings"><?php __('COM.SETTINGS', 'Settings'); ?></a> / 
            <?php __('COM.USERS', 'Users'); ?>
        </h2>
        
        <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>
        
        <p class="buttons-top">
            <?php HtmlUtils::mgrActionLink('USERS.BTN.VIEWGROUPS', 'admin.php?com=users&action=listgroups'); ?>  
            <?php HtmlUtils::mgrActionLink('USERS.BTN.NEW', 'admin.php?com=users&action=add'); ?>   
        </p>
        <table id="table_liquid" cellspacing="0">
            <?php 
                HtmlUtils::mgrThead(array(
                    __('GLOBAL.NAME',      'Name', 1),
                    __('GLOBAL.LAST_LOGIN', 'Last Login', 1),
                    __('GLOBAL.TASKS',     'Tasks', 1)
                )); 
            ?>
            <?php if (!count($data)) : ?>
                <tr>
                    <td colspan="3"><?php __('GLOBAL.NO_ITEMS', 'No items to display'); ?></td>
                </tr>
            <?php else : ?>
                <?php $i=0; ?>
                <?php foreach ($data as $item) : ?>
                <tr class="<?php echo ($i % 2 == 0) ? 'even' : 'odd' ; ?>">
                    <td><?php echo $item->getName(); ?></td>
                    <?php
                        $lastlogin = $item->getLastLogin();
                        if (trim($lastlogin) == "") {
                            $lastlogin = __('GLOBAL.NEVER', 'Never', 1);
                        }
                        else {
                            $lastlogin = date("D M j,  Y G:i:s T", $lastlogin);
                        }
                    ?>
                    <td><?php echo $lastlogin; ?></td>
                    <td width="125">
                        <?php if ($count > 1) : ?>
                            <?php HtmlUtils::mgrTasks($i, $count, $item, array('edit','delete')); ?>
                        <?php else : ?>
                            <?php HtmlUtils::mgrTasks($i, $count, $item, array('edit')); ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
                <?php if ($pageCount > 1) : ?>
                <tfoot>
                    <tr>
                        <td colspan="4" align="right">
                            <ul class="pagination">
                                <?php for ($n=1; $n<=$pageCount; $n++) : ?>
                                    <?php $active = $pageNum == $n ? ' ui-state-active' : '' ; ?>
                                    <li class="ui-state-default ui-corner-all<?php echo $active; ?>"><a href="admin.php?com=users&action=list&pageNum=<?php echo $n; ?>"><?php echo $n; ?></a></li>
                                <?php endfor; ?>
                            </ul>
                        </td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            <?php endif; ?>
        </table>
        <p class="buttons-bottom">
            <?php HtmlUtils::mgrActionLink('USERS.BTN.VIEWGROUPS', 'admin.php?com=users&action=listgroups'); ?>  
            <?php HtmlUtils::mgrActionLink('USERS.BTN.NEW', 'admin.php?com=users&action=add'); ?>   
        </p>
    </div>
</div>