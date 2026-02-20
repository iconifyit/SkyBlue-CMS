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

add_stylesheet('media.css', 'resources/media/views/css/media.css');

$media = $this->getData();
$pageCount = $this->getVar('pageCount');
$pageNum   = $this->getVar('pageNum');

$folder = Filter::noInjection($_GET, 'folder');
if (!empty($folder)) {
    $this->assign('folder', MediaHelper::getItemFolder($folder));
}

?>
<h1 class="h3 mb-3">
    <a href="admin.php?com=console" class="text-muted text-decoration-none"><?php __('COM.CONSOLE', 'Dashboard'); ?></a>
    <span class="text-muted">/</span>
    <a href="admin.php?com=media" class="text-muted text-decoration-none"><?php __('COM.MEDIA.FOLDERS', 'Media Folders'); ?></a>
    <span class="text-muted">/</span>
    <?php __('COM.MEDIA', 'Media Files'); ?>
</h1>

<?php echo HtmlUtils::formatMessage($this->getMessage()); ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><?php __('COM.MEDIA', 'Media Files'); ?></h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <?php
                    HtmlUtils::mgrThead(array(
                        __('GLOBAL.NAME',     'Name', 1),
                        __('MEDIA.FOLDER',    'Folder', 1),
                        __('GLOBAL.FILETYPE', 'File Type', 1),
                        __('GLOBAL.FILESIZE', 'File Size', 1),
                        __('GLOBAL.TASKS',    'Tasks', 1)
                    ));
                ?>
                <tbody>
                <?php if (count($media)) : ?>
                    <?php foreach ($media as $Item) : ?>
                    <?php $filePath = str_replace(SB_MEDIA_DIR, "", $Item->getFilepath()); ?>
                    <tr>
                        <td>
                            <span class="tooltip image_preview" title="<?php echo "media/{$filePath}"; ?>" style="margin-right: 8px;"></span>
                            <?php echo $Item->getName(); ?>
                        </td>
                        <td><?php echo dirname($filePath); ?></td>
                        <td><?php echo $Item->getFiletype(); ?></td>
                        <td><?php echo Utils::formatBytes($Item->getFilesize(), 2); ?></td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                            <?php
                                $params = array(
                                    'folder'   => dirname($filePath) . '/',
                                    'redirect' => $this->getVar('redirect')
                                );
                                $currentPageNum = Filter::getNumeric($_GET, 'pageNum');
                                if (!empty($currentPageNum)) {
                                    $params['pageNum'] = $currentPageNum;
                                }
                                $params['name'] = $Item->getName();
                                $destination  = "admin.php?com=media&action=delete&id={$Item->getName()}";
                                $destination .= "&folder=" . $params['folder'] . "&redirect=" . $params['redirect'];
                                $destination .= "&name={$Item->getName()}";
                            ?>
                            <?php
                                HtmlUtils::mgrTask(
                                    'media',
                                    'edit',
                                    $Item->getName(),
                                    $Item->getName(),
                                    'pencil',
                                    array(
                                        'title' => __("TASKS.EDIT", 'Edit', 1) . " " . $Item->getName()
                                    ),
                                    $params
                                );
                            ?>
                            <?php
                                HtmlUtils::mgrTask(
                                    'media',
                                    'delete',
                                    $Item->getName(),
                                    $Item->getName(),
                                    'closethick',
                                    array(
                                        'onclick' => "confirm_delete(event, ' \'{$Item->getName()}\' ', false, '{$destination}');",
                                        'title'   => __("TASKS.DELETE", 'Delete', 1) . " " . $Item->getName()
                                    ),
                                    $params
                                );
                            ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <?php __('GLOBAL.NO_ITEMS', 'No items to display'); ?>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if ($pageCount > 1) : ?>
    <div class="card-footer">
        <nav aria-label="Page navigation">
            <ul class="pagination mb-0 justify-content-end">
                <?php
                    $folderParam = $this->getVar('folder');
                    if (!empty($folderParam)) $folderParam = "&folder={$folderParam}";
                    $currentPageNum = Filter::getNumeric($_REQUEST, 'pageNum', 1);
                ?>
                <?php for ($n=1; $n<=$pageCount; $n++) : ?>
                    <li class="page-item<?php echo ($currentPageNum == $n) ? ' active' : ''; ?>">
                        <a class="page-link" href="admin.php?com=media&action=list&pageNum=<?php echo $n; ?><?php echo $folderParam; ?>">
                            <?php echo $n; ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>
