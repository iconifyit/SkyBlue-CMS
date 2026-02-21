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

$Item = $this->getData();
$theAction = $this->getVar('action');

?>
<h1 class="h3 mb-3">
    <a href="admin.php?com=console" class="text-muted text-decoration-none"><?php __('COM.CONSOLE', 'Dashboard'); ?></a>
    <span class="text-muted">/</span>
    <a href="admin.php?com=collections" class="text-muted text-decoration-none"><?php __('COM.COLLECTIONS', 'Collections'); ?></a>
    <span class="text-muted">/</span>
    <a href="admin.php?com=plugin" class="text-muted text-decoration-none"><?php __('COM.PLUGINS', 'Plugins'); ?></a>
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
            <?php HtmlUtils::mgrButton('BTN.APPLY', 'apply', array('class' => 'btn btn-primary me-2')); ?>
            <?php HtmlUtils::mgrButton('BTN.SAVE', 'save', array('class' => 'btn btn-success')); ?>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="id" class="form-label"><?php __('GLOBAL.ID', 'Plugin ID'); ?></label>
                <input type="text" class="form-control" value="<?php echo $Item->getId(); ?>" disabled readonly />
                <input id="id" type="hidden" name="id" value="<?php echo $Item->getId(); ?>" />
            </div>
            <div class="col-md-4">
                <label for="name" class="form-label"><?php __('GLOBAL.NAME', 'Name'); ?></label>
                <input class="form-control" type="text" name="name" value="<?php echo $Item->getName(); ?>" id="name" />
            </div>
            <div class="col-md-4">
                <label for="published" class="form-label"><?php __('GLOBAL.PUBLISHED', 'Published'); ?></label>
                <?php echo HtmlUtils::yesNoList('published', $Item->getPublished()); ?>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <label for="pluginContent" class="form-label"><?php __('PLUGIN.CONTENT', 'Plugin Code'); ?></label>
                <textarea class="form-control font-monospace" name="content" id="pluginContent" rows="15"><?php echo $Item->getContent(); ?></textarea>
                <input type="hidden" name="datafile" value="<?php echo $Item->getDataFile(); ?>" />
            </div>
        </div>

        <input type="hidden" name="isCheckedOut" value="0" />
        <input type="hidden" name="checkedOutBy" value="" />
        <input type="hidden" name="checkedOutDate" value="" />
        <input type="hidden" name="is_new" value="<?php echo $this->getVar('is_new'); ?>" />
    </div>
    <div class="card-footer d-flex justify-content-end">
        <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel', array('class' => 'btn btn-outline-secondary me-2')); ?>
        <?php HtmlUtils::mgrButton('BTN.APPLY', 'apply', array('class' => 'btn btn-primary me-2')); ?>
        <?php HtmlUtils::mgrButton('BTN.SAVE', 'save', array('class' => 'btn btn-success')); ?>
    </div>
</div>

<?php HtmlUtils::mgrFormClose(); ?>
