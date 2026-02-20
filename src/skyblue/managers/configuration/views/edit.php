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

$Item = $this->getData();
$theAction = $this->getVar('action');
$states = Utils::getStates();

?>
<h1 class="h3 mb-3">
    <a href="admin.php?com=console" class="text-muted text-decoration-none"><?php __('COM.CONSOLE', 'Dashboard'); ?></a>
    <span class="text-muted">/</span>
    <a href="admin.php?com=settings" class="text-muted text-decoration-none"><?php __('COM.SETTINGS', 'Settings'); ?></a>
    <span class="text-muted">/</span>
    <?php __('COM.CONFIGURATION', 'Configuration'); ?>
</h1>

<?php echo HtmlUtils::formatMessage($this->getMessage()); ?>

<?php HtmlUtils::mgrFormOpen($this->getVar('com')); ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><?php __('COM.CONFIGURATION', 'Configuration'); ?></h5>
        <div>
            <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel', array('class' => 'btn btn-outline-secondary me-2')); ?>
            <?php HtmlUtils::mgrButton('BTN.SAVE', 'save', array('class' => 'btn btn-success')); ?>
        </div>
    </div>
    <div class="card-body">
        <input id="id" type="hidden" name="id" value="<?php echo $Item->getId(); ?>" />

        <!-- Bootstrap Nav Tabs -->
        <ul class="nav nav-tabs mb-3" id="configTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="siteinfo-tab" data-bs-toggle="tab" data-bs-target="#siteinfo-panel" type="button" role="tab" aria-controls="siteinfo-panel" aria-selected="true">
                    <?php __('CONFIGURATION.SITEINFO', 'Site Info'); ?>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-panel" type="button" role="tab" aria-controls="contact-panel" aria-selected="false">
                    <?php __('CONFIGURATION.ADMINCONTACT', 'Admin Contact'); ?>
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="configTabsContent">
            <!-- Site Info Tab -->
            <div class="tab-pane fade show active" id="siteinfo-panel" role="tabpanel" aria-labelledby="siteinfo-tab">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="site_name" class="form-label"><?php __('CONFIG.SITE_NAME', 'Name'); ?></label>
                        <input class="form-control" type="text" name="site_name" value="<?php echo $Item->getSite_name(); ?>" id="site_name" />
                    </div>
                    <div class="col-md-4">
                        <label for="site_slogan" class="form-label"><?php __('CONFIG.SLOGAN', 'Site Tagline'); ?></label>
                        <input class="form-control" type="text" name="site_slogan" value="<?php echo $Item->getSite_slogan(); ?>" id="site_slogan" />
                    </div>
                    <div class="col-md-4">
                        <label for="site_url" class="form-label"><?php __('CONFIG.URL', 'Site URL'); ?></label>
                        <input class="form-control" type="text" name="site_url" value="<?php echo $Item->getSite_url(); ?>" id="site_url" />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="site_editor" class="form-label"><?php __('CONFIG.EDITOR', 'HTML Editor'); ?></label>
                        <?php echo ConfigurationHelper::getEditorSelector($Item->getSite_editor()); ?>
                    </div>
                    <div class="col-md-4">
                        <label for="ui_theme" class="form-label"><?php __('CONFIG.ADMIN_THEME', 'Admin Theme'); ?></label>
                        <?php echo ConfigurationHelper::getUiThemeSelector($Item->getUi_theme()); ?>
                    </div>
                    <div class="col-md-4">
                        <label for="site_lang" class="form-label"><?php __('CONFIG.LANG', 'Language'); ?></label>
                        <?php echo ConfigurationHelper::getLanguageSelector($Item->getSite_lang()); ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="use_cache" class="form-label"><?php __('CONFIG.USE_CACHE', 'Enable Page Caching?'); ?></label>
                        <div class="d-flex align-items-center">
                            <?php echo HtmlUtils::yesNoList('use_cache', $Item->getUse_cache()); ?>
                            <a href="admin.php?com=configuration&action=clear_cache" class="btn btn-sm btn-outline-secondary ms-2">
                                <?php __('CONFIG.BTN.CLEAR_CACHE', 'Clear Cache'); ?>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="sef_urls" class="form-label"><?php __('CONFIG.USE_SEF', 'User SEF URLs?'); ?></label>
                        <?php echo HtmlUtils::yesNoList('sef_urls', $Item->getSef_urls()); ?>
                        <small class="text-muted d-block mt-1"><?php __('CONFIG.USE_SEF_REQUIRED', 'Requires mod_rewrite Apache module'); ?></small>
                    </div>
                </div>
            </div>

            <!-- Admin Contact Tab -->
            <div class="tab-pane fade" id="contact-panel" role="tabpanel" aria-labelledby="contact-tab">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="contact_name" class="form-label"><?php __('CONFIG.CONTACT_NAME', 'Name'); ?></label>
                        <input class="form-control" type="text" name="contact_name" value="<?php echo $Item->getContact_name(); ?>" id="contact_name" />
                    </div>
                    <div class="col-md-6">
                        <label for="contact_title" class="form-label"><?php __('CONFIG.CONTACT_TITLE', 'Title'); ?></label>
                        <input class="form-control" type="text" name="contact_title" value="<?php echo $Item->getContact_title(); ?>" id="contact_title" />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="contact_address" class="form-label"><?php __('CONFIG.ADDRESS1', 'Address'); ?></label>
                        <input class="form-control" type="text" name="contact_address" value="<?php echo $Item->getContact_address(); ?>" id="contact_address" />
                    </div>
                    <div class="col-md-6">
                        <label for="contact_address_2" class="form-label"><?php __('CONFIG.ADDRESS2', 'Address 2'); ?></label>
                        <input class="form-control" type="text" name="contact_address_2" value="<?php echo $Item->getContact_address_2(); ?>" id="contact_address_2" />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="contact_city" class="form-label"><?php __('CONFIG.CITY', 'City'); ?></label>
                        <input class="form-control" type="text" name="contact_city" value="<?php echo $Item->getContact_city(); ?>" id="contact_city" />
                    </div>
                    <div class="col-md-4">
                        <label for="contact_state" class="form-label"><?php __('CONFIG.STATE', 'State'); ?></label>
                        <?php $count = count($states); ?>
                        <select name="contact_state" id="contact_state" class="form-select">
                            <option value=""><?php __('GLOBAL.CHOOSE', ' -- Choose -- '); ?></option>
                            <?php for ($i=0; $i<$count; $i++) : ?>
                                <?php $selected = strtolower($states[$i]) == strtolower($Item->getContact_state()) ? " selected=\"selected\"" : "" ?>
                                <option value="<?php echo $states[$i]; ?>"<?php echo $selected; ?>><?php echo ucwords($states[$i]); ?></option>
                            <?php endfor;?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="contact_zip" class="form-label"><?php __('CONFIG.POSTAL_CODE', 'Zip Code'); ?></label>
                        <input class="form-control" type="text" name="contact_zip" value="<?php echo $Item->getContact_zip(); ?>" id="contact_zip" />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="contact_email" class="form-label"><?php __('CONFIG.EMAIL', 'Email'); ?></label>
                        <input class="form-control" type="email" name="contact_email" value="<?php echo $Item->getContact_email(); ?>" id="contact_email" />
                    </div>
                    <div class="col-md-4">
                        <label for="contact_phone" class="form-label"><?php __('CONFIG.PHONE', 'Phone'); ?></label>
                        <input class="form-control" type="text" name="contact_phone" value="<?php echo $Item->getContact_phone(); ?>" id="contact_phone" />
                    </div>
                    <div class="col-md-4">
                        <label for="contact_fax" class="form-label"><?php __('CONFIG.FAX', 'Fax'); ?></label>
                        <input class="form-control" type="text" name="contact_fax" value="<?php echo $Item->getContact_fax(); ?>" id="contact_fax" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-end">
        <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel', array('class' => 'btn btn-outline-secondary me-2')); ?>
        <?php HtmlUtils::mgrButton('BTN.SAVE', 'save', array('class' => 'btn btn-success')); ?>
    </div>
</div>

<?php HtmlUtils::mgrFormClose(); ?>
