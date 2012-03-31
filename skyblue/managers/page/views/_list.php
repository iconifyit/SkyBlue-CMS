<?php defined('SKYBLUE') or die('Bad file request');

/**
* @version        v 1.1 2009-04-19 10:37:00 $
* @package        SkyBlueCanvas
* @copyright      Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
* @license        GNU/GPL, see COPYING.txt
* SkyBlueCanvas is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYING.txt for copyright notices and details.
*/

add_head_element('jquery.ui', '', 'include');
add_head_element(
    'com.css', 
    '<link rel="stylesheet" type="text/css" href="managers/page/views/css/styles.css" />'
);

add_head_element('jquery.cookie');

$data = $this->getData();

$pageCount    = $this->getVar('pageCount');
$pageNum      = $this->getVar('pageNum');
$itemsPerPage = $this->getVar('itemsPerPage');

?>
<style type="text/css">
    table td img, table td a img {border: none !important;}
</style>
<div class="jquery_tab">
    <div class="content">
        <h2>
            <a href="admin.php?com=console"><?php __('COM.CONSOLE', 'Dashboard'); ?></a> /
            <?php __('COM.PAGES', 'Pages'); ?>
        </h2>
        <?php echo HtmlUtils::formatMessage($this->getMessage()); ?>
        <?php PageHelper::getTreeView($data, array('block_id' => 'page-tree')); ?>
    </div>
</div>
