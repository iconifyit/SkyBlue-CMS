<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2009-06-21 13:12:00 $
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

$PageHelper = Singleton::getInstance('PageHelper');
$Page       = $this->getData();
$isNew      = $this->getVar('is_new');

$theAction = $this->getVar('action');
$layouts   = $this->getVar('layouts');
$menus     = $this->getVar('menus');
$parents   = $this->getVar('parents');

add_head_element('jquery.cookie');
add_head_element('jquery.utils');

add_head_element(Config::get('site_editor'), '', 'include');

?>
<h1 class="h3 mb-3">
    <a href="admin.php?com=console" class="text-muted text-decoration-none"><?php __('COM.CONSOLE', 'Dashboard'); ?></a>
    <span class="text-muted">/</span>
    <a href="admin.php?com=page" class="text-muted text-decoration-none"><?php __('COM.PAGES', 'Pages'); ?></a>
    <span class="text-muted">/</span>
    <?php __("GLOBAL.{$theAction}", ucwords($theAction)); ?>
</h1>

<?php echo HtmlUtils::formatMessage($this->getMessage()); ?>

<?php HtmlUtils::mgrFormOpen('page'); ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><?php echo $Page->getName() ?: __('PAGE.NEW', 'New Page', 1); ?></h5>
        <div>
            <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel', array('class' => 'btn btn-outline-secondary me-2')); ?>
            <?php HtmlUtils::mgrButton('BTN.APPLY', 'apply', array('class' => 'btn btn-primary wymupdate me-2')); ?>
            <?php HtmlUtils::mgrButton('BTN.SAVE', 'save', array('class' => 'btn btn-success wymupdate')); ?>
        </div>
    </div>
    <div class="card-body">
        <!-- Bootstrap Nav Tabs -->
        <ul class="nav nav-tabs mb-3" id="pageTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="meta-tab" data-bs-toggle="tab" data-bs-target="#meta-panel" type="button" role="tab" aria-controls="meta-panel" aria-selected="true">
                    <?php __('PAGE.META_TAB', 'Page Info'); ?>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="text-tab" data-bs-toggle="tab" data-bs-target="#text-panel" type="button" role="tab" aria-controls="text-panel" aria-selected="false">
                    <?php __('PAGE.TEXT_TAB', 'Page Text'); ?>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="acl-tab" data-bs-toggle="tab" data-bs-target="#acl-panel" type="button" role="tab" aria-controls="acl-panel" aria-selected="false">
                    <?php __('PAGE.ACL_TAB', 'Page Access'); ?>
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="pageTabsContent">
            <!-- Page Info Tab -->
            <div class="tab-pane fade show active" id="meta-panel" role="tabpanel" aria-labelledby="meta-tab">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="id" class="form-label"><?php __('PAGE.ID', 'Page ID'); ?></label>
                        <input type="text" class="form-control" value="<?php echo $Page->getId(); ?>" disabled readonly />
                        <input id="id" type="hidden" name="id" value="<?php echo $Page->getId(); ?>" />
                    </div>
                    <div class="col-md-4">
                        <label for="name" class="form-label"><?php __('PAGE.NAME', 'Name'); ?></label>
                        <input class="form-control" type="text" name="name" value="<?php echo $Page->getName(); ?>" id="name" />
                    </div>
                    <div class="col-md-4">
                        <label for="title" class="form-label"><?php __('PAGE.TITLE', 'Title'); ?></label>
                        <input class="form-control" type="text" name="title" value="<?php echo $Page->getTitle(); ?>" id="title" />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <?php $permalink = $PageHelper->getPermalink($Page); ?>
                        <label for="permalink" class="form-label"><?php __('PAGE.SEF_URL', 'Search-Friendly URL'); ?></label>
                        <input class="form-control" type="text" name="permalink" value="<?php echo $permalink; ?>" id="permalink" />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label for="meta_description" class="form-label"><?php __('PAGE.DESCRIPTION', 'Description'); ?></label>
                        <textarea class="form-control" name="meta_description" id="meta_description" rows="3"><?php echo $Page->getMeta_description(); ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="pagetype" class="form-label"><?php __('PAGE.LAYOUT', 'Template'); ?></label>
                        <select name="pagetype" id="pagetype" class="form-select">
                            <option value=""> -- <?php __('PAGE.CHOOSE_LAYOUT', 'Choose Template'); ?> -- </option>
                            <?php for ($i=0; $i<count($layouts); $i++) : ?>
                                <?php $selected = $Page->getPagetype() == $layouts[$i] ? ' selected="selected"' : '' ; ?>
                                <option value="<?php echo $layouts[$i]; ?>"<?php echo $selected; ?>><?php echo $layouts[$i]; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="menu" class="form-label"><?php __('PAGE.MENU', 'Menu'); ?></label>
                        <select name="menu" id="menu" class="form-select">
                            <option value=""> -- <?php __('PAGE.CHOOSE_MENU', 'Choose Menu'); ?> -- </option>
                            <?php foreach ($menus as $menu) : ?>
                                <?php $selected = $Page->getMenu() == $menu->id ? ' selected="selected"' : '' ; ?>
                                <option value="<?php echo $menu->id; ?>"<?php echo $selected; ?>><?php echo $menu->title; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="parent" class="form-label"><?php __('PAGE.PARENT', 'Parent'); ?></label>
                        <select name="parent" id="parent" class="form-select">
                            <option value=""> -- <?php __('PAGE.CHOOSE_PARENT', 'Choose Parent'); ?> -- </option>
                            <?php foreach ($parents as $pg) : ?>
                                <?php $selected = $Page->getParent() == $pg->getId() ? ' selected="selected"' : '' ; ?>
                                <option value="<?php echo $pg->getId(); ?>"<?php echo $selected; ?>><?php echo $pg->getName(); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="isdefault" class="form-label"><?php __('PAGE.IS_HOME_PAGE', 'Is this the Home Page?'); ?></label>
                        <?php echo HtmlUtils::yesNoList('isdefault', $Page->getIsdefault()); ?>
                    </div>
                    <div class="col-md-4">
                        <label for="is_error_page" class="form-label"><?php __('PAGE.IS_404_PAGE', 'Is this the 404 Page?'); ?></label>
                        <?php echo HtmlUtils::yesNoList('is_error_page', $Page->getIs_error_page()); ?>
                    </div>
                    <div class="col-md-4">
                        <label for="usesitename" class="form-label"><?php __('PAGE.USE_SITE_NAME', 'Use Site Name in Page Title?'); ?></label>
                        <?php echo HtmlUtils::yesNoList('usesitename', $Page->getUsesitename()); ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="published" class="form-label"><?php __('PAGE.PUBLISHED', 'Published?'); ?></label>
                        <?php echo HtmlUtils::yesNoList('published', $Page->getPublished()); ?>
                    </div>
                    <div class="col-md-4">
                        <label for="show_in_navigation" class="form-label"><?php __('PAGE.INCLUDE_IN_NAV', 'Include In Navigation?'); ?></label>
                        <?php echo HtmlUtils::yesNoList('show_in_navigation', $Page->getShow_in_navigation()); ?>
                    </div>
                    <div class="col-md-4">
                        <label for="syndicate" class="form-label"><?php __('PAGE.SYNDICATE', 'Syndicated?'); ?></label>
                        <?php echo HtmlUtils::yesNoList('syndicate', $Page->getSyndicate()); ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="author" class="form-label"><?php __('PAGE.AUTHOR', 'Author'); ?></label>
                        <?php echo PageHelper::getAuthorSelector($Page->getAuthor()); ?>
                    </div>
                </div>
            </div>

            <!-- Page Text Tab -->
            <div class="tab-pane fade" id="text-panel" role="tabpanel" aria-labelledby="text-tab">
                <div class="editor_anchor"></div>
                <textarea class="wysiwyg form-control" name="story_content" id="story_content" rows="20"><?php echo $Page->getStory_content(); ?></textarea>
                <input type="hidden" name="story" value="<?php echo $Page->getStory(); ?>" />
                <input type="hidden" name="pageNum" value="<?php echo Filter::get($_GET, 'pageNum', 1); ?>" />
                <input type="hidden" name="is_new" value="<?php echo $isNew; ?>" />
            </div>

            <!-- Page Access Tab -->
            <div class="tab-pane fade" id="acl-panel" role="tabpanel" aria-labelledby="acl-tab">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="acltype" class="form-label"><?php __('PAGE.ACL_TYPE', 'ACL Type'); ?></label>
                        <?php echo PageHelper::AclTypeSelector($Page->getAcltype()); ?>
                    </div>
                    <div class="col-md-4">
                        <label for="aclusers" class="form-label"><?php __('PAGE.ACL_USERS', 'ACL Users'); ?></label>
                        <?php echo PageHelper::UserSelector($Page->getAclusers()); ?>
                    </div>
                    <div class="col-md-4">
                        <label for="aclgroups" class="form-label"><?php __('PAGE.ACL_GROUPS', 'ACL Groups'); ?></label>
                        <?php echo PageHelper::UserGroupsSelector($Page->getAclgroups()); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-end">
        <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel', array('class' => 'btn btn-outline-secondary me-2')); ?>
        <?php HtmlUtils::mgrButton('BTN.APPLY', 'apply', array('class' => 'btn btn-primary wymupdate me-2')); ?>
        <?php HtmlUtils::mgrButton('BTN.SAVE', 'save', array('class' => 'btn btn-success wymupdate')); ?>
    </div>
</div>

<?php HtmlUtils::mgrFormClose(); ?>
