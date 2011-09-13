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

define('SB_INCLUDE_SECURITY_HEADER', "<?php defined('SKYBLUE') or die('Bad file request'); ?>\n");

# ###################################################################################
# JQUERY TABS : Constants for the jQuery Tab panels
# ###################################################################################

define('TABS_JS',  '$("#tabs").tabs({fx: {opacity: "toggle"}, cookie: { expires: 30 }});');
define('TABS_CSS', '#tabs {height: auto; margin-bottom: 20px;}');

# ###################################################################################
# CONTEXT_ADMIN   - String constant for the admin section context.
# CONTEXT_FRONT   - String constant for the front end context
# CONTEXT_UNKNOWN - String constant for undetermined context
# ###################################################################################

sb_conf('CONTEXT_ADMIN',   'admin');
sb_conf('CONTEXT_FRONT',   'front');
sb_conf('CONTEXT_UNKNOWN', 'unknown');

# ###################################################################################
# UI_THEME_NAME is the string name of the jQuery UI Theme
# ###################################################################################

sb_conf('JQUERY_UI_THEME',   'smoothness');
sb_conf('JQUERY_UI_VERSION', 'jquery-ui-1.8.1');
sb_conf('JQUERY_VERSION',    'jquery-1.4.2');

# ###################################################################################
# UI_STATE_* CSS Class strings for various UI states
# ###################################################################################

sb_conf('UI_STATE_ERROR',     'ui-state-error');
sb_conf('UI_STATE_SUCCESS',   'ui-state-success');
sb_conf('UI_STATE_INFO',      'ui-state-info');
sb_conf('UI_STATE_HIGHLIGHT', 'ui-state-highlight');

sb_conf('UI_ICON_ERROR',      'ui-icon-alert');
sb_conf('UI_ICON_SUCCESS',    'ui-icon-check');
sb_conf('UI_ICON_INFO',       'ui-icon-info');

# ###################################################################################
# UI_ICON_SET is the string name of the Dashboard UI Icon Set
# ###################################################################################

sb_conf('UI_ICON_SET',   'milky');

# ###################################################################################
# SB_SITEMAPPER_CLASS is the string name of the Sitemapper plugin class
# ###################################################################################

sb_conf('SB_SITEMAPPER_CLASS', 'sitemapper');
sb_conf('SB_SAFE_URL_CHARS',   'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-');
sb_conf('SB_PASSWORD_CHARS',   'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_@$%*&!()+=[]{}');

# ###################################################################################
# SB_NOT_ENOUGH_PRIVILEGES is the HTML for the message telling the user that 
# they do not have sufficient privileges to access a particular manager or 
# feature.
# ###################################################################################

sb_conf('SB_NOT_ENOUGH_PRIVILEGES', 
    "<h2 class=\"caution\">[TERM:STR.NOTE_ENOUGH_PERMS]</h2>\n"
);

# ###################################################################################    
# SB_MSG_STRING defines the default HTML block for displaying SkyBlue Admin UI
# messages.
# ###################################################################################

sb_conf('SB_MSG_STRING', '<h2 class="message">{msg}</h2>');
sb_conf('SB_MSG_EDIT',   '<h2 class="edit">{msg}</h2>');

# ###################################################################################    
# SB_DATE_MODIFIED_FORMAT defines the date format for modification 
# date of content items.
# ###################################################################################

sb_conf('SB_DATE_MODIFIED_FORMAT', 'Y-m-d\TH:i:s+00:00');

# ###################################################################################
# SB_FATAL_INSTALL_ERROR defines the message seen when the SetUp Assistant 
# cannot complete the setup process.
# ###################################################################################

sb_conf('SB_FATAL_INSTALL_ERROR', '[TERM:STR.BASE_INSTALL_FAILED]');

# ###################################################################################
# SB_NO_ITEMS_TO_DISPLAY defines the message seen when there are no data 
# items to display in the Managers list view.
# ###################################################################################

sb_conf('SB_NO_ITEMS_TO_DISPLAY', '[TERM:STR.NO_ITEMS_TO_SHOW]');

