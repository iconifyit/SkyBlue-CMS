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

add_head_element('jquery.cookie');
add_head_element('jquery.utils');
add_head_element('page.onload', TABS_JS);
add_head_element('page.style',  TABS_CSS);
add_head_element('jquery.simplemodal');

add_script('media.js', 'resources/managers/media/js/edit.js');

$Item = $this->getData();
$theAction = $this->getVar('action');

?>
<!--[if lt IE 7]>
<style type='text/css'>
    #simplemodal-container a.modalCloseImg {
        background:none;
        right:-14px;
        width:22px;
        height:26px;
        filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(
            src='ui/images/x.png', sizingMethod='scale'
        );
    }
</style>
<![endif]-->
<div class="jquery_tab">
    <div class="content">
        <h2>
            <a href="admin.php?com=media"><?php __('COM.MEDIA', 'Media'); ?></a> / 
            <?php __("GLOBAL.{$theAction}", ucwords($theAction)); ?> / 
            <?php echo $Item->getName(); ?>
        </h2>
        
        <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>
        
        <?php HtmlUtils::mgrFormOpen($this->getVar('com')); ?>

        <p class="buttons-top">
            <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel'); ?>
            <?php HtmlUtils::mgrButton('BTN.SAVE', 'save'); ?>
        </p>
        
        <div id="tabs">
            <ul>
                <li><a href="#tab-one"><?php echo $Item->getName(); ?></a></li>
            </ul>
            
            <!-- First Tab -->
            <div class="tab-body" id="tab-one">
                <fieldset class="three-column">
                    <div class="column">
                        <label for="name"><?php __('GLOBAL.NAME', 'Name'); ?>:</label>
                        <input class="input-medium" type="text" name="name" value="<?php echo $Item->getName(); ?>" id="name" />
                        <input class="input-medium" type="hidden" name="id" value="<?php echo $Item->getId(); ?>" id="id" />
                    </div>
                    <div class="column">
                        <label for="filetype"><?php __('MEDIA.ACTION', 'Action'); ?>:</label>
                        <?php echo MediaHelper::getActionOverrideSelector(Filter::noInjection($_POST, 'action_override')); ?>
                    </div>
                    <div id="destination_column" class="column">
                        <label for="destination"><?php __('MEDIA.DESTINATION', 'Destination'); ?>:</label>
                        <?php echo MediaHelper::getFolderSelector(Filter::noInjection($_POST, 'destination'), $Item); ?>
                    </div>
                    <div class="clear"></div>
                </fieldset>
                <fieldset class="three-column">
                    <div class="column">
                        <label for="name"><?php __('GLOBAL.NAME', 'Name'); ?>:</label>
                        <?php echo $Item->getName(); ?>
                    </div>
                    <div class="column">
                        <label for="filetype"><?php __('MEDIA.FILETYPE', 'File Type'); ?>:</label>
                        <?php echo $Item->getFiletype(); ?>
                    </div>
                    <div class="column">
                        <label for="filesize"><?php __('MEDIA.FILESIZE', 'File Size'); ?>:</label>
                        <?php echo MediaHelper::formatFileSize($Item->getFilesize()); ?>
                    </div>
                    <div class="clear"></div>
                </fieldset>
                <fieldset class="three-column last">
                    <?php if (MediaHelper::mediaType($Item->getFilepath()) == "image") : ?>
                        <?php $Item->setFilepath(str_replace(_SBC_WWW_,'',$Item->getFilepath())); ?>
                        <div class="column">
                            <img src="<?php echo $Item->getFilepath(); ?>" 
                                 width="<?php echo ImageUtils::width($Item->getFilepath()) > 200 ? MediaHelper::scaleWidth($Item->getFilepath(), '.5') : ImageUtils::width($Item->getFilepath()) ; ?>" 
                                 style="float: left; margin: 10px 10px 10px 0px;"
                                 id="imagePreview"
                                 />
                        </div>
                    <?php endif; ?>
                    <div class="column">
                        <label for="name"><?php __('GLOBAL.FILEPATH', 'File Path'); ?>:</label>
                        <p><?php echo $Item->getFilepath(); ?></p>
                        
                        <label for="width"><?php __('MEDIA.WIDTH', 'Width'); ?>:</label>
                        <p id="width"><?php echo ImageUtils::width($Item->getFilepath()); ?> px</p>
                        
                        <label for="height"><?php __('MEDIA.HEIGHT', 'Height'); ?>:</label>
                        <p id="height"><?php echo ImageUtils::height($Item->getFilepath()); ?> px</p>
                    </div>
                    <div class="clear"></div>
                </fieldset>
           </div>
        </div>
        <p class="buttons-bottom">
            <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel'); ?>
            <?php HtmlUtils::mgrButton('BTN.SAVE', 'save'); ?>
        </p>    
        <?php $pageNum = Filter::getNumeric($_GET, 'pageNum'); ?>
        <?php if (!empty($pageNum)) : ?>
        <input type="hidden" name="pageNum" value="<?php echo $pageNum; ?>" />
        <?php endif; ?>
        <input type="hidden" name="redirect" value="<?php echo $this->getVar('redirect');?>" />
        <input type="hidden" name="folder" value="<?php echo $this->getVar('folder');?>" />

        <input type="hidden" name="filetype" value="<?php echo $Item->getFiletype(); ?>" />
        <input type="hidden" name="filesize" value="<?php echo $Item->getFilesize(); ?>" />
        <input type="hidden" name="filepath" value="<?php echo $Item->getFilepath(); ?>" />
        <input type="hidden" name="type" value="<?php echo $Item->getType(); ?>" />
        <input type="hidden" name="objtype" value="<?php echo $Item->getObjtype(); ?>" />

        <?php HtmlUtils::mgrFormClose(); ?>
    </div>
</div>