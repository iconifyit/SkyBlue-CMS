<?php defined('SKYBLUE') or die('Bad File Request');

/**
 * @version        v 1.2 2009-06-15 23:55:00 $
 * @package        SkyBlueCanvas
 * @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license        http://opensource.org/licenses/gpl-license.php GNU Public License
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

function rss_syndicated($item) {
    if (!isset($item->syndicate)) return true;
    return $item->syndicate;
}

function rss_date($date) {
    return date("D, j M Y H:i:s T", strtotime(str_replace('T', null, $date)));
}

function rss_site_description() {
    Loader::load("managers.meta.helpers.*", true, _SBC_SYS_);
    $description = RSS_NO_DESCRIPTION;
    $meta = MetaHelper::getMetaData();
    foreach ($meta as $m) {
        if ($m->name == 'description') {
            $description = $m->content;
            break;
        }
    }
    return $description;
}

function rss_end_of_sentence($shred) {
    if ($shred{strlen($shred)-1} == '.' || 
        strlen($shred) < 1 || 
        strpos($shred, '.') === false)
    {
        return $shred;
    }
    else {
        $words = explode(' ', $shred);
        for ($i=count($words); $i>0; $i--) {
            $word = $words[$i];
            if ($word == '.' || $word{strlen($word)-1} == '.') {
                $shred = implode(' ', array_slice($words, 0, $i+1));
            }
        }
        return $shred;
    }
}

function rss_filter_tokens($text) {
    // Filter out snippet calls
    $regex = "/({snippet\(([^}]*)\)})/i";
    if (preg_match_all($regex, $text, $matches)) {
        $tokens = $matches[0];
        for ($i=0; $i<count($tokens); $i++) {
            $text = str_replace($tokens[$i], "", $text);
        }
    }
    // Filter out fragment calls
    $regex = "/({fragment\(([^}]*)\)})/i";
    if (preg_match_all($regex, $text, $matches)) {
        $tokens = $matches[0];
        for ($i=0; $i<count($tokens); $i++) {
            $text = str_replace($tokens[$i], "", $text);
        }
    }
    // Filter out skyblue vars
    $regex = "/\[\[(.*)\]\]/i";
    if (preg_match_all($regex, $text, $matches)) {
        $tokens = $matches[0];
        for ($i=0; $i<count($tokens); $i++) {
            $text = str_replace($tokens[$i], "", $text);
        }
    }
    return $text;
}

function rss_text_blob($shred, $length=RSS_TEXT_LENGTH) {
     if (strlen($shred) <= $length) return rss_filter_tokens($shred);
     $text = null;
     $n=0;
     while (strlen($text) < $length || $n==$length) {
         $text .= $shred{$n};
         $n++;
     }
     return rss_filter_tokens($text);
}

function rss_story_text($content) {
    global $Core;
    if (trim($content) != "") {
        $content = base64_decode($content);
        $shred = str_replace(">", "> ", $content);
        if (trim($shred) != "") {
            $shred = rss_text_blob(strip_tags($shred), RSS_TEXT_LENGTH);
        }
        return ( trim($shred) == "" ? RSS_NO_DESCRIPTION : $shred );
    }
    return RSS_NO_DESCRIPTION;
}