;$("body").removeClass("noscript");

$(function() {

    $("#topnav ul").attr("class", "submenu");
    $("#topnav ul li").removeClass("nav");
    $("#topnav ul a").removeClass("nav-item");

    /**
     * A little trick to remove elements not needed 
     * when JavaScript is present.
     */
    $(".noscript", document.body).remove();
    
    /**
     * Replace Submit inputs with anchors so we 
     * can completely control the look and behavior via JS.
     */
    $("input.button").each(function() {
        $(this).before(
            '<a href="#' + $(this).parents().find('form').eq(0).attr('id') + 
            '" class="button cufon">' + $(this).val() + 
            '</a>'
        );
        $(this).remove();
    });
    
    /** 
     * Add the submit behavior to any hard-coded a.button elements.
     */
    $(".button").bind("click", function(e) {
        e.preventDefault();
        alert("Submitting form " + $(this).attr("href"));
    });

    /**
     * Add universal firstChild and lastChild support.
     */
    $(".submenu li").eq(0).addClass("firstChild");
    $(".submenu").each(function(i) {
        $("li:last", this).addClass("lastChild");
    });

    /**
     * Replace our type with the font of our choice.
     */
    Cufon.replace(
        ".cufon, " + 
        ".nav-item, " +
        ".submenu li a, " + 
        "#menu ul li a, " + 
        ".to_top, " + 
        ".section-heading, " +
        ".article-header h2, " + 
        ".article-header h3, " + 
        ".article-header h2 a, " +
        ".article-header h3 a, " + 
        "#search-div-full h2"
    );
    
    /**
     * Add the drop down behaviors
     */
    $(".nav").hover(
        function() {
            hideSearchForm();
            doShow($(".submenu", this), 300);             
        },
        function() {
           doHide($(".submenu", this), 300);
        }
    );
    
    /**
     * Cufon doesn't support the :hover behavior, 
     * so we add the behavior via JS.
     */
    $(".nav-item").hover(
        function() {
            $(this).css("color", "#FFF");
            Cufon.replace($(this));
        },
        function() {
            $(this).css("color", "#BBB");
            Cufon.replace($(this));
        }
    );

    $(".submenu li a, #menu li a").hover(
        function() {
            $(this).css("color", "#000");
            Cufon.replace(this);
        },
        function() {
            $(this).css("color", "#EEE");
            Cufon.replace(this);
        }
    );
    
    $(".article-header h2 a").hover(
        function() {
            $(this).css("color", "#000");
            Cufon.replace(this);
        },
        function() {
            $(this).css("color", "#333");
            Cufon.replace(this);
        }
    );
    
    $("p.comments a").hover(
        function() {
            $(this).css("color", "#000");
            Cufon.replace(this);
        },
        function() {
            $(this).css("color", "#666");
            Cufon.replace(this);
        }
    );
    
    /**
     * Add scroll behavior for a little smoother user experience.
     */
    $(".to_top, .to_next, .to_previous, p.comments a").bind("click", function(e) {
        e.preventDefault();
        $.scrollTo($(this).attr("href"), 600);
    });
    
    doInitSlider({feed: "/skins/paper-aeroplane/photos.xml"});
    doInitSearch();
});

function doInitSearch() {
    addSearchForm();
    $("#search-button").bind("click", function(e) {
        e.preventDefault();
        $(this).blur();
        if ($("#search-form").css("display") == "none") {
            showSearchForm();
        }
        else {
            hideSearchForm();
        }
    });
    $("#search-button").bind("mouseover", showSearchForm);
};

function showSearchForm() {
    doShow("#search-form");
};

function hideSearchForm() {
    doHide("#search-form");
};

function addSearchForm() {
    /*
    $("#search-button").parents(0).append(
        '<div id="search-form">' + 
        '<div class="top"></div>' + 
        '<form>' + 
        '<input type="text" name="search" value="" />' +
        '<a href="search.html">Go</a>' +
        '</form>' +
        '<div class="bottom"></div>' + 
        '</div>'
    );
    */
    
    $("#search-button").parents(0).append(
        '<div id="search-form">' + 
        '<div class="top"></div>' + 
        '<form id="cse-search-box" action="search-page">' + 
        '<input type="hidden" name="cx" value="partner-pub-5898061751640869:kqv8yr-bihr" />' + 
		'<input type="hidden" name="cof" value="FORID:9" />' + 
		'<input type="hidden" name="ie" value="ISO-8859-1" />' + 
        '<input type="text" name="q" value="" />' +
        '<a href="search.html">Go</a>' +
        '<script type="text/javascript" src="http://www.google.com/coop/cse/brand?form=cse-search-box&amp;lang=en"></script>' +
        '</form>' +
        '<div class="bottom"></div>' + 
        '</div>'
    );
    /*
    <form id="cse-search-box" action="search.htm">
		<input type="hidden" name="cx" value="partner-pub-5898061751640869:kqv8yr-bihr" />
		<input type="hidden" name="cof" value="FORID:9" />
		<input type="hidden" name="ie" value="ISO-8859-1" />
		<input type="text" name="q" size="16" />
		<button name="sa" type="submit" id="search-submit" value="Submit"></button>
		<script type="text/javascript" src="http://www.google.com/coop/cse/brand?form=cse-search-box&amp;lang=en"></script>
	</form>
    */

    Cufon.replace("#search-form a");
    $("#search-form").hover(
        function() {/* Do nothing */},
        function() {
            doHide($(this));
        }
    );
    $("#search-form a").hover(
        function() {
            $(this).css("color", "#000");
            Cufon.replace(this);
        },
        function() {
            $(this).css("color", "#FFF");
            Cufon.replace(this);
        }
    );
};

function initScrollable() {
    $(".scrollable").scrollable({circular: true});
};

function preloadImage(src) {
    if (! window._preload) window._preload = new Array();
    window._preload.push(new Image());
    window._preload[window._preload.length-1].src = src;
};

function doInitSlider(options) {
    $("#slider .items").eq(0).before(
        '<div id="slider-prev" class="custom prev"></div>' + 
        '<div id="slider-next" class="custom next"></div>'
    );
    $("#slider .items").addClass("scripted");
    if (! $.getFeed) return;
    $.getFeed({
        url: options.feed,
        success: function(feed) {
            for(var i = 0; i < feed.items.length; i++) {
                var item = feed.items[i];
                preloadImage(item.link);
                $("#slider .items").append(
                    "<div>\n" + 
                    "<img src=\"" + item.link + "\" alt=\"" + item.title + "\" />\n" + 
                    "<h4 class=\"title\">" + item.title + "</h4>\n" +
                    "<p class=\"description\">" + item.description + "</p>\n" +
                    "</div>\n"
                );
            }
            var tid = setTimeout(function() {
                $(".scrollable").scrollable({circular: true})
            }, 1000);
        }
    });
};

/**
 * IE has a problem with fading PNGs with alpha channels. The transparent area of the 
 * image will show up as a solid black area until the opacity reaches 100%, then the 
 * alpha channel is applied. To avoid this ugly situation, IE just gets a "show/hide" 
 * effect which uses the "display" attribute rather than opacity.
 */
function doShow(selector, speed) {
    if ($.browser.msie) {
        $(selector).show();
    }
    else {
        $(selector).fadeIn(speed ? speed : 200);
    }
};

function doHide(selector, speed) {
    if ($.browser.msie) {
        $(selector).hide();
    }
    else {
        $(selector).fadeOut(speed ? speed : 200);
    }
};