<?php defined('SKYBLUE') or die('Bad file request'); 

# $items = GalleryFragment::getData();

$showTitle = Filter::get($params, 'show_title', true);

if (strcasecmp($showTitle, 'true') == 0) {
    $showTitle = true;
}
else if (strcasecmp($showTitle, 'false') == 0) {
    $showTitle = false;
}
else if (intval($showTitle) == 1) {
    $showTitle = true;
}
else if (intval($showTitle) == 0) {
    $showTitle = false;
}

?>
<?php if ($showTitle) : ?>
    <h3 class="body-title"><?php __('THEME.SHOWCASE', 'SkyBlue Showcase'); ?></h3>
<?php endif; ?>
<ul class="gallery">
    <li><a href="#lambre" class="lightbox"><img src="media/pages/lambre_125x125.png" alt="" /></a></li>
    <li><a href="#bankston_and_bailey" class="lightbox"><img src="media/pages/bab_125x125.png" alt="" /></a></li>
    <li><a href="#perfect_nutrition" class="lightbox"><img src="media/pages/perfectnutrition_125x125.png" alt="" /></a></li>
    <li><a href="http://skybluecanvas.com/media/pages/gr_125x125_v2.gif" class="lightbox"><img src="http://skybluecanvas.com/media/pages/gr_125x125_v2.gif" alt="" /></a></li>
    <li><a href="http://skybluecanvas.com/media/pages/gr_125x125_v2.gif" class="lightbox"><img src="http://skybluecanvas.com/media/pages/gr_125x125_v2.gif" alt="" /></a></li>
    <li><a href="http://skybluecanvas.com/media/pages/gr_125x125_v2.gif" class="lightbox"><img src="http://skybluecanvas.com/media/pages/gr_125x125_v2.gif" alt="" /></a></li>
</ul>

<div style="display: none;">
    <div id="lambre" class="big_picture">
        <img src="media/pages/lambre_big.png" />
        <p><span class="theme_title">L'Ambre</span> L'Ambre Company is a website that promotes the L'Ambre Group line of Perfumes 
           <br />and provides information about the L'Ambre Group's business associates program for 
           <br />Belarus, Russian and Ukraine.
            <a id="visit-site-1" href="http://lambre.ws" class="aico-button aico-grey-go">Visit Site<span></span></a>
        </p>
    </div>
</div>

<div style="display: none;">
    <div id="bankston_and_bailey" class="big_picture">
        <img src="media/pages/bab_big.png" />
        <p><span class="theme_title">Bankston &amp; Bailey</span> Bankston & Bailey, LLC, are 
           <br />Central Virginia's premier designers and makers of fine custom furniture. They also design 
           <br />and build custom architectural elements as well as fine museum display cases.
           <a id="visit-site-2" href="http://Bankstonandbailey.com" class="aico-button aico-grey-go">Visit Site<span></span></a>
        </p>
    </div>
</div>

<div style="display: none;">
    <div id="perfect_nutrition" class="big_picture">
        <img src="media/pages/perfectnutrition_big.png" />
        <p><span class="theme_title">Perfect Nutrition, Inc.</span> Perfection Nutrition, 
           <br />headquartered in Los Angeles CA Perfect Nutrition Inc is a leading distributor 
           <br />of nutritional sport supplements, beverages and functional food products.
           <a id="visit-site-3" href="http://lambre.ws" class="aico-button aico-grey-go">Visit Site<span></span></a>
        </p>
    </div>
</div>