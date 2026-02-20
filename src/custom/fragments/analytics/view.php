<?php defined('SKYBLUE') or die("Bad file request"); ?>
<script type="text/javascript">
    var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
    document.write(unescape(
        "%3Cscript src='" + gaJsHost 
        + "google-analytics.com/ga.js'"
        + " type='text/javascript'%3E%3C/script%3E"
    ));
</script>
<script type="text/javascript">

    window.pageTracker = _gat._getTracker("UA-407560-10");
    window.pageTracker._trackPageview();
    
    (function($) {
        $(".trackable").bind("click", function(e) {
            var title = $(this).attr("title");
            if (! title) {
                title = window._titles[$(this).attr("id")];
            }
            if (typeof(title) == "string" && title != "") {
                window.pageTracker._trackEvent(
                    "[[page.name]]", 
                    "click", 
                    title ? title : "[[page.name]] Element"
                );
            }
        });
    })(jQuery);
    
    function getTarget(e) {
        var targ;
        if (!e) var e = window.event;
        if (e.target) targ = e.target;
        else if (e.srcElement) targ = e.srcElement;
        if (targ.nodeType == 3) // defeat Safari bug
            targ = targ.parentNode;
        return targ;
    };
    
    function parse_title(_string, _defaults) {
        if (typeof(_defaults) != "object") {
            _defaults = {
                "category" : "[[page.name]]",
                "label"    : "Slider Ad"
            }
        }
        var _bits = _string.split(",");
        return {
            "category": _bits.length > 0 ? _bits[0] : _defaults.category, 
            "label": _bits.length > 1 ? _bits[1] : _defaults.label
        };
    };
</script>