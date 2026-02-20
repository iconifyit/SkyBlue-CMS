if (! SBC) var SBC = {};

var pluginPath = (function() {
    var scr  = document.getElementsByTagName("script");
    var path = scr[scr.length-1].getAttribute("src");
    var bits = path.split("/");
    return path.replace(bits[bits.length-1], "");
})();

jQuery.extend(SBC, {
    options: {
        basepath: (function() {
        var loc = window.location.toString();
        var bits = loc.split('/');
        return loc.replace(bits[bits.length-1], '');
    })(),
        buttons: [],
        IBOX_IMG_PATH: pluginPath,
        IBOX_PATH: pluginPath
    },
    cacheForExec: {},
    cacheBeforeExec: function(cmd) {
        if (SBC.cacheForExec) return true;
        return false;
    },
    splinterTable: [],
    cache: {},
    addbehavior: function(trigger, callback) {
        this.splinterTable[trigger] = callback;
    },
    addbutton: function(key, options) {
        if (options.cachceSelection) SBC.cacheForExec.key = 1;
        this.options.buttons[key] = options;
        SBC.addbehavior(options.cmd, options.handler);
    },
    trigger: function(wym, cmd) {
        if (typeof(SBC.splinterTable[cmd]) != "undefined") {
            SBC.splinterTable[cmd](wym);
            return true;
         }
         return false;
    },
    overlay: function(wym, sType, callback) {
      var btn = SBC.options.buttons[sType];

      var dialogHeight = 400;
      switch (sType) {
          case WYMeditor.DIALOG_LINK:
              dialogHeight = 220;
              break;
          case WYMeditor.DIALOG_IMAGE:
              dialogHeight = 450;
              break;
          case WYMeditor.DIALOG_TABLE:
              dialogHeight = 260;
              break;
          case WYMeditor.DIALOG_PASTE:
              dialogHeight = 320;
              break;
          case WYMeditor.DIALOG_SITEVAR:
              dialogHeight = 250;
              break;
          case WYMeditor.DIALOG_GADGET:
              dialogHeight = 250;
              break;
          case WYMeditor.DIALOG_VIDEO:
              dialogHeight = 320;
              break;
      }
      
      // If the SBC button is not defined, let the call 
      // pass thru to WYMeditor's dialog behavior

      if (typeof(btn) == "undefined") {
          wym.dialog(sType);
          return false;
      }

      var file_path = SBC.options.wym_path + btn.href + '?index=' + wym._index;

      $.get(file_path, function(data) {
          showModalDialog(data, {
              minWidth: 520,
              minHeight: dialogHeight,
              onShow: callback
          });
      });
      return false;
    },
    hideOverlay: function(index) {
        try {
            $.modal.close();
            window.WYMeditor.INSTANCES[index].update();
        }
        catch(e) {/*Fail silently*/}
    },
    basename: function(src) {
        var bits = src.split('/');
        return bits.length ? bits[bits.length-1] : src;
    },
    val: function(id) {
        return jQuery("#"+id).val();
    },
    cacheSelection: function(wym) {
        var w = wym._iframe.contentWindow;
        var d = wym._doc;
        if (w.getSelection) {
            var selection = w.getSelection();
            if (selection.rangeCount > 0) {
              var selectedRange = selection.getRangeAt(0);
              w._selection = selectedRange.cloneRange();
            }
            else {
              return null;
            }
          }
          else if (d.selection) {
            var selection = d.selection;
            if (selection.type.toLowerCase() == 'text') {
              w._selection = selection.createRange().getBookmark();
            }
            else {
              this.cache.selection = null;
            }
          }
          else {
            this.cache.selection = null;
        }
    },
    restoreSelection: function(index) {
        var wym = window.WYMeditor.INSTANCES[index];
        var w = wym._iframe.contentWindow;
        var d = wym._doc;
        try {jQuery(d).focus();} catch(e) {}
        if (w._selection) {
            if (w.getSelection) {
              var selection = w.getSelection();
              selection.removeAllRanges();
              selection.addRange(w._selection);
              w._selection = null;
            }
            else if (d.selection && d.body.createTextRange) {
              var range = d.body.createTextRange();
              range.moveToBookmark(w._selection);
              range.select();
              w._selection = null;
            }
        }
    },
    replaceSelection: function(index, text) {
        var wym = window.WYMeditor.INSTANCES[index];
        var w = wym._iframe.contentWindow;
        var d = wym._doc;
        try {jQuery(d).focus();} catch(e) {}
        if (!w.getSelection && !d.selection && !d.body.createTextRange) {
            return false;
        }
        SBC.restoreSelection(index);
        if (w.getSelection) {
          var selection = w.getSelection();
          var range = selection.getRangeAt(0);
          range.deleteContents();
          var textNode = d.createTextNode(text);
          range.insertNode(textNode);
          if (textNode.normalize) textNode.normalize();
        }
        else if (d.selection && d.body.createTextRange) {
          var range = d.selection.createRange(); // d.body.createTextRange();
          range.text = text;
          w._selection = null;
        }
    },
    setSelection: function(index, node) {
        var wym = window.WYMeditor.INSTANCES[index];
        var w = wym._iframe.contentWindow;
        var d = wym._doc;
        try {jQuery(d).focus();} catch(e) {}
        if (!w.getSelection && !d.selection && !d.body.createTextRange) {
            return false;
        }
        if (w.getSelection) {
          var selection = w.getSelection();
        }
        else if (d.selection && d.body.createTextRange) {
          var range = d.body.createTextRange();
          range.select();
        }
    }
});

