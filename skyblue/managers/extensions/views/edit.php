<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version        2.0 2010-01-30 00:00:01 $
 * @package        SkyBlueCanvas
 * @copyright      Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license        GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

/**
 * @author Scott Lewis
 * @date   June 20, 2009
 */

add_head_element('jquery.cookie');
add_head_element('jquery.utils');
add_head_element('page.onload', TABS_JS);
add_head_element('page.style',  TABS_CSS);

$theAction = $this->getVar('action');
$groups = $this->getVar('groups');

$Bean = $this->getData();

$context    = $Bean->getContext(); // $this->getVar('context');
$content    = $Bean->getContent(); // $this->getVar('content');
$itemName   = $Bean->getName();    // $this->getVar('name');
$itemId     = $Bean->getId();
$is_new     = $this->getVar('is_new');

$saveBtnAction = "save_{$context}_config";
?>
<div class="jquery_tab">
    <div class="content">
        <h2>
            <a href="admin.php?com=console"><?php __('COM.CONSOLE', 'Dashboard'); ?></a> /
            <a href="admin.php?com=settings"><?php __('COM.SETTINGS', 'Settings'); ?></a> / 
            <?php __("EXTENSIONS.".strtoupper($theAction), ucwords($theAction)); ?> / 
            <?php echo ucwords($itemName); ?>
        </h2>
        
        <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>
        
        <?php HtmlUtils::mgrFormOpen($this->getVar('com')); ?>

        <p class="buttons-top">
            <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel'); ?>
            <?php HtmlUtils::mgrButton('BTN.SAVE', $saveBtnAction); ?>
        </p>
        <div id="tabs">
            <ul>
                <li><a href="#tab-one"><?php echo ucwords($itemName); ?></a></li>
            </ul>
            <!-- First Tab -->
            <div class="tab-body" id="tab-one">
                <?php 
                    $configString = "";
                    $count=10;
                    if (!empty($itemConfig)) {
                        foreach ($itemConfig as $key=>$value) {
                            $count++;
                            if (!is_array($value)) {
                                $configString .= "{$key}={$value}\n";
                            }
                            else {
                                for ($x=0; $x<count($value); $x++) {
                                    $configString .= "{$key}[]={$value[$x]}\n";
                                }
                            }
                        }
                    } 
                ?>
                <textarea name="content" class="auto" rows="<?php echo $count; ?>"><?php echo $content; ?></textarea>
            </div>
        </div>
        <p class="buttons-bottom">
            <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel'); ?>
            <?php HtmlUtils::mgrButton('BTN.SAVE', $saveBtnAction); ?>
        </p>    
        <input type="hidden" name="name" value="<?php echo $itemName; ?>" />
        <input type="hidden" name="context" value="<?php echo $context; ?>" />
        <input type="hidden" name="id" value="<?php echo $itemId; ?>" />
        <input type="hidden" name="is_new" value="<?php echo $is_new; ?>" />
        <?php HtmlUtils::mgrFormClose(); ?>
    </div>
</div>