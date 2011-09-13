(function($) {
    $(function() {
        $('.slider').easySlider({
            auto: false,
            continuous: true,
            onLoad: easySliderOnLoad,
            numeric: false
        });
    });
})(jQuery);

function easySliderOnLoad() {
    $(".slider li").hover(function(e) { 
            var theBlurbDiv = $("div.slider-blurb", e.currentTarget);
            theBlurbDiv.fadeIn("slow");
        }, function(e) {
            $("div.slider-blurb", e.currentTarget).fadeOut("slow");
    });
};