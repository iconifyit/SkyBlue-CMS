<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version        2.0 2009-04-19 10:37:00 $
 * @package        SkyBlueCanvas
 * @copyright      Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license        GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

add_head_element('jquery.ui', '', 'include');
add_head_element(
    'com.css', 
    '<link rel="stylesheet" type="text/css" href="managers/page/views/css/styles.css" />'
);

add_head_element('jquery.cookie');
# add_head_element('jquery.treeview');

$data = $this->getData();

$pageCount    = $this->getVar('pageCount');
$pageNum      = $this->getVar('pageNum');
$itemsPerPage = $this->getVar('itemsPerPage');

?>
<style type="text/css">
    table td img, table td a img {border: none !important;}
</style>
<div class="jquery_tab">
    <div class="content">
        <h2>
            <a href="admin.php?com=console"><?php __('COM.CONSOLE', 'Dashboard'); ?></a> /
            <?php __('COM.PAGES', 'Pages'); ?>
        </h2>
        
        <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>
        
        <p class="buttons-top">
            <?php HtmlUtils::mgrActionLink('PAGE.NEW_PAGE', "admin.php?com=page&action=add&pageNum={$pageNum}"); ?>   
        </p>
        <table id="table_liquid" cellspacing="0"">
            <?php 
                HtmlUtils::mgrThead(array(
                    __('GLOBAL.ITEM_ID',   'ID', 1),
                    __('GLOBAL.NAME',      'Name', 1),
                    __('GLOBAL.LAYOUT',    'Layout', 1),
                    __('GLOBAL.PUBLISHED', 'Published', 1),
                    __('GLOBAL.TASKS',     'Tasks', 1)
                )); 
            ?>
            <?php if (! count($data)) : ?>
                <tr>
                    <td colspan="5"><?php __('GLOBAL.NO_ITEMS', 'No items to display'); ?></td>
                </tr>
            <?php else : ?>
                <?php $i=0; ?>
                <?php foreach ($data as $Page) : ?>
                <tr class="<?php echo ($i % 2 == 0 ? 'even' : 'odd'); ?>">
                    <td><?php echo $Page->getId(); ?></td>
                    <td><?php echo $Page->getName(); ?></td>
                    <td><?php echo $Page->getPagetype(); ?></td>
                    <td><?php __($Page->getPublished() ? 'GLOBAL.YES' : 'GLOBAL.NO', ''); ?></td>
                    <td width="150">
                        <?php if ($i > 0 || $pageNum > 1) : ?>
                            <?php $pg = ($pageNum > 1) ? $pageNum-1 : $pageNum ; ?>
                            <?php HtmlUtils::mgrTaskOrder('page', $Page->getId(), $Page->getName(), 'up', array(), array('pageNum'=>$pg)); ?>
                        <?php else: ?>
                            <?php HtmlUtils::mgrTaskSlug(); ?>
                        <?php endif; ?>
                        <?php if ($i < count($data)-1) : ?>
                            <?php HtmlUtils::mgrTaskOrder('page', $Page->getId(), $Page->getName(), 'down', array(), array('pageNum'=>$pageNum)); ?>
                        <?php elseif ($pageCount > 1 && $pageNum == 1) : ?>
                            <?php HtmlUtils::mgrTaskOrder('page', $Page->getId(), $Page->getName(), 'down', array(), array('pageNum'=>$pageNum + 1)); ?>
                        <?php else: ?>
                            <?php HtmlUtils::mgrTaskSlug(); ?>
                        <?php endif; ?>
                        <?php HtmlUtils::mgrTasks($i, count($data), $Page, array('publish','edit','copy','delete'), array('pageNum'=>$pageNum)); ?>
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
                                    <li class="ui-state-default ui-corner-all<?php echo $active; ?>"><a href="admin.php?com=page&action=list&pageNum=<?php echo $n; ?>"><?php echo $n; ?></a></li>
                                <?php endfor; ?>
                            </ul>
                        </td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            <?php endif; ?>
        </table>
        <p class="buttons-bottom">
            <?php HtmlUtils::mgrActionLink('PAGE.NEW_PAGE', 'admin.php?com=page&action=add'); ?>
        </p>
    </div>
</div>
