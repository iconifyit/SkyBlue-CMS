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
$folders = $this->getVar('folders');

?>
<script type="text/javascript">
function init_upload() {
    $("#uploadButton_top, #uploadButton_bottom").bind("click", function(e) {
        window.uploadCount = 0;
        $("input[type='file']").each(function() {
            if ($(this).val().trim() != "") window.uploadCount++;
        });
        if (window.uploadCount == 0) {
            e.preventDefault();
            alert("<?php __('MEDIA.NO_FILES_SELECTED', 'You have not selected a file for upload'); ?>");
        }
    });
    $(".add_row").bind("click", function(e) {
        e.preventDefault();
        var rows = $("#rows").find(".row");
        if (rows.length && rows[rows.length-1].cloneNode) {
            var newRow = rows[0].cloneNode(true);
            var rowId = $.uniqueId('row');
            $(newRow).appendTo("#rows")
                .css('display', 'none')
                .attr('id', rowId);
            $(newRow).find('input[type="file"]').val('');
            var data = {"rowId": rowId}
            $("<p style=\"text-align: right;\"><a href=\"#delete-row\"><?php __('GLOBAL.REMOVE', 'Remove'); ?></a></p>")
                .appendTo(newRow)
                .find('A')
                .bind("click", data, function(e) {
                    confirm_action(e, 
                        "<?php __('GLOBAL.PLEASE_CONFIRM',    'Please Confirm'); ?>",
                        "<?php __('MEDIA.CONFIRM_DELETE_ROW', 'Are you sure you want to remove this row?'); ?>",
                        function() {
                            $("#"+e.data.rowId).fadeOut(300, function() {
                                $("#"+e.data.rowId).remove();
                            });
                        }
                    );
            });                
            $(newRow).fadeIn(1500);
        }
    });
};
</script>
<?php add_onload_scriptlet('init_upload', "init_upload();"); ?>
<div class="jquery_tab">
    <div class="content">
        <h2>
            <a href="admin.php?com=media"><?php __('COM.MEDIA', 'Media'); ?></a> / 
            <?php __("GLOBAL.{$theAction}", ucwords($theAction)); ?> / 
            <?php echo $Item->getName(); ?>
        </h2>
        
        <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>
        
        <?php HtmlUtils::mgrFormOpen($this->getVar('com'), array('enctype'=>'multipart/form-data')); ?>

        <p class="buttons-top">
            <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel'); ?>
            <?php HtmlUtils::mgrButton('BTN.UPLOAD', 'upload', array('id'=>'uploadButton_top')); ?>
        </p>
        
        <div id="tabs">
            <ul>
                <li><a href="#tab-one"><?php echo $Item->getName(); ?></a></li>
            </ul>
            
            <!-- First Tab -->
            <div class="tab-body" id="tab-one">
                <p style="text-align: right;"><a href="#add-row" class="add_row"><?php __('MEDIA.ADD_ROW', 'Add Row'); ?></a></p>
                <div id="rows">
                    <fieldset class="row three-column">
                        <div class="column">
                            <label for="file-1"><?php __('MEDIA.LABEL.UPLOAD', 'Upload File:'); ?></label>
                            <input type="file" name="uploads[]" class="input-medium" id="file-1" />
                        </div>
                        <div class="column">&nbsp;</div>
                        <div class="column">
                            <label for="file-1"><?php __('MEDIA.LABEL.TO_DIR', 'To Directory:'); ?></label>
                            <?php $count = count($folders); ?>
                            <select name="folders[]">
                                <option value=""><?php __('GLOBAL.CHOOSE', ' -- Choose -- '); ?></option>
                                <?php for ($i=0; $i<$count; $i++) : ?>
                                <?php $selected = ""; ?>
                                <?php $theFolder = str_replace(_SBC_WWW_, '', $folders[$i]); ?>
                                <option value="<?php echo $theFolder; ?>"<?php echo $selected; ?>><?php echo $theFolder; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="clear"></div>
                    </fieldset>
                </div>
            </div>
        </div>
        <p class="buttons-bottom">
            <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel'); ?>
            <?php HtmlUtils::mgrButton('BTN.UPLOAD', 'upload', array('id'=>'uploadButton_bottom')); ?>
        </p>    
        
        <input type="hidden" name="is_new" value="<?php echo $this->getVar('is_new'); ?>" />

        <?php HtmlUtils::mgrFormClose(); ?>
    </div>
</div>