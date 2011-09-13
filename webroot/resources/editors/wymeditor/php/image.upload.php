<?php

require_once('wym.base.php');

Loader::load('managers.media.beans.*', true, _SBC_SYS_);

$dirs = FileSystem::list_dirs(SB_MEDIA_DIR);

$tmp = $dirs;
$dirs = array();
for ($i=0; $i<count($tmp); $i++) {
    if (strcasecmp(basename($tmp[$i]), "videos") === 0) continue;
    array_push($dirs, $tmp[$i]);
}

$message = '<h2>' . __('WYM.UPLOAD.NEW', 'Upload New Image', 1) . '</h2>';
$success = false;

$index = Filter::get($_GET, 'wym', 0);
    
if (isset($_FILES['upload']) && !empty($_FILES['upload']['name'])) {

    $file = Filter::get($_FILES, 'upload', array());
    $dest = _SBC_WWW_ . Filter::get($_POST, 'upload_dir', null);
    $ini = FileSystem::read_config(
        SB_MANAGERS_DIR . "media/config.php"
    );
    $mimes = Filter::get($ini, 'mimes', array());
    
    $targets = FileSystem::list_dirs(SB_MEDIA_DIR);
    array_push($targets, ACTIVE_SKIN_DIR . "images/");
    
    $filename = basename(Filter::get($file, 'name'));

    $Uploader = new Uploader($mimes, $targets);
    list($exitCode, $newfile) = $Uploader->upload(new Media(array(
        'id'       => time(),
        'objtype'  => 'media',
        'type'     => 'media',
        'name'     => $filename,
        'filetype' => Filter::get($file, 'type'),
        'tmp_name' => Filter::get($file, 'tmp_name'),
        'filesize' => Filter::get($file, 'size'), 
        'filepath' => $dest . $filename
    )));
    
    if ($exitCode == 1) {
        $success = true;
        $message = '<div class="ui-state-alert"><span class="ui-icon ui-icon-alert" style="float: left; margin: 2px 8px 0px 0px;"></span><h2>' . __('WYM.SUCCESS', 'Success', 1) . '</h2></div>';
    }
    else {
        $message = '<div class="ui-state-error"><span class="ui-icon ui-icon-alert" style="float: left; margin: 2px 8px 0px 0px;"></span><h2>' . __('WYM.UNKNOWN_ERROR', 'Error', 1) . '</h2></div>'; 
    }
}

?>
<html>
    <head>
        <script type="text/javascript" src="../../../ui/js/jquery.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../ui/elements/css/elements.css" />
        <link type="text/css" href="../../../ui/js/jquery-ui-1.8.1/css/smoothness/jquery-ui-1.8.1.custom.css" rel="stylesheet" />
        <script type="text/javascript" src="../../../ui/js/jquery-ui-1.8.1/js/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="../../../ui/js/jquery-ui-1.8.1/js/jquery-ui-1.8.1.custom.min.js"></script>
        <script type="text/javascript" src="../../../ui/js/jquery.cookie.js"></script>
        <script type="text/javascript" src="../../../ui/js/jquery.dropshadow.js"></script>
        <link rel="stylesheet" href="../../../ui/admin/css/main.css" type="text/css" media="screen" />

        <?php if ($success) : ?>
        <script type="text/javascript">
            $(function() {
                $.get("image.php?index=<?php echo $index; ?>", function(data) {
                    try {
                        $(parent.jQuery('#simplemodal-data')).html(data);
                    }
                    catch(e) {
                        $("#message").html(
                            "<h2><?php __('WYM.UPLOAD.WARNING'); ?></h2>" + 
                            "<p><?php __('WYM.UPLOAD.THUMB_ERROR'); ?></p>"
                        );
                    }
                });
            });
        </script>
        <?php endif; ?>
        <script type="text/javascript">
            $(function() {
                $("#upload_button").bind("click", function(e) {
                    if ($("#upload_file").val() == "") {
                        e.preventDefault();
                    }
                });
            });
        </script>
    </head>
    <body>
        <div id="message">
            <?php echo $message; ?>
        </div>
        <form id="mgrform" method="post" action="image.upload.php?wym=<?php echo $index; ?>" enctype="multipart/form-data" >
        
            <input id="upload_file" type="file" name="upload" size="12" />
        
            <select name="upload_dir" size="1" id="upload_dir">
                <?php for ($i=0; $i<count($dirs); $i++) : ?>
                <?php $theDir = str_replace(_SBC_WWW_, '', $dirs[$i]); ?>
                <option value="<?php echo $theDir; ?>"><?php echo $theDir; ?></option>
                <?php endfor; ?>
            </select>
        
            <input type="hidden" name="MAX_FILE_SIZE" value="6291456" />
            
            <button name="upload" id="upload_button" class="sb-button ui-state-default ui-corner-all">Upload</button>
        </form>
    </body>
</html>