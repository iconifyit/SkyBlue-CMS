<?php defined('SKYBLUE') or die('Bad file request'); 

$items    = NewsFragment::getData();
$pageId   = Filter::getNumeric($_GET, 'pid', DEFAULT_PAGE);
$maxItems = Filter::getNumeric($_GET, 'max', 5);

$maxItems = $maxItems ? $maxItems : count($items) ;

?>    
<?php if (count($items)) : ?>
<ul class="links">
    <?php for ($i=0; $i<$maxItems; $i++) : ?>
        <?php $item =& $items[$i]; ?>
        <?php $the_link = NewsFragment::getLink(5, array('news_id' => Filter::getNumeric($item, 'id'))); ?>
        <li><a href="<?php echo $the_link; ?>.html"><?php echo Filter::get($items[$i], 'title'); ?></a></li>
    <?php endfor; ?>
</ul>
<?php endif; ?>