SBC.InsertGadget = function(index) {
    var wym = window.WYMeditor.INSTANCES[index];
    var doc = window.document;
    var selected = wym.selected();
    var dialogType = jQuery(wym._options.dialogTypeSelector).val();
    var sStamp = wym.uniqueStamp();
    var gadget = SBC.val('gadgets');
    
    if (gadget == "" || typeof(gadget) == "undefined") return;

    if (selected && selected.tagName.toLowerCase() != WYMeditor.BODY) {
        jQuery(selected).after("{gadget(" + gadget + ")}");
    } 
    else {
        jQuery(wym._doc.body).append("{gadget(" + gadget + ")}");
    }
};

SBC.InsertVideo = function(index) {
    var wym = window.WYMeditor.INSTANCES[index];
    var doc = window.document;
    var selected = wym.selected();
    var dialogType = jQuery(wym._options.dialogTypeSelector).val();
    var sStamp = wym.uniqueStamp();
    
    var theVideo  = $("#video").val();
    var thePortal = $("#portal").val();
    var showLink  = $("#show_video_link").val();
    var linkText  = $("#video_link_text").val();
    
    var theLink = showLink == '1' ? '' : 'nolink' ;
    
    var theReplacementToken = "[" +  thePortal + " " + theVideo + " " + theLink + "]";

    if (selected && selected.tagName.toLowerCase() != WYMeditor.BODY) {
        jQuery(selected).after(theReplacementToken);
    } 
    else {
        jQuery(wym._doc.body).append(theReplacementToken);
    }
};

SBC.InsertSiteVar = function(index) {
    var wym = window.WYMeditor.INSTANCES[index];
    var doc = window.document;
    var selected = wym.selected();
    var dialogType = jQuery(wym._options.dialogTypeSelector).val();
    var sStamp = wym.uniqueStamp();
    var sitevar = SBC.val('sitevars');
    
    if (sitevar == "" || typeof(sitevar) == "undefined") return;

    if (selected && selected.tagName.toLowerCase() != WYMeditor.BODY) {
        SBC.cacheSelection(wym);
        SBC.replaceSelection(index, "[[" + sitevar + "]]");
    } 
    else {
        jQuery(wym._doc.body).append("[[" + sitevar + "]]");
    }
};

SBC.InsertTable = function(index) {
    var wym = window.WYMeditor.INSTANCES[index];
    var doc = window.document;
    var selected = wym.selected();
    var sStamp   = wym.uniqueStamp();
    
    // Capture the form data
    
    var iRows    = jQuery("#tablerows").val();
    var iCols    = jQuery("#tablecolumns").val();
    var _class   = jQuery("#tableclass").val();
    var sCaption = jQuery("#tablecaption").val();
    
    if (iRows > 0 && iCols > 0) {
        var table = wym._doc.createElement(WYMeditor.TABLE);
        jQuery(table).attr("class", _class);
        var newRow = null;
        var newCol = null;
        
        // create the caption
        table.createCaption().innerHTML = sCaption;
        
        // create the rows and cells
        for (x=0; x<iRows; x++) {
            newRow = table.insertRow(x);
            // for(y=0; y<iCols; y++) {newRow.insertCell(y);}
            for (y=0; y<iCols; y++) {
                jQuery(newRow).append("<td>" + y + "</td>");
            }
        }
        
        jQuery("tr", table).each(function() {
            // for(y=0; y<iCols; y++) {jQuery(this).append("<td>" + y + "</td>");}
        });
            
      // append the table after the selected container
      var node = jQuery(wym.findUp(wym.container(),WYMeditor.MAIN_CONTAINERS)).get(0);
      if (!node || !node.parentNode) {
          jQuery(wym._doc.body).append(table);
      }
      else {
          jQuery(node).after(table);
      }
    }
};

