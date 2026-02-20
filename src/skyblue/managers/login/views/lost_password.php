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
$email = $this->getVar('email');

?>
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 col-lg-4">
            <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><?php __("LOGIN.LOST_PASSWORD", "Lost Password"); ?></h5>
                </div>
                <div class="card-body">
                    <?php HtmlUtils::mgrFormOpen($this->getVar('com')); ?>

                    <p class="text-muted mb-3"><?php __("LOGIN.LOST_PASSWORD_INSTRUCTIONS", "Enter your username and email address to receive password reset instructions."); ?></p>

                    <div class="mb-3">
                        <label for="username" class="form-label"><?php __('GLOBAL.USERNAME', 'Username'); ?></label>
                        <input class="form-control" type="text" name="username" value="<?php echo $username; ?>" id="username" />
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label"><?php __('GLOBAL.EMAIL', 'Email'); ?></label>
                        <input class="form-control" type="email" name="email" value="<?php echo $email; ?>" id="email" />
                    </div>

                    <div class="d-grid mb-3">
                        <?php HtmlUtils::mgrButton('BTN.SUBMIT', 'retrieve', array('class' => 'btn btn-primary')); ?>
                    </div>

                    <div class="text-center">
                        <a href="admin.php?com=login"><?php __("LOGIN.BACK_TO_LOGIN", "Back to Login"); ?></a>
                    </div>

                    <?php HtmlUtils::mgrFormClose(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