# ###################################################################################
# NO_ITEMS_TO_ORDER_STRING defines the message seen when there are no items to order, 
# i.e., there are 0-1 items in a list.
# ###################################################################################

sb_conf('NO_ITEMS_TO_ORDER_STRING', '[TERM:STR.NO_ITEMS_TO_SORT]');

# ###################################################################################
# WILL_SHOW_AFTER_SAVE defines the message seen when there are items that will be 
# displayed after the first save.
# ###################################################################################

sb_conf('WILL_SHOW_AFTER_SAVE', '[TERM:STR.SHOW_AFTER_SAVE]');

# ###################################################################################
# NO_GROUPS_STRING defines the message seen when there are no groups defined for a 
# manager that has items and groups.
# ###################################################################################

sb_conf('NO_GROUPS_STRING', '[TERM:STR.NO_GROUPS]');

sb_conf( 'MSG_FEATURE_DISABLED_IN_DEMO', '[TERM:STR.DISABLED_IN_DEMO]');
    
sb_conf('MSG_NO_DELETE_MENUS', '[TERM:STR.TOP_AND_MAIN_MENU]');

sb_conf('IMG_TRAIL_HTML', 
    '<img src="{camera}" width="20" height="14" '
    . 'onmouseover="showtrail(this, \'{src}\');" '
    . 'onmouseout="hidetrail();" />&nbsp;&nbsp;|&nbsp;&nbsp;'
);

# ###################################################################################
# The in-line style for lists that are longer than the max list length.
# ###################################################################################

define('sLIST_OVERFLOW_STYLE', 'style="height: auto; overflow: auto;"');
define('sLIST_OVERFLOW_HEIGHT_STYLE', 'style="height: 400px; overflow: auto;"');
define('sLIST_OVERFLOW_WIDTH_STYLE', 'style="width: 100%;"');

define('sCONFIRM_DELETE_JS', 'return confirmDelete(\'{name}\', 0);');

# ###################################################################################
# Constant for identifying a 404 Not Found error
# ###################################################################################

sb_conf('NOT_FOUND', 'notfound');

# ##################################################################################
# Constants used in password validation
# ##################################################################################

sb_conf('PW_LEGAL_CHARS',
    'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'."\n".
    '0123456789!@#$%&*'
);

# ###################################################################################
# The following constants support SkyBlueCanvas's {site:var} feature.
# It may seem like a lack of planning that the replacement values for all of 
# the vars are not defined here, but this was unavoidable as some of the values 
# are dependent on other constants that are set dynamically. As much as possible 
# I tried to define all values here but in cases where it was necessary to 
# define values elsewhere, I did so where the constants most logically fit
# (i.e., dirs.consts.php, server.consts.php, etc.)
# ###################################################################################

sb_conf('SB_VALIDATE_XHTML', 'http://validator.w3.org/check?uri=referer');
sb_conf('SB_VALIDATE_CSS', 'http://jigsaw.w3.org/css-validator/check/referer');
sb_conf('SB_NOSCRIPT', 
    "<noscript><h2>[TERM:STR.JS_REQUIRED]</h2></noscript>\n"
);

sb_conf('VAR_SITE_NAME',     '{site:name}');
sb_conf('VAR_SITE_CONTACT',  '{site:contact}');
sb_conf('VAR_SITE_URL',      '{site:address}');
sb_conf('VAR_SITE_SLOGAN',   '{site:slogan}');
sb_conf('VAR_SITE_RSS',      '{site:rss}');
sb_conf('VAR_SITE_XHTML',    '{site:xhtml}');
sb_conf('VAR_SITE_CSS',      '{site:css}');
sb_conf('VAR_SITE_NOSCRIPT', '{site:noscript}');
sb_conf('VAR_SB_PROD_NAME',  '{skyblue:name}');
sb_conf('VAR_SB_VERSION',    '{skyblue:version}');
sb_conf('VAR_SB_TAGLINE',    '{skyblue:tagline}');
sb_conf('VAR_SB_INFO_LINK',  '{skyblue:link}');

