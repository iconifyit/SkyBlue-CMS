<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2009-04-19 10:37:00 $
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

$pageCount    = $this->getVar('pageCount');
$pageNum      = $this->getVar('pageNum');
$itemsPerPage = $this->getVar('itemsPerPage');

?>
<h1 class="h3 mb-3">
    <a href="admin.php?com=console" class="text-muted text-decoration-none"><?php __('COM.CONSOLE', 'Dashboard'); ?></a>
    <span class="text-muted">/</span>
    <?php __('COM.PAGES', 'Pages'); ?>
</h1>

<?php echo HtmlUtils::formatMessage($this->getMessage()); ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><?php __('COM.PAGES', 'Pages'); ?></h5>
        <div>
            <?php HtmlUtils::mgrActionLink('PAGE.NEW_PAGE', "admin.php?com=page&action=add&pageNum={$pageNum}"); ?>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <?php
                    HtmlUtils::mgrThead(array(
                        __('GLOBAL.ITEM_ID',   'ID', 1),
                        __('GLOBAL.NAME',      'Name', 1),
                        __('GLOBAL.LAYOUT',    'Layout', 1),
                        __('GLOBAL.PUBLISHED', 'Published', 1),
                        __('GLOBAL.TASKS',     'Tasks', 1)
                    ));
                ?>
                <tbody>
                <?php if (! count($data)) : ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <?php __('GLOBAL.NO_ITEMS', 'No items to display'); ?>
                        </td>
                    </tr>
                <?php else : ?>
                    <?php $i=0; ?>
                    <?php foreach ($data as $Page) : ?>
                    <tr>
                        <td><?php echo $Page->getId(); ?></td>
                        <td>
                            <a href="admin.php?com=page&action=edit&id=<?php echo $Page->getId(); ?>&pageNum=<?php echo $pageNum; ?>">
                                <?php echo $Page->getName(); ?>
                            </a>
                        </td>
                        <td><?php echo $Page->getPagetype(); ?></td>
                        <td>
                            <?php if ($Page->getPublished()) : ?>
                                <span class="badge bg-success"><?php __('GLOBAL.YES', 'Yes'); ?></span>
                            <?php else : ?>
                                <span class="badge bg-secondary"><?php __('GLOBAL.NO', 'No'); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
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
                        <a class="page-link" href="admin.php?com=page&action=list&pageNum=<?php echo $n; ?>">
                            <?php echo $n; ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>
