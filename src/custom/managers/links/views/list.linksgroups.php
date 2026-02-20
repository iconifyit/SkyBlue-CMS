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
$pageCount = $this->getVar('pageCount');
$pageNum   = $this->getVar('pageNum');

?>
<h1 class="h3 mb-3">
    <a href="admin.php?com=console" class="text-muted text-decoration-none"><?php __('COM.CONSOLE', 'Dashboard'); ?></a>
    <span class="text-muted">/</span>
    <a href="admin.php?com=collections" class="text-muted text-decoration-none"><?php __('COM.COLLECTIONS', 'Collections'); ?></a>
    <span class="text-muted">/</span>
    <?php __('COM.LINKS.GROUPS', 'Links Groups'); ?>
</h1>

<?php echo HtmlUtils::formatMessage($this->getMessage()); ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><?php __('COM.LINKS.GROUPS', 'Links Groups'); ?></h5>
        <div>
            <?php HtmlUtils::mgrActionLink('LINKS.BTN.VIEWITEMS', 'admin.php?com=links', array('class' => 'btn btn-outline-secondary me-2')); ?>
            <?php HtmlUtils::mgrActionLink('LINKS.BTN.NEWGROUP', 'admin.php?com=links&action=addgroup'); ?>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <?php
                    HtmlUtils::mgrThead(array(
                        __('GLOBAL.NAME', 'Name', 1),
                        __('GLOBAL.TASKS', 'Tasks', 1)
                    ));
                ?>
                <tbody>
                <?php if (!count($data)) : ?>
                    <tr>
                        <td colspan="2" class="text-center text-muted py-4">
                            <?php __('GLOBAL.NO_ITEMS', 'No items to display'); ?>
                        </td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($data as $item) : ?>
                    <tr>
                        <td>
                            <a href="admin.php?com=links&action=editgroup&id=<?php echo $item->getId(); ?>">
                                <?php echo $item->getName(); ?>
                            </a>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                            <?php
                                HtmlUtils::mgrTask(
                                    'links',
                                    'editgroup',
                                    $item->getId(),
                                    $item->getName(),
                                    'pencil',
                                    array(
                                        'title' => __("TASKS.EDIT", 'Edit', 1) . " " . $item->getName()
                                    )
                                );
                            ?>
                            <?php
                                HtmlUtils::mgrTask(
                                    'links',
                                    'deletegroup',
                                    $item->getId(),
                                    $item->getName(),
                                    'closethick',
                                    array(
                                        'onclick' => "confirm_delete(event, '{$item->getName()}', false, 'admin.php?com=links&action=deletegroup&id={$item->getId()}');",
                                        'title'   => __("TASKS.DELETE", 'Delete', 1) . " " . $item->getName()
                                    )
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
                        <a class="page-link" href="admin.php?com=links&action=listgroups&pageNum=<?php echo $n; ?>">
                            <?php echo $n; ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>