sb_conf('VAR_MGR',    'mgr');
sb_conf('VAR_COM',    'com');
sb_conf('VAR_REDIR',  'redir');
sb_conf('VAR_ACTION', 'action');
sb_conf('VAR_EVENT',  'event');
sb_conf('VAR_ISAJAX', 'is_ajax');

sb_conf('LINK_LOGOUT', '<a href="admin.php?com=login&amp;action=logout">[TERM:LOGOUT]</a>');
sb_conf('LINK_INBOX',  '<a href="admin.php?com=email">[TERM:INBOX]{unread}</a>');
sb_conf('TASK_COL_WIDTH', '150');
sb_conf('TASK_SEPARATOR', '|');

sb_conf('CHECK_OUT_NOT_OWNER', -1);
sb_conf('CHECK_OUT_SYS_ERR',    0);

# ###################################################################################
# The 'Info' iframe on the main dashboard
# ###################################################################################

sb_conf('INFO_IFRAME_SRC',   'info.php');
sb_conf('INFO_IFRAME_TAG',   '<iframe src="info.php" frameborder="no" scrolling="no"></iframe>');

# ###################################################################################
# DEFAULT_HTML is a default HTML body to use in case the skin HTML file cannot be 
# found for whatever reason. This avoids an error causing the page build to fail.
# ###################################################################################

sb_conf('DEFAULT_HTML',
'<div id="wrapper" style="width: 720px; margin: 0px auto;">'."\n".
'    <div id="header">'."\n".
'       <h1>'."\n".
'           <a href="{site:address}">{site:name}</a>'."\n".
'       </h1>'."\n".
'   </div>'."\n".
'   <h2>The specified skin could not be found</h2>'."\n".
'   <div id="top">'."\n".
'       {region:top}'."\n".
'   </div>'."\n".
'   <div id="left">'."\n".
'       {region:left}'."\n".
'   </div>'."\n".
'   <div id="main">'."\n".
'       {region:main}'."\n".
'   </div>'."\n".
'   <div id="right">'."\n".
'       {region:right}'."\n".
'   </div>'."\n".
'   <div id="footer">'."\n".
'       {region:footer}'."\n".
'   </div>'."\n".
'</div>'."\n");

# ###################################################################################
# In rare instances where no default page is set and no 404 page exists, the 
# NO_404_PAGE text is output instead.
# ###################################################################################

sb_conf('NO_404_PAGE', "<h2>[TERM:STR.404.HEADING]</h2>\n<p>[TERM:STR.404.MSG]</p>\n");

# ##################################################################################
# Constants used by the User Manager/ACLs
#
# USER_LOGIN_FORM_REGION should be set to the skin template region where the login
#   form should be displayed if a user attempts to access a page they are not
#   authorized to.
# USER_LOGIN_SHOW_REGIONS should be set to list of regions it is okay to show to
#   unauthorized users when they are asked to log in.  Only regions that don't
#   contain protected content should appear in these regions.
# ##################################################################################

sb_conf('USER_LOGIN_FORM_REGION', 'main');
sb_conf('USER_LOGIN_SHOW_REGIONS', 'styles,scripts,favicon');

sb_conf('USER_OK',                  null);
sb_conf('USER_INVALID_LOGIN',      '[TERM:ACL.INVALID_LOGIN]');
sb_conf('USER_NO_SITE_ADMIN_EDIT', '[TERM:USERS.NO_SITE_ADMIN_EDIT]');
       
sb_conf('USER_NO_SITE_ADMIN_DEL', '[TERM:USERS.USER_NO_SITE_ADMIN_DEL]');

