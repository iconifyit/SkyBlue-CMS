<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2009-06-21 13:12:00 $
 * @package      SkyBlueCanvas
 * @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license      GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
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
add_head_element('page.onload', TABS_JS);
add_head_element('page.style',  TABS_CSS);

add_head_element(Config::get('site_editor'), '', 'include');

?>
<div class="jquery_tab">
    <div class="content">
        <h2>
            <a href="admin.php?com=page"><?php __('COM.PAGES', 'Pages'); ?></a> / 
            <?php __("GLOBAL.{$theAction}", ucwords($theAction)); ?> / 
            <?php echo $Page->getName(); ?>
        </h2>
        <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>
        <?php HtmlUtils::mgrFormOpen('page'); ?>
        <p class="buttons-top">
            <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel'); ?>
            <?php HtmlUtils::mgrButton('BTN.APPLY', 'apply', array('class'=>'sb-button ui-state-default ui-corner-all wymupdate')); ?>
            <?php HtmlUtils::mgrButton('BTN.SAVE', 'save', array('class'=>'sb-button ui-state-default ui-corner-all wymupdate')); ?>
        </p>
        
        <div id="tabs">
            <ul>
                <li><a href="#meta-tab"><?php __('PAGE.META_TAB', 'Page Info'); ?></a></li>
                <li><a href="#text-tab"><?php __('PAGE.TEXT_TAB', 'Page Text'); ?></a></li>
                <li><a href="#acl-tab"><?php __('PAGE.ACL_TAB', 'Page Access'); ?></a></li>
            </ul>
            
            <!-- First Tab -->
            <div class="tab-body" id="meta-tab">
                <fieldset class="three-column">
                    <div class="column">
                        <label for="id"><?php __('PAGE.ID', 'Page ID'); ?>:</label> <?php echo $Page->getId(); ?>
                        <input id="id" type="hidden" name="id" value="<?php echo $Page->getId(); ?>" />
                    </div>
                    <div class="column">
                        <label for="name"><?php __('PAGE.NAME', 'Name'); ?>:</label>
                        <input class="input-small" type="text" name="name" value="<?php echo $Page->getName(); ?>" id="name" />
                    </div>
                    <div class="column">
                        <label for="name"><?php __('PAGE.TITLE', 'Title'); ?>:</label>
                        <input class="input-medium" type="text" name="title" value="<?php echo $Page->getTitle(); ?>" id="title" />
                    </div>
                    <div class="clear"></div>
                </fieldset>
        
                <fieldset>
                    <?php $permalink = $PageHelper->getPermalink($Page); ?>
                    <label for="permalink"><?php __('PAGE.SEF_URL', 'Search-Friendly URL'); ?></label>
                    <input class="input-flex" type="text" name="permalink" value="<?php echo $permalink; ?>" style="width: 100%;" />
                </fieldset>
        
                <fieldset>
                    <label for="meta_description"><?php __('PAGE.DESCRIPTION', 'Description'); ?>:</label>
                    <textarea name="meta_description" id="meta_description"><?php echo $Page->getMeta_description(); ?></textarea>
                </fieldset>
                
                <fieldset>
                    <label for="keywords"><?php __('PAGE.KEYWORDS', 'Keywords'); ?>:</label>
                    <textarea name="keywords" id="keywords"><?php echo $Page->getKeywords(); ?></textarea>
                </fieldset>
                
                <fieldset class="three-column">
                    <div class="column">
                        <label for="pagetype"><?php __('PAGE.LAYOUT', 'Template'); ?>:</label>
                        <select name="pagetype" id="pagetype">
                            <option value=""> -- <?php __('PAGE.CHOOSE_LAYOUT', 'Choose Template'); ?> -- </option>
                            <?php for ($i=0; $i<count($layouts); $i++) : ?>
                                <?php $selected = $Page->getPagetype() == $layouts[$i] ? ' selected="selected"' : '' ; ?>
                                <option value="<?php echo $layouts[$i]; ?>"<?php echo $selected; ?>><?php echo $layouts[$i]; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="column">
                        <label for="menu"><?php __('PAGE.MENU', 'Menu'); ?>:</label>
                        <select name="menu" id="menu">
                            <option value=""> -- <?php __('PAGE.CHOOSE_MENU', 'Choose Menu'); ?> -- </option>
                            <?php foreach ($menus as $menu) : ?>
                                <?php $selected = $Page->getMenu() == $menu->id ? ' selected="selected"' : '' ; ?>
                                <option value="<?php echo $menu->id; ?>"<?php echo $selected; ?>><?php echo $menu->title; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="column">
                        <label for="parent"><?php __('PAGE.PARENT', 'Parent'); ?>:</label>
                        <select name="parent" id="parent">
                            <option value=""> -- <?php __('PAGE.CHOOSE_PARENT', 'Choose Parent'); ?> -- </option>
                            <?php foreach ($parents as $pg) : ?>
                                <?php $selected = $Page->getParent() == $pg->getId() ? ' selected="selected"' : '' ; ?>
                                <option value="<?php echo $pg->getId(); ?>"<?php echo $selected; ?>><?php echo $pg->getName(); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="clear"></div>
                </fieldset>
                
                <fieldset class="three-column">
                    <div class="column">
                        <label for="isdefault"><?php __('PAGE.IS_HOME_PAGE', 'Is this the Home Page?'); ?></label>
                        <?php echo HtmlUtils::yesNoList('isdefault', $Page->getIsdefault()); ?>
                    </div>
                    <div class="column">
                        <label for="is_error_page"><?php __('PAGE.IS_404_PAGE', 'Is this the 404 Page?'); ?></label>
                        <?php echo HtmlUtils::yesNoList('is_error_page', $Page->getIs_error_page()); ?>
                    </div>
                    <div class="column">
                        <label for="usesitename"><?php __('PAGE.USE_SITE_NAME', 'Use Site Name in Page Title?'); ?></label>
                        <?php echo HtmlUtils::yesNoList('usesitename', $Page->getUsesitename()); ?>
                    </div>
                    <div class="clear"></div>
                </fieldset>
                
                
                <fieldset class="three-column">
                    <div class="column">
                        <label for="published"><?php __('PAGE.PUBLISHED', 'Published?'); ?></label>
                        <?php echo HtmlUtils::yesNoList('published', $Page->getPublished()); ?>
                    </div>
                    <div class="column">
                        <label for="show_in_navigation"><?php __('PAGE.INCLUDE_IN_NAV', 'Include In Navigation?'); ?></label>
                        <?php echo HtmlUtils::yesNoList('show_in_navigation', $Page->getShow_in_navigation()); ?>
                    </div>
                    <div class="column">
                        <label for="published"><?php __('PAGE.SYNDICATE', 'Syndicated?'); ?></label>
                        <?php echo HtmlUtils::yesNoList('syndicate', $Page->getSyndicate()); ?>
                    </div>
                    <div class="clear"></div>
                </fieldset>
                
                <fieldset class="three-column last">
                    <div class="column">
                        <label for="author"><?php __('PAGE.AUTHOR', 'Author'); ?></label>
                        <?php echo PageHelper::getAuthorSelector($Page->getAuthor()); ?>
                    </div>
                    <div class="column">&nbsp;</div>
                    <div class="column">&nbsp;</div>
                    <div class="clear"></div>
                </fieldset>
                
            </div>
            <!-- / First Tab -->
            
            <!-- Second Tab -->
            <div class="tab-body" id="text-tab">
                <div class="editor_anchor"></div>
                <textarea class="wysiwyg" name="story_content" id="story_content"><?php echo $Page->getStory_content(); ?></textarea>
                <input type="hidden" name="story" value="<?php echo $Page->getStory(); ?>" />
                <input type="hidden" name="pageNum" value="<?php echo Filter::get($_GET, 'pageNum', 1); ?>" />
                <input type="hidden" name="is_new" value="<?php echo $isNew; ?>" />
            </div>
            <!-- / Second Tab -->
            
            <!-- Third Tab -->
            <div class="tab-body" id="acl-tab">
                <fieldset class="three-column last">
                    <div class="column">
                         <label for="acltype"><?php __('PAGE.ACL_TYPE', 'ACL Type'); ?></label>
                        <?php echo PageHelper::AclTypeSelector($Page->getAcltype()); ?>
                    </div>
                    <div class="column">
                        <label for="aclusers"><?php __('PAGE.ACL_USERS', 'ACL Users'); ?></label>
                        <?php echo PageHelper::UserSelector($Page->getAclusers()); ?>
                    </div>
                    <div class="column">
                        <label for="aclgroups"><?php __('PAGE.ACL_GROUPS', 'ACL Groups'); ?></label>
                        <?php echo PageHelper::UserGroupsSelector($Page->getAclgroups()); ?>
                    </div>
                </fieldset>
            </div>
            <!-- / Third Tab -->
        </div>
        
        <p class="buttons-bottom">
            <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel'); ?>
            <?php HtmlUtils::mgrButton('BTN.APPLY', 'apply', array('class'=>'sb-button ui-state-default ui-corner-all wymupdate')); ?>
            <?php HtmlUtils::mgrButton('BTN.SAVE', 'save', array('class'=>'sb-button ui-state-default ui-corner-all wymupdate')); ?>
        </p>
        
        <?php HtmlUtils::mgrFormClose(); ?>

    </div>
</div>