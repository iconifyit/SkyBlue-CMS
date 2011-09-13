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
    add_scriptlet(
        'hideMessageBlock', 
        "delayedReaction(\"fadeAndRemove('#$msgId')\", " . SB_DIALOG_TIMEOUT . ")"
    );

    switch ($Message->getType()) {
        case UI_STATE_ERROR:
            $ui_icon_type = UI_ICON_ERROR;
            break;
        case UI_STATE_SUCCESS:
            $ui_icon_type = UI_ICON_SUCCESS;
            break;
        case UI_STATE_INFO:
        default:
            $ui_icon_type = UI_ICON_INFO;
            break;
    }

?>
<div class="ui-widget" id="action-result">
    <div id="<?php echo $msgId; ?>" class="ui-corner-all <?php echo $Message->getType(); ?>" style="margin: 20px 0px; padding: 15px;">
        <?php if (is_array($Message->getMessage())) : ?>
            <p style="padding: 0px;">
                <span class="ui-icon <?php echo $ui_icon_type; ?>" style="float: left; margin: 2px 8px 0px 0px;"></span>
                <strong><?php echo $Message->getTitle(); ?></strong>
            </p>
            <ul class="errorlist">
            <?php $strings = $Message->getMessage(); ?>
            <?php $count2 = count($strings); ?>
            <?php for ($i=0; $i<$count2; $i++) : ?>
                <li><?php echo $strings[$i]; ?></li>
            <?php endfor; ?>
            </ul>
        <?php else : ?>
            <p style="padding: 0px;">
                <span class="ui-icon <?php echo $ui_icon_type; ?>" style="float: left; margin: 2px 8px 0px 0px;"></span>
                <strong><?php echo $Message->getTitle(); ?></strong>
                <?php echo $Message->getMessage(); ?>
            </p>
        <?php endif; ?>
    </div>
</div>
<?php }?>