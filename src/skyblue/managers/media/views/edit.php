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

add_head_element('jquery.simplemodal');
add_script('media.js', 'resources/managers/media/js/edit.js');

$Item = $this->getData();
$theAction = $this->getVar('action');

?>
<h1 class="h3 mb-3">
    <a href="admin.php?com=console" class="text-muted text-decoration-none"><?php __('COM.CONSOLE', 'Dashboard'); ?></a>
    <span class="text-muted">/</span>
    <a href="admin.php?com=media" class="text-muted text-decoration-none"><?php __('COM.MEDIA', 'Media'); ?></a>
    <span class="text-muted">/</span>
    <?php __("GLOBAL.{$theAction}", ucwords($theAction)); ?>
</h1>

<?php echo HtmlUtils::formatMessage($this->getMessage()); ?>

<?php HtmlUtils::mgrFormOpen($this->getVar('com')); ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><?php echo $Item->getName(); ?></h5>
        <div>
            <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel', array('class' => 'btn btn-outline-secondary me-2')); ?>
            <?php HtmlUtils::mgrButton('BTN.SAVE', 'save', array('class' => 'btn btn-success')); ?>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="name" class="form-label"><?php __('GLOBAL.NAME', 'Name'); ?></label>
                <input class="form-control" type="text" name="name" value="<?php echo $Item->getName(); ?>" id="name" />
                <input type="hidden" name="id" value="<?php echo $Item->getId(); ?>" id="id" />
            </div>
            <div class="col-md-4">
                <label for="action_override" class="form-label"><?php __('MEDIA.ACTION', 'Action'); ?></label>
                <?php echo MediaHelper::getActionOverrideSelector(Filter::noInjection($_POST, 'action_override')); ?>
            </div>
            <div class="col-md-4" id="destination_column">
                <label for="destination" class="form-label"><?php __('MEDIA.DESTINATION', 'Destination'); ?></label>
                <?php echo MediaHelper::getFolderSelector(Filter::noInjection($_POST, 'destination'), $Item); ?>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label"><?php __('GLOBAL.NAME', 'Name'); ?></label>
                <p class="form-control-plaintext"><?php echo $Item->getName(); ?></p>
            </div>
            <div class="col-md-4">
                <label class="form-label"><?php __('MEDIA.FILETYPE', 'File Type'); ?></label>
                <p class="form-control-plaintext"><?php echo $Item->getFiletype(); ?></p>
            </div>
            <div class="col-md-4">
                <label class="form-label"><?php __('MEDIA.FILESIZE', 'File Size'); ?></label>
                <p class="form-control-plaintext"><?php echo MediaHelper::formatFileSize($Item->getFilesize()); ?></p>
            </div>
        </div>

        <?php if (MediaHelper::mediaType($Item->getFilepath()) == "image") : ?>
        <?php $Item->setFilepath(str_replace(_SBC_WWW_,'',$Item->getFilepath())); ?>
        <div class="row mb-3">
            <div class="col-md-6">
                <img src="<?php echo $Item->getFilepath(); ?>"
                     class="img-fluid rounded border"
                     width="<?php echo ImageUtils::width($Item->getFilepath()) > 200 ? MediaHelper::scaleWidth($Item->getFilepath(), '.5') : ImageUtils::width($Item->getFilepath()) ; ?>"
                     id="imagePreview"
                     alt="<?php echo $Item->getName(); ?>"
                     />
            </div>
            <div class="col-md-6">
                <label class="form-label"><?php __('GLOBAL.FILEPATH', 'File Path'); ?></label>
                <p class="form-control-plaintext text-break"><?php echo $Item->getFilepath(); ?></p>

                <label class="form-label"><?php __('MEDIA.WIDTH', 'Width'); ?></label>
                <p class="form-control-plaintext" id="width"><?php echo ImageUtils::width($Item->getFilepath()); ?> px</p>

                <label class="form-label"><?php __('MEDIA.HEIGHT', 'Height'); ?></label>
                <p class="form-control-plaintext" id="height"><?php echo ImageUtils::height($Item->getFilepath()); ?> px</p>
            </div>
        </div>
        <?php else : ?>
        <div class="row mb-3">
            <div class="col-12">
                <label class="form-label"><?php __('GLOBAL.FILEPATH', 'File Path'); ?></label>
                <p class="form-control-plaintext text-break"><?php echo $Item->getFilepath(); ?></p>
            </div>
        </div>
        <?php endif; ?>

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
    </div>
    <div class="card-footer d-flex justify-content-end">
        <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel', array('class' => 'btn btn-outline-secondary me-2')); ?>
        <?php HtmlUtils::mgrButton('BTN.SAVE', 'save', array('class' => 'btn btn-success')); ?>
    </div>
</div>

<?php HtmlUtils::mgrFormClose(); ?>
