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
    <a href="admin.php?com=settings" class="text-muted text-decoration-none"><?php __('COM.SETTINGS', 'Settings'); ?></a>
    <span class="text-muted">/</span>
    <a href="admin.php?com=users&action=listgroups" class="text-muted text-decoration-none"><?php __('COM.USERS.GROUPS', 'User Groups'); ?></a>
    <span class="text-muted">/</span>
    <?php __("GLOBAL.{$theAction}", ucwords($theAction)); ?>
</h1>

<?php echo HtmlUtils::formatMessage($this->getMessage()); ?>

<?php HtmlUtils::mgrFormOpen($this->getVar('com')); ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><?php echo $Item->getName() ?: __('USERS.NEW_GROUP', 'New Group', 1); ?></h5>
        <div>
            <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel', array('class' => 'btn btn-outline-secondary me-2')); ?>
            <?php HtmlUtils::mgrButton('BTN.SAVE', 'save_group', array('class' => 'btn btn-success')); ?>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="id" class="form-label"><?php __('USERS.ID', 'Item ID'); ?></label>
                <input type="text" class="form-control" value="<?php echo $Item->getId(); ?>" disabled readonly />
                <input id="id" type="hidden" name="id" value="<?php echo $Item->getId(); ?>" />
            </div>
            <div class="col-md-4">
                <label for="name" class="form-label"><?php __('USERS.NAME', 'Name'); ?></label>
                <input class="form-control" type="text" name="name" value="<?php echo $Item->getName(); ?>" id="name" />
            </div>
            <div class="col-md-4">
                <label for="siteadmin" class="form-label"><?php __('USERS.SITEADMIN', 'Site Admin?'); ?></label>
                <?php echo HtmlUtils::yesNoList('siteadmin', $Item->getSiteadmin()); ?>
            </div>
        </div>

        <input type="hidden" name="is_new" value="<?php echo $this->getVar('is_new'); ?>" />
    </div>
    <div class="card-footer d-flex justify-content-end">
        <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel', array('class' => 'btn btn-outline-secondary me-2')); ?>
        <?php HtmlUtils::mgrButton('BTN.SAVE', 'save_group', array('class' => 'btn btn-success')); ?>
    </div>
</div>

<?php HtmlUtils::mgrFormClose(); ?>
