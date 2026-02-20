<?php defined('SKYBLUE') or die('Bad file request');

# $pages  = MenuFragment::getPublished();
$menuId = Filter::noInjection($params, 'menu_id');

MenuFragment::getSiteTree(array(
    'ul_id'=>'topnav', 
    'ul_class'=>'grid_8', 
    'li_class'=>'grid_2 nav', 
    'a_class'=>'nav-item',
    'ul/li/ul' => 'submenu'
));

echo MenuFragment::getMenu();