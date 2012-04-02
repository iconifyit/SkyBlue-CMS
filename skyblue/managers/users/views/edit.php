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
 * @date   June 20, 2009
 */

add_head_element('jquery.cookie');
add_head_element('jquery.utils');
add_head_element('page.onload', TABS_JS);
add_head_element('page.style',  TABS_CSS);

$Item = $this->getData();
$theAction = $this->getVar('action');

$groups = $this->getVar('groups');

?>
<div class="jquery_tab">
    <div class="content">
        <h2>
            <a href="admin.php?com=users"><?php __('COM.USERS', 'Users'); ?></a> / 
            <?php __("GLOBAL.{$theAction}", ucwords($theAction)); ?> / 
            <?php echo $Item->getUsername(); ?>
        </h2>
        
        <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>
        
        <?php HtmlUtils::mgrFormOpen($this->getVar('com')); ?>

        <p class="buttons-top">
            <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel'); ?>
            <?php HtmlUtils::mgrButton('BTN.APPLY', 'apply'); ?>
            <?php HtmlUtils::mgrButton('BTN.SAVE', 'save'); ?>
        </p>
        
        <div id="tabs">
            <ul>
                <li><a href="#tab-one"><?php echo $Item->getName(); ?></a></li>
            </ul>
            
            <!-- First Tab -->
            <div class="tab-body" id="tab-one">

                <fieldset class="three-column">
                    <div class="column">
                        <label for="id"><?php __('USERS.ID', 'Item ID'); ?>:</label> <?php echo $Item->getId(); ?>
                        <input id="id" type="hidden" name="id" value="<?php echo $Item->getId(); ?>" />
                    </div>
                    <div class="column">
                        <label for="name"><?php __('USERS.NAME', 'Name'); ?>:</label>
                        <input class="input-medium" type="text" name="name" value="<?php echo $Item->getName(); ?>" id="name" />
                    </div>
                    <div class="column">
                        <label for="email"><?php __('USERS.EMAIL', 'Email'); ?>:</label>
                        <input class="input-medium" type="text" name="email" value="<?php echo $Item->getEmail(); ?>" id="email" />
                    </div>
                    <div class="clear"></div>
                </fieldset>
                
                <fieldset class="three-column">
                    <div class="column">
                        <label for="username"><?php __('USERS.USERNAME', 'Username'); ?>:</label>
                        <input class="input-medium" type="text" name="username" value="<?php echo $Item->getUsername(); ?>" id="username" />
                    </div>
                    <div class="column">
                        <label for="password"><?php __('USERS.PASSWORD', 'Password'); ?>:</label>
                        <input class="input-medium" type="password" name="password" value="" id="password" />
                    </div>
                    <div class="column">
                        <label for="confirm-password"><?php __('USERS.CONFIRM_PASSWORD', 'Confirm Password'); ?>:</label>
                        <input class="input-medium" type="password" name="confirm-password" value="" id="confirm-password" />
                    </div>
                    <div class="clear"></div>
                </fieldset>
                
                <fieldset class="three-column last">
                    <div class="column">
                        <label for="groups"><?php __('USERS.GROUPS', 'Groups'); ?>:</label>
                        <ul>
                        <?php $count = count($groups); ?>
                        <?php for ($i=0; $i<$count; $i++) : ?>
                        <?php $checked = UsersHelper::hasGroup($Item, $groups[$i]) ? " checked=\"checked\"" : "" ; ?>
                        <li>
                            <input type="checkbox" name="groups[]" value="<?php echo $groups[$i]->getId();?>"<?php echo $checked;?>/>&nbsp;
                            <?php echo $groups[$i]->getName(); ?>
                        </li>
                        <?php endfor; ?>
                        </ul>
                    </div>
                    <div class="column">
                        <label for="block"><?php __('USERS.BLOCK', 'Block User?'); ?>:</label>
                        <?php echo HtmlUtils::yesNoList('block', $Item->getBlock()); ?>
                        </div>
                    <div class="clear"></div>
                </fieldset>

           </div>
        </div>
        <p class="buttons-bottom">
            <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel'); ?>
            <?php HtmlUtils::mgrButton('BTN.APPLY', 'apply'); ?>
            <?php HtmlUtils::mgrButton('BTN.SAVE', 'save'); ?>
        </p>     
        
        <input type="hidden" name="is_new" value="<?php echo $this->getVar('is_new'); ?>" />

        <?php HtmlUtils::mgrFormClose(); ?>
    </div>
</div>