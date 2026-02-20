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

$theAction = $this->getVar('action');
$groups = $this->getVar('groups');

$Bean = $this->getData();

$context    = $Bean->getContext();
$content    = $Bean->getContent();
$itemName   = $Bean->getName();
$itemId     = $Bean->getId();
$is_new     = $this->getVar('is_new');

$saveBtnAction = "save_{$context}_config";
?>
<h1 class="h3 mb-3">
    <a href="admin.php?com=console" class="text-muted text-decoration-none"><?php __('COM.CONSOLE', 'Dashboard'); ?></a>
    <span class="text-muted">/</span>
    <a href="admin.php?com=settings" class="text-muted text-decoration-none"><?php __('COM.SETTINGS', 'Settings'); ?></a>
    <span class="text-muted">/</span>
    <a href="admin.php?com=extensions" class="text-muted text-decoration-none"><?php __('COM.EXTENSIONS', 'Extensions'); ?></a>
    <span class="text-muted">/</span>
    <?php echo ucwords($itemName); ?>
</h1>

<?php echo HtmlUtils::formatMessage($this->getMessage()); ?>

<?php if ($context === 'skins'): ?>
<div class="alert alert-info d-flex align-items-center mb-3" role="alert">
    <i data-feather="info" class="me-2"></i>
    <div>
        <strong>Notice:</strong> The skin configuration editor is not yet implemented.
    </div>
</div>
<?php endif; ?>

<?php HtmlUtils::mgrFormOpen($this->getVar('com')); ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><?php echo ucwords($itemName); ?> Configuration</h5>
        <div>
            <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel', array('class' => 'btn btn-outline-secondary me-2')); ?>
            <?php HtmlUtils::mgrButton('BTN.SAVE', $saveBtnAction, array('class' => 'btn btn-success')); ?>
        </div>
    </div>
    <div class="card-body">
        <?php
            $configString = "";
            $count = 15;
            if (!empty($itemConfig)) {
                foreach ($itemConfig as $key => $value) {
                    $count++;
                    if (!is_array($value)) {
                        $configString .= "{$key}={$value}\n";
                    }
                    else {
                        for ($x = 0; $x < count($value); $x++) {
                            $configString .= "{$key}[]={$value[$x]}\n";
                        }
                    }
                }
            }
        ?>
        <div class="mb-3">
            <label for="content" class="form-label"><?php __('EXTENSIONS.CONFIG', 'Configuration'); ?></label>
            <textarea name="content" id="content" class="form-control font-monospace" rows="<?php echo $count; ?>"><?php echo $content; ?></textarea>
        </div>

        <input type="hidden" name="name" value="<?php echo $itemName; ?>" />
        <input type="hidden" name="context" value="<?php echo $context; ?>" />
        <input type="hidden" name="id" value="<?php echo $itemId; ?>" />
        <input type="hidden" name="is_new" value="<?php echo $is_new; ?>" />
    </div>
    <div class="card-footer d-flex justify-content-end">
        <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel', array('class' => 'btn btn-outline-secondary me-2')); ?>
        <?php HtmlUtils::mgrButton('BTN.SAVE', $saveBtnAction, array('class' => 'btn btn-success')); ?>
    </div>
</div>

<?php HtmlUtils::mgrFormClose(); ?>
