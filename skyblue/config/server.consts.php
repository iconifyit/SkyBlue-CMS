<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version        2.0 2010-01-30 00:01 $
 * @package        SkyBlueCanvas
 * @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license        GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

/**
 * NOTE:
 * The constants files MUST be loaded in the following order:
 * 
 * server.consts.php
 * dirs.consts.php
 * files.consts.php
 * strings.consts.php
 * tokens.consts.php
 * regex.consts.php
 */


/**
 * SB_SERVER_PATH:
 * Do not edit this setting unless you are certain you know what you are doing.
 */

sb_conf('SB_SERVER_PATH', str_replace('config', null, dirname(__FILE__)));

/**
 * The path to the directory where session files are saved. Typically this is /tmp 
 * and does not need to be changed.
 */
sb_conf('SB_SESSION_SAVE_PATH', '/tmp');


/**
 * The path scope of session cookies. 
 */
sb_conf('SB_COOKIE_PATH', '/');

/**
 * The PHP Session ID Variable 
 */
sb_conf('SB_PHPSESSID', 'PHPSESSID');

/**
 * The default session lifetime in seconds 
 */
sb_conf('SB_SESSION_LIFETIME', 86400);

/**
 * The relative path from the WYMeditor iframe to the SBC root. 
 */
sb_conf('WYM_RELATIVE_PATH', '../../../../../../');

# ###################################################################################
# SITE tells SkyBlueCanvas where to find the user data.
# ###################################################################################

sb_conf('SITE', 'data');

# ###################################################################################
# SB_GID tells SKyBlueCanvas the access privileges of the current user. 
# 1 = User (Cannot edit skins)
# 2 = Admin (Cannot edit skins)
# 3 = Super Admin (can install and edit skins.
# ###################################################################################

sb_conf('SB_GID', 3);

# ###################################################################################
# WARNING! YOU SHOULD NOT NEED TO MODIFY ANY SETTING BELOW THIS POINT.
# ###################################################################################

# ###################################################################################
# SB_SETUP_PAGE defines the path to the setup wizard.
# ###################################################################################

sb_conf('SB_SETUP_PAGE', 'setup/index.php');

# ###################################################################################
# SB_PASS_SALT is used to prevent dictionary style attacks on the password 
# fingerprint. You can set this to any string you want but once it is set, 
# if the salt value is changed, all username and passwords for every site 
# will need to be updated.
# ###################################################################################

sb_conf('SB_PASS_SALT', 'voodoo47trail2');

# ###################################################################################
# SB_MAX_LOOP is set so you can add safeguards against endless loops
# to your code. Example:
#
# $i=0;
# while ($condition && $i<=SB_MAX_LOOP)
# {
#     DoSomething();
#     $i++;
# }
# ###################################################################################

sb_conf('SB_MAX_LOOP', 500);

# ###################################################################################
# SB_MAX_LIST_ROWS limits the number of items displayed in the list view of 
# all SkyBlueCanvas managers. If the number of items exceeds this limit, 
# the content is over-flowed using the CSS attribute overflow: auto;
# ###################################################################################

sb_conf('SB_MAX_LIST_ROWS', 25);

# ###################################################################################
# MAX_NAME_DISPLAY_LEN limits the length of item names shown in the list view 
# of SkyBlueCanvas managers. This prevents each item from breaking over two 
# lines and therefore making the list harder to read.
# ###################################################################################

sb_conf('MAX_NAME_DISPLAY_LEN', 26);

# ###################################################################################
# MAX_THUMB_DIM limits the width and/or height of admin thumbnails.
# ###################################################################################

sb_conf('MAX_THUMB_DIM', 100);

# ###################################################################################
# SB_MAX_IMG_UPLOAD_SIZE limits the size of image uploads. Apache and PHP set 
# their own limits to 2MB. If you change the setting here to higher than 2MB, 
# be sure to change the settings in php.ini and httpd.conf as well.
# ###################################################################################

sb_conf('SB_MAX_IMG_UPLOAD_SIZE', ((6 * 1024) * 1024));

# ###################################################################################
# SB_MAX_FILE_UPLOAD_SIZE limits the size of form file uploads. Apache and PHP set 
# their own limits to 2MB. If you change the setting here to higher than 2MB, 
# be sure to change the settings in php.ini and httpd.conf as well.
# ###################################################################################

sb_conf('SB_MAX_FILE_UPLOAD_SIZE', ((2 * 1024) * 1024));

# ###################################################################################
# SB_LANGUAGE sets the current language. Full support for 
# localization has not been implemented as of 16 Jan, 2007 so this setting 
# should not be changed until full support is implemented.
# ###################################################################################

sb_conf('SB_LANGUAGE', 'en');

# ###################################################################################
# SB_ADMIN_TIMEOUT and SB_USER_TIMEOUT define the length of time in seconds before
# a login is timed out.  A timeout of 0 means, the login never expires.
# ###################################################################################

sb_conf('SB_ADMIN_TIMEOUT', 86400);
sb_conf('SB_USER_TIMEOUT', 86400);

# ###################################################################################
# SB_DIALOG_TIMEOUT sets the number of microseconds a dialog is displayed before 
# it fades out.
# ###################################################################################

sb_conf('SB_DIALOG_TIMEOUT', 10000); 

# ###################################################################################
# The following settings are used as the 'This site created by' comment that appears 
# at the end of each page generated by SkyBlue.
# ###################################################################################

sb_conf('SB_PROD_NAME',    '&copy; 2010 Scott Lewis. SkyBlueCanvas');
sb_conf('SB_TAGLINE',      'Point. Click. Publish.');
sb_conf('SB_VERSION',      '2.0');

# ###################################################################################
# SKYBLUE_INFO_LINK is set here so that it does not need to be updated on
# every SkyBlueCanvas site if the SkyBlueCanvas page changes on the
# Bright-Crayon site. To automatically include this link on a site, simply
# place the token {skyblue:link} anywhere in your skin.
# ###################################################################################

sb_conf('SKYBLUE_INFO_LINK', '<a href="http://www.skybluecanvas.com">SkyBlueCanvas</a>');

# ###################################################################################
# SKYBLUE_BUILD is used in the META data.
# ###################################################################################

sb_conf('SKYBLUE_BUILD', '2010-01-30 00:01');

# ###################################################################################
# NOTE: All constants beginning with 'BIN_' refer to system binaries. The safest 
# approach to using binaries is to use the full path to the binary. The location 
# of binaries may change from system to system so you may need to check with your 
# server administrator to get the correct path information. The default values 
# in SkyBlueCanvas are for MacOS X 10.4 (Panther), which is based on FreeBSD.
# ###################################################################################

sb_conf('BIN_ZIP', '/usr/bin/zip');
sb_conf('BIN_UNZIP', '/usr/bin/unzip');