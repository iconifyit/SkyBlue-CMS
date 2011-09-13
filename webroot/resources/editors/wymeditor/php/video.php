<?php

require_once('wym.base.php');

$videos = array();

if (is_dir(SB_MEDIA_DIR . "videos/")) {
    FileSystem::list_files(
        SB_MEDIA_DIR . "videos/"
    );
}

$count = count($videos);

for ($i=0; $i<$count; $i++) {
    $videos[$i] = str_replace(_SBC_WWW_, "", $videos[$i]);
}

$index  = Filter::get($_GET, 'index', '0');

?>
<style type="text/css">
    #video-tabs {height: auto; margin-bottom: 20px;}
    #video-tabs .ui-widget { display: none; }
    #video-tabs .ui-widget p { padding: 0px; }
    #video-tabs .ui-state-error { padding: 5px; }
    #video-tabs .ui-state-error strong { color: #000; }
    #video-tabs .ui-icon { float: left; margin: 2px 8px 0px 0px; }
    #video-tabs .message-text { color: #333; }
    #video-tabs .inputdiv h3 { margin-bottom: 4px; }
</style>
<script type="text/javascript">

    /**
     * Execute the callback to insert a video
     */
    function doInsertVideo(thePortalType, thePortal, theVideo, showLink, linkText) {
        if (theVideo) {
            $("#video").val(theVideo);
            $("#portal").val(thePortal);
            $("#show_video_link").val(showLink);
            $("#video_link_text").val(linkText);
            SBC.InsertVideo("<?php echo $index; ?>"); 
            SBC.hideOverlay("<?php echo $index; ?>");
        }
        else {
            $("#" + thePortalType + "-message").text('<?php __("WYM.VIDEOS.SELECT_VIDEO", "Please select a video"); ?>');
            $("#" + thePortalType + "-error-div").fadeIn(200, function() {
                delayedReaction("fadeAndRemove('#" + thePortalType + "-error-div')", "<?php echo SB_DIALOG_TIMEOUT; ?>");
            });
        }
    };
    
    /**
     * Execute the callback to insert a local video
     */
    function doInsertLocalVideo() {
        doInsertVideo(
            'local',
            'local', 
            $('#video-local').val(), 
            $('#include_link_local').val(), 
            $('#local_linktext').val()
        );
    };
    
    /**
     * Execute the callback to insert a portal video
     */
    function doInsertPortalVideo() {
        doInsertVideo(
            'portal',
            $('#portal_name').val(), 
            $('#portal_video').val(), 
            $('#include_link_portal').val(), 
            $('#portal_linktext').val()
        );
    };
    
    /**
     * Execute the callback to insert a video URL
     */
    function doInsertRemoteVideo() {
        doInsertVideo(
            'remote', 
            'video',
            $('#video-remote').val(), 
            $('#include_link_remote').val(), 
            $('#remote_linktext').val()
        );
    };
    
    /**
     * Enable/disable the Local Link Text field
     */
    function doToggleLocalLinkText() {
        $("#local_linktext").attr("disabled", $("#include_link_local").val() == "0");
    };
    
    /**
     * Enable/disable the Portal Link Text field
     */
    function doTogglePortalLinkText() {
        $("#portal_linktext").attr("disabled", $("#include_link_portal").val() == "0");
    };
    
    /**
     * Enable/disable the URL Link Text field
     */
    function doToggleRemoteLinkText() {
        $("#remote_linktext").attr("disabled", $("#include_link_remote").val() == "0");
    };
    
    /**
     * Disable fields if portal is Daily Motion or Garage TV
     */
    function doDailyMotion() {
        if ($('#video-portal').val() == "dailymotion" || 
            $('#video-portal').val() == "garagetv") {
            
            $("#portal_linktext").attr("disabled", $("#include_link_portal").val() == "0");
        }
    };
