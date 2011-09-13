<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2009-06-20 21:41:00 $
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
 * @date   June 27, 2009
 */

$components = $this->getData();

?>
<div class="jquery_tab">
    <div class="content">
        <h2>
            <a href="admin.php?com=console"><?php __('COM.CONSOLE', 'Dashboard'); ?></a> / 
            <?php __('COM.SETTINGS', 'Settings'); ?>
        </h2>
        
        <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>
        <ul class="dashboard-controls">
            <?php for ($i=0; $i<count($components); $i++) : ?>
            <?php 
                $c = $components[$i];
                $style = "";
                if ($c->getIcon()) {
                    $c->setIcon("resources/ui/admin/icons/" . UI_ICON_SET . "/" . $c->getIcon());    
                }
            ?>
            <li>
                <span class="icon">
                    <img src="<?php echo $c->getIcon(); ?>" class="reflect" />
                </span>
                <h2><a href="admin.php?com=<?php echo strtolower($c->getName()); ?>"><span class="heading"><?php __($c->getNameToken(), $c->getName()); ?></span></a></h2>
                <p><?php __($c->getInfoToken(), "Manage your " . $c->getName()); ?></p>
            </li>
        <?php endfor; ?>
        </li>
    </div>
</div>