<?php defined('SKYBLUE') or die('Bad file request');

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

# ###################################################################################
# WARNING: YOU SHOULD NOT EDIT THIS FILE UNLESS YOU KNOW WHAT YOU ARE DOING!!!
#
# Changing any of the settings in this file can very easily cause SkyBlue to 
# not work. Edit this file at your own risk.
# ###################################################################################

# ###################################################################################
# PATH_PREFIX is the base path for the current site relative to 
# the SkyBlueServer engine.
# ###################################################################################

sb_conf('PATH_PREFIX', 'data/');

# ###################################################################################
# SITES_DIR tells SkyBlue where to find the websites it is currently managing. 
# It is not recommended that this be changed.
# ###################################################################################

sb_conf('SITES_DIR', _SBC_APP_.'sites/');

# ###################################################################################
# SB_SITE_DATA_DIR points to the directory for a specific site. DO NOT edit 
# this constant.
# ###################################################################################

sb_conf('SB_SITE_DATA_DIR', _SBC_APP_.'data/');

# ###################################################################################
# SB_FRAGMENTS_DIR points to fragments library
# ###################################################################################

sb_conf('SB_FRAGMENTS_DIR', _SBC_APP_ . 'fragments/');

# ###################################################################################
# SB_SITE_DATA_DIR points to the directory for a specific site. DO NOT edit 
# this constant.
# ###################################################################################

sb_conf('SB_USER_PLUGINS_DIR', _SBC_APP_ . 'plugins/');

# ###################################################################################
# SB_SITE_SNIPPETS_DIR points to the directory where user-created code snippets
# are stored
# ###################################################################################

sb_conf('SB_SITE_SNIPPETS_DIR', SB_SITE_DATA_DIR . 'snippets/');

# ###################################################################################
# SB_RSS_DIR points to the rss directory within a specific site
# ###################################################################################

sb_conf('SB_RSS_DIR', _SBC_WWW_ . 'rss/');

# ###################################################################################
# SB_LANG_DIR is language package directory.
# ###################################################################################

sb_conf('SB_LANG_DIR', _SBC_WWW_ . 'languages/');

# ###################################################################################
# User Site Data Paths
# SB_STORY_DIR points to the location of the text (story) files for a 
# particular site.
# NOTE: This setting must be checked with 'defined' first because the 
# Wymeditor preview function sets the xml directory to site/tmp/ to build 
# pages on the fly that may not have been saved or published.
# ###################################################################################

sb_conf('SB_STORY_DIR', SB_SITE_DATA_DIR.'stories/');

# ###################################################################################
# SB_TMP_DIR points to the location of tmp dir for the site currently being edited.
# ###################################################################################

sb_conf('SB_TMP_DIR', '/tmp/');

# ###################################################################################
# SB_DOWNLOADS_DIR points to the location of downloadable files within a 
# particular site's data structure.
# SB_UPLOADS_DIR points to the location where files uploaded via forms will be
# stored within a particular site's data structure.
# ###################################################################################

sb_conf('SB_DOWNLOADS_DIR', _SBC_WWW_.'downloads/');
sb_conf('SB_UPLOADS_DIR', _SBC_WWW_.'uploads/');

# ###################################################################################
# SB_XML_DIR points to the location of the XML data storage files.
# NOTE: This setting must be checked with 'defined' first because the 
# Wymeditor preview function sets the xml directory to site/tmp/ to build 
# pages on the fly that may not have been saved or published.
# ###################################################################################

sb_conf('SB_XML_DIR', SB_SITE_DATA_DIR.'xml/');

# ###################################################################################
# SB_LIB_DIR points to the location of the lib files. The use of libs may be 
# deprecated in the next version so any custom development should not use this 
# directory.
# ###################################################################################

sb_conf('SB_LIB_DIR', SB_BASE_PATH.'libs/');

# ###################################################################################
# SB_MEDIA_DIR points to the location of images and multi-media files 
# (e.g., MPG, MP3, Flash, etc.) within a particular sites data structure.
# ###################################################################################

sb_conf('SB_MEDIA_DIR', _SBC_WWW_ . 'media/');

# ###################################################################################
# SB_INC_DIR points to the location of the includes directory within the 
# SkyBlueServer engine directory structure.
# ###################################################################################

sb_conf('SB_INC_DIR', _SBC_SYS_ . 'includes/');

