<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      v 1.1 2009-04-19 10:37:00 $
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

$data = $this->getData();
$count = count($data);

$pageCount = $this->getVar('pageCount');
$pageNum   = $this->getVar('pageNum');

?>
<h1 class="h3 mb-3">
    <a href="admin.php?com=console" class="text-muted text-decoration-none"><?php __('COM.CONSOLE', 'Dashboard'); ?></a>
    <span class="text-muted">/</span>
    <a href="admin.php?com=settings" class="text-muted text-decoration-none"><?php __('COM.SETTINGS', 'Settings'); ?></a>
    <span class="text-muted">/</span>
    <?php __('COM.USERS', 'Users'); ?>
</h1>

<?php echo HtmlUtils::formatMessage($this->getMessage()); ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><?php __('COM.USERS', 'Users'); ?></h5>
        <div>
            <?php HtmlUtils::mgrActionLink('USERS.BTN.VIEWGROUPS', 'admin.php?com=users&action=listgroups', array('class' => 'btn btn-outline-secondary me-2')); ?>
            <?php HtmlUtils::mgrActionLink('USERS.BTN.NEW', 'admin.php?com=users&action=add'); ?>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <?php
                    HtmlUtils::mgrThead(array(
                        __('GLOBAL.NAME',      'Name', 1),
                        __('GLOBAL.LAST_LOGIN', 'Last Login', 1),
                        __('GLOBAL.TASKS',     'Tasks', 1)
                    ));
                ?>
                <tbody>
                <?php if (!count($data)) : ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">
                            <?php __('GLOBAL.NO_ITEMS', 'No items to display'); ?>
                        </td>
                    </tr>
                <?php else : ?>
                    <?php $i=0; ?>
                    <?php foreach ($data as $item) : ?>
                    <?php
                        $lastlogin = $item->getLastLogin();
                        if (trim($lastlogin) == "") {
                            $lastlogin = __('GLOBAL.NEVER', 'Never', 1);
                        }
                        else {
                            $lastlogin = date("D M j, Y G:i:s T", $lastlogin);
                        }
                    ?>
                    <tr>
                        <td>
                            <a href="admin.php?com=users&action=edit&id=<?php echo $item->getId(); ?>">
                                <?php echo $item->getName(); ?>
                            </a>
                        </td>
                        <td><?php echo $lastlogin; ?></td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <?php if ($count > 1) : ?>
                                    <?php HtmlUtils::mgrTasks($i, $count, $item, array('edit','delete')); ?>
                                <?php else : ?>
                                    <?php HtmlUtils::mgrTasks($i, $count, $item, array('edit')); ?>
                                <?php endif; ?>
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
    <?php if ($pageCount > 1) : ?>
    <div class="card-footer">
        <nav aria-label="Page navigation">
            <ul class="pagination mb-0 justify-content-end">
                <?php for ($n=1; $n<=$pageCount; $n++) : ?>
                    <li class="page-item<?php echo ($pageNum == $n) ? ' active' : ''; ?>">
                        <a class="page-link" href="admin.php?com=users&action=list&pageNum=<?php echo $n; ?>">
                            <?php echo $n; ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>
