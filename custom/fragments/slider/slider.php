<?php defined('SKYBLUE') or die("Bad file request"); ?>
<?php 
/**
 * See skins/blog/js/combined.js for scripts -->
 * See skins/blog/css/combined.css for styles -->
 */
class SliderFragment {
    function blurb($themeName) {
    ?>
        Your purchase of this theme at <a href="http://themeforest.net?ref=skybluecanvas" title="[[page.name]], <?php echo $themeName; ?>" class="inquire">ThemeForest.net</a> will help cover what it costs me to provide SkyBlueCanvas for free.
    <?php
    }
    function title($href, $title, $text) {
    ?>
    <a href="<?php echo $href; ?>" title="<?php echo $title; ?>" class="trackable"><?php echo $text; ?></a>
    <?php
    }
    function thumbnail($href, $title, $image) {
    ?>
<a href="<?php echo $href; ?>" title="<?php echo $title; ?>" class="trackable"><img src="slider/images/<?php echo $image; ?>" alt="<?php echo $title; ?>" /></a>
    <?php
    }
}
?>
<style type="text/css">
.price {
    display:   block;
    float:     right;
    font-size: 28px;
    padding:   0px 3px 3px 0px;
    color:     #000;
}
</style>
<div class="jflow-content-slider" id="themeSlider">
    <div id="slides">
    
        <!--slide_01-->
        <div class="slide-wrapper">
            <div class="slide-thumbnail">
                <?php 
                    SliderFragment::thumbnail(
                        'http://themeforest.net/item/whitewall-html-design-6in1/70844?ref=skybluecanvas',
                        '[[page.name]], Whitewall Theme [thumbnail]',
                        'whitewall_theme.png'
                    );
                ?>
            </div>
            <div class="slide-details">
                <span class="price">$14</span>
                <h2>
                    <?php 
                        SliderFragment::title(
                            'http://themeforest.net/item/whitewall-html-design-6in1/70844?ref=skybluecanvas',
                            '[[page.name]], Whitewall Theme [title]',
                            'Whitewall <br />6-in-1'
                        ); 
                    ?>
                </h2>
                <div class="description">
                    <?php SliderFragment::blurb("WhiteWall"); ?>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <!--/slide_01-->

        <!--slide_02-->
        <div class="slide-wrapper">
            <div class="slide-thumbnail">
                <?php 
                    SliderFragment::thumbnail(
                        'http://themeforest.net/item/wde-crisp-clean-5-html-design/81490?ref=skybluecanvas',
                        '[[page.name]], WDE Crisp Theme [thumbnail]',
                        'wde_crisp.png'
                    );
                ?>
            </div>
            <div class="slide-details">
                <span class="price">$12</span>
                <h2>
                    <?php 
                        SliderFragment::title(
                            'http://themeforest.net/item/wde-crisp-clean-5-html-design/81490?ref=skybluecanvas',
                            '[[page.name]], WDE Crisp Theme [title]',
                            'WDE Crisp / Clean 5+'
                        ); 
                    ?>
                </h2>
                <div class="description">
                    <?php SliderFragment::blurb("WDE+Crisp"); ?>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <!--/slide_02-->
            
        <!--slide_03-->
        <div class="slide-wrapper">
            <div class="slide-thumbnail">
                <?php 
                    SliderFragment::thumbnail(
                        'http://themeforest.net/item/dm-theme-simple-versatile-xhtmlcss-template/81069?ref=skybluecanvas',
                        '[[page.name]], DM Theme [thumbnail]',
                        'dm_theme.png'
                    );
                ?>
            </div>
            <div class="slide-details">
                <span class="price">$12</span>
                <h2>
                    <?php 
                        SliderFragment::title(
                            'http://themeforest.net/item/dm-theme-simple-versatile-xhtmlcss-template/81069?ref=skybluecanvas',
                            '[[page.name]], DM Theme [title]',
                            'DM Theme'
                        ); 
                    ?>
                </h2>
                <div class="description">
                    <?php SliderFragment::blurb("DM+Theme"); ?>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <!--/slide_03-->
        
        <!--slide_04-->
        <div class="slide-wrapper">
            <div class="slide-thumbnail">
                <?php 
                    SliderFragment::thumbnail(
                        'http://themeforest.net/item/spectrum-html5-theme/80549?ref=skybluecanvas',
                        '[[page.name]], Spectrum Theme [thumbnail]',
                        'spectrum_theme.png'
                    );
                ?>
            </div>
            <div class="slide-details">
                <span class="price">$12</span>
                <h2>
                    <?php 
                        SliderFragment::title(
                            'http://themeforest.net/item/spectrum-html5-theme/80549?ref=skybluecanvas',
                            '[[page.name]], Spectrum Theme [title]',
                            'Spectrum HTML5'
                        ); 
                    ?>
                </h2>
                <div class="description">
                    <?php SliderFragment::blurb("Spectrum"); ?>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <!--/slide_04-->
            
        <!--slide_05-->
        <div class="slide-wrapper">
            <div class="slide-thumbnail">
                <?php 
                    SliderFragment::thumbnail(
                        'http://themeforest.net/item/design-agency-template/53968?ref=skybluecanvas',
                        '[[page.name]], Design Agency Theme [thumbnail]',
                        'design_agency_theme.png'
                    );
                ?>
            </div>
            <div class="slide-details">
                <span class="price">$12</span>
                <h2>
                    <?php 
                        SliderFragment::title(
                            'http://themeforest.net/item/design-agency-template/53968?ref=skybluecanvas',
                            '[[page.name]], Design Agency Theme [title]',
                            'Design Agency'
                        ); 
                    ?>
                </h2>
                <div class="description">
                    <?php SliderFragment::blurb("Design+Agency"); ?>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <!--/slide_05-->
        
        <!--slide_06-->
        <div class="slide-wrapper">
            <div class="slide-thumbnail">
                <?php 
                    SliderFragment::thumbnail(
                        'http://themeforest.net/item/prolific-business-portfolio-html-template-2-skin/63304?ref=skybluecanvas',
                        '[[page.name]], Prolific Theme [thumbnail]',
                        'prolific_theme.png'
                    );
                ?>
            </div>
            <div class="slide-details">
                <span class="price">$16</span>
                <h2>
                    <?php 
                        SliderFragment::title(
                            'http://themeforest.net/item/prolific-business-portfolio-html-template-2-skin/63304?ref=skybluecanvas',
                            '[[page.name]], Prolific Theme [title]',
                            'Prolific Business & Portfolio'
                        ); 
                    ?>
                </h2>
                <div class="description">
                    <?php SliderFragment::blurb("Prolific"); ?>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <!--/slide_06-->
    </div>
    <div id="myController">
        <span class="jFlowPrev">Prev</span>
        <span class="jFlowControl">1</span>
        <span class="jFlowControl">2</span>
        <span class="jFlowControl">3</span>
        <span class="jFlowControl">4</span>
        <span class="jFlowControl">5</span>
        <span class="jFlowControl">6</span>
        <span class="jFlowNext">Next</span>
    </div>
    <div class="clear"></div>
</div>