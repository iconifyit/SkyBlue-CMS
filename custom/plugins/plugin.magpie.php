<?php

/**
 * @version        RC 1.1 2008-08-14 18:12:00 $
 * @package        SkyBlueCanvas
 * @copyright      Copyright (C) 2005 - 2008 Scott Edwin Lewis. All rights reserved.
 * @license        GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

defined('SKYBLUE') or die("Bad file request");

/**
 * Display a feed in the HTML of your skin with the following format:
 * <!--#magpie(http://www.yoursite.com/rss_feed/*5)-->
 *
 * To specify the number of items to display, 
 * add *0-9 to the end of the feed url
 */

define('REGEX_MAGPIE_COMMENT_FEED', "/<!--#magpie\((.*)\)-->/i");

/**
 * Display a feed in the content of a text block with the following format:
 * {magpie(http://www.yoursite.com/rss_feed/*5)}
 *
 * To specify the number of items to display, 
 * add *0-9 to the end of the feed url
 */

define('REGEX_MAGPIE_INLINE_FEED',  "/{magpie\((.*)\)}/");

global $Core;

/**
* Register the plugin event
*/

Event::register('OnRenderPage', 'plg_magpie');

function plg_magpie($html) {
    global $Core;

    require_once(SB_PLUGIN_DIR . 'magpierss-0.72/rss_fetch.inc');

    if (preg_match_all(REGEX_MAGPIE_COMMENT_FEED, $html, $matches)) {
        for ($i=0; $i<count($matches[0]); $i++) {
            $token = $matches[0][$i];
            list($url, $count) = plgMagpieCount($matches[1][$i]);
            $rss   = fetch_rss($url);

            $links = null;
            $n=0;
            foreach ($rss->items as $item ) {
                if ($n > $count-1) continue;
                $links .= "<li><a href=\"{$item['link']}\" " 
                    . "rel=\"bookmark\">{$item['title']}</a></li>\n";
                $n++;
            }
            
            $feed = 
            "<h2>{$rss->channel['title']}</h2>\n" . 
            "<div class=\"blog-links\">\n" . 
            // "<h2 class=\"body-title\">" . __('GLOBAL.RECENT_BLOG_POSTS', 'Recent Blog Posts', 1) . "</h2>\n" . 
            "<ul class=\"links\">\n";
            $feed .= $links;
            $feed .= "</ul>\n";
            $feed .= "</div>\n";
            $html = str_replace($token, $feed, $html);
        }        
    }
    
    if (preg_match_all(REGEX_MAGPIE_INLINE_FEED, $html, $matches)) {
        for ($i=0; $i<count($matches[0]); $i++) {
            $token = $matches[0][$i];
            $url   = $matches[1][$i];
            $rss   = fetch_rss($url);
        
            $links = null;
            $n=0;
            foreach ($rss->items as $item ) {
                if ($n > 4) continue;
                $links .= "<li><a href=\"{$item['link']}\" " 
                    . "rel=\"bookmark\">{$item['title']}</a></li>\n";
                $n++;
            }
            
            $feed = 
            "<div class=\"blog-links\">\n" . 
            "<h2 class=\"linksgroup\">Latest Blog Entries</h2>\n" . 
            "<ul class=\"links\">\n";
            $feed .= $links;
            $feed .= "</ul>\n";
            $feed .= "</div>\n";
            $html = str_replace($token, $feed, $html);
        }        
    }
    
    return $html;
}

function plgMagpieCount($url) {
    if (strpos($url, '#') === false) return $url;
    $bits = explode('#', $url);
    return array($bits[0], $bits[1]);
}

?>
