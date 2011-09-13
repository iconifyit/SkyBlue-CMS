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

$Contact = $this->getData();
$theAction = $this->getVar('action');
$states = Utils::getStates();

?>
<div class="jquery_tab">
    <div class="content">
        <h2>
            <a href="admin.php?com=contacts"><?php __('COM.CONTACTS', 'Contacts'); ?></a> / 
            <?php __("GLOBAL.{$theAction}", ucwords($theAction)); ?> / 
            <?php echo $Contact->getName(); ?>
        </h2>

        <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>
        
        <?php HtmlUtils::mgrFormOpen($this->getVar('com')); ?>

        <p class="buttons-top">
            <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel'); ?>
            <?php HtmlUtils::mgrButton('BTN.SAVE', 'save'); ?>
        </p>
        
        <div id="tabs">
            <ul>
                <li><a href="#tab-one"><?php echo $Contact->getName(); ?></a></li>
            </ul>
            
            <!-- First Tab -->
            <div class="tab-body" id="tab-one">
            
                <fieldset class="three-column">
                    <div class="column">
                        <label for="id"><?php __('CONTACTS.ID', 'Item ID'); ?>:</label> <?php echo $Contact->getId(); ?>
                        <input id="id" type="hidden" name="id" value="<?php echo $Contact->getId(); ?>" />
                    </div>
                    <div class="column">
                        <label for="name"><?php __('CONTACTS.NAME', 'Name'); ?>:</label>
                        <input class="input-medium" type="text" name="name" value="<?php echo $Contact->getName(); ?>" id="name" />
                    </div>
                    <div class="column">
                        <label for="name"><?php __('CONTACTS.TITLE', 'Title'); ?>:</label>
                        <input class="input-medium" type="text" name="title" value="<?php echo $Contact->getTitle(); ?>" id="title" />
                    </div>
                    <div class="clear"></div>
                </fieldset>
                
                <fieldset class="three-column">
                    <div class="column">
                        <label for="email"><?php __('CONTACTS.EMAIL', 'Email'); ?>:</label>
                        <input class="input-medium" type="text" name="email" value="<?php echo $Contact->getEmail(); ?>" id="email" />
                    </div>
                    <div class="column">
                        <label for="name"><?php __('CONTACTS.PHONE', 'Phone'); ?>:</label>
                        <input class="input-medium" type="text" name="phone" value="<?php echo $Contact->getPhone(); ?>" id="phone" />
                    </div>
                    <div class="column">
                        <label for="fax"><?php __('CONTACTS.FAX', 'Fax'); ?>:</label>
                        <input class="input-medium" type="text" name="fax" value="<?php echo $Contact->getFax(); ?>" id="fax" />
                    </div>
                    <div class="clear"></div>
                </fieldset>
                
                <fieldset class="one-column">
                    <div class="column">
                        <label for="address"><?php __('CONTACTS.ADDRESS', 'Address'); ?>:</label>
                        <input class="input-big" type="text" name="address" value="<?php echo $Contact->getAddress(); ?>" id="address" />
                    </div>
                </fieldset>
                <fieldset class="three-column">
                    <div class="column">
                        <label for="city"><?php __('CONTACTS.CITY', 'City'); ?>:</label>
                        <input class="input-medium" type="text" name="city" value="<?php echo $Contact->getCity(); ?>" id="city" />
                    </div>
                    <div class="column">
                        <label for="state"><?php __('CONTACTS.STATE', 'State'); ?>:</label>
                        <?php $count = count($states); ?>
                        <select name="state" id="state">
                        <?php for ($i=0; $i<$count; $i++) : ?>
                            <?php $selected = strtolower($states[$i]) == strtolower($Contact->getState()) ? " selected=\"selected\"" : "" ?>
                            <option value="<?php echo $states[$i]; ?>"<?php echo $selected; ?>><?php echo ucwords($states[$i]); ?></option>
                        <?php endfor;?>
                        </select>
                    </div>
                    <div class="column">
                        <label for="zip"><?php __('CONTACTS.ZIP', 'Zip'); ?>:</label>
                        <input class="input-medium" type="text" name="zip" value="<?php echo $Contact->getZip(); ?>" id="zip" />
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