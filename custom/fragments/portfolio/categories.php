<?php defined('SKYBLUE') or die('Bad file request'); 

global $Core;
global $Router;

$categories = get_portfolio_groups();
$pageId = Filter::get($_GET, 'pid');

?>
<ul id="sub-menu">
    <?php foreach ($categories as $cat) : ?>
        <?php
            $title = Filter::get($cat, 'title'); 
            $link  = $Router->GetLink(
                $pageId, 
                array('-pg-' => $pageId, '-c' => $cat->id)
            );
            $class = "";
            if (Filter::get($_GET, 'cid') == $cat->id) {
                $class = ' class="active"';
            }
        ?>
        <li><a href="<?php echo $link; ?>"<?php echo $class; ?>><?php echo $title; ?></a></li>
    <?php endforeach; ?>
</ul>