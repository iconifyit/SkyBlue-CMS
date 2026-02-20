<?php defined('SKYBLUE') or die('Bad file request');
    global $Core;
    
    $ACTIVE_SKIN_DIR = ACTIVE_SKIN_DIR;
    
    add_stylesheet("slider_css", "{$ACTIVE_SKIN_DIR}css/slider/screen.css");
    add_script("slider_script1", "{$ACTIVE_SKIN_DIR}js/slider/jquery.js");
    add_script("slider_script2", "{$ACTIVE_SKIN_DIR}js/slider/easySlider1.7.js");
    add_script("slider_script3", "{$ACTIVE_SKIN_DIR}js/slider/slider.js");

?>
<?php if ($items = PortfolioFragment::items($params)) : ?>
<div class="slider-wrap">
    <div class="slider">
        <ul>
            <?php $i = 1; ?>
            <?php foreach ($items as $item) : ?>
                <?php
                    $image = Filter::get($item, 'artwork');
                    $title = Filter::get($item, 'title');
                    $link  = Filter::get($item, 'link');
                    $link  = trim($link) == "" ? "javascript:void(0);" : $link ;
                    $theBlurb = $title;
                    $theFile = SB_STORY_DIR . Filter::get($item, 'story');
                    if (!is_dir($theFile) && file_exists($theFile)) {
                        $theBlurb = FileSystem::read_file($theFile);
                    }
                ?>
                <li>
                    <a href="<?php echo $link; ?>"><img src="<?php echo $image; ?>" alt="<?php echo $title; ?>" /></a>
                    <div class="slider-blurb" id="slider-blurb-<?php echo $i; ?>"><?php echo $theBlurb; ?></div>
                </li>
            <?php $i++; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php endif; ?>