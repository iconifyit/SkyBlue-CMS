<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2009-06-22 17:37:00 $
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
 * @date   June 22, 2009
 */

$com  = $this->getVar('com');
$acos = $this->getData();
$pageCount = $this->getVar('pageCount');
$pageNum   = $this->getVar('pageNum');

?>
<div class="jquery_tab">
    <div class="content">
        <h2>
            <a href="admin.php?com=console"><?php __('COM.CONSOLE', 'Dashboard'); ?></a> /
            <a href="admin.php?com=settings"><?php __('COM.SETTINGS', 'Settings'); ?></a> /  
            <?php __('COM.ADMINACL', 'Component Access'); ?>
        </h2>
        
        <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>

        <table cellpadding="0" cellspacing="0" id="table_liquid">
        <?php 
            HtmlUtils::mgrThead(array(
                __('ADMINACL.HEADING.MANAGER', 'Manager', 1),
                __('GLOBAL.TASKS', 'Tasks', 1)
            )); 
        ?>
        <?php if (!count($acos)) : ?>
            <tr>
                <td colspan="2"><?php __('GLOBAL.NO_ITEMS', 'No items to display'); ?></td>
            </tr>
        <?php else : ?>
            <?php $i=0; ?>
            <?php foreach ($acos as $Aco) : ?>
                <tr class="<?php echo $i % 2 == 0 ? 'even' : 'odd'; ?>">
                    <td width="90%">
                        <?php echo $Aco->getName(); ?>
                    </td>
                    <td width="10%">
                        <?php 
                            HtmlUtils::mgrTask(
                                'adminacl', 
                                'edit', 
                                $Aco->getName(), 
                                $Aco->getName(), 
                                'pencil',
                                array(
                                    'title' => __('TASKS.EDIT', 'Edit', 1) . " " . __('ADMINACL.LABEL.ACL_FOR', 'access to', 1) . " " . $Aco->getName() . " " . __('COM.MANAGER', 'Manager', 1),
                                    'href'  => "admin.php?com={$com}&action=edit&id={$Aco->getId()}&pageNum={$pageNum}"
                                ),
                                $params
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
                                    <li class="ui-state-default ui-corner-all<?php echo $active; ?>"><a href="admin.php?com=adminacl&action=list&pageNum=<?php echo $n; ?>"><?php echo $n; ?></a></li>
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