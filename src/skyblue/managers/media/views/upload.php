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
        var rows = $("#rows").find(".upload-row");
        if (rows.length && rows[rows.length-1].cloneNode) {
            var newRow = rows[0].cloneNode(true);
            var rowId = $.uniqueId('row');
            $(newRow).appendTo("#rows")
                .css('display', 'none')
                .attr('id', rowId);
            $(newRow).find('input[type="file"]').val('');
            var data = {"rowId": rowId}
            $("<div class=\"col-12 text-end\"><a href=\"#delete-row\" class=\"btn btn-sm btn-outline-danger\"><?php __('GLOBAL.REMOVE', 'Remove'); ?></a></div>")
                .appendTo($(newRow).find('.row'))
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

<h1 class="h3 mb-3">
    <a href="admin.php?com=console" class="text-muted text-decoration-none"><?php __('COM.CONSOLE', 'Dashboard'); ?></a>
    <span class="text-muted">/</span>
    <a href="admin.php?com=collections" class="text-muted text-decoration-none"><?php __('COM.COLLECTIONS', 'Collections'); ?></a>
    <span class="text-muted">/</span>
    <a href="admin.php?com=media" class="text-muted text-decoration-none"><?php __('COM.MEDIA', 'Media'); ?></a>
    <span class="text-muted">/</span>
    <?php __("GLOBAL.{$theAction}", ucwords($theAction)); ?>
</h1>

<?php echo HtmlUtils::formatMessage($this->getMessage()); ?>

<?php HtmlUtils::mgrFormOpen($this->getVar('com'), array('enctype' => 'multipart/form-data')); ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><?php __('MEDIA.UPLOAD_FILES', 'Upload Files'); ?></h5>
        <div>
            <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel', array('class' => 'btn btn-outline-secondary me-2')); ?>
            <?php HtmlUtils::mgrButton('BTN.UPLOAD', 'upload', array('id' => 'uploadButton_top', 'class' => 'btn btn-primary')); ?>
        </div>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-end mb-3">
            <a href="#add-row" class="btn btn-sm btn-outline-primary add_row">
                <i data-feather="plus"></i> <?php __('MEDIA.ADD_ROW', 'Add Another File'); ?>
            </a>
        </div>

        <div id="rows">
            <div class="upload-row mb-3 p-3 border rounded">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="file-1" class="form-label"><?php __('MEDIA.LABEL.UPLOAD', 'Upload File'); ?></label>
                        <input type="file" name="uploads[]" class="form-control" id="file-1" />
                    </div>
                    <div class="col-md-6">
                        <label for="folder-1" class="form-label"><?php __('MEDIA.LABEL.TO_DIR', 'To Directory'); ?></label>
                        <?php $count = count($folders); ?>
                        <select name="folders[]" class="form-select" id="folder-1">
                            <option value=""><?php __('GLOBAL.CHOOSE', ' -- Choose -- '); ?></option>
                            <?php for ($i = 0; $i < $count; $i++) : ?>
                            <?php $theFolder = str_replace(_SBC_WWW_, '', $folders[$i]); ?>
                            <option value="<?php echo $theFolder; ?>"><?php echo $theFolder; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="is_new" value="<?php echo $this->getVar('is_new'); ?>" />
    </div>
    <div class="card-footer d-flex justify-content-end">
        <?php HtmlUtils::mgrButton('BTN.CANCEL', 'cancel', array('class' => 'btn btn-outline-secondary me-2')); ?>
        <?php HtmlUtils::mgrButton('BTN.UPLOAD', 'upload', array('id' => 'uploadButton_bottom', 'class' => 'btn btn-primary')); ?>
    </div>
</div>

<?php HtmlUtils::mgrFormClose(); ?>
