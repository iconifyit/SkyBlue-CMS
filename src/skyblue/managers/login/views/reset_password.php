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

$Item = $this->getData();
$theAction = $this->getVar('action', __('GLOBAL.LOGIN', 'Sign In', 1));
$username = $this->getVar('username');
$tempkey = $this->getVar('tempkey');

?>
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 col-lg-4">
            <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><?php __("LOGIN.NEW_PASSWORD", "New Password"); ?></h5>
                </div>
                <div class="card-body">
                    <?php HtmlUtils::mgrFormOpen($this->getVar('com')); ?>

                    <p class="text-muted mb-3"><?php __("LOGIN.NEW_PASSWORD_INSTRUCTIONS", "Enter your new password below."); ?></p>

                    <div class="mb-3">
                        <label for="password" class="form-label"><?php __('GLOBAL.PASSWORD', 'Password'); ?></label>
                        <input class="form-control" type="password" name="password" value="" id="password" />
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label"><?php __('GLOBAL.CONFIRM_PASSWORD', 'Confirm Password'); ?></label>
                        <input class="form-control" type="password" name="confirm_password" value="" id="confirm_password" />
                    </div>

                    <div class="d-grid">
                        <?php HtmlUtils::mgrButton('BTN.SUBMIT', 'update_password', array('class' => 'btn btn-primary')); ?>
                    </div>

                    <input type="hidden" name="username" value="<?php echo $username; ?>" />
                    <input type="hidden" name="tempkey" value="<?php echo $tempkey; ?>" />

                    <?php HtmlUtils::mgrFormClose(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
