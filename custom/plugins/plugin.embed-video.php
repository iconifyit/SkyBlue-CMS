<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version        2.0 2010-07-05 10:30:00 $
 * @package        SkyBlueCanvas
 * @copyright      Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license        GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

/**
 * This plugin is based on the Embedded Video plugin for Wordpress by Stefan He&szlig.
 * The original Plugin comments have been included to give credit where it is due.
 *
 * Plugin Name: Embedded Video
 * Plugin URI: http://www.jovelstefan.de/embedded-video/
 * Description: Easy embedding of videos from various portals or local video files with corresponding link. <a href="options-general.php?page=embeddedvideo_options_page">Configure...</a>
 * Version: 4.1
 * License: GPL
 * Author: Stefan He&szlig;
 * Author URI: http://www.jovelstefan.de
 * Contact mail: jovelstefan@gmx.de
 */

/**
 * Config Options:
 *
 *     embeddedvideo_prefix       String         Prefix for video link
 *     embeddedvideo_shownolink   Boolean        Whether or not to show a link to this plugin
 *     embeddedvideo_width        Int *REQUIRED* The video width
 */

define("EV_VERSION", 41);

Event::register('OnRenderPage', 'doEmbedVideos');

function doEmbedVideos($html) {
    $Plugin = new EmbedVideoPlugin($html);
    $Plugin->execute();
    return $Plugin->getHtml();
}

class EmbedVideoPlugin extends SkyBluePlugin {

    var $filename = "plugin.embed-video.php";

    function __construct($html) {
        parent::__construct($html);
        $this->loadSettings();
    }
    
    function execute($content="") {
        parent::execute($content);
        $output = preg_replace_callback(
            $this->getOption("REGEXP_1"), 
            'EmbedVideoPlugin::regexCallback', 
            $this->getHtml()
        );
        $output = preg_replace_callback(
            $this->getOption("REGEXP_2"), 
            'EmbedVideoPlugin::regexCallback', 
            $output
        );
        $output = preg_replace_callback(
            $this->getOption("REGEXP_3"), 
            'EmbedVideoPlugin::regexCallback', 
            $output
        );
        $this->setHtml($output);
    }

