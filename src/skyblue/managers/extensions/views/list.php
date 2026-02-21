<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2010-01-30 00:00:01 $
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
 * @date   June 20, 2009
 */

$data = $this->getData();

$pageCount = $this->getVar('pageCount');
$pageNum   = $this->getVar('pageNum');
$context   = $this->getVar('context');

?>
<h1 class="h3 mb-3">
    <a href="admin.php?com=console" class="text-muted text-decoration-none"><?php __('COM.CONSOLE', 'Dashboard'); ?></a>
    <span class="text-muted">/</span>
    <?php __('COM.EXTENSIONS', 'Extensions'); ?>
</h1>

<?php echo HtmlUtils::formatMessage($this->getMessage()); ?>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0"><?php __('COM.EXTENSIONS', 'Extensions'); ?></h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <?php
                    HtmlUtils::mgrThead(array(
                        __('GLOBAL.NAME',  'Name', 1),
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
                        <td><?php echo $item; ?></td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                            <?php
                                $options = $this->getVar('task_edit_options');
                                $ctx = Filter::get($options, 'context');
                                $action  = Filter::get($options, 'action');
                                HtmlUtils::mgrTaskEdit(
                                    'extensions',
                                    $item,
                                    $item,
                                    array(
                                        'href' => "admin.php?com=extensions&context={$ctx}&action={$action}&name={$item}"
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
                        <a class="page-link" href="admin.php?com=extensions&context=<?php echo $context; ?>&action=list_<?php echo $package; ?>&pageNum=<?php echo $n; ?>">
                            <?php echo $n; ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>
