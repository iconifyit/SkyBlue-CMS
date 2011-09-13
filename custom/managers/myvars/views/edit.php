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
* @date   November 7, 2010
*/

add_head_element('jquery.cookie');
add_head_element('jquery.utils');
add_head_element('page.onload', TABS_JS);
add_head_element('page.style',  TABS_CSS);

$Bean = $this->getData();
$theAction = $this->getVar('action');

?>
<div class="jquery_tab">
    <div class="content">
        <h2>
            <a href="admin.php?com=contacts"><?php __('COM.MYVARS', 'MyVars'); ?></a> / 
            <?php __("GLOBAL.{$theAction}", ucwords($theAction)); ?> / 
            <?php echo $Bean->getName(); ?>
        </h2>

        <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>
        
        <?php HtmlUtils::mgrFormOpen($this->getVar('com')); ?>

        <p class="buttons-top">
            <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel'); ?>
            <?php HtmlUtils::mgrButton('BTN.SAVE', 'save'); ?>
        </p>
        
        <div id="tabs">
            <ul>
                <li><a href="#tab-one"><?php echo $Bean->getName(); ?></a></li>
            </ul>
            
            <!-- First Tab -->
            <div class="tab-body" id="tab-one">
            
                <fieldset class="three-column">
                    <div class="column">
                        <label for="id"><?php __('MYVARS.ID', 'Item ID'); ?>:</label> <?php echo $Bean->getId(); ?>
                        <input id="id" type="hidden" name="id" value="<?php echo $Bean->getId(); ?>" />
                    </div>
                    <div class="clear"></div>
                </fieldset>
                
                <fieldset class="three-column last">
                    <div class="column">
                        <label for="name"><?php __('MYVARS.NAME', 'Name'); ?>:</label>
                        <input class="input-medium" type="text" name="name" value="<?php echo $Bean->getName(); ?>" id="name" />
                    </div>
                    <div class="column">
                        <label for="vartype"><?php __('MYVARS.VARTYPE', 'Type'); ?>:</label>
                        <select name="vartype">
                            <option value=""><?php __('GLOBAL.CHOOSE', ' -- Choose -- '); ?></option>
                            <?php echo MyvarsHelper::getVartypeSelector($Bean->getVartype()); ?>
                        </select>
                    </div>
                    <div class="column">
                        <label for="value"><?php __('MYVARS.VALUE', 'Value'); ?>:</label>
                        <input class="input-medium" type="text" name="value" value="<?php echo $Bean->getValue(); ?>" id="value" />
                    </div>
                    <div class="clear"></div>
                </fieldset>
                <input type="hidden" name="is_new" value="<?php echo $this->getVar('is_new'); ?>" />
            </div>
        </div>
        
        <p class="buttons-bottom">
            <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel'); ?>
            <?php HtmlUtils::mgrButton('BTN.SAVE', 'save'); ?>
        </p>    

        <?php HtmlUtils::mgrFormClose(); ?>
    </div>
</div>