# ###################################################################################
# SB_SKINS_DIR points to the location of the skins within a specific site's 
# data directory structure.
# ###################################################################################

sb_conf('SB_SKINS_DIR', 'skins/');

# ###################################################################################
# SB_UI_DIR points to the location of the SkyBlueCanvas user interface directory 
# ###################################################################################

sb_conf('SB_UI_DIR', 'resources/ui/');

# ###################################################################################
# SB_ADMIN_UI_DIR points to the location of the HTML files for the admin UI.
# ###################################################################################

sb_conf('SB_ADMIN_UI_DIR', SB_UI_DIR . 'admin/');

# ###################################################################################
# SB_ADMIN_SKINS_DIR points to the location of the HTML files for the admin UI.
# ###################################################################################

sb_conf('SB_ADMIN_SKINS_DIR', SB_UI_DIR . 'admin/html/');

# ###################################################################################
# SB_ADMIN_IMG_DIR points to the location of the image files for the admin UI.
# ###################################################################################

sb_conf('SB_ADMIN_IMG_DIR', SB_UI_DIR . 'admin/images/');

# ###################################################################################
# SB_MANAGERS_DIR points to the location of the Managers for the admin section.
# ###################################################################################

sb_conf('SB_MANAGERS_DIR', _SBC_SYS_ . 'managers/');

# ###################################################################################
# SB_APP_MANAGERS_DIR points to the location of the Managers for the admin section.
# ###################################################################################

sb_conf('SB_APP_MANAGERS_DIR', _SBC_APP_ . 'managers/');

# ###################################################################################
# SB_CONFIGS_DIR points to the location of configuration and constants files 
# for SkyBlueCanvas.
# ###################################################################################

sb_conf('SB_CONFIGS_DIR', _SBC_SYS_ . 'config/');

# ###################################################################################
# SB_PLUGIN_DIR points to the location of SkyBlueCanvas plugins.
# ###################################################################################

sb_conf('SB_PLUGIN_DIR', _SBC_SYS_ . 'plugins/');

# ###################################################################################
# SB_EDITORS_DIR defines where the editors are stored.
# ###################################################################################

sb_conf('SB_EDITORS_DIR', _SBC_WWW_ . 'resources/editors/');

# ###################################################################################
# SB_EDITORS_DIR defines where the editors are stored.
# ###################################################################################

sb_conf('SB_MANAGER_RESOURCES', 'resources/managers/');

# ###################################################################################
# SB_UI_DEFAULTS_DIR points to the location of the SkyBlueCanvas default 
# UI settings and features (i.e., CSS for built-in DropDown Menus).
# ###################################################################################

sb_conf('SB_UI_DEFAULTS_DIR', 'ui/front/');

# ###################################################################################
# SB_UI_ELEMENTS_DIR points to the location of the SkyBlueCanvas 
# UI elements JS directory
# ###################################################################################

sb_conf('SB_UI_ELEMENTS_DIR', 'ui/elements/');

# ###################################################################################
# SB_SYSTEM_JS_DIR points to the location of the JavaScript files used by 
# the SkyBlueServer engine. Note that these files are different from any 
# JavaScript associated with a specific site skin.
# ###################################################################################

sb_conf('SB_SYSTEM_JS_DIR', SB_UI_DEFAULTS_DIR.'js/');

# ###################################################################################
# SB_SYSTEM_CSS_DIR points to the location of the CSS files used by 
# the SkyBlueServer engine. Note that these files are different from any 
# CSS associated with a specific site skin.
# ###################################################################################

sb_conf('SB_SYSTEM_CSS_DIR', SB_UI_DEFAULTS_DIR.'css/');

# ###################################################################################
# SB_SYSTEM_IMG_DIR points to the location of the image files used by 
# the SkyBlueServer engine. Note that these files are different from any 
# images associated with a specific site skin.
# ###################################################################################

sb_conf('SB_SYSTEM_IMG_DIR', SB_UI_DEFAULTS_DIR.'images/');

# ###################################################################################
# SB_ADMIN_JS points to the location of the JavaScript files used by the admin 
# section of the SkyBlueServer engine. This directory and setting are different 
# from the System JavaScript files. System JavaScript files can be used by 
# individual sites, whereas the admin JavaScript files are specific to the 
# admin section only.
# ###################################################################################

sb_conf('SB_ADMIN_JS', 'ui/js/');