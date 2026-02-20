<?php defined('SKYBLUE') or die('Bad file request'); 

$items = GalleryFragment::getData();

?>
<div id="myGallery">
    <?php if (count($items)) : ?>
    <?php foreach ($items as $item) : ?>
    <div class="imageElement">
        <h3><?php echo $item->title; ?></h3>
        <p><?php echo $item->title; ?></p>
        <a href="<?php echo $item->artwork; ?>" title="open image" class="open"></a>
        <img src="<?php echo $item->artwork; ?>" class="full" />
        <img src="<?php echo $item->thumbnail; ?>" class="thumbnail" />
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>