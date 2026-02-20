<?php defined('SKYBLUE') or die('Bad file request'); 

$items   = NewsFragment::getData();
$pageId  = Filter::getNumeric($_GET, 'pid',     DEFAULT_PAGE);
$newsId  = Filter::getNumeric($_GET, 'show', null);
$maxItems = Filter::getNumeric($_GET, 'max', 5);
$article = Utils::selectObject($items, $newsId);

$showTitlesOnly = Filter::get($params, 'titles_only', false);
if ($showTitlesOnly == 1 || $showTitlesOnly == "1") {
    $showTitlesOnly = true;
}
else if (strtolower($showTitlesOnly) == "true") {
    $showTitlesOnly = true;
}
else if (strtolower($showTitlesOnly) == "false") {
    $showTitlesOnly = false;
}

$maxItems = $maxItems ? $maxItems : count($items) ;

?>
<div class="news-archive">
<?php if (empty($article)) : ?>
    <!-- News Excerpts -->
    <?php if (count($items)) : ?>
        <ul class="news-list">
        <?php for ($i=0; $i<$maxItems; $i++) : ?>
            <?php $item =& $items[$i]; ?>
            <?php $the_link = NewsFragment::getLink($pageId, array('news_id' => Filter::getNumeric($item, 'id'))); ?>
            <li class="news-intro">
                <?php if ($showTitlesOnly) : ?>
                    <a href="<?php echo $the_link; ?>.html" rel="bookmark"><?php echo Filter::get($items[$i], 'title'); ?></a>
                <?php else : ?>
                    <h2><a href="<?php echo $the_link; ?>.html" rel="bookmark"><?php echo Filter::get($items[$i], 'title'); ?></a></h2>
                    <p class="item-date"><?php echo Filter::get($item, 'date'); ?></p>
                    <p class="item-intro"><?php echo NewsFragment::getIntro($item); ?></p>
                <?php endif; ?>
            </li>
        <?php endfor; ?>
        </ul>
    <?php else : ?>
        <p><?php __('GLOBAL.NO_ITEMS', 'No items to display'); ?></p>
    <?php endif; ?>
<?php else : ?>
    <?php NewsFragment::setPageTitle($data, Filter::get($article, 'title')); ?>
    <!-- News Article -->
    <div class="news-item">
        <h2 class="item-title"><?php echo Filter::get($article, 'title'); ?></h2>
        <?php echo NewsFragment::getStory($article); ?>
        <?php $the_link = NewsFragment::getLink($pageId); ?>
        <p class="item-link"><a href="<?php echo $the_link; ?>">&#171; <?php __('GLOBAL.BACK_TO_LIST', 'Back to list'); ?></a></p>
    </div>
<?php endif; ?>
</div>