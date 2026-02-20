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

$Contact = $this->getData();
$theAction = $this->getVar('action');
$states = Utils::getStates();

?>
<h1 class="h3 mb-3">
    <a href="admin.php?com=console" class="text-muted text-decoration-none"><?php __('COM.CONSOLE', 'Dashboard'); ?></a>
    <span class="text-muted">/</span>
    <a href="admin.php?com=collections" class="text-muted text-decoration-none"><?php __('COM.COLLECTIONS', 'Collections'); ?></a>
    <span class="text-muted">/</span>
    <a href="admin.php?com=contacts" class="text-muted text-decoration-none"><?php __('COM.CONTACTS', 'Contacts'); ?></a>
    <span class="text-muted">/</span>
    <?php __("GLOBAL.{$theAction}", ucwords($theAction)); ?>
</h1>

<?php echo HtmlUtils::formatMessage($this->getMessage()); ?>

<?php HtmlUtils::mgrFormOpen($this->getVar('com')); ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><?php echo $Contact->getName() ?: __('CONTACTS.NEW', 'New Contact', 1); ?></h5>
        <div>
            <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel', array('class' => 'btn btn-outline-secondary me-2')); ?>
            <?php HtmlUtils::mgrButton('BTN.SAVE', 'save', array('class' => 'btn btn-success')); ?>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="id" class="form-label"><?php __('CONTACTS.ID', 'Item ID'); ?></label>
                <input type="text" class="form-control" value="<?php echo $Contact->getId(); ?>" disabled readonly />
                <input id="id" type="hidden" name="id" value="<?php echo $Contact->getId(); ?>" />
            </div>
            <div class="col-md-4">
                <label for="name" class="form-label"><?php __('CONTACTS.NAME', 'Name'); ?></label>
                <input class="form-control" type="text" name="name" value="<?php echo $Contact->getName(); ?>" id="name" />
            </div>
            <div class="col-md-4">
                <label for="title" class="form-label"><?php __('CONTACTS.TITLE', 'Title'); ?></label>
                <input class="form-control" type="text" name="title" value="<?php echo $Contact->getTitle(); ?>" id="title" />
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="email" class="form-label"><?php __('CONTACTS.EMAIL', 'Email'); ?></label>
                <input class="form-control" type="email" name="email" value="<?php echo $Contact->getEmail(); ?>" id="email" />
            </div>
            <div class="col-md-4">
                <label for="phone" class="form-label"><?php __('CONTACTS.PHONE', 'Phone'); ?></label>
                <input class="form-control" type="text" name="phone" value="<?php echo $Contact->getPhone(); ?>" id="phone" />
            </div>
            <div class="col-md-4">
                <label for="fax" class="form-label"><?php __('CONTACTS.FAX', 'Fax'); ?></label>
                <input class="form-control" type="text" name="fax" value="<?php echo $Contact->getFax(); ?>" id="fax" />
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <label for="address" class="form-label"><?php __('CONTACTS.ADDRESS', 'Address'); ?></label>
                <input class="form-control" type="text" name="address" value="<?php echo $Contact->getAddress(); ?>" id="address" />
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="city" class="form-label"><?php __('CONTACTS.CITY', 'City'); ?></label>
                <input class="form-control" type="text" name="city" value="<?php echo $Contact->getCity(); ?>" id="city" />
            </div>
            <div class="col-md-4">
                <label for="state" class="form-label"><?php __('CONTACTS.STATE', 'State'); ?></label>
                <?php $count = count($states); ?>
                <select name="state" id="state" class="form-select">
                <?php for ($i=0; $i<$count; $i++) : ?>
                    <?php $selected = strtolower($states[$i]) == strtolower($Contact->getState()) ? " selected=\"selected\"" : "" ?>
                    <option value="<?php echo $states[$i]; ?>"<?php echo $selected; ?>><?php echo ucwords($states[$i]); ?></option>
                <?php endfor;?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="zip" class="form-label"><?php __('CONTACTS.ZIP', 'Zip'); ?></label>
                <input class="form-control" type="text" name="zip" value="<?php echo $Contact->getZip(); ?>" id="zip" />
            </div>
        </div>

        <input type="hidden" name="is_new" value="<?php echo $this->getVar('is_new'); ?>" />
    </div>
    <div class="card-footer d-flex justify-content-end">
        <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel', array('class' => 'btn btn-outline-secondary me-2')); ?>
        <?php HtmlUtils::mgrButton('BTN.SAVE', 'save', array('class' => 'btn btn-success')); ?>
    </div>
</div>

<?php HtmlUtils::mgrFormClose(); ?>