SBC.InsertPaste = function(index) {
    var sData = jQuery("#paste_text").val();
    var wym = window.WYMeditor.INSTANCES[index];
    var doc = window.document;
    var selected = wym.selected();
    var dialogType = jQuery(wym._options.dialogTypeSelector).val();
    var sStamp = wym.uniqueStamp();
    var sTmp;
    var container = wym.selected();
    
    // split the data, using double newlines as the separator
    var aP = sData.split(wym._newLine + wym._newLine);
    var rExp = new RegExp(wym._newLine, "g");
    
    // add a P for each item
    if (container && container.tagName.toLowerCase() != WYMeditor.BODY) {
        for (x = aP.length - 1; x >= 0; x--) {
            sTmp = aP[x];
            // simple newlines are replaced by a break
            sTmp = sTmp.replace(rExp, "<br />");
            jQuery(container).after("<p>" + sTmp + "</p>");
        }
    } 
    else {
        for (x = 0; x < aP.length; x++) {
            sTmp = aP[x];
            // simple newlines are replaced by a break
            sTmp = sTmp.replace(rExp, "<br />");
            jQuery(wym._doc.body).append("<p>" + sTmp + "</p>");
        }
    }
};

/**
* Image Handling Methods
*/

SBC.relativeImagePaths = function() {
    var wyms = window.WYMeditor.INSTANCES;
    for (var x=0; x<wyms.length; x++) {
        var wym = wyms[x];
        var imgs = wym._doc.body.getElementsByTagName(WYMeditor.IMG);
        for (i=0; i<imgs.length; i++) {
            var sStamp = jQuery(imgs[i]).attr("id");
            if (sStamp == "undefined") {
                sStamp = wym.uniqueStamp();
            }
            var options = {index: x, id: sStamp};
            jQuery(imgs[i]).bind("dblclick", options, function(e) {
                SBC.HandleImageDoubleClick(this, e.data);
            });
        }
    }
};

SBC.InsertImage = function(src, width, height, index) {  
    var wym = window.WYMeditor.INSTANCES[index];
    var src = wym._options.relativepath + src;
    var doc = window.document;
    var selected = wym.selected();
    var dialogType = jQuery(wym._options.dialogTypeSelector).val();
    var sStamp = wym.uniqueStamp();
    if (src.length > 0) {
        wym._exec(WYMeditor.INSERT_IMAGE, sStamp);
        var image = null;
        var nodes = wym._doc.body.getElementsByTagName(WYMeditor.IMG);
        for(var i=0; i < nodes.length; i++) {
            if (jQuery(nodes[i]).attr(WYMeditor.SRC) == sStamp) {
                image = jQuery(nodes[i]);
                break;
            }
        }
        if (image) {
            image.attr(WYMeditor.SRC, src);
            image.attr(WYMeditor.TITLE, "image");
            image.attr(WYMeditor.ALT,   "image");
            image.attr("id", sStamp);
            
            var options = {index: index, id: sStamp};
            image.bind("dblclick", function() {
                SBC.HandleImageDoubleClick(this, options);
            });
        }
    }
};
    
SBC.AddImageAttrs = function(index, id) {
    var _alt    = SBC.val('alt-text');
    var _title  = SBC.val('title-text');
    var _class  = SBC.val('css-class');

    var wym = window.WYMeditor.INSTANCES[index];
    var doc = window.document;
    var selected = wym.selected();
    
    var image = null;
    
    var nodes = wym._doc.body.getElementsByTagName(WYMeditor.IMG);
    for(var i=0; i < nodes.length; i++) {
        if (jQuery(nodes[i]).attr("id") == id) {
            image = jQuery(nodes[i]);
            break;
        }
    }

    if (image) {
        image.attr("alt",   _alt);
        image.attr("title", _title);
        image.attr("class", _class);
    }
};
    
