<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2009-06-20 21:41:00 $
 * @package      SkyBlue CMS
 * @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license      GNU/GPL, see COPYING.txt
 * SkyBlue CMS is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

/**
 * @author Scott Lewis
 * @date   July 9, 2009
 */

$messages = Filter::get($data, 'message', array());
$closable = Filter::get($data, 'closable') ? ' closeable' : '' ;

if (! is_array($messages)) {
    $messages = array($messages);
}

$count = count($messages);
for ($x=0; $x<$count; $x++) {

    $Message = $messages[$x];

    $msgId = "msg-" . mt_rand(0,100);

    // Map message types to Bootstrap alert classes and Feather icons
    switch ($Message->getType()) {
        case UI_STATE_ERROR:
            $alertClass = 'alert-danger';
            $iconName   = 'alert-triangle';
            break;
        case UI_STATE_SUCCESS:
            $alertClass = 'alert-success';
            $iconName   = 'check-circle';
            break;
        case UI_STATE_INFO:
        default:
            $alertClass = 'alert-info';
            $iconName   = 'info';
            break;
    }

?>
<div id="<?php echo $msgId; ?>" class="alert <?php echo $alertClass; ?> alert-dismissible fade show d-flex align-items-start" role="alert">
    <i data-feather="<?php echo $iconName; ?>" class="me-2"></i>
    <div class="flex-grow-1">
        <?php if (is_array($Message->getMessage())) : ?>
            <strong><?php echo $Message->getTitle(); ?></strong>
            <ul class="mb-0 mt-2">
            <?php $strings = $Message->getMessage(); ?>
            <?php $count2 = count($strings); ?>
            <?php for ($i=0; $i<$count2; $i++) : ?>
                <li><?php echo $strings[$i]; ?></li>
            <?php endfor; ?>
            </ul>
        <?php else : ?>
            <strong><?php echo $Message->getTitle(); ?></strong>
            <?php echo $Message->getMessage(); ?>
        <?php endif; ?>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php }?>
