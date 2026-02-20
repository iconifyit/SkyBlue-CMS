<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2009-06-22 17:37:00 $
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
 * @date   June 22, 2009
 */

$com  = $this->getVar('com');
$acos = $this->getData();
$pageCount = $this->getVar('pageCount');
$pageNum   = $this->getVar('pageNum');

?>
<h1 class="h3 mb-3">
    <a href="admin.php?com=console" class="text-muted text-decoration-none"><?php __('COM.CONSOLE', 'Dashboard'); ?></a>
    <span class="text-muted">/</span>
    <a href="admin.php?com=settings" class="text-muted text-decoration-none"><?php __('COM.SETTINGS', 'Settings'); ?></a>
    <span class="text-muted">/</span>
    <?php __('COM.ADMINACL', 'Component Access'); ?>
</h1>

<?php echo HtmlUtils::formatMessage($this->getMessage()); ?>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0"><?php __('COM.ADMINACL', 'Component Access'); ?></h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <?php
                    HtmlUtils::mgrThead(array(
                        __('ADMINACL.HEADING.MANAGER', 'Manager', 1),
                        __('GLOBAL.TASKS', 'Tasks', 1)
                    ));
                ?>
                <tbody>
                <?php if (!count($acos)) : ?>
                    <tr>
                        <td colspan="2" class="text-center text-muted py-4">
                            <?php __('GLOBAL.NO_ITEMS', 'No items to display'); ?>
                        </td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($acos as $Aco) : ?>
                    <tr>
                        <td>
                            <a href="admin.php?com=<?php echo $com; ?>&action=edit&id=<?php echo $Aco->getId(); ?>&pageNum=<?php echo $pageNum; ?>">
                                <?php echo $Aco->getName(); ?>
                            </a>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
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
                                    array()
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
    <?php if ($pageCount > 1) : ?>
    <div class="card-footer">
        <nav aria-label="Page navigation">
            <ul class="pagination mb-0 justify-content-end">
                <?php for ($n=1; $n<=$pageCount; $n++) : ?>
                    <li class="page-item<?php echo ($pageNum == $n) ? ' active' : ''; ?>">
                        <a class="page-link" href="admin.php?com=adminacl&action=list&pageNum=<?php echo $n; ?>">
                            <?php echo $n; ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>
