<?php defined('SKYBLUE') or die('Bad file request');

/**
* @version        v 1.1 2009-04-19 10:37:00 $
* @package        SkyBlueCanvas
* @copyright      Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
* @license        GNU/GPL, see COPYING.txt
* SkyBlueCanvas is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYING.txt for copyright notices and details.
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
<div class="jquery_tab">
    <div class="content">
        <h2 class="xjquery_tab_title">
            <a href="admin.php?com=media"><?php __('COM.MEDIA.FOLDERS', 'Media Folders'); ?></a>
            &nbsp;&gt;&nbsp;<?php __('COM.MEDIA', 'Media Files'); ?>
        </h2>
        <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>
        <table id="table_liquid" cellspacing="0">
            <?php 
                HtmlUtils::mgrThead(array(
                    __('GLOBAL.NAME',     'Name', 1),
                    __('MEDIA.FOLDER',    'Folder', 1),
                    __('GLOBAL.FILETYPE', 'File Type', 1),
                    __('GLOBAL.FILESIZE', 'File Size', 1),
                    __('GLOBAL.TASKS',    'Tasks', 1)
                )); 
            ?>
            <?php $i=0; ?>
            <?php if (count($media)) : ?>
                <?php foreach ($media as $Item) : ?>
                <tr class="<?php echo ($i % 2 == 0) ? 'even' : 'odd' ; ?>">
                    <?php $filePath = str_replace(SB_MEDIA_DIR, "", $Item->getFilepath()); ?>
                    <td><span class="tooltip image_preview" title="<?php echo "media/{$filePath}"; ?>" style="margin-right: 8px;"></span><?php echo $Item->getName(); ?></td>
                    <td><?php echo dirname($filePath); ?></td>
                    <td><?php echo $Item->getFiletype(); ?></td>
                    <td><?php echo Utils::formatBytes($Item->getFilesize(), 2); ?></td>
                    <td width="125">
                        <?php 
                            $params = array(
                                'folder' => dirname($filePath) . '/', 
                                'redirect' => $this->getVar('redirect')
                            ); 
                            $pageNum = Filter::getNumeric($_GET, 'pageNum');
                            if (!empty($pageNum)) {
                                $params['pageNum'] = $pageNum;
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
                                    'title'   => __("TASKS.EDIT", 'Edit', 1) . " " . $Item->getName()
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
                    </td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
                <?php if ($pageCount > 1) : ?>
                <tfoot>
                    <tr>
                        <td colspan="5" align="right">
                            <ul class="pagination">
                                <?php 
                                    $folder = $this->getVar('folder');
                                    if (!empty($folder)) $folder = "&folder={$folder}";
                                    $pageNum = Filter::getNumeric($_REQUEST, 'pageNum', 1);
                                ?>
                                <?php for ($n=1; $n<=$pageCount; $n++) : ?>
                                    <?php $active = $pageNum == $n ? ' ui-state-active' : '' ; ?>
                                    <li class="ui-state-default ui-corner-all<?php echo $active; ?>"><a href="admin.php?com=media&action=list&pageNum=<?php echo $n; ?><?php echo $folder; ?>"><?php echo $n; ?></a></li>
                                <?php endfor; ?>
                            </ul>
                        </td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5"><?php __('GLOBAL.NO_ITEMS', 'No items to display'); ?></td>
                </tr>
            <?php endif; ?>
        </table>
     </div>
</div>