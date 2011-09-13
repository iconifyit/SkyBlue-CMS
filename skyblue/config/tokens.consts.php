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
# Content replacement tokens
# ###################################################################################

# sb_conf('TOKEN_BUILD',             '{build}');
sb_conf('TOKEN_PAGE_TITLE',        '{page:title}');
sb_conf('TOKEN_EDITOR',            '<!--#html.editor.links-->');
sb_conf('TOKEN_SCRIPTS',           '{inc:scripts}');
sb_conf('TOKEN_ADMIN_NAV',         '<!--{admin.nav}-->');
sb_conf('TOKEN_DASHBOARD',         '{page:dashboard}');
sb_conf('TOKEN_CONTENT',           '{page:content}');
sb_conf('TOKEN_SB_VERSION',        '{skyblue:version}');
sb_conf('TOKEN_SB_NAME',           '{skyblue:name}');
sb_conf('TOKEN_LINK_LOGOUT',       '<!--{admin.logout}-->');
sb_conf('TOKEN_LINK_INBOX',        '<!--{admin.inbox}-->');
sb_conf('TOKEN_ANALYTICS',         '<!--analytics-->');
sb_conf('TOKEN_INFO_IFRAME',       '<!--{dashboard.info}-->');
sb_conf('TOKEN_UNREAD_MESSAGES',   '{unread}');
sb_conf('TOKEN_SKYBLUE_INFO_LINK', '{skyblue:link}');
sb_conf('TOKEN_BODY_CLASS',        '{body:class}');