sb_conf('USER_LOGIN_FAILED',
'<div class="msg-error">
  <h2>[TERM:USERS.LOGIN.FAILED.MSG]</h2>
</div> 
<br />');

sb_conf('USER_JS_TEST_SCRIPT',
    '<script>window.location = "'.BASE_PAGE.'?com=login&js=1{redirect}";</script>'
);

sb_conf('USER_MUST_HAVE_JS_AND_COOKIES',
'<div class="msg-error"> 
  <h2>[TERM:GLOBAL.ERROR]</h2>
  <p>[TERM:USERS.MUST_HAVE_JS_AND_COOKIES]</p>
</div>');
sb_conf('USER_MUST_HAVE_JS',
'<div class="msg-error">
  <h2>[TERM:GLOBAL.ERROR]</h2>
    <p>[TERM:USERS.MUST_HAVE_JS]</p>
</div>');
sb_conf('USER_MUST_HAVE_COOKIES',
'<div class="msg-error">
  <h2>[TERM:GLOBAL.ERROR]</h2>
    <p>[TERM:USERS.MUST_HAVE_COOKIES]</p>
</div>');

sb_conf('USER_ADMIN_LINK',
'<a href="admin.php?com=console">[TERM:USERS.SITE_ADMIN]</a>');

sb_conf('USER_NOT_AUTHORIZED',
'<div id="unauthorized">
  <h2>[TERM:USERS.AUTH.ERROR]</h2>
  <p>[TERM:USERS.AUTH.ERROR.MSG]</p>
  {login}
</div>');
sb_conf('USER_LOGIN_FORM_FE',
'<div id="login-div">
  <h2 class="formtitle">[TERM:LOGIN.USER_LOGIN]</h2>
  <form method="post" action="{redirect}" id="login">
    {error}
    <label for="login-user">[TERM:GLOBAL.USERNAME]</label>
    <input type="text" maxlength="60" name="username" id="login-user"  size="15" value="" />
    <label for="login-pwd">[TERM:GLOBAL.PASSWORD]</label>
    <input type="password" name="password" id="login-pwd"  maxlength="60"  size="15" />
    <input type="submit" name="login" value="Sign In" class="button" />
    <input type="hidden" name="formid" id="formid" value="_login" />
  </form>
</div>');
sb_conf('USER_LOGOUT_FORM_FE',
'<div id="login-div">
  <form method="post" action="{redirect}" id="logout">
   <p>{name}, [TERM:USERS.YOU_ARE_LOGGED_IN]</p>
   {adminlink}
   <input type="submit" name="logout" value="Sign Out" class="button" />
   <input type="hidden" name="formid" id="formid" value="_logout" />
 </form>
</div>');
sb_conf('USER_LOGIN_LINK_FE',
'<a href="admin.php?com=console&redir={redirect}">[TERM:USERS.SIGN_IN]</a>');
sb_conf('USER_LOGOUT_LINK_FE',
'<a href="admin.php?com=console&action=logout&redir={redirect}">[TERM:USERS.SIGN_OUT]</a>{adminlink}');
sb_conf('USER_LOGIN_ADMIN',
'<div id="login_wrapper">
  {message}
  <form id="loginform" method="post" action="'.BASE_PAGE.'?com=console&amp;js=1&amp;try=1">
  <fieldset>
    <div id="login-background">
    <div id="lock">
    <table class="linkstable" cellpadding="0" cellspacing="0">
      <tr>
        <td width="29%" valign="bottom" align="right" style="padding-bottom:6px;text-align:right;">[TERM:GLOBAL.USERNAME]:</td>
        <td valign="bottom">
          <input type="text" class="inputbox" name="username" value="" />
        </td>
      </tr>
      <tr>
        <td width="29%" valign="top" align="right" style="padding-top:6px;text-align:right;">[TERM:GLOBAL.PASSWORD]:</td>
        <td valign="top">
          <input type="password" class="inputbox" name="password" value="" />
        </td>
      </tr>
    </table>
    </div>
    </div>
    {redirect}
    <input type="hidden" name="formid" id="formid" value="_login" />
    <input class="button" type="submit" name="button" value="Sign In" />
  </fieldset>
  </form>
</div>');
sb_conf('USER_LOGIN_NO_COOKIES', 
'<fieldset id="loginfieldset">
  <form id="loginform" action="#">
    <div id="login_wrapper">
      <table class="linkstable" cellpadding="0" cellspacing="0">
        <tr>
          <td>
            {message}
          </td>
        </tr>
      </table>
    </div>
  </form>
</fieldset>');