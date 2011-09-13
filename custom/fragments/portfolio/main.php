<?php defined('SKYBLUE') or die('Bad file request'); 

global $Core;

$items = get_portfolio_items($data, $params);
$categories = get_portfolio_groups();

$catId = Filter::get($_GET, 'cid', @$categories[0]->id);

?>
<?php if (count($items)) : ?>
<ol id="portfolio-content">
    <?php foreach ($items as $item) : ?>
        <?php if (Filter::get($item, 'category') != $catId) continue; ?>
        <?php
            $image = Filter::get($item, 'artwork');
            $alt   = Filter::get($item, 'title');
            $title = Filter::get($item, 'title');
            $link  = Filter::get($item, 'link');
            $link  = trim($link) == "" ? '#' : $link ;
            $description = get_portfolio_item_description($item);
        ?>
        <li>
            <div class="portfolio-item">
                <a href="<?php echo $link; ?>"><img src="<?php echo $image; ?>" alt="<?php echo $image; ?>" /></a>
                <div class="portfolio-description">
                    <h2><?php echo $title; ?></h2>
                    <p><?php echo $description; ?></p>
                </div> <!--/.portfolio-description-->
            </div> <!--/.portfolio-item-->
        </li>
    <?php endforeach; ?>
</ol> <!--/#portfolio-content-->
<?php endif; ?>