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

$Item = $this->getData();
$theAction = $this->getVar('the_action');

add_head_element('jquery.cookie');
add_head_element('jquery.utils');
add_head_element('page.onload', TABS_JS);
add_head_element('page.style',  TABS_CSS);

if ($Item->getSnippetType() == Snippet::TYPE_WYSIWYG) {
    add_head_element(Config::get('site_editor'), '', 'include');
    add_head_element('snippets.javascript', 
        '<script type="text/javascript" src="managers/snippets/views/js/snippets.js"></script>'
    );
}

?>
<div class="jquery_tab">
    <div class="content">
        <h2>
            <a href="admin.php?com=snippets"><?php __('COM.SNIPPETS', 'Snippets'); ?></a> / 
            <?php __("GLOBAL.{$theAction}", ucwords($theAction)); ?> / 
            <?php echo $Item->getName(); ?>
        </h2>
        
        <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>
        
        <?php HtmlUtils::mgrFormOpen($this->getVar('com')); ?>

        <p class="buttons-top">
            <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel'); ?>
            <?php HtmlUtils::mgrButton('BTN.APPLY', 'apply', array('class'=>'sb-button ui-state-default ui-corner-all wymupdate')); ?>
            <?php HtmlUtils::mgrButton('BTN.SAVE', 'save', array('class'=>'sb-button ui-state-default ui-corner-all wymupdate')); ?>
        </p>
        
        <div id="tabs">
            <ul>
                <li><a href="#tab-one"><?php echo $Item->getName(); ?></a></li>
            </ul>
            
            <!-- First Tab -->
            <div class="tab-body" id="tab-one">
                <fieldset class="three-column">
                    <div class="column">
                        <label for="id"><?php __('SNIPPETS.ID', 'Snippet ID'); ?>:</label> <?php echo $Item->getId(); ?>
                        <input id="id" type="hidden" name="id" value="<?php echo $Item->getId(); ?>" />
                    </div>
                    <div class="column">
                        <label for="changeSnippetType"><?php __('SNIPPETS.SNIPPETTYPE', 'Snippet Type'); ?>:</label>
                        <p><?php echo $Item->getSnippetType(); ?> (<a href="admin.php?com=snippets&action=changetype&id=<?php echo $Item->getId();?>&snippetType=<?php echo $Item->getSnippetType();?>" id="changeSnippetType"><?php __('SNIPPETS.EDIT', 'Edit'); ?></a>)</p>
                    </div>
                    <div class="column">
                        <label for="name"><?php __('SNIPPETS.NAME', 'Name'); ?>:</label>
                        <input class="input-medium" type="text" name="name" value="<?php echo $Item->getName(); ?>" id="name" />
                    </div>
                    <div class="clear"></div>
                </fieldset>
                
                <fieldset class="three-column last">
                    <?php $taClass = ""; ?>
                    <?php if (strcasecmp($Item->getSnippetType(), Snippet::TYPE_WYSIWYG) === 0) : ?>
                        <div class="editor_anchor"></div>
                        <?php $taClass = " class=\"wysiwyg\""; ?>
                    <?php endif;?>
                    <textarea <?php echo $taClass;?>name="content" id="snippetContent" style="height: 300px;"><?php echo $Item->getContent(); ?></textarea>
                    <input type="hidden" name="datafile" value="<?php echo SnippetsHelper::getDataFile($Item); ?>" />
                </fieldset>
           </div>
        </div>
        <p class="buttons-bottom">
            <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel'); ?>
            <?php HtmlUtils::mgrButton('BTN.APPLY', 'apply', array('class'=>'sb-button ui-state-default ui-corner-all wymupdate')); ?>
            <?php HtmlUtils::mgrButton('BTN.SAVE', 'save', array('class'=>'sb-button ui-state-default ui-corner-all wymupdate')); ?>
        </p>
        
        <input type="hidden" name="is_new" value="<?php echo $this->getVar('is_new'); ?>" />
        
        <input type="hidden" name="snippetType" value="<?php echo $Item->getSnippetType(); ?>" />
        <input type="hidden" name="datafile" value="<?php echo $Item->getDatafile(); ?>" />
        <?php HtmlUtils::mgrFormClose(); ?>
    </div>
</div>