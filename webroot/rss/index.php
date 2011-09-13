<?php

/**
* @version        v 1.1 2008-12-04 00:43:00 $
* @package        SkyBlueCanvas
* @copyright      Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
* @license        GNU/GPL, see COPYING.txt
* SkyBlueCanvas is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYING.txt for copyright notices and details.
*/

define('DS', DIRECTORY_SEPARATOR);

define('SKYBLUE', 1);
define('_SBC_ROOT_', '../../');
define('BASE_PAGE', 'index.php');

$here = dirname(__FILE__);

define('_SBC_', implode('/', array_slice(explode('/', $here), 0, -2)) . '/');
define('_SBC_SYS_',  _SBC_ . 'skyblue/');
define('_SBC_APP_',  _SBC_ . 'custom/');
define('_SBC_WWW_',  _SBC_ . 'webroot/');

require_once(_SBC_SYS_ . 'base.php');
require_once('./functions.php');

$Core = new Core(array(
    'path'     => _SBC_ROOT_,
    'lifetime' => 3600,
    'events'   => array()
));

$Router = new Router(_SBC_ROOT_);

Config::load();

define('RSS_TEXT_LENGTH', 500);
define('RSS_NO_DESCRIPTION', 'No description available.');

$Cache = new Cache('rss.xml', 60);

if (Config::get('use_cache', 0) && $Cache->isCached()) {
    $rss = $Cache->getCache();
    $rss .= "\n<!-- from cache -->\n";
}
else {
    $pages = get_pages();
    $fragments = FileSystem::list_dirs(SB_FRAGMENTS_DIR);
    ob_start();
?>   
<rss version="2.0">
    <channel>
        <title><?php echo Config::get('site_name'); ?></title>
        <link><?php echo Config::get('site_url'); ?></link>
        <description><![CDATA[<?php echo rss_site_description(); ?>]]></description>
        <copyright><?php echo @date('Y') . ' - ' . Config::get('site_url'); ?></copyright>
        <language><?php echo Config::get('site_lang'); ?></language>
        <generator>SkyBlueCanvas <?php echo SB_VERSION; ?></generator>
        <!-- Page Items -->
        <?php foreach ($pages as $Page) : ?>
        <?php if (! intval($Page->getSyndicate())) continue; ?>
        <?php if ($Page->getIs_error_page()) continue; ?>
        <item>
            <guid><?php echo $Router->GetLink($Page->getId()); ?></guid>
            <pubDate><?php echo rss_date($Page->getModified()); ?></pubDate>
            <title><![CDATA[<?php echo $Page->title; ?>]]></title>
            <link><?php echo $Router->GetLink($Page->getId()); ?></link>
            <description><![CDATA[<?php echo rss_story_text($Page->getStory_content()); ?>]]></description>
        </item>
        <?php endforeach; ?>
        <!-- Fragments Feeds -->
        <?php
            for ($i=0; $i<count($fragments); $i++) {
                if (file_exists("{$fragments[$i]}rss.php")) {
                    @include("{$fragments[$i]}rss.php");
                }
            }
        ?>
    </channel>
</rss>
<?php 
    $rss = ob_get_contents(); 
    ob_end_clean();
}
    $Cache->saveCache($rss . "\n<!--cached on: " . date(DATE_RFC822) . "-->");
    header('Content-type: text/xml');
    echo $rss;
?>