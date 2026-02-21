<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2009-06-20 21:41:00 $
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

add_head_element('jquery.contextmenu');
add_stylesheet('media.css', SB_MANAGER_RESOURCES . 'media/css/media.css');

$folders = $this->getData();
// PHP 8.2: Ensure $folders is an array before counting
if (!is_array($folders)) {
    $folders = array();
}
$count = count($folders);

$folderTree = $this->getVar('folderTree');

?>
<h1 class="h3 mb-3">
    <a href="admin.php?com=console" class="text-muted text-decoration-none"><?php __('COM.CONSOLE', 'Dashboard'); ?></a>
    <span class="text-muted">/</span>
    <?php __('COM.MEDIA.FOLDERS', 'Media Folders'); ?>
</h1>

<?php echo HtmlUtils::formatMessage($this->getMessage()); ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><?php __('COM.MEDIA.FOLDERS', 'Media Folders'); ?></h5>
        <div>
            <?php HtmlUtils::mgrActionLink('MEDIA.BTN.NEW', 'admin.php?com=media&action=add'); ?>
        </div>
    </div>
    <div class="card-body">
        <ul id="folder-tree" class="list-unstyled">
            <li class="folder" id="root-folder">
                <span class="icon me-2">
                    <i data-feather="folder"></i>
                </span>
                <a href="admin.php?com=media&action=list" class="folder-label text-decoration-none">Media</a>
                <div class="clearfix"></div>
                <?php echo $folderTree; ?>
            </li>
        </ul>
    </div>
</div>

<div id="newFolderDialog" class="d-none">
    <p>Dialog content goes here.</p>
</div>

<ul id="mediaDirsContextMenu" class="contextMenu d-none">
    <li class="edit"><a href="#add">New Sub-folder</a></li>
    <li class="edit"><a href="#edit">Re-name</a></li>
    <li class="edit"><a href="#edit">Move</a></li>
    <li class="delete separator"><a href="#delete">Delete</a></li>
</ul>
