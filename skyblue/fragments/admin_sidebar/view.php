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

$com = Filter::getAlphaNumeric($params, 'com');

?>
<ul class="nav">
    <li><a class="headitem item3" href="javascript:void(0);"><?php __('CONSOLE.CONTROLS', 'Controls'); ?></a>
        <ul class="opened">
            <li<?php if ($com == 'console') : ?> class="current"<?php endif; ?>><a href="admin.php?com=console"><?php __('COM.CONSOLE', 'Dashboard'); ?></a></li>
            <li<?php if ($com == 'page') : ?> class="current"<?php endif; ?>><a href="admin.php?com=page"><?php __('COM.PAGES', 'Pages'); ?></a></li>
            <li<?php if ($com == 'media') : ?> class="current"<?php endif; ?>><a href="admin.php?com=media"><?php __('COM.MEDIA', 'Media'); ?></a></li>
            <li<?php if ($com == 'collections') : ?> class="current"<?php endif; ?>><a href="admin.php?com=collections"><?php __('COM.COLLECTIONS', 'Collections'); ?></a></li>
            <li<?php if ($com == 'settings') : ?> class="current"<?php endif; ?>><a href="admin.php?com=settings"><?php __('COM.SETTINGS', 'Settings'); ?></a></li>
            <li<?php if ($com == 'users') : ?> class="current"<?php endif; ?>><a href="admin.php?com=users"><?php __('COM.USERS', 'Users'); ?></a></li>
        </ul>
    </li>
    <li><a class="headitem item8" href="admin.php?com=page"><?php __('CONSOLE.QUICKLINKS', 'Quick Links'); ?></a>
        <ul class="opened">
            <li><a href="admin.php?com=page&action=add"><?php __('ACTION.PAGE.ADD', 'New Page'); ?></a></li>
            <li><a href="<?php echo Config::get('site_url'); ?>"><?php __('CONSOLE.VIEWSITE', 'View Site'); ?></a></li>
            <li<?php if ($com == 'help') : ?> class="current"<?php endif; ?>><a href="admin.php?com=help"><?php __('CONSOLE.HELP', 'Help'); ?></a></li>
            <li<?php if ($com == 'email') : ?> class="current"<?php endif; ?>><a href="admin.php?com=email"><?php __('CONSOLE.MAIL', 'Mail'); ?></a></li>
        </ul>
    </li>
</ul><!--end subnav-->