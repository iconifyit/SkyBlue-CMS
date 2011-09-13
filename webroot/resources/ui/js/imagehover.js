/*
Simple Image Trail script- By JavaScriptKit.com
Visit http://www.javascriptkit.com for this script and more
This notice must stay intact
*/

var offsetfrommouse=[15,0];  //image x,y offsets from cursor position in pixels. Enter 0,0 for no offset
var displayduration=0;         //duration in seconds image should remain visible. 0 for always.
var currentImageHeight = 270;  // maximum image size.

$(function() {
    $(document.body).append('<div id="trailimageid"></div>');
});

function truebody() {
    if (!window.opera && document.compatMode && document.compatMode!="BackCompat") {
        return document.documentElement;
    }
    return document.body;
}

function showtrail(obj, src, width, height, hideinfo) {
    var image = new Image();
    image.onload = function(e) {
        trail(obj, image);
    };
    image.src = src;
}

function trail(obj, image) {

    $(obj).css("cursor", "pointer");
    
    if (image.height > 0) {
        currentImageHeight = image.height;
    }

    $("#trailimageid").css("visibility", "hidden");
    $("#trailimageid").css("position", "absolute");
    
    $(window.document).bind('mousemove', followmouse);

    var dims = maxDims(200, image.width, image.height);

    $("#trailimageid").html(
        '<div style="padding: 4px; background-color: #FFF; border: 1px solid #888;">' + 
        '<div align="center" style="padding: 0px;">' + 
        '<img src="' + image.src + '" width="' + dims[0] + '" height="' + dims[1] + '" border="0" />' + 
        '</div>' + 
        '</div>'
    );

    $("#trailimageid").css("visibility", "visible");
};

/*

    maxDims() added by Scott Lewis, 
              Bright-Crayon, LLC
              on 05/21/06.
    
    Arguments:
    
    0: the int value of the largest dimension
    1: the actual width of the image
    2: the actual height of the image

*/

function maxDims(max, w, h) {
    if (w > max || h > max) {
        var widthratio = 1;
        if (w > max) {
            widthratio = max / w;
        }
        
        var heightratio = 1;
        if (h > max) {
            heightratio = max / h;
        }
        
        var ratio = heightratio;
        if (widthratio < heightratio) {
            ratio = widthratio;
        }
        
        // Scale the image
        w = Math.ceil(w * ratio);
        h = Math.ceil(h * ratio);
        
        // Tweak the new dims to match max exactly
        
        if (ratio == heightratio && ratio != 1) {
            if (h < max) {
                while (h < max) {
                    ratio = ratio * 1.01;
                    h = Math.ceil(h * ratio);
                    w = Math.ceil(w * ratio);
                }
            }
        }
        
        if (ratio == widthratio && ratio != 1) {
            if (w < max) {
                while (w < max) {
                    ratio = ratio * 1.01;
                    h = Math.ceil(h * ratio);
                    w = Math.ceil(w * ratio);
                }
            }
        }
    }
    
    return new Array(w, h);
}

function hidetrail() {
    $("#trailimageid").css("visibility", "hidden");
    $(window.document).unbind('mousemove');
    $("#trailimageid").css("left", "-500px");
}

function followmouse(e) {

    var xcoord = offsetfrommouse[0];
    var ycoord = offsetfrommouse[1];

    if (document.all) {
        var docwidth  = truebody().scrollLeft+truebody().clientWidth;
        var docheight = Math.min(truebody().scrollHeight, truebody().clientHeight);
    }
    else {
        var docwidth  = pageXOffset+window.innerWidth-15;
        var docheight = Math.min(window.innerHeight);
    }

    if (typeof e != "undefined") {
        if (docwidth - e.pageX < 300) {
            xcoord = e.pageX - xcoord - 286; // Move to the left side of the cursor
        } 
        else {
            xcoord += e.pageX;
        }
        if (docheight - e.pageY < (currentImageHeight)) {
            ycoord += e.pageY - Math.max(0,(currentImageHeight + e.pageY - docheight - truebody().scrollTop));
        } 
        else {
            ycoord += e.pageY;
        }

    } 
    else if (typeof window.event != "undefined") {
        if (docwidth - event.clientX < 300) {
            // Move to the left side of the cursor
            xcoord = event.clientX + truebody().scrollLeft - xcoord - 286; 
        } 
        else {
            xcoord += truebody().scrollLeft + event.clientX;
        }
        if (docheight - event.clientY < (currentImageHeight + 110)) {
            ycoord += event.clientY + truebody().scrollTop - Math.max(0,(110 + currentImageHeight + event.clientY - docheight));
        } 
        else {
            ycoord += truebody().scrollTop + event.clientY;
        }
    }
    
    if (document.all) {
        var docwidth  = truebody().scrollLeft+truebody().clientWidth;
        var docheight = Math.max(truebody().scrollHeight, truebody().clientHeight);
    }
    else {
        var docwidth  = pageXOffset+window.innerWidth-15;
        var docheight = Math.max(document.body.offsetHeight, window.innerHeight);
    }
    
    if (ycoord < 0) { 
        ycoord *= -1; 
    }
    
    $("#trailimageid").css({
        "left": xcoord + "px",
        "top": ycoord + "px"
    });
}