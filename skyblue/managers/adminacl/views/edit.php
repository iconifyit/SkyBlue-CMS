<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2009-06-22 17:37:00 $
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
 * @date   June 22, 2009
 */

add_head_element('jquery.cookie');
add_head_element('jquery.utils');
add_head_element('page.onload', TABS_JS);
add_head_element('page.style',  TABS_CSS);

$com       = $this->getVar('com');
$users     = $this->getVar('usersList');
$groups    = $this->getVar('groupsList');
$actions   = $this->getVar('acl');
$Item      = $this->getData();
$theAction = $this->getVar('action');
$pageNum   = $this->getVar('pageNum');

?>
<style type="text/css">
.clickable, input[type='checkbox'] { cursor: pointer; }
</style>
<script type="text/javascript">
$(function() {
    $(".clickable").bind("click", function(e) {
        var id = $(this).attr("id");
        var id = id.replace("label_", "");
        if ($("#"+id).attr("checked")) {
             $("#"+id).attr("checked", false);
        }
        else {
             $("#"+id).attr("checked", "checked");
        }
    });
});
</script>
<div class="jquery_tab">
    <div class="content">
        <h2 class="jquery_tab_title">
            <a href="admin.php?com=adminacl"><?php __('COM.ADMINACL', 'Access Rules'); ?></a> / 
            <?php __("GLOBAL.{$theAction}", ucwords($theAction)); ?> / 
            <?php echo $Item->getName(); ?>
        </h2>
        
        <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>
        
        <?php HtmlUtils::mgrFormOpen($this->getVar('com')); ?>

        <p class="buttons-top">
            <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel'); ?>
            <?php HtmlUtils::mgrButton('BTN.APPLY', 'apply', array('class'=>'sb-button ui-state-default ui-corner-all')); ?>
            <?php HtmlUtils::mgrButton('BTN.SAVE', 'save'); ?>
        </p>
        
        <div id="tabs">
            <ul>
                <li><a href="#tab-one"><?php echo $Item->getName(); ?></a></li>
            </ul>
            
            <!-- First Tab -->
            <div class="tab-body" id="tab-one">
            
                <fieldset class="three-column">
                    <div class="column"><label><?php __('ADMINACL.ACTION', 'Action'); ?></label></div>
                    <div class="column"><label><?php __('ADMINACL.USERS', 'Users'); ?></label></div>
                    <div class="column"><label><?php __('ADMINACL.GROUPS', 'Groups'); ?></label></div>
                    <div class="clear">&nbsp;</div>
                </fieldset>
                

                <?php $actionCount = count($actions); ?>
                <?php for ($x=0; $x<$actionCount; $x++) : ?>
                <?php $access = $actions[$x]; ?>
                <fieldset class="three-column<?php if ($x==$actionCount-1) :?> last<?php endif;?>">
                    <div class="column">
                        <label><?php echo $actions[$x]; ?></label>
                    </div>
                    <div class="column">
                        <ul>
                        <?php $count = count($users); ?>
                        <?php for ($i=0; $i<$count; $i++) : ?>
                        <?php $checked = $Item->hasUser($actions[$x], $users[$i]->getId()) ? " checked=\"checked\"" : "" ; ?>
                        <li>
                            <input id="user_<?php echo "{$i}_{$x}"; ?>" type="checkbox" name="acl[<?php echo $access; ?>][users][]" value="<?php echo $users[$i]->getId();?>"<?php echo $checked;?>/>&nbsp;
                            <span id="label_user_<?php echo "{$i}_{$x}"; ?>" class="clickable"><?php echo $users[$i]->getName(); ?></span>
                        </li>
                        <?php endfor; ?>
                        </ul>
                    </div>
                    <div class="column">
                        <ul>
                        <?php $count = count($groups); ?>
                        <?php for ($i=0; $i<$count; $i++) : ?>
                        <?php $checked = $Item->hasGroup($actions[$x], $groups[$i]->getId()) ? " checked=\"checked\"" : "" ; ?>
                        <li>
                            <input id="group_<?php echo "{$i}_{$x}"; ?>" type="checkbox" name="acl[<?php echo $access; ?>][groups][]" value="<?php echo $groups[$i]->getId();?>"<?php echo $checked;?>/>&nbsp;
                            <span id="label_group_<?php echo "{$i}_{$x}"; ?>" class="clickable"><?php echo $groups[$i]->getName(); ?></span>
                        </li>
                        <?php endfor; ?>
                        </ul>
                    </div>
                    <div class="clear"></div>
                </fieldset>
                <?php endfor; ?>
            </div>
        </div>
        
        <p class="buttons-bottom">
            <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel'); ?>
            <?php HtmlUtils::mgrButton('BTN.APPLY', 'apply', array('class'=>'sb-button ui-state-default ui-corner-all')); ?>
            <?php HtmlUtils::mgrButton('BTN.SAVE', 'save'); ?>
        </p>    

        <input type="hidden" name="id" value="<?php echo $Item->getId(); ?>" />
        <input type="hidden" name="name" value="<?php echo $Item->getName(); ?>" />
        <input type="hidden" name="objtype" value="<?php echo $Item->getObjtype(); ?>" />
        <input type="hidden" name="type" value="<?php echo $Item->getType(); ?>" />
        <input type="hidden" name="pageNum" value="<?php echo $pageNum; ?>" />

        <?php HtmlUtils::mgrFormClose(); ?>
        
    </div>
</div>