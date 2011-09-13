<?php defined('SKYBLUE') or die('Bad file request');

global $Authenticate;
# if (!$Authenticate->isAdmin()) return; 

/**
 * @version      2.0 2010-01-01 00:00:00 $
 * @package      SkyBlueCanvas
 * @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license      GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

/**
 * @author Scott Lewis
 * @date   June 20, 2009
 */

$inbox    = Filter::get($params, 'inbox', "0");
$username = Filter::get($params, 'username');
$userid   = Filter::get($params, 'userid');

?>
<div class="head_memberinfo_logo">
    <a href="admin.php?com=email"><span><?php echo @$inbox; ?></span>
    <img src="ui/admin/images/unreadmail.png" alt="" style="border: none;" /></a>
</div>

<span class='memberinfo_span'>
     <?php __('GLOBAL.WELCOME', 'Welcome')?> <a href="admin.php?com=users&id=<?php echo $userid; ?>"><?php echo @$username; ?></a>
</span>

<span class='memberinfo_span'>
    <a href="admin.php?com=configuration"><?php __('COM.SETTINGS', 'Settings'); ?></a>
</span>

<span>
    <a href="admin.php?com=login&action=logout"><?php __('GLOBAL.LOGOUT', 'Logout'); ?></a>
</span>

<span class='memberinfo_span2'>
    <a href="javascript:void(0);">Type. Click. Publish.</a>
</span>