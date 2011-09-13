/**
 * @version      2.0 2009-04-19 10:37:00 $
 * @package      SkyBlueCanvas
 * @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license      GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

$.editor = function() {};

$.fn.editor = function(options) {
    return $(this).each(function() {
        $.editor.impl.init(this, options);
    });
};

$.editor.impl = {

    className: "editor",

    object: null,
    
    type: "", 
    
    data: [], 

    currentFragmentID: "",
    
    fragmentContent: "",

    options: [],
    
    _listener: null,
    
    cache: [],
    
    listen: function() {
        // this._listener = new Listener();
        $(document).bind('keydown', 'ctrl+shift+e', function(e) {
            // e.preventDefault();
            if (sbc.getOption("context", "") == "frontend") {
                if (sbc.getOption("logged_in", false)) {
                    $.editor.impl.logout();
                }
                else {
                    $.editor.impl.login();
                }
            }
        });
    },
    
    login: function() {
        $.get("admin.php?com=login&is_ajax=1", function(data) {
            $(data).modal({
                close: false,
                position: ["10%", /*center*/],
                overlayId: 'editor-overlay',
                containerId: 'editor-container',
                onOpen: $.editor.impl.open, 
                onShow: $.editor.impl.show, 
                onClose: $.editor.impl.close
            });
        });
    },
    
    logout: function() {
        $.get("admin.php?com=login&action=logout", function() {
            window.location.href = window.location.href.toString();
        });
    },
    
    after_login: function(dialog) {
        $.editor.impl.close(dialog);
        window.location.href = window.location.href.toString();
    },
    
    /*
     * Tooltip script 
     * powered by jQuery (http://www.jquery.com)
     * written by Alen Grakalic (http://cssglobe.com)
     * for more info visit http://cssglobe.com/post/1695/easiest-tooltip-and-image-preview-using-jquery
     */
    tooltip: function(selector) {    
        xOffset = 10;
        yOffset = 20;    
        selector = selector ? selector : ".tooltip" ;
        $(selector).hover(function(e){                                              
            this.t = this.title;
            this.title = "";
            if ($(this).hasClass("image_preview")) {
                if (this.t.indexOf('<img') == -1) {
                    this.t = '<img src="' + this.t + '" />';
                }
            }
            $("body").append("<p id='tooltip'>"+ this.t +"</p>");
            $("#tooltip")
                .css("top", ((e.pageY - xOffset) + 15) + "px")
                .css("left",(e.pageX + yOffset) + "px")
                .css("z-index", "100001")
                .css("position", "absolute")
                .css("overflow", "hidden");
            if ($(this).hasClass("image_preview")) {
                $("#tooltip").css("padding", "0px");
                $("#tooltip").css("margin", "0px");
                $("#tooltip").css("border", "1px solid #000");
            }
            $("#tooltip").fadeIn("fast");
        },
        function(){
            this.title = this.t;
            $("#tooltip").remove();
        });
        $(selector).mousemove(function(e){
            $("#tooltip")
                .css("top",((e.pageY - xOffset) + 15) + "px")
                .css("left",(e.pageX + yOffset) + "px");
        });        
    },
    
    toolbar: function() {
//        $.get("editor/toolbar", function(toolbar) {
//            $("body").append(toolbar);
//            $.editor.impl.tooltip();
//        });
    },
    
    dialog: function(message, options) {
        var _options = {
            title:     window.TERMS.LABEL_DIALOG, 
            autoOpen:  false,
            resizable: false, 
            modal:     true
        };
        $.extend(_options, options);
        $("body").append(
              '<div id="dialog">'
            + '<p><span class="ui-icon ui-icon-' 
            + _options.iconClass 
            + '" style="float:left; margin:0 7px 20px 0;"></span>'
            + message
            + '</p>'
            + '</div>'
        );
        $("#dialog").dialog(_options);
        $("#dialog").dialog("open");
    },

    init: function(obj, options) {
        $.editor.impl.tooltip();
    },
    
    message: null,
    
    open: function (dialog) {

        var h = 500;

        var title = $('#editor-container .editor-title').html();
        $('#editor-container .editor-title').html('Loading...');
        
        dialog.overlay.fadeIn(200, function () {
            dialog.container.fadeIn(200, function () {
                dialog.data.fadeIn(200, function () {
                    $('#editor-container .editor-content').animate({
                            height: h
                        }, function () {

                            // Display the modal content and initialize behaviors
                            // Use sub-classes here
                            
                            $(".editorCloseButton").bind("click", function(e) {
                                e.preventDefault();
                                $.modal.close();
                            });
                    });
                });
            });
        });
    },

    show: function (dialog) {
        // Typical view behavior here
    },

    close: function (dialog) {
        $('#editor-container .editor-message').fadeOut();
        $('#editor-container .editor-title').html('Goodbye...');
        $("#contentEditor").hide();
        $('#editor-container .editor-content').animate({
                height: 40
            }, function () {
                   dialog.data.fadeOut(200, function () {
                    dialog.container.fadeOut(200, function () {
                        dialog.overlay.fadeOut(200, function () {
                            $.modal.close();
                        });
                    });
                });
        });
    },
    
    error: function (xhr) {
        alert(xhr.statusText);
    },

    showError: function () {
        $('#editor-container .editor-message')
            .html($('<div class="contact-error">').append(contact.message))
            .fadeIn(200);
    }

};