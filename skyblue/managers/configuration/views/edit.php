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
$states = Utils::getStates();

?>
<div class="jquery_tab">
    <div class="content">
        <h2>
            <a href="admin.php?com=console"><?php __('COM.CONSOLE', 'Dashboard'); ?></a> /
            <a href="admin.php?com=settings"><?php __('COM.SETTINGS', 'Settings'); ?></a> / 
            <?php __('COM.CONFIGURATION', 'Configuration'); ?>
        </h2>
        
        <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>
        
        <?php HtmlUtils::mgrFormOpen($this->getVar('com')); ?>

        <p class="buttons-top">
            <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel'); ?>
            <?php HtmlUtils::mgrButton('BTN.SAVE', 'save'); ?>
        </p>
        
        <div id="tabs">
            <ul>
                <li><a href="#tab-one"><?php __('CONFIGURATION.SITEINFO', 'Site Info'); ?></a></li>
                <li><a href="#tab-two"><?php __('CONFIGURATION.ADMINCONTACT', 'Admin Contact'); ?></a></li>
            </ul>
            
            <input id="id" type="hidden" name="id" value="<?php echo $Item->getId(); ?>" />
            
            <!-- First Tab -->
            <div class="tab-body" id="tab-one">
                <fieldset class="three-column">
                    <div class="column">
                        <label for="site_name"><?php __('CONFIG.SITE_NAME', 'Name'); ?>:</label>
                        <input class="input-medium" type="text" name="site_name" value="<?php echo $Item->getSite_name(); ?>" id="site_name" />
                    </div>
                    <div class="column">
                        <label for="site_slogan"><?php __('CONFIG.SLOGAN', 'Site Tagline'); ?>:</label>
                        <input class="input-medium" type="text" name="site_slogan" value="<?php echo $Item->getSite_slogan(); ?>" id="site_slogan" />
                    </div>
                    <div class="column">
                        <label for="site_url"><?php __('CONFIG.URL', 'Site URL'); ?>:</label>
                        <input class="input-medium" type="text" name="site_url" value="<?php echo $Item->getSite_url(); ?>" id="site_url" />
                    </div>
                    <div class="clear"></div>
                </fieldset>
                
                <fieldset class="three-column">
                    <div class="column">
                        <label for="site_editor"><?php __('CONFIG.EDITOR', 'HTML Editor'); ?>:</label>
                        <?php echo ConfigurationHelper::getEditorSelector($Item->getSite_editor()); ?>
                    </div>
                    <div class="column">
                        <label for="ui_theme"><?php __('CONFIG.ADMIN_THEME', 'Admin Theme'); ?>:</label>
                        <?php echo ConfigurationHelper::getUiThemeSelector($Item->getUi_theme()); ?>
                    </div>
                    <div class="column">
                        <label for="site_lang"><?php __('CONFIG.LANG', 'Language'); ?>:</label>
                        <?php echo ConfigurationHelper::getLanguageSelector($Item->getSite_lang()); ?>
                    </div>
                    <div class="clear"></div>
                </fieldset>
                
                <fieldset class="three-column last">
                    <div class="column">
                        <label for="sef_urls"><?php __('CONFIG.USE_CACHE', 'Enable Page Caching?'); ?>:</label>
                        <?php echo HtmlUtils::yesNoList('use_cache', $Item->getUse_cache()); ?>
                        &nbsp;<?php HtmlUtils::mgrActionLink('CONFIG.BTN.CLEAR_CACHE', 'admin.php?com=configuration&action=clear_cache'); ?>
                    </div>
                    <div class="column">
                        <label for="sef_urls"><?php __('CONFIG.USE_SEF', 'User SEF URLs?'); ?>:</label>
                        <?php echo HtmlUtils::yesNoList('sef_urls', $Item->getSef_urls()); ?>
                        <p><br /><em><?php __('CONFIG.USE_SEF_REQUIRED', 'Requires mod_rewrite Apache module'); ?></em></p>
                    </div>
                    <div class="clear"></div>
                </fieldset>
            </div>
            
            <div class="tab-body" id="tab-two">
                <fieldset class="three-column">
                    <div class="column">
                        <label for="contact_name"><?php __('CONFIG.CONTACT_NAME', 'Name'); ?>:</label>
                        <input class="input-medium" type="text" name="contact_name" value="<?php echo $Item->getContact_name(); ?>" id="contact_name" />
                    </div>
                    <div class="column">
                        <label for="contact_title"><?php __('CONFIG.CONTACT_TITLE', 'Title'); ?>:</label>
                        <input class="input-medium" type="text" name="contact_title" value="<?php echo $Item->getContact_title(); ?>" id="contact_title" />
                    </div>
                    <div class="clear"></div>
                </fieldset>
                
                <fieldset class="three-column">
                    <div class="column">
                        <label for="contact_address"><?php __('CONFIG.ADDRESS1', 'Address'); ?>:</label>
                        <input class="input-medium" type="text" name="contact_address" value="<?php echo $Item->getContact_address(); ?>" id="contact_address" />
                    </div>
                    <div class="column">
                        <label for="contact_address_2"><?php __('CONFIG.ADDRESS2', 'Address 2'); ?>:</label>
                        <input class="input-medium" type="text" name="contact_address_2" value="<?php echo $Item->getContact_address_2(); ?>" id="contact_address_2" />
                    </div>
                    <div class="clear"></div>
                </fieldset>
                
                <fieldset class="three-column">
                    <div class="column">
                        <label for="contact_city"><?php __('CONFIG.CITY', 'City'); ?>:</label>
                        <input class="input-medium" type="text" name="contact_city" value="<?php echo $Item->getContact_city(); ?>" id="contact_city" />
                    </div>
                    <div class="column">
                        <label for="site_url"><?php __('CONFIG.STATE', 'State'); ?>:</label>
                        <?php $count = count($states); ?>
                        <select name="contact_state" id="contact_state">
                        <option value=""><?php ' -- ' . __('GLOBAL.CHOOSE', 'Choose') . ' -- '; ?></option>
                        <?php for ($i=0; $i<$count; $i++) : ?>
                            <?php $selected = strtolower($states[$i]) == strtolower($Item->getContact_state()) ? " selected=\"selected\"" : "" ?>
                            <option value="<?php echo $states[$i]; ?>"<?php echo $selected; ?>><?php echo ucwords($states[$i]); ?></option>
                        <?php endfor;?>
                        </select>
                    </div>
                    <div class="column">
                        <label for="contact_zip"><?php __('CONFIG.POSTAL_CODE', 'Zip Code'); ?>:</label>
                        <input class="input-medium" type="text" name="contact_zip" value="<?php echo $Item->getContact_zip(); ?>" id="contact_zip" />
                    </div>
                    <div class="clear"></div>
                </fieldset>
                
                <fieldset class="three-column last">
                    <div class="column">
                        <label for="contact_email"><?php __('CONFIG.EMAIL', 'Email'); ?>:</label>
                        <input class="input-medium" type="text" name="contact_email" value="<?php echo $Item->getContact_email(); ?>" id="contact_email" />
                    </div>
                    <div class="column">
                        <label for="contact_phone"><?php __('CONFIG.PHONE', 'Phone'); ?>:</label>
                        <input class="input-medium" type="text" name="contact_phone" value="<?php echo $Item->getContact_phone(); ?>" id="contact_phone" />
                    </div>
                    <div class="column">
                        <label for="contact_fax"><?php __('CONFIG.FAX', 'Fax'); ?>:</label>
                        <input class="input-medium" type="text" name="contact_fax" value="<?php echo $Item->getContact_fax(); ?>" id="contact_fax" />
                    </div>
                    <div class="clear"></div>
                </fieldset>
            </div>
        </div>
        <p class="buttons-bottom">
            <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel'); ?>
            <?php HtmlUtils::mgrButton('BTN.SAVE', 'save'); ?>
        </p>    

        <?php HtmlUtils::mgrFormClose(); ?>
    </div>
</div>