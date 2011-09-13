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

/**
 * This file contains the JavaScript code that handles the Admin Manager actions.
 */

/**
 * Initializes the Manager form
 * @return void
 */
jQuery(function() {

   /**
    * Disable the enter key on form fields so that we 
    * can force the user to explicitly trigger an action.
    */
   
    jQuery('input').keypress(function(e) { 
       if (e.keyCode == 13) {
           e.preventDefault();
       }
   });
   
});

/**
 * Sets the action variable in Manager forms. The action variable 
 * is the event that is triggered in the $Manager->trigger() method 
 * in the Manger PHP class.
 * @param object  The button object of the clicked button
 * @param string  The action the button is to trigger
 * @return void
 */
function set_action(button, action) {
    try {
        jQuery("#mgrform").find("input[name='action']").val(action);
    }
    catch (e) {
        alert(e);
    }
};

/**
 * Confirm intent when a User clicks a Delete button.
 * @param object    The window.event object
 * @param string    The name of the item to be deleted
 * @param boolean   Whether or not the action can be undone
 * @param string    The destination URL to redirect to if the action is confirmed
 * @return void
 */
function confirm_delete(e, itemName, canUndo, destination) {

    // Stop the default event
    
    if (e && e.preventDefault) e.preventDefault();
    
    // Build the message string
    
    var message = window.TERMS.CONFIRM_DELETE_ITEM.replace('{itemname}', itemName);
    if (canUndo == 1 || canUndo == true || canUndo == 'true') {
        message += window.TERMS.CAN_UNDO;
    }
    else {
        message += window.TERMS.CANNOT_UNDO;
    }
    
    var myButtons = {};
    
    myButtons[window.TERMS.DELETE] = function() {
        window.location.href = destination;
    };
    
    myButtons[window.TERMS.CANCEL] = function() {
        $(this).dialog('close');
    };
    
    // Display the dialog
    
    $.editor.impl.dialog(message, { 
        title: window.TERMS.LABEL_CONFIRM_DELETE,
        buttons: myButtons,
        iconClass: 'alert'
    });
};

/**
 * Confirm user intended to perform the requested action
 * @param object    The window.event object
 * @param string    The message for the dialog
 * @param function  The callback to execute on TRUE
 * @param function  The callback to execute on FALSE/CANCEL
 * @return void
 */
function confirm_action(e, dialogTitle, dialogMessage, onTrueCallback, onFalseCallback) {

    // Stop the default event
    
    if (e && e.preventDefault) e.preventDefault();
    
    var myButtons = {};
    
    myButtons[window.TERMS.NO] = function() {
        if (onFalseCallback) {
            onFalseCallback();
            $(this).dialog('close');
        }
        else {
            $(this).dialog('close');
        }
    };
    
    myButtons[window.TERMS.YES] = function() {
        onTrueCallback();
        $(this).dialog('close');
    };
    
    // Display the dialog
    
    $.editor.impl.dialog(dialogMessage, { 
        title: dialogTitle,
        buttons: myButtons,
        iconClass: 'alert'
    });
};


/**
 * Confirm intent when a User clicks a Undo Check-out button.
 * @param string    The name of the item to be checked-in
 * @param boolean   Whether or not the action can be undone
 * @return boolean  Whether or not the User confirmed the action
 */
function confirm_checkin(e, itemName, destination) {
    
    // Stop the default event
    
    if (e && e.preventDefault) e.preventDefault();
    
    var myButtons = {};
    
    myButtons[window.TERMS.OKAY] = function() {
        window.location.href = destination;
    };
    
    myButtons[window.TERMS.CANCEL] = function() {
        $(this).dialog('close');
    };

    // Show the dialog

    $.editor.impl.dialog(window.TERMS.CONFIRM_CHECK_IN.replace('{itemname}', itemName), { 
        title: window.TERMS.LABEL_CONFIRM_CHECKIN,
        buttons: myButtons,
        iconClass: 'alert'
    });
};

/**
 * Show an alert to the user
 * @param object    The window.event object
 * @param string    The message for the dialog
 * @param function  The callback to execute when Okay button is clicked
 * @return void
 */
function show_alert(e, dialogTitle, dialogMessage, theCallback) {

    // Stop the default event
    
    if (e && e.preventDefault) e.preventDefault();
    
    var myButtons = {};
    
    if (typeof(theCallback) == "function") {
		myButtons[window.TERMS.OKAY] = function() {
			theCallback();
			$(this).dialog('close');
		};
    }
    
    // Display the dialog
    
    $.editor.impl.dialog(dialogMessage, { 
        title: dialogTitle,
        buttons: myButtons,
        iconClass: 'alert'
    });
};

/**
 * Fades out and removes a DOM element
 * @param The element ID
 * @return void
 */
function fadeAndRemove(blockId) {
    $(blockId).fadeOut("slow", function() {
        $(blockId).remove();
    });
};

/**
 * Sets a timer to delay an action
 * @param theExpression  Must be a string such as "myFunction()"
 * @param theDelay       The amount of the delay in micro-seconds
 * @return void
 */
function delayedReaction(theExpression, theDelay) {
    if (typeof(window.timers) == "undefined") {
        window.timers = [];
    }
    window.timers.push(setTimeout(theExpression, theDelay));
};

/**
 * Displays a modal overlay dialog (Simple Modal)
 * @param String  dialogContent  The HTML of the dialog content
 * @param Object  options        An Object of options for the dialog
 * @return void
 */
function showModalDialog(dialogContent, options) {
    this.options = {
        minWidth:  520,
        overlayClose: true, 
        opacity: 75,
        zIndex: 100001,
        onOpen: function (dialog) {
            dialog.overlay.fadeIn('slow', function () {
                dialog.data.hide();
                dialog.container.fadeIn('slow', function () {
                    dialog.data.fadeIn('slow');
                });
            });
        },
        onClose: function (dialog) {
            dialog.data.fadeOut('slow', function () {
                dialog.container.fadeOut('slow', function () {
                    dialog.overlay.fadeOut('slow', function () {
                        $.modal.close();
                    });
                });
            });
        }
    };
    $.modal(dialogContent, $.extend(this.options, options));
};

/**
 * Constrains a set of width/height dimensions based on the max dimension
 * @param int max  The maximum dimension in either direction
 * @param int w    The current width
 * @param int h    The current height
 * @return Array   An array in order w, h of the re-sized dimensions
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
                    h = Math.ceil( h * ratio );
                    w = Math.ceil( w * ratio );
                }
            }
        }
        
        if (ratio == widthratio && ratio != 1) {
            if (w < max) {
                var Ticker = 0;
                while (w < max && Ticker < SB_MAX_LOOP_NUM) {
                    ratio = ratio * 1.01;
                    h = Math.ceil( h * ratio );
                    w = Math.ceil( w * ratio );
                    Ticker++;
                }
            }
        }
    }
    return [w,h];
};

/**
 *
 */
function _preventEmptySubmit(e) {
    if ($("#upload_file").val() == "") {
        e.preventDefault();
        alert(window.TERMS.SELECT_UPLOAD_FILE);
    }
};

/**
 *
 */
function dump(theObject) {
    var win = window.open();
    var doc = win.document;
    for (key in theObject) {
        doc.write(key + " => " + theObject[key]  + "<br />\n");
    }
    doc.close();
};