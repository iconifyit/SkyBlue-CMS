<?php defined('SKYBLUE') or die('Bad file request'); 

$items = GalleryFragment::getData();

?>
<div id="scroller">
    <ul>
        <li>
            <a href="<?php the_skin_path(); ?>images/portfolio/chopin-b.jpg" class="lightbox"><img src="<?php the_skin_path(); ?>images/scroller/chopin.jpg" alt="" /><span class="scroll-mag2"></span></a>
        </li>
        <li>
            <a href="<?php the_skin_path(); ?>images/portfolio/twitter-b.jpg" class="lightbox"><img src="<?php the_skin_path(); ?>images/scroller/twitter.jpg" alt="" /><span class="scroll-mag2"></span></a>
        </li>
    </ul>
</div>