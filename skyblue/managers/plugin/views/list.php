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

$pageCount = $this->getVar('pageCount');
$pageNum   = $this->getVar('pageNum');
$itemsPerPage = $this->getVar('items_per_page');

/**
 * The tooltips on this page are causing the window size to change and flicker.
 * Un-commenting the line below will disable the tolltips only for this page.
 */
# add_scriptlet("tooltip-override", "$.editor.impl.tooltip = function() {};");

?>
<script type="text/javascript">

</script>
<div class="jquery_tab">
    <div class="content">
        <h2>
            <a href="admin.php?com=console"><?php __('COM.CONSOLE', 'Dashboard'); ?></a> /
            <a href="admin.php?com=collections"><?php __('COM.COLLECTIONS', 'Collections'); ?></a> / 
            <?php __('COM.PLUGINS', 'Plugins'); ?>
        </h2>
        
        <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>
        
        <table id="table_liquid" cellspacing="0">
            <?php 
                HtmlUtils::mgrThead(array(
                    __('GLOBAL.NAME',      'Name', 1),
                    __('GLOBAL.PUBLISHED', 'Published', 1),
                    __('GLOBAL.ORDER',     'Order', 1),
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
                    <td><?php echo Utils::intToYesNo($item->getPublished()); ?></td>
                    <td><?php echo $i + 1; ?></td>
                    <td width="80">
                        <?php HtmlUtils::mgrTasks($i, $this->dao->countItems(), $item, array('order', 'publish'), array(), $itemsPerPage); ?>
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
                                    <li class="ui-state-default ui-corner-all<?php echo $active; ?>"><a href="admin.php?com=plugin&action=list&pageNum=<?php echo $n; ?>"><?php echo $n; ?></a></li>
                                <?php endfor; ?>
                            </ul>
                        </td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            <?php endif; ?>
        </table>

    </div>
</div>