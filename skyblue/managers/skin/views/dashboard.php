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

$data = $this->getData();

$pageCount = $this->getVar('pageCount');
$pageNum   = $this->getVar('pageNum');

$ActiveSkin = $this->getVar('activeSkin');
$ActiveSkin->thumbnail = ACTIVE_SKIN_DIR . "images/{$ActiveSkin->getName()}.jpg";
$data = Utils::deleteObject($data, $ActiveSkin->getId());

?>
<div class="jquery_tab">
    <div class="content">
        <h2>
            <a href="admin.php?com=console"><?php __('COM.CONSOLE', 'Dashboard'); ?></a> /
            <?php __('COM.SKINS', 'SKINS'); ?>
        </h2>
        
        <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>
        
        <div id="activeSkinDetails">
            <img src="<?php echo $ActiveSkin->thumbnail; ?>" 
                 width="<?php echo ImageUtils::width($ActiveSkin->thumbnail); ?>" 
                 height="<?php echo ImageUtils::height($ActiveSkin->thumbnail); ?>" 
                 />
            <h3><?php __('SKIN.CURRENTLY_ACTIVE', 'The currently active skin is ')?> <?php echo $ActiveSkin->getName(); ?></h3>
            <div class="clearfix">&nbsp;</div>
        </div>
        
        <table id="table_liquid" cellspacing="0">
            <?php 
                HtmlUtils::mgrThead(array(
                    __('GLOBAL.NAME',      'Name', 1),
                    __('GLOBAL.TASKS',     'Tasks', 1)
                )); 
            ?>
            <?php if (!count($data)) : ?>
                <tr>
                    <td colspan="2"><?php __('GLOBAL.NO_ITEMS', 'No items to display'); ?></td>
                </tr>
            <?php else : ?>
                <?php $i=0; ?>
                <?php foreach ($data as $item) : ?>
                <?php 
                    $imageName = strtolower($item->getName());
                    $imageFilePath = SB_SKINS_DIR . "{$imageName}/images/{$imageName}.jpg";
                    
                    $spanClass = "tooltip";
                    $spanTitle = "No preview available";
                    if (file_exists($imageFilePath)) {
                        $spanClass = "tooltip image_preview";
                        $spanTitle = $imageFilePath;
                    }
                ?>
                <tr class="<?php echo ($i % 2 == 0) ? 'even' : 'odd' ; ?>">
                    <td><span class="<?php echo $spanClass; ?>" title="<?php echo $spanTitle; ?>"><?php echo $item->getName(); ?></span></td>
                    <td width="125">
                        <?php if ($item->getPublished() != 1) : ?>
                            <?php HtmlUtils::mgrTasks($i, count($data), $item, array('publish')); ?>
                        <?php endif; ?>
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
                                    <li class="ui-state-default ui-corner-all<?php echo $active; ?>"><a href="admin.php?com=skin&action=list&pageNum=<?php echo $n; ?>"><?php echo $n; ?></a></li>
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