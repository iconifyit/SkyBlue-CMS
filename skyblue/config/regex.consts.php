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
# SB_REGEX_NUM defines the Regular Expression for a number.
# ###################################################################################

sb_conf('SB_REGEX_NUM', "^[-]?[0-9]+([\.][0-9]+)?$");

# ###################################################################################
# SB_REGEX_EMAIL defines the Regular Express for an email address.
# ###################################################################################

sb_conf('SB_REGEX_EMAIL', 
    "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$");

# ###################################################################################
# SB_REGEX_URL defines the Regular Express for an URL.
# ###################################################################################

sb_conf('SB_REGEX_URL', 
    '!^((ht|f)tps?\:\/\/)?[a-zA-Z]{1}([\w\-]+\.)+([\w]{2,5})/?$!i');

# ###################################################################################
# REGEX_IMG defines the Regular Express for finding all <img ../> in a string.
# ###################################################################################

sb_conf('REGEX_IMG', '/<img.*?[^>]+>/im');

# ###################################################################################
# REGEX_NAME_ATTR defines the Regular Express for the name attribute in a string.
# This is mainly used for cleaning up the output from HTML Tidy, which incorrectly 
# adds the name attribute to images (that won't validate as XHMTL strict).
# ###################################################################################

sb_conf('REGEX_NAME_ATTR', '/(name="[^"]*")/im');

# ###################################################################################
# REGEX_NAME_ATTR defines the Regular Express for the name attribute in a string.
# This is mainly used for cleaning up the output from HTML Tidy, which incorrectly 
# adds the name attribute to images (that won't validate as XHMTL strict).
# ###################################################################################

sb_conf('REGEX_SRC_ATTR', '/(src="([^"]+)")/');

# ###################################################################################
# REGEX_NAME_ATTR defines the Regular Express for the name attribute in a string.
# This is mainly used for cleaning up the output from HTML Tidy, which incorrectly 
# adds the name attribute to images (that won't validate as XHMTL strict).
# ###################################################################################

sb_conf('REGEX_EMPTY_ATTR', '/[a-zA-Z]+="[\s]*"/');

# ###################################################################################
# SB_REGEX_TOKEN defines the Regular Expression for detecting SkyBlue tokens.
# ###################################################################################

sb_conf('SB_REGEX_TOKEN', "/{([a-zA-Z0-9]+)}/");

# ###################################################################################
# SB_REGEX_REGION_TOKEN defines the Regular Expression for detecting SkyBlue
# skin region tokens.
# ###################################################################################

sb_conf('SB_REGEX_REGION_TOKEN', "/{region:([a-zA-Z0-9.-]+)}/");

# ###################################################################################
# SB_REGEX_AMP defines the Regular Expression for detecting an ampersand that 
# is not encoded or part of an HTML entity.
# ###################################################################################

sb_conf(
    'SB_REGEX_AMP',
    "/&(?!(?i:\#((x([\dA-F]){1,5})|".
    "(104857[0-5]|10485[0-6]\d|".
    "1048[0-4]\d\d|104[0-7]\d{3}|".
    "10[0-3]\d{4}|0?\d{1,6}))|".
    "([A-Za-z\d.]{2,31}));)/"
);

# ###################################################################################
# Regular expressions for object token matching
# ###################################################################################

sb_conf('SB_REGEX_OBJ_TOKEN', "/{OBJ:[^}]*}/i");
sb_conf('SB_REGEX_OBJ_PRE', '{OBJ:');
sb_conf('SB_REGEX_OBJ_END', '}');

# ###################################################################################
# '/^[\s]*\/\/(.*?)$/im' -> matches // End of line comments
# '/^[\s]*#(.*)$/im' -> matches # single line comments
# ###################################################################################

sb_conf('SB_REGEX_EOL_COMMENT', '/^[\s]*\/\/(.*?)$/im');
sb_conf('SB_REGEX_SINGLELINE_COMMENT', '/^[\s]*#(.*)$/im');

# ###################################################################################
# SB_REGEX_MYCONFIG defines the Regular Expression for matching config settings in
# myconfig.php. Before parsing the config settings, EOL and Single-Line comments are
# stripped from the stream using SB_REGEX_EOL_COMMENT and SB_REGEX_SINGLELINE_COMMENT 
# and preg_replace($pattern, $replacement, $subject);
#
# Example:
#
# sb_conf('MY_URL', 'http://www.mydomain.com');
#
# Returns:
#
# array(
#     [0] => Array(
#                [0]=> ('MY_URL', 'http://www.mydomain.com')
#            )
#     [1] => Array(
#               [0]=> MY_URL
#            )
#     [2] => Array(
#               [0]=> 'http://www.mydomain.com'
#            )
# )
#
# ###################################################################################

sb_conf('SB_REGEX_MYCONFIG', '/\(\'(.*?)\',(.*?)\)/i');