SBC.AttachImageDblClick = function(img) {
    var wyms = window.WYMeditor.INSTANCES;
    for (var x=0; x<wyms.length; x++) {
        var wym = wyms[x];
        var imgs = wym._doc.body.getElementsByTagName(WYMeditor.IMG);
        for (i=0; i<imgs.length; i++) {
            var sStamp = jQuery(imgs[i]).attr("id");
            if (sStamp == "undefined") {
                sStamp = wym.uniqueStamp();
            }
            var options = {index: x, id: sStamp};
            jQuery(imgs[i]).bind("dblclick", options, function(e) {
                SBC.HandleImageDoubleClick(this, e.data);
            });
        }
    }
};
    
SBC.HandleImageDoubleClick = function(img, options) {
    var file_path = 
        SBC.options.wym_path + "php/image.attrs.php?index=" + options.index 
        + '&id='    + options.id 
        + '&alt='   + jQuery(img).attr("alt")
        + '&title=' + jQuery(img).attr('title')
        + '&class=' + jQuery(img).attr('class');
          
    $.get(file_path, function(data) {
        showModalDialog(data, {
            minWidth: 520, 
            minHeight: 180, 
            background: "#FFF"
        });
    });
};

SBC.InsertLink = function(index) {    
    var file  = SBC.val('filedownload');
    var page  = SBC.val('internalpage');
    var link  = SBC.val('externallink');
    var title = SBC.val('linktitle');
    
    var sUrl = file;
    if (page != "" && page != "undefined") var sUrl = page;
    if (link != "" && link != "undefined") var sUrl = link;
    
    var wym = window.WYMeditor.INSTANCES[index];
    var doc = window.document;
    var selected = wym.selected();
    var dialogType = jQuery(wym._options.dialogTypeSelector).val();
    var sStamp = wym.uniqueStamp();

    // ensure that we select the link to populate the fields
    if (selected && selected.tagName && selected.tagName.toLowerCase != WYMeditor.A)
        selected = jQuery(selected).parentsOrSelf(WYMeditor.A);
    
    // fix MSIE selection if link image has been clicked
    if (!selected && wym._selected_image)
        selected = jQuery(wym._selected_image).parentsOrSelf(WYMeditor.A);
    
    if (sUrl.length > 0) {
        wym._exec(WYMeditor.CREATE_LINK, sStamp);
        var link = null;
        var nodes = wym._doc.body.getElementsByTagName(WYMeditor.A);
        for (var i=0; i < nodes.length; i++) {
            if (jQuery(nodes[i]).attr(WYMeditor.HREF) == sStamp) {
                link = jQuery(nodes[i]);
                break;
            }
        }
        if (link) {
            link.attr(WYMeditor.HREF, sUrl);
            link.attr(WYMeditor.TITLE, title);
        }
    }
};
    
/* Create custom string constants for the Gadget button */

WYMeditor.INSERT_GADGET  = 'Gadget';
WYMeditor.DIALOG_GADGET  = 'Gadget';

WYMeditor.DIALOG_VIDEO   = "Video";
WYMeditor.INSERT_VIDEO   = "InsertVideo";

WYMeditor.INSERT_SITEVAR = 'SiteVar';
WYMeditor.DIALOG_SITEVAR = 'SiteVar';

/* Attach the server-side processor files to our buttons */

// SBC.addbutton(buttonName, options);

SBC.addbutton(
    WYMeditor.DIALOG_IMAGE, {
    'href': 'php/image.php', 
    'title': 'Select an image',
    'cmd': WYMeditor.INSERT_IMAGE,
    'cachceSelection': true,
    'handler': function (wym) {
        if (! getFocusNode(wym)) {
            if ($.browser.msie) {
                jQuery(wym._doc.body).focus();
            }
            else {
                SBC.setSelection(wym._index, wym._doc.body);
            }
        }
        SBC.overlay(wym, WYMeditor.DIALOG_IMAGE);
    }
});

SBC.addbutton(
    WYMeditor.DIALOG_VIDEO, {
    'href': 'php/video.php', 
    'title': 'Select a video',
    'cmd': WYMeditor.INSERT_VIDEO,
    'cachceSelection': true,
    'handler': function (wym) {
        if (! getFocusNode(wym)) {
            if ($.browser.msie) {
                jQuery(wym._doc.body).focus();
            }
            else {
                SBC.setSelection(wym._index, wym._doc.body);
            }
        }
        SBC.overlay(
            wym, 
            WYMeditor.DIALOG_VIDEO, function() { 
               $("#video-tabs").tabs({
                   fx: {opacity: "toggle"}, 
                   cookie: { expires: 30 }
               }); 
               $("#video-tabs").tabs('select', 0);
        });
    }
});

