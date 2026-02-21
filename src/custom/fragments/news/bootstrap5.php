<?php defined('SKYBLUE') or die('Bad file request');

/**
 * Bootstrap 5 News/Blog Fragment
 *
 * Renders news items using Bootstrap 5 card layout.
 * Supports both listing view and single article view.
 */

$items    = NewsFragment::getData();
$pageId   = Filter::getNumeric($_GET, 'pid', DEFAULT_PAGE);
$newsId   = Filter::getNumeric($_GET, 'show', null);
$maxItems = Filter::getNumeric($params, 'max', 5);
$article  = Utils::selectObject($items, $newsId);

$showTitlesOnly = Filter::get($params, 'titles_only', false);
if ($showTitlesOnly == 1 || $showTitlesOnly == "1" || strtolower($showTitlesOnly) == "true") {
    $showTitlesOnly = true;
}

$maxItems = $maxItems ? $maxItems : count($items);

?>
<div class="news-container">
<?php if (empty($article)) : ?>
    <!-- News Listing -->
    <?php if (count($items)) : ?>
        <?php if ($showTitlesOnly) : ?>
            <ul class="list-group list-group-flush">
            <?php for ($i = 0; $i < $maxItems && $i < count($items); $i++) : ?>
                <?php $item =& $items[$i]; ?>
                <?php $theLink = NewsFragment::getLink($pageId, array('news_id' => Filter::getNumeric($item, 'id'))); ?>
                <li class="list-group-item">
                    <a href="<?php echo $theLink; ?>" class="text-decoration-none">
                        <?php echo Filter::get($item, 'title'); ?>
                    </a>
                </li>
            <?php endfor; ?>
            </ul>
        <?php else : ?>
            <div class="row row-cols-1 row-cols-md-2 g-4">
            <?php for ($i = 0; $i < $maxItems && $i < count($items); $i++) : ?>
                <?php $item =& $items[$i]; ?>
                <?php $theLink = NewsFragment::getLink($pageId, array('news_id' => Filter::getNumeric($item, 'id'))); ?>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="<?php echo $theLink; ?>" class="text-decoration-none text-dark">
                                    <?php echo Filter::get($item, 'title'); ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted small">
                                <i class="bi bi-calendar3 me-1"></i>
                                <?php echo Filter::get($item, 'date'); ?>
                            </p>
                            <p class="card-text"><?php echo NewsFragment::getIntro($item); ?></p>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <a href="<?php echo $theLink; ?>" class="btn btn-outline-primary btn-sm">
                                Read More <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
            </div>
        <?php endif; ?>
    <?php else : ?>
        <div class="alert alert-info" role="alert">
            <i class="bi bi-info-circle me-2"></i>
            <?php __('GLOBAL.NO_ITEMS', 'No items to display'); ?>
        </div>
    <?php endif; ?>
<?php else : ?>
    <?php NewsFragment::setPageTitle($data, Filter::get($article, 'title')); ?>
    <!-- Single News Article -->
    <article class="news-article">
        <header class="mb-4">
            <h1 class="display-5"><?php echo Filter::get($article, 'title'); ?></h1>
            <p class="text-muted">
                <i class="bi bi-calendar3 me-1"></i>
                <?php echo Filter::get($article, 'date'); ?>
            </p>
        </header>
        <div class="article-content">
            <?php echo NewsFragment::getStory($article); ?>
        </div>
        <footer class="mt-4 pt-4 border-top">
            <?php $theLink = NewsFragment::getLink($pageId); ?>
            <a href="<?php echo $theLink; ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                <?php __('GLOBAL.BACK_TO_LIST', 'Back to all posts'); ?>
            </a>
        </footer>
    </article>
<?php endif; ?>
</div>
