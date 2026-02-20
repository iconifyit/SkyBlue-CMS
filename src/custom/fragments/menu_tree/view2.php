<?php defined('SKYBLUE') or die('Bad file request');

$pages = Menu_treeFragment::getPublished();
$menuId = Filter::noInjection($params, 'menu_id');

?>
<ul id="topnav" class="grid_8">
    <?php foreach ($pages as $Page) : ?>
    <?php if ($Page->getMenu() != $menuId) continue; ?>
    <?php if (intval($Page->getParent()) != 0) continue; ?>
    <li class="grid_2 nav">
        <a href="<?php echo $Page->getPermalink(); ?>" class="nav-item"><span><?php echo $Page->getName(); ?></span></a>
        <?php if ($children = Menu_treeFragment::getChildren($Page)) : ?>
        <ul class="submenu grid_2">
            <?php foreach ($children as $Child) : ?>
            <li><a href="<?php echo $Child->getPermalink(); ?>"><?php echo $Child->getName(); ?></a></li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </li>
    <?php endforeach; ?>
</ul>