SBC.addbutton(
    WYMeditor.DIALOG_LINK, {
    'href': 'php/link.php', 
    'title': 'Enter the link details below',
    'cmd': WYMeditor.CREATE_LINK,
    'cachceSelection': true,
    'handler': function (wym) {
        if (wym.container() || wym._selected_image) {
            SBC.overlay(wym, WYMeditor.DIALOG_LINK);
        }
    }
});

function getFocusNode(wym) {
    var focusNode = wym.selected();    
    if (focusNode && focusNode.nodeName.toLowerCase() == 'br') {
       var _newFocusNode = focusNode.parentNode;
       jQuery(focusNode).remove();
       focusNode = _newFocusNode;
    }
    return focusNode;
};

SBC.addbutton(
    WYMeditor.DIALOG_TABLE, {
    'href': 'php/table.php', 
    'title': 'Enter the table details below',
    'cmd': WYMeditor.PASTE,
    'handler': function (wym) {
        SBC.overlay(wym, WYMeditor.DIALOG_PASTE);
    }
});

SBC.addbutton(
    WYMeditor.DIALOG_PASTE, {
    'href': 'php/paste.php', 
    'title': 'Paste your text in the field below',
    'cmd': WYMeditor.INSERT_TABLE,
    'handler': function (wym) {
        SBC.overlay(wym, WYMeditor.DIALOG_TABLE);
    }
});

SBC.addbutton(
    WYMeditor.DIALOG_GADGET, {
    'href': 'php/gadget.php', 
    'title': 'Select a Gadget to insert',
    'cmd': WYMeditor.INSERT_GADGET,
    'handler': function (wym) {
        SBC.overlay(wym, WYMeditor.DIALOG_GADGET);
    }
});

SBC.addbutton(
    WYMeditor.DIALOG_SITEVAR, {
    'href': 'php/sitevars.php', 
    'title': 'Select a Variable to insert',
    'cmd': WYMeditor.INSERT_SITEVAR,
    'cachceSelection': true,
    'handler': function (wym) {
        SBC.overlay(wym, WYMeditor.DIALOG_SITEVAR);
    }
});

/* Overload the wym.exec function to handle the custom Gadgets button */

if (! WYMeditor.editor.splinterTable) 
    WYMeditor.editor.splinterTable = [];

WYMeditor.editor.prototype.trigger = function(wym, cmd) {
    if (typeof(WYMeditor.editor.splinterTable[cmd]) != "undefined") {
        WYMeditor.editor.splinterTable[cmd](wym);
        return true;
    }
    return false;
}

WYMeditor.editor.addbehavior = function(key, value) {
    this.splinterTable[key] = value;
}

WYMeditor.editor.addbehavior(WYMeditor.CREATE_LINK, function(wym) {
    var container = wym.container();
    if (container || wym._selected_image) wym.dialog(WYMeditor.DIALOG_LINK);
});

WYMeditor.editor.addbehavior(WYMeditor.INSERT_IMAGE, function(wym) {
    wym.dialog(WYMeditor.DIALOG_IMAGE);
});

WYMeditor.editor.addbehavior(WYMeditor.INSERT_TABLE, function(wym) {
    wym.dialog(WYMeditor.DIALOG_TABLE);
});

WYMeditor.editor.addbehavior(WYMeditor.PASTE, function(wym) {
    wym.dialog(WYMeditor.DIALOG_PASTE);
});

WYMeditor.editor.addbehavior(WYMeditor.TOGGLE_HTML, function(wym) {
    wym.update();
    jQuery(wym._box).find(wym._options.htmlSelector).slideToggle("slow");
});

WYMeditor.editor.addbehavior(WYMeditor.PREVIEW, function(wym) {
    wym.dialog(WYMeditor.PREVIEW);
});

WYMeditor.editor.prototype.exec = function (cmd) {
    if (SBC.cacheBeforeExec(cmd)) SBC.cacheSelection(this);
    if (SBC.trigger(this, cmd))  return true;
    if (this.trigger(this, cmd)) return false;
    this._exec(cmd);
};