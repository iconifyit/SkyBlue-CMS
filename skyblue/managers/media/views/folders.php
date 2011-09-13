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

add_head_element('jquery.contextmenu');
add_stylesheet('media.css', SB_MANAGER_RESOURCES . 'media/css/media.css');
# add_script('media.js', COM_PATH . 'views/js/media.js');

$folders = $this->getData();
$count = count($folders);

$folderTree = $this->getVar('folderTree');

?>
<div class="jquery_tab">
    <div class="content">
        <h2>
            <a href="admin.php?com=console"><?php __('COM.CONSOLE', 'Dashboard'); ?></a> /
            <?php __('COM.MEDIA.FOLDERS', 'Media Folders'); ?>
        </h2>
        
        <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>
        
        <p class="buttons-top">
            <?php HtmlUtils::mgrActionLink('MEDIA.BTN.NEW', 'admin.php?com=media&action=add'); ?>
        </p>
        <ul id="folder-tree">
            <li class="folder" id="root-folder">
                <span class="icon">
                    <img src="<?php echo SB_UI_DIR; ?>admin/icons/<?php echo UI_ICON_SET; ?>/folder.png" />
                </span>
                <a href="admin.php?com=media&action=list" class="folder-label">Media</a>
                <div class="clearfix"></div>
                <?php echo $folderTree; ?>
            </li>
        </ul>
        
        <div id="newFolderDialog">
            <p>Dialog content goes here.</p>
        </div>
    </div>
</div>

<ul id="mediaDirsContextMenu" class="contextMenu">
    <li class="edit"><a href="#add">New Sub-folder</a></li>
    <li class="edit"><a href="#edit">Re-name</a></li>
    <li class="edit"><a href="#edit">Move</a></li>
    <li class="delete separator"><a href="#delete">Delete</a></li>
</ul>