</script>
<div class="jquery_tab">
    <div id="video-tabs">
        <ul>
            <li id="portal-tab"><a href="#portal-body"><span><?php __("WYM.VIDEOS.PORTAL_VIDEO", "Portal Video"); ?></span></a></li>
            <li id="local-tab"><a href="#local-body"><span><?php __("WYM.VIDEOS.LOCAL_VIDEO", "Local Video"); ?></span></li></a>
            <!-- <li id="upload-tab"><a href="#upload-body"><span>Upload video</span></a></li> -->
            <li id="remote-tab"><a href="#remote-body"><span><?php __("WYM.VIDEOS.VIDEO_URL", "Video URL"); ?></span></a></li>
        </ul>
        
        <form style="display: none;" name="video-form">
            <input type="hidden" id="video" name="video" value="" />
            <input type="hidden" id="portal" name="portal" value="" />
            <input type="hidden" id="show_video_link" name="show_video_link" value="" />
            <input type="hidden" id="video_link_text" name="video_link_text" value="" />
        </form>
        
        <div class="tab-body" id="portal-body">
            <div id="portal-error-div" class="ui-widget">
                <div class="ui-corner-all ui-state-error">
                    <p>
                        <span class="ui-icon ui-icon-alert"></span>
                        <strong><?php __("GLOBAL.ERROR", "Error"); ?></strong>
                        <span id="portal-message" class="message-text"></span>
                    </p>
                </div>
            </div>
            <form id="portal-video-form" method="get" action="javascript:return void(0);">
                <div class="inputdiv">
                    <h3><?php __("WYM.VIDEOS.SELECT_PORTAL", "Select video portal"); ?></h3>
                    <select name="portal_name" id="portal_name" onChange="doDailyMotion();">
                        <option value=""><?php __("GLOBAL.CHOOSE", "Choose"); ?></option>
                        <option value="youtube">YouTube</option>
                        <option value="google">Google Video</option>
                        <option value="myspace">Myspace Video</option>
                        <option value="dailymotion">dailymotion</option>
                        <option value="revver">Revver</option>
                        <option value="sevenload">Sevenload</option>
                        <option value="clipfish">Clipfish</option>
                        <option value="metacafe">Metacaf&eacute;</option>
                        <option value="myvideo">MyVideo</option>
                        <option value="yahoo">Yahoo! Video</option>
                        <option value="ifilm">ifilm</option>
                        <option value="brightcove">brightcove</option>
                        <option value="aniboom">aniBOOM</option>
                        <option value="vimeo">vimeo</option>
                        <option value="guba">GUBA</option>
                        <option value="garagetv">Garage TV</option>
                        <option value="gamevideo">GameVideos</option>
                        <option value="vsocial">vSocial</option>
                        <option value="veoh">Veoh</option>
                        <option value="gametrailers">Gametrailers</option>
                    </select>
                </div>
                <div class="inputdiv">
                    <h3><?php __("WYM.VIDEOS.VIDEO_ID", "Insert Video ID"); ?></h3>
                    <input name="portal_video" 
                           type="text" 
                           id="portal_video" 
                           class="inputfield"
                           value="" 
                           />
                </div>
                <div class="inputdiv">
                    <h3><?php __("WYM.VIDEOS.INCLUDE_LINK", "Include Download Link?"); ?></h3>
                    <select name="include_link_portal" id="include_link_portal" onchange="doTogglePortalLinkText();">
                        <option value=""><?php __("GLOBAL.CHOOSE", "Choose"); ?></option>
                        <option value="1"><?php __("GLOBAL.YES", "Yes"); ?></option>
                        <option value="0"><?php __("GLOBAL.NO", "No"); ?></option>
                    </select>
                </div>
                <div class="inputdiv">
                    <h3><?php __("WYM.VIDEOS.LINK_TEXT", "Link Text"); ?></h3>
                    <input name="portal_linktext" 
                           type="text" 
                           id="portal_linktext" 
                           class="inputfield"
                           value="" 
                           />
                </div>
                <div class="inputdivlast">
                    <input type="button" 
                           class="sb-button ui-state-default ui-corner-all"
                           name="save" 
                           value="Ok" 
                           onclick="doInsertPortalVideo();"
                           />
                    <input type="button" 
                           class="sb-button ui-state-default ui-corner-all"
                           name="cancel" 
                           value="Cancel" 
                           onclick="SBC.hideOverlay('<?php echo $index; ?>');"
                           />
                </div>
            </form>
        </div>
        <!--/portal-body-->
        
        <div class="tab-body" id="local-body">
            <div id="local-error-div" class="ui-widget">
                <div class="ui-corner-all ui-state-error">
                    <p>
                        <span class="ui-icon ui-icon-alert"></span>
                        <strong><?php __("GLOBAL.ERROR", "Error"); ?></strong>
                        <span id="local-message" class="message-text"></span>
                    </p>
                </div>
            </div>
            <?php if (! $count) : ?>
                <div>
                    <p><?php __('WYM.VIDEOS.NO_VIDEOS', "No videos were found"); ?></p>
                </div>
            <?php else : ?>
                <form id="local-video-form" method="get" action="javascript:return void(0);">
                    <div class="inputdiv">
                        <h3><?php __("WYM.VIDEOS.CHOOSE_VIDEO", "Choose Video"); ?></h3>
                        <select name="video-local" id="video-local">
                            <option value=""><?php __("WYM.VIDEOS.CHOOSE_VIDEO", "Choose Video"); ?></option>
                            <?php foreach ($videos as $video) : ?>
                                <option value="<?php echo $video; ?>"><?php echo basename($video); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="inputdiv">
                        <h3><?php __("WYM.VIDEOS.INCLUDE_LINK", "Include Download Link?"); ?></h3>
                        <select name="include_link_local" id="include_link_local" onchange="doToggleLocalLinkText();">
                            <option value=""><?php __("GLOBAL.CHOOSE", "Choose"); ?></option>
                            <option value="1"><?php __("GLOBAL.YES", "Yes"); ?></option>
                            <option value="0"><?php __("GLOBAL.NO", "No"); ?></option>
                        </select>
                    </div>
                    <div class="inputdiv">
                        <h3><?php __("WYM.VIDEOS.LINK_TEXT", "Link Text"); ?></h3>
                        <input name="local_linktext" 
                               type="text" 
                               id="local_linktext" 
                               class="inputfield"
                               value="" 
                               />
                    </div>
                    <div class="inputdivlast">
                        <input type="button" 
                               class="sb-button ui-state-default ui-corner-all"
                               name="save" 
                               value="Ok" 
                               onclick="doInsertLocalVideo();"
                               />
                        <input type="button" 
                               class="sb-button ui-state-default ui-corner-all"
                               name="cancel" 
                               value="Cancel" 
                               onclick="SBC.hideOverlay('<?php echo $index; ?>');"
                               />
                    </div>
                </form>
            <?php endif; ?>
        </div>
        <!--/local-body-->
        
        <!-- <div class="tab-body" id="upload-body">Upload tab body</div> -->
        
        <div class="tab-body" id="remote-body">
            <div id="remote-error-div" class="ui-widget">
                <div class="ui-corner-all ui-state-error">
                    <p>
                        <span class="ui-icon ui-icon-alert"></span>
                        <strong><?php __("GLOBAL.ERROR", "Error"); ?></strong>
                        <span id="remote-message" class="message-text"></span>
                    </p>
                </div>
            </div>
            <form id="remote-video-form" method="get" action="javascript:return void(0);">
                <div class="inputdiv">
                    <h3><?php __("WYM.VIDEOS.INSERT_URL", "Insert video URL"); ?></h3>
                    <input name="video-remote" 
                           type="text" 
                           id="video-remote" 
                           class="inputfield"
                           value="" 
                           />
                </div>
                <div class="inputdiv">
                    <h3><?php __("WYM.VIDEOS.INCLUDE_LINK", "Include Download Link?"); ?></h3>
                    <select name="include_link_remote" id="include_link_remote" onchange="doToggleRemoteLinkText();">
                        <option value=""><?php __('GLOBAL.CHOOSE', 'Choose'); ?></option>
                        <option value="1"><?php __('GLOBAL.YES', 'Yes'); ?></option>
                        <option value="0"><?php __('GLOBAL.NO', 'No'); ?></option>
                    </select>
                </div>
                <div class="inputdiv">
                    <h3><?php __("WYM.VIDEOS.LINK_TEXT", "Link Text"); ?></h3>
                    <input name="remote_linktext" 
                           type="text" 
                           id="remote_linktext" 
                           class="inputfield"
                           value="" 
                           />
                </div>
                <div class="inputdivlast">
                    <input type="button" 
                           class="sb-button ui-state-default ui-corner-all"
                           name="save" 
                           value="<?php __('GLOBAL.OK', 'Ok'); ?>" 
                           onclick="doInsertRemoteVideo();"
                           />
                    <input type="button" 
                           class="sb-button ui-state-default ui-corner-all"
                           name="cancel" 
                           value="<?php __('GLOBAL.CANCEL', 'Cancel'); ?>" 
                           onclick="SBC.hideOverlay('<?php echo $index; ?>');"
                           />
                </div>
            </form>
        </div>
        <!--/remote-body-->
    </div>
</div>