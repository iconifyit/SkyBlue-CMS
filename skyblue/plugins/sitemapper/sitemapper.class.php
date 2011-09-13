<?php defined('SKYBLUE') or die("Bad file request");

/**
* @version      2.0 2009-04-19 10:37:00 $
* @package        SkyBlueCanvas
* @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
* @license        GNU/GPL, see COPYING.txt
* SkyBlueCanvas is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYING.txt for copyright notices and details.
*/

// The SiteMapper class creates XML Sitemaps for Google SiteMaps.

define('SITEMAP_XML_FILE', SB_SERVER_PATH.'sitemap.xml');

define('SITEMAP_HEAD', 
'<?xml version="1.0" encoding="UTF-8"?>'."\n".
'<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">'."\n".
'{entries}'.
'</urlset>'."\n");
define('SITEMAP_LINE', "    <url><loc>{loc}</loc><lastmod>{lastmod}</lastmod></url>\n");

class sitemapper {
    var $pages;
    var $xml;
    
    function __construct($channel_data = array()) {
        $this->GetPages();
        $this->BuildMap();
        $this->WriteMap();
    }
    
    function GetPages() {
        global $Core;
        $this->pages = get_pages();
    }
    
    function BuildMap() {
        global $Core;
        global $Router;
        
        if (!count($this->pages)) return;
        
        $this->xml = SITEMAP_HEAD;
        
        $lines = null;
        foreach ($this->pages as $page) {
            if (isset($page->published) && !$page->published) continue;
            $link = $Router->GetLink($page->id);
            if (strpos($link, '404-page-not-found') !== false) continue;
            $line = str_replace('{loc}', $link, SITEMAP_LINE);
            $line = str_replace('{lastmod}', date('Y-m-d\TH:i:s+00:00',time()), $line);
            $lines .= $line;
        }
        $this->xml = str_replace('{entries}', $lines, $this->xml);
    }
    
    function WriteMap() {
        global $Core;
        if (!empty($this->xml)) {
            FileSystem::write_file(SITEMAP_XML_FILE, $this->xml);
        }
    }
    
}