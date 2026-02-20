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

?>
<h1 class="h3 mb-3">
    <a href="admin.php?com=console" class="text-muted text-decoration-none"><?php __('COM.CONSOLE', 'Dashboard'); ?></a>
    <span class="text-muted">/</span>
    <?php __('COM.HELP', 'Help'); ?>
</h1>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0"><?php __('HELP.EDIT', 'Help Editor'); ?></h5>
    </div>
    <div class="card-body">
        <div class="alert alert-info" role="alert">
            <i data-feather="info" class="me-2"></i>
            <?php __('HELP.NOT_IMPLEMENTED', 'The help editor is not yet implemented.'); ?>
        </div>
    </div>
</div>
