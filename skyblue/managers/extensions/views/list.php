<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version        2.0 2010-01-30 00:00:01 $
 * @package        SkyBlueCanvas
 * @copyright      Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license        GNU/GPL, see COPYING.txt
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
$context   = $this->getVar('context');

?>
<div class="jquery_tab">
    <div class="content">

        <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>
        
        <table id="table_liquid" cellspacing="0">
            <?php 
                HtmlUtils::mgrThead(array(
                    __('GLOBAL.NAME',      'Name', 1),
                    __('GLOBAL.TASKS',     'Tasks', 1)
                )); 
            ?>
            <?php if (!count($data)) : ?>
                <tr>
                    <td colspan="2"><?php __('GLOBAL.NO_ITEMS', 'No items to display'); ?></td>
                </tr>
            <?php else : ?>
                <?php $i=0; ?>
                <?php foreach ($data as $item) : ?>
                <tr class="<?php echo ($i % 2 == 0) ? 'even' : 'odd' ; ?>">
                    <td><?php echo $item; ?></td>
                    <td width="50">
                        <?php 
                            $options = $this->getVar('task_edit_options');
                            $context = Filter::get($options, 'context');
                            $action  = Filter::get($options, 'action');
                            HtmlUtils::mgrTaskEdit(
                                'extensions',
                                $item,
                                $item, 
                                array(
                                    'href' => "admin.php?com=extensions&context={$context}&action={$action}&name={$item}"
                                )
                            );
                        ?>
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
                                    <li class="ui-state-default ui-corner-all<?php echo $active; ?>"><a href="admin.php?com=extensions&context=<?php echo $context; ?>&action=list_<?php echo $package; ?>&pageNum=<?php echo $n; ?>"><?php echo $n; ?></a></li>
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