    function regexCallback($match) {
        $output = "";
        $match0 = "";
        $match1 = "";
        $match2 = "";
        $match3 = "";
        if (is_array($match)) {
            $match0 = isset($match[0]) ? $match[0] : "" ;
            $match1 = isset($match[1]) ? $match[1] : "" ;
            $match2 = isset($match[2]) ? $match[2] : "" ;
            $match3 = isset($match[3]) ? $match[3] : "" ;
        
            switch ($match1) {
                case "youtube":      $output .= $this->getOption("YOUTUBE_TARGET"); break;
                case "google":       $output .= $this->getOption("GOOGLE_TARGET"); break;
                case "myvideo":      $output .= $this->getOption("MYVIDEO_TARGET"); break;
                case "clipfish":     $output .= $this->getOption("CLIPFISH_TARGET"); break;
                case "sevenload":    $output .= $this->getOption("SEVENLOAD_TARGET"); break;
                case "revver":       $output .= $this->getOption("REVVER_TARGET"); break;
                case "metacafe":     $output .= $this->getOption("METACAFE_TARGET"); break;
                case "yahoo":        $output .= $this->getOption("YAHOO_TARGET"); break;
                case "ifilm":        $output .= $this->getOption("IFILM_TARGET"); break;
                case "myspace":      $output .= $this->getOption("MYSPACE_TARGET"); break;
                case "brightcove":   $output .= $this->getOption("BRIGHTCOVE_TARGET"); break;
                case "aniboom":      $output .= $this->getOption("ANIBOOM_TARGET"); break;
                case "vimeo":        $output .= $this->getOption("VIMEO_TARGET"); break;
                case "guba":         $output .= $this->getOption("GUBA_TARGET"); break;
                case "gamevideo":    $output .= $this->getOption("GAMEVIDEO_TARGET"); break;
                case "vsocial":      $output .= $this->getOption("VSOCIAL_TARGET"); break;
                case "dailymotion":  $output .= $this->getOption("DAILYMOTION_TARGET"); $match3 = "nolink"; break;
                case "garagetv":     $output .= $this->getOption("GARAGE_TARGET"); $match3 = "nolink"; break;
                case "veoh":         $output .= $this->getOption("VEOH_TARGET"); break;
                case "gametrailers": $output .= $this->getOption("GAMETRAILERS_TARGET"); break;
                case "local":
                    if (preg_match($this->getOption('LOCAL_QUICKTIME_TARGET_REGEX'), $match2)) { 
                        $output .= $this->getOption("LOCAL_QUICKTIME_TARGET"); 
                        break; 
                    }
                    elseif (preg_match($this->getOption('LOCAL_TARGET_REGEX'), $match2)) { 
                        $output .= $this->getOption('LOCAL_TARGET'); 
                        break; 
                    }
                    elseif (preg_match($this->getOption('LOCAL_FLASHPLAYER_TARGET_REGEX'), $match2)) { 
                        $output .= $this->getOption("LOCAL_FLASHPLAYER_TARGET"); 
                        break; 
                    }
                case "video":
                    if (preg_match($this->getOption('QUICKTIME_TARGET_REGEX'), $match2)) { 
                        $output .= $this->getOption("QUICKTIME_TARGET"); 
                        break; 
                    }
                    elseif (preg_match($this->getOption('VIDEO_TARGET_REGEX'), $match2)) { 
                        $output .= $this->getOption('VIDEO_TARGET'); 
                        break; 
                    }
                    elseif (preg_match($this->getOption('FLASHPLAYER_TARGET_REGEX'), $match2)) { 
                        $output .= $this->getOption("FLASHPLAYER_TARGET"); 
                        break; 
                    }
                default: break;
            }
            
            if (strcasecmp($match3, "nolink") !== 0) {
                $output .= "<p class=\"video-link\">";
                switch ($match1) {
                    case "youtube":      $output .= $this->getOption("YOUTUBE_LINK"); break;
                    case "google":       $output .= $this->getOption("GOOGLE_LINK"); break;
                    case "myvideo":      $output .= $this->getOption("MYVIDEO_LINK"); break;
                    case "clipfish":     $output .= $this->getOption("CLIPFISH_LINK"); break;
                    case "sevenload":    $output .= $this->getOption("SEVENLOAD_LINK"); break;
                    case "revver":       $output .= $this->getOption("REVVER_LINK"); break;
                    case "metacafe":     $output .= $this->getOption("METACAFE_LINK"); break;
                    case "yahoo":        $output .= $this->getOption("YAHOO_LINK"); break;
                    case "ifilm":        $output .= $this->getOption("IFILM_LINK"); break;
                    case "myspace":      $output .= $this->getOption("MYSPACE_LINK"); break;
                    case "brightcove":   $output .= $this->getOption("BRIGHTCOVE_LINK"); break;
                    case "aniboom":      $output .= $this->getOption("ANIBOOM_LINK"); break;
                    case "vimeo":        $output .= $this->getOption("VIMEO_LINK"); break;
                    case "guba":         $output .= $this->getOption("GUBA_LINK"); break;
                    case "gamevideo":    $output .= $this->getOption("GAMEVIDEO_LINK"); break;
                    case "vsocial":      $output .= $this->getOption("VSOCIAL_LINK"); break;
                    case "veoh":         $output .= $this->getOption("VEOH_LINK"); break;
                    case "gametrailers": $output .= $this->getOption("GAMETRAILERS_LINK"); break;
                    case "local":        $output .= $this->getOption("LOCAL_LINK"); break;
                    case "video":        $output .= $this->getOption("VIDEO_LINK"); break;
                    default: break;
                }
                $output .= "</p>\n";
            }
        
            /**
             * Post processing
             * first replace linktext
             */
            $output = str_replace("###TXT###", $this->getOption("LINKTEXT"), $output);
            /**
             * special handling of Yahoo! Video IDs
             */
            if (strcasecmp($match1, "yahoo") === 0) {
                $temp = explode(".", $match2);
                $match2 = isset($temp[1]) ? $temp[1] : "" ;
                $output = str_replace("###YAHOO###", isset($temp[0]) ? $temp[0] : "" , $output);
            }
            /**
             * replace video IDs and text
             */
            $output = str_replace("###VID###",   $match2, $output);
            $output = str_replace("###THING###", $match3, $output);
    
            $output .= $this->getOption('EMBED_VIDEO_COMMENT');
        }
        return $output;
    }
    
    function loadSettings() {
    
        /**
         * Set linktext
         */
        $this->addOption("LINKTEXT", $this->getOption('embeddedvideo_prefix'));
    
        /**
         * Video format heights
         */
        $this->addOption("YOUTUBE_HEIGHT",      floor($this->getOption('embeddedvideo_width') * 14/17));
        $this->addOption("GOOGLE_HEIGHT",       floor($this->getOption('embeddedvideo_width') * 14/17));
        $this->addOption("MYVIDEO_HEIGHT",      floor($this->getOption('embeddedvideo_width') * 367/425));
        $this->addOption("CLIPFISH_HEIGHT",     floor($this->getOption('embeddedvideo_width') * 95/116));
        $this->addOption("SEVENLOAD_HEIGHT",    floor($this->getOption('embeddedvideo_width') * 408/500));
        $this->addOption("REVVER_HEIGHT",       floor($this->getOption('embeddedvideo_width') * 49/60));
        $this->addOption("METACAFE_HEIGHT",     floor($this->getOption('embeddedvideo_width') * 69/80));
        $this->addOption("YAHOO_HEIGHT",        floor($this->getOption('embeddedvideo_width') * 14/17));
        $this->addOption("IFILM_HEIGHT",        floor($this->getOption('embeddedvideo_width') * 365/448));
        $this->addOption("MYSPACE_HEIGHT",      floor($this->getOption('embeddedvideo_width') * 173/215));
        $this->addOption("BRIGHTCOVE_HEIGHT",   floor($this->getOption('embeddedvideo_width') * 206/243));
        $this->addOption("QUICKTIME_HEIGHT",    floor($this->getOption('embeddedvideo_width') * 3/4));
        $this->addOption("VIDEO_HEIGHT",        floor($this->getOption('embeddedvideo_width') * 3/4));
        $this->addOption("ANIBOOM_HEIGHT",      floor($this->getOption('embeddedvideo_width') * 93/112));
        $this->addOption("FLASHPLAYER_HEIGHT",  floor($this->getOption('embeddedvideo_width') * 93/112));
        $this->addOption("VIMEO_HEIGHT",        floor($this->getOption('embeddedvideo_width') * 3/4));
        $this->addOption("GUBA_HEIGHT",         floor($this->getOption('embeddedvideo_width') * 72/75));
        $this->addOption("DAILYMOTION_HEIGHT",  floor($this->getOption('embeddedvideo_width') * 334/425));
        $this->addOption("GARAGE_HEIGHT",       floor($this->getOption('embeddedvideo_width') * 289/430));
        $this->addOption("GAMEVIDEO_HEIGHT",    floor($this->getOption('embeddedvideo_width') * 3/4));
        $this->addOption("VSOCIAL_HEIGHT",      floor($this->getOption('embeddedvideo_width') * 40/41));
        $this->addOption("VEOH_HEIGHT",         floor($this->getOption('embeddedvideo_width') * 73/90));
        $this->addOption("GAMETRAILERS_HEIGHT", floor($this->getOption('embeddedvideo_width') * 392/480));
        
        /**
         * Embed Video Generator Comment
         */
        $this->addOption('EMBED_VIDEO_COMMENT', "\n<!-- generated by EmbedVideoPlugin based on WordPress plugin Embedded Video -->\n");
        
        /**
         * Video token patterns
         */
        $this->addOption("REGEXP_1", "/\[(google|youtube|myvideo|clipfish|sevenload|revver|metacafe|yahoo|ifilm|myspace|brightcove|aniboom|vimeo|guba|dailymotion|garagetv|gamevideo|vsocial|veoh|gametrailers|local|video) ([[:graph:]]+) (nolink)\]/");
        $this->addOption("REGEXP_2", "/\[(google|youtube|myvideo|clipfish|sevenload|revver|metacafe|yahoo|ifilm|myspace|brightcove|aniboom|vimeo|guba|dailymotion|garagetv|gamevideo|vsocial|veoh|gametrailers|local|video) ([[:graph:]]+) ([[:print:]]+)\]/");
        $this->addOption("REGEXP_3", "/\[(google|youtube|myvideo|clipfish|sevenload|revver|metacafe|yahoo|ifilm|myspace|brightcove|aniboom|vimeo|guba|dailymotion|garagetv|gamevideo|vsocial|veoh|gametrailers|local|video) ([[:graph:]]+)\]/");
    
        /**
         * Video Target Regex patterns
         */
        $this->addOption('LOCAL_QUICKTIME_TARGET_REGEX',   "%([[:print:]]+).(mov|qt|MOV|QT)$%");
        $this->addOption('LOCAL_TARGET_REGEX',             "%([[:print:]]+).(wmv|mpg|mpeg|mpe|asf|asx|wax|wmv|wmx|avi|WMV|MPG|MPEG|MPE|ASF|ASX|WAX|WMV|WMX|AVI)$%");
        $this->addOption('LOCAL_FLASHPLAYER_TARGET_REGEX', "%([[:print:]]+).(swf|flv|SWF|FLV)$%");
        $this->addOption('QUICKTIME_TARGET_REGEX',         "%([[:print:]]+).(mov|qt|MOV|QT)$%");
        $this->addOption('VIDEO_TARGET_REGEX',             "%([[:print:]]+).(wmv|mpg|mpeg|mpe|asf|asx|wax|wmv|wmx|avi|WMV|MPG|MPEG|MPE|ASF|ASX|WAX|WMV|WMX|AVI)$%");
        $this->addOption('FLASHPLAYER_TARGET_REGEX',       "%([[:print:]]+).(swf|flv|SWF|FLV)$%");
                
        /**
         * Object targets and links
         */
        $this->addOption("YOUTUBE_TARGET",           "<object type=\"application/x-shockwave-flash\" data=\"http://www.youtube.com/v/###VID###\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('YOUTUBE_HEIGHT')."\"><param name=\"movie\" value=\"http://www.youtube.com/v/###VID###\" /><param name=\"autostart\" value=\"true\" /><param name=\"wmode\" value=\"transparent\" /></object><br />");
        $this->addOption("YOUTUBE_LINK",             "<a title=\"YouTube\" href=\"http://www.youtube.com/watch?v=###VID###\">YouTube ###TXT######THING###</a>");
        $this->addOption("GOOGLE_TARGET",            "<object type=\"application/x-shockwave-flash\" data=\"http://video.google.com/googleplayer.swf?docId=###VID###\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('GOOGLE_HEIGHT')."\"><param name=\"movie\" value=\"http://video.google.com/googleplayer.swf?docId=###VID###\" /><param name=\"wmode\" value=\"transparent\" /></object><br />");
        $this->addOption("GOOGLE_LINK",              "<a title=\"Google Video\" href=\"http://video.google.com/videoplay?docid=###VID###\">Google ###TXT######THING###</a>");
        $this->addOption("MYVIDEO_TARGET",           "<object type=\"application/x-shockwave-flash\" data=\"http://www.myvideo.de/movie/###VID###\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('MYVIDEO_HEIGHT')."\"><param name=\"movie\" value=\"http://www.myvideo.de/movie/###VID###\" /><param name=\"wmode\" value=\"transparent\" /></object><br />");
        $this->addOption("MYVIDEO_LINK",             "<a title=\"MyVideo\" href=\"http://www.myvideo.de/watch/###VID###\">MyVideo ###TXT######THING###</a>");
        $this->addOption("CLIPFISH_TARGET",          "<object type=\"application/x-shockwave-flash\" data=\"http://www.clipfish.de/videoplayer.swf?as=0&amp;videoid=###VID###&amp;r=1\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('CLIPFISH_HEIGHT')."\"><param name=\"movie\" value=\"http://www.clipfish.de/videoplayer.swf?as=0&amp;videoid=###VID###&amp;r=1\" /><param name=\"wmode\" value=\"transparent\" /></object><br />");
        $this->addOption("CLIPFISH_LINK",            "<a title=\"Clipfish\" href=\"http://www.clipfish.de/player.php?videoid=###VID###\">Clipfish ###TXT######THING###</a>");
        $this->addOption("SEVENLOAD_TARGET",         "<script type='text/javascript' src='http://sevenload.com/pl/###VID###/".$this->getOption('embeddedvideo_width')."x".$this->getOption('SEVENLOAD_HEIGHT')."'></script><br />");
        $this->addOption("SEVENLOAD_LINK",           "<a title=\"Sevenload\" href=\"http://sevenload.com/videos/###VID###\">Sevenload ###TXT######THING###</a>");
        $this->addOption("REVVER_TARGET",            "<object type=\"application/x-shockwave-flash\" data=\"http://flash.revver.com/player/1.0/player.swf?mediaId=###VID###\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('REVVER_HEIGHT')."\"><param name=\"movie\" value=\"http://flash.revver.com/player/1.0/player.swf?mediaId=###VID###\" /><param name=\"wmode\" value=\"transparent\" /></object><br />");
        $this->addOption("REVVER_LINK",              "<a title=\"Revver\" href=\"http://one.revver.com/watch/###VID###\">Revver ###TXT######THING###</a>");
        $this->addOption("METACAFE_TARGET",          "<object type=\"application/x-shockwave-flash\" data=\"http://www.metacafe.com/fplayer/###VID###.swf\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('METACAFE_HEIGHT')."\"><param name=\"movie\" value=\"http://www.metacafe.com/fplayer/###VID###.swf\" /><param name=\"wmode\" value=\"transparent\" /></object><br />");
        $this->addOption("METACAFE_LINK",            "<a title=\"Metacaf&eacute;\" href=\"http://www.metacafe.com/watch/###VID###\">Metacaf&eacute; ###TXT######THING###</a>");
        $this->addOption("YAHOO_TARGET",             "<object type=\"application/x-shockwave-flash\" data=\"http://us.i1.yimg.com/cosmos.bcst.yahoo.com/player/media/swf/FLVVideoSolo.swf?id=###VID###\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('YAHOO_HEIGHT')."\"><param name=\"movie\" value=\"http://us.i1.yimg.com/cosmos.bcst.yahoo.com/player/media/swf/FLVVideoSolo.swf?id=###VID###\" /><param name=\"wmode\" value=\"transparent\" /></object><br />");
        $this->addOption("YAHOO_LINK",               "<a title=\"Yahoo! Video\" href=\"http://video.yahoo.com/video/play?vid=###YAHOO###.###VID###\">Yahoo! ###TXT######THING###</a>");
        $this->addOption("IFILM_TARGET",             "<object type=\"application/x-shockwave-flash\" data=\"http://www.ifilm.com/efp?flvbaseclip=###VID###\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('IFILM_HEIGHT')."\"><param name=\"movie\" value=\"http://www.ifilm.com/efp?flvbaseclip=###VID###\" /><param name=\"wmode\" value=\"transparent\" /></object><br />");
        $this->addOption("IFILM_LINK",               "<a title=\"ifilm\" href=\"http://www.ifilm.com/video/###VID###\">ifilm ###TXT######THING###</a>");
        $this->addOption("MYSPACE_TARGET",           "<object type=\"application/x-shockwave-flash\" data=\"http://lads.myspace.com/videos/vplayer.swf?m=###VID###&amp;type=video\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('MYSPACE_HEIGHT')."\"><param name=\"movie\" value=\"http://lads.myspace.com/videos/vplayer.swf?m=###VID###&amp;type=video\" /><param name=\"wmode\" value=\"transparent\" /></object><br />");
        $this->addOption("MYSPACE_LINK",             "<a title=\"MySpace Video\" href=\"http://vids.myspace.com/index.cfm?fuseaction=vids.individual&amp;videoid=###VID###\">MySpace ###TXT######THING###</a>");
        $this->addOption("BRIGHTCOVE_TARGET",        "<object type=\"application/x-shockwave-flash\" data=\"http://admin.brightcove.com/destination/player/player.swf?initVideoId=###VID###&amp;servicesURL=http://services.brightcove.com/services&amp;viewerSecureGatewayURL=https://services.brightcove.com/services/amfgateway&amp;cdnURL=http://admin.brightcove.com&amp;autoStart=false\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('BRIGHTCOVE_HEIGHT')."\"><param name=\"movie\" value=\"http://admin.brightcove.com/destination/player/player.swf?initVideoId=###VID###&amp;servicesURL=http://services.brightcove.com/services&amp;viewerSecureGatewayURL=https://services.brightcove.com/services/amfgateway&amp;cdnURL=http://admin.brightcove.com&amp;autoStart=false\" /><param name=\"wmode\" value=\"transparent\" /></object><br />");
        $this->addOption("BRIGHTCOVE_LINK",          "<a title=\"brightcove\" href=\"http://www.brightcove.com/title.jsp?title=###VID###\">brightcove ###TXT######THING###</a>");
        $this->addOption("ANIBOOM_TARGET",           "<object type=\"application/x-shockwave-flash\" data=\"http://api.aniboom.com/embedded.swf?videoar=###VID###&amp;allowScriptAccess=sameDomain&amp;quality=high\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('ANIBOOM_HEIGHT')."\"><param name=\"movie\" value=\"http://api.aniboom.com/embedded.swf?videoar=###VID###&amp;allowScriptAccess=sameDomain&amp;quality=high\" /><param name=\"wmode\" value=\"transparent\" /></object><br />");
        $this->addOption("ANIBOOM_LINK",             "<a title=\"aniBOOM\" href=\"http://www.aniboom.com/Player.aspx?v=###VID###\">aniBOOM ###TXT######THING###</a>");
        $this->addOption("VIMEO_TARGET",             "<object type=\"application/x-shockwave-flash\" data=\"http://www.vimeo.com/moogaloop.swf?clip_id=###VID###\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('VIMEO_HEIGHT')."\"><param name=\"movie\" value=\"http://www.vimeo.com/moogaloop.swf?clip_id=###VID###\" /><param name=\"wmode\" value=\"transparent\" /></object><br />");
        $this->addOption("VIMEO_LINK",               "<a title=\"vimeo\" href=\"http://www.vimeo.com/clip:###VID###\">vimeo ###TXT######THING###</a>");
        $this->addOption("GUBA_TARGET",              "<object type=\"application/x-shockwave-flash\" data=\"http://www.guba.com/f/root.swf?video_url=http://free.guba.com/uploaditem/###VID###/flash.flv&amp;isEmbeddedPlayer=true\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('GUBA_HEIGHT')."\"><param name=\"movie\" value=\"http://www.guba.com/f/root.swf?video_url=http://free.guba.com/uploaditem/###VID###/flash.flv&amp;isEmbeddedPlayer=true\" /><param name=\"wmode\" value=\"transparent\" /></object><br />");
        $this->addOption("GUBA_LINK",                "<a title=\"GUBA\" href=\"http://www.guba.com/watch/###VID###\">GUBA ###TXT######THING###</a>");
        $this->addOption("DAILYMOTION_TARGET",       "<object type=\"application/x-shockwave-flash\" data=\"http://www.dailymotion.com/swf/###VID###\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('DAILYMOTION_HEIGHT')."\"><param name=\"movie\" value=\"http://www.dailymotion.com/swf/###VID###\" /><param name=\"wmode\" value=\"transparent\" /></object><br />");
        $this->addOption("GARAGE_TARGET",            "<object type=\"application/x-shockwave-flash\" data=\"http://www.garagetv.be/v/###VID###/v.aspx\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('GARAGE_HEIGHT')."\"><param name=\"movie\" value=\"http://www.garagetv.be/v/###VID###/v.aspx\" /><param name=\"wmode\" value=\"transparent\" /></object><br />");
        $this->addOption("GAMEVIDEO_TARGET",         "<object type=\"application/x-shockwave-flash\" data=\"http://gamevideos.com:80/swf/gamevideos11.swf?embedded=1&amp;autoplay=0&amp;src=http://gamevideos.com:80/video/videoListXML%3Fid%3D###VID###%26adPlay%3Dfalse\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('GAMEVIDEO_HEIGHT')."\"><param name=\"movie\" value=\"http://gamevideos.com:80/swf/gamevideos11.swf?embedded=1&fullscreen=1&amp;autoplay=0&amp;src=http://gamevideos.com:80/video/videoListXML%3Fid%3D###VID###%26adPlay%3Dfalse\" /><param name=\"wmode\" value=\"transparent\" /></object><br />");
        $this->addOption("GAMEVIDEO_LINK",           "<a title=\"GameVideos\" href=\"http://gamevideos.com/video/id/###VID###\">GameVideos ###TXT######THING###</a>");
        $this->addOption("VSOCIAL_TARGET",           "<object type=\"application/x-shockwave-flash\" data=\"http://static.vsocial.com/flash/ups.swf?d=###VID###&a=0\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('VSOCIAL_HEIGHT')."\"><param name=\"movie\" value=\"http://static.vsocial.com/flash/ups.swf?d=###VID###&a=0\" /><param name=\"wmode\" value=\"transparent\" /></object><br />");
        $this->addOption("VSOCIAL_LINK",             "<a title=\"vSocial\" href=\"http://www.vsocial.com/video/?d=###VID###\">vSocial ###TXT######THING###</a>");
        $this->addOption("VEOH_TARGET",              "<object type=\"application/x-shockwave-flash\" data=\"http://www.veoh.com/videodetails2.swf?player=videodetailsembedded&type=v&permalinkId=###VID###&id=anonymous\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('VEOH_HEIGHT')."\"><param name=\"movie\" value=\"http://www.veoh.com/videodetails2.swf?player=videodetailsembedded&type=v&permalinkId=###VID###&id=anonymous\" /><param name=\"autostart\" value=\"true\" /><param name=\"wmode\" value=\"transparent\" /></object><br />");
        $this->addOption("VEOH_LINK",                "<a title=\"Veoh\" href=\"http://www.veoh.com/videos/###VID###\">Veoh ###TXT######THING###</a>");
        $this->addOption("GAMETRAILERS_TARGET",      "<object type=\"application/x-shockwave-flash\" data=\"http://www.gametrailers.com/remote_wrap.php?mid=###VID###\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('GAMETRAILERS_HEIGHT')."\"><param name=\"movie\" value=\"http://www.gametrailers.com/remote_wrap.php?mid=###VID###\" /><param name=\"autostart\" value=\"true\" /><param name=\"wmode\" value=\"transparent\" /></object><br />");
        $this->addOption("GAMETRAILERS_LINK",        "<a title=\"Gametrailers\" href=\"http://www.gametrailers.com/player/###VID###.html\">Gametrailers ###TXT######THING###</a>");
        $this->addOption("LOCAL_QUICKTIME_TARGET",   "<object classid=\"clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B\" codebase=\"http://www.apple.com/qtactivex/qtplugin.cab\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('QUICKTIME_HEIGHT')."\"><param name=\"src\" value=\"".Config::get('site_url')."###VID###\" /><param name=\"autoplay\" value=\"false\" /><param name=\"pluginspage\" value=\"http://www.apple.com/quicktime/download/\" /><param name=\"controller\" value=\"true\" /><!--[if !IE]> <--><object data=\"".Config::get('site_url')."###VID###\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('QUICKTIME_HEIGHT')."\" type=\"video/quicktime\"><param name=\"pluginurl\" value=\"http://www.apple.com/quicktime/download/\" /><param name=\"controller\" value=\"true\" /><param name=\"autoplay\" value=\"false\" /></object><!--> <![endif]--></object><br />");
        $this->addOption("LOCAL_FLASHPLAYER_TARGET", "<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('FLASHPLAYER_HEIGHT')."\"><param value=\"#FFFFFF\" name=\"bgcolor\" /><param name=\"movie\" value=\"".Config::get('site_url')."/wp-content/plugins/embedded-video-with-link/mediaplayer/player.swf\" /><param value=\"file=".Config::get('site_url')."###VID###&amp;showdigits=true&amp;autostart=false&amp;overstretch=false&amp;showfsbutton=false\" name=\"flashvars\" /><param name=\"wmode\" value=\"transparent\" /><!--[if !IE]> <--><object data=\"".Config::get('site_url')."/wp-content/plugins/embedded-video-with-link/mediaplayer/player.swf\" type=\"application/x-shockwave-flash\" height=\"".$this->getOption('FLASHPLAYER_HEIGHT')."\" width=\"".$this->getOption('embeddedvideo_width')."\"><param value=\"#FFFFFF\" name=\"bgcolor\"><param value=\"file=".Config::get('site_url')."###VID###&amp;showdigits=true&amp;autostart=false&amp;overstretch=false&amp;showfsbutton=false\" name=\"flashvars\" /><param name=\"wmode\" value=\"transparent\" /></object><!--> <![endif]--></object><br />");
        $this->addOption("LOCAL_TARGET",             "<object classid=\"clsid:22D6f312-B0F6-11D0-94AB-0080C74C7E95\" codebase=\"http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,7,1112\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('VIDEO_HEIGHT')."\" type=\"application/x-oleobject\"><param name=\"filename\" value=\"".Config::get('site_url')."###VID###\" /><param name=\"autostart\" value=\"false\" /><param name=\"showcontrols\" value=\"true\" /><!--[if !IE]> <--><object data=\"".Config::get('site_url')."###VID###\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('VIDEO_HEIGHT')."\" type=\"application/x-mplayer2\"><param name=\"pluginurl\" value=\"http://www.microsoft.com/Windows/MediaPlayer/\" /><param name=\"ShowControls\" value=\"true\" /><param name=\"ShowStatusBar\" value=\"true\" /><param name=\"ShowDisplay\" value=\"true\" /><param name=\"Autostart\" value=\"0\" /></object><!--> <![endif]--></object><br />");
        $this->addOption("LOCAL_LINK",               "<a title=\"Video File\" href=\"".Config::get('site_url')."###VID###\">Download Video</a>");
        $this->addOption("QUICKTIME_TARGET",         "<object classid=\"clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B\" codebase=\"http://www.apple.com/qtactivex/qtplugin.cab\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('QUICKTIME_HEIGHT')."\"><param name=\"src\" value=\"###VID###\" /><param name=\"autoplay\" value=\"false\" /><param name=\"pluginspage\" value=\"http://www.apple.com/quicktime/download/\" /><param name=\"controller\" value=\"true\" /><!--[if !IE]> <--><object data=\"###VID###\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('QUICKTIME_HEIGHT')."\" type=\"video/quicktime\"><param name=\"pluginurl\" value=\"http://www.apple.com/quicktime/download/\" /><param name=\"controller\" value=\"true\" /><param name=\"autoplay\" value=\"false\" /></object><!--> <![endif]--></object><br />");
        $this->addOption("FLASHPLAYER_TARGET",       "<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('FLASHPLAYER_HEIGHT')."\"><param value=\"#FFFFFF\" name=\"bgcolor\" /><param name=\"movie\" value=\"".Config::get('site_url')."/wp-content/plugins/embedded-video-with-link/mediaplayer/player.swf\" /><param value=\"file=###VID###&amp;showdigits=true&amp;autostart=false&amp;overstretch=false&amp;showfsbutton=false\" name=\"flashvars\" /><param name=\"wmode\" value=\"transparent\" /><!--[if !IE]> <--><object data=\"".Config::get('site_url')."/wp-content/plugins/embedded-video-with-link/mediaplayer/player.swf?file=###VID###\" type=\"application/x-shockwave-flash\" height=\"".$this->getOption('FLASHPLAYER_HEIGHT')."\" width=\"".$this->getOption('embeddedvideo_width')."\"><param value=\"#FFFFFF\" name=\"bgcolor\"><param value=\"file=###VID###&amp;showdigits=true&amp;autostart=false&amp;overstretch=false&amp;showfsbutton=false\" name=\"flashvars\"><param name=\"wmode\" value=\"transparent\" /></object><!--> <![endif]--></object><br />");
        $this->addOption("VIDEO_TARGET",             "<object classid=\"clsid:22D6f312-B0F6-11D0-94AB-0080C74C7E95\" codebase=\"http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,7,1112\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('VIDEO_HEIGHT')."\" type=\"application/x-oleobject\"><param name=\"filename\" value=\"###VID###\" /><param name=\"autostart\" value=\"false\" /><param name=\"showcontrols\" value=\"true\" /><!--[if !IE]> <--><object data=\"###VID###\" width=\"".$this->getOption('embeddedvideo_width')."\" height=\"".$this->getOption('VIDEO_HEIGHT')."\" type=\"application/x-mplayer2\"><param name=\"pluginurl\" value=\"http://www.microsoft.com/Windows/MediaPlayer/\" /><param name=\"ShowControls\" value=\"true\" /><param name=\"ShowStatusBar\" value=\"true\" /><param name=\"ShowDisplay\" value=\"true\" /><param name=\"Autostart\" value=\"0\" /></object><!--> <![endif]--></object><br />");
        $this->addOption("VIDEO_LINK",               "<a title=\"Video File\" href=\"###VID###\">Download Video</a>");
    }

}