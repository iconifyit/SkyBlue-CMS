<?php defined('SKYBLUE') or die('Bad file request');

// Load the header links for Wymeditor 

global $Core;

$path    = SB_EDITORS_DIR;
$csspath = ACTIVE_SKIN_DIR;

$cssname = basename(ACTIVE_SKIN_DIR);

$styleSheetPath = ACTIVE_SKIN_DIR . "css/{$cssname}.css";

$wym_path = "resources/editors/wymeditor/wymeditor/";
$wym_skin = $wym_path . "skins/silver/";
$wym_plug = $wym_path . "plugins/";

$btnSiteVars = "";
if (file_exists(_SBC_APP_ . 'plugins/plugin.sitevars.php')) {
    $btnSiteVars = ",{'name': 'SiteVar', 'title': 'SiteVar', 'css': 'wym_tools_sitevars'}\n";
}

?>
<!--[WYMeditor]-->
<script type="text/javascript" src="<?php echo $wym_path; ?>jquery.wymeditor.js"></script>

<!-- Load jQuery Plugins -->
<script type="text/javascript" src="<?php echo SB_UI_DIR; ?>js/jquery.simplemodal-1.3.min.js"></script>
<script type="text/javascript" src="<?php echo $wym_path; ?>jquery.wymeditor.overlay.js"></script>

<!--[if lt IE 7]>
<style type='text/css'>
  #modalContainer a.modalCloseImg{
    background:none;
    right:-14px;
    width:22px;
    height:26px;
    filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(
        src='img/x.png', sizingMethod='scale'
      );
  }
  #modalContainer {
    top: expression((document.documentElement.scrollTop
        || document.body.scrollTop) + Math.round(15 *
        (document.documentElement.offsetHeight
        || document.body.clientHeight) / 100) + 'px');
  }
</style>
<![endif]-->
        
<script type="text/javascript">
jQuery(function() {
    doInitWymeditor();
});
function doInitWymeditor() {
    jQuery(".wysiwyg").wymeditor({
        updateSelector:".buttonsave", 
        lang: "<?php echo Config::get('site_lang', 'en'); ?>", 
        langPath: "languages/<?php echo Config::get('site_lang', 'en'); ?>/wymeditor/",
        boxHtml:   "<div class='wym_box'>"
              + "<div class='wym_area_top'>"
              + WYMeditor.TOOLS
              + WYMeditor.CONTAINERS
              + WYMeditor.CLASSES
              + "</div>"
              + "<div class='clear'></div>"
              + "<div class='wym_area_left'></div>"
              + "<div class='wym_area_right'>"
              + "</div>"
              + "<div class='wym_area_main'>"
              + WYMeditor.HTML
              + WYMeditor.IFRAME
              + WYMeditor.STATUS
              + "</div>"
              + "<div class='wym_area_bottom'>"
              + "</div>"
              + "</div>",
        toolsItems:[
            {'name': 'Bold', 'title': 'Strong', 'css': 'wym_tools_strong'}, 
            {'name': 'Italic', 'title': 'Emphasis', 'css': 'wym_tools_emphasis'},
            {'name': 'Superscript', 'title': 'Superscript',
                'css': 'wym_tools_superscript'},
            {'name': 'Subscript', 'title': 'Subscript',
                'css': 'wym_tools_subscript'},
            {'name': 'InsertOrderedList', 'title': 'Ordered_List',
                'css': 'wym_tools_ordered_list'},
            {'name': 'InsertUnorderedList', 'title': 'Unordered_List',
                'css': 'wym_tools_unordered_list'},
            {'name': 'Indent', 'title': 'Indent', 'css': 'wym_tools_indent'},
            {'name': 'Outdent', 'title': 'Outdent', 'css': 'wym_tools_outdent'},
            {'name': 'Undo', 'title': 'Undo', 'css': 'wym_tools_undo'},
            {'name': 'Redo', 'title': 'Redo', 'css': 'wym_tools_redo'},
            {'name': 'CreateLink', 'title': 'Link', 'css': 'wym_tools_link'},
            {'name': 'Unlink', 'title': 'Unlink', 'css': 'wym_tools_unlink'},
            {'name': 'InsertImage', 'title': 'Image', 'css': 'wym_tools_image'},
            {'name': 'InsertVideo', 'title': 'Video', 'css': 'wym_tools_video'},
            {'name': 'InsertTable', 'title': 'Table', 'css': 'wym_tools_table'},
            {'name': 'Paste', 'title': 'Paste_From_Word',
                'css': 'wym_tools_paste'},
            {'name': 'ToggleHtml', 'title': 'HTML', 'css': 'wym_tools_html'}
            <?php echo $btnSiteVars; ?>
        ],
        relativepath: "<?php echo WYM_RELATIVE_PATH; ?>",
        skin:         "silver",
        stylesheet:   "<?php echo $styleSheetPath; ?>",
        postInit: function(wym) {
            SBC.options.wym_path = "resources/editors/wymeditor/",
            SBC.AttachImageDblClick();
            
            jQuery(".wym_dropdown h2").hover(
                function() {
                    jQuery(this).css("background-position", "0px -24px"); 
                },
                function() {
                    jQuery(this).css("background-position", ""); 
                }
            );
            jQuery(".wymupdate").bind("click", wym, function(e) {
                if (e.data.update) e.data.update();
            });
            if (jQuery(".wym_classes li").size() > 0) {
                jQuery(".wym_classes").show();
            }
            else {
                jQuery(".wym_classes a").bind("click", wym, function(e) {
                    if (e.data.update) e.data.update();
                });
            }
        }
    });
};
</script>
<!--[/WYMeditor]-->