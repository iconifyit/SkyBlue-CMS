/**
 * @version      2.0 2009-04-19 10:37:00 $
 * @package        SkyBlueCanvas
 * @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license        GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

var SkyBlue = function() {

    /**
    * Store any paths needed by SkyBlue
    */

    this.paths   = [];
    
    /**
    * Various configuration options for SkyBlue
    */

    this.options = [];
    
    /**
    * Create a wrapper for the window.location object 
    * that has some additional functionality to make 
    * it a little more usable.
    */
    
    this.request = {};
    
    /**
     * Create a global storage space for queuing various entities
     */
    
    this.queue = [];
    
    /**
    * Wrap jQuery inside of the SkyBlue app so there is no 
    * conflict if the site owner wants to use some other 
    * JavaScript library in their site templates.
    */
    
    this.$ = {};
};

/**
* Add a prototype object to the base SkyBlue function 
* so we can extend the function and use the SkyBlue.myFunction 
* syntax.
*/

SkyBlue.prototype = {};

/**
* Mutator to add options to the SkyBlue object
*/

SkyBlue.prototype.addOption = function(name, value) {
    this.options[name] = value;
};

/**
* Mutator to add paths to the SkyBlue object
*/

SkyBlue.prototype.addPath = function(name, value) {
    this.paths[name] = value;
};

/**
 * Add item to queue
 */

SkyBlue.prototype.enqueue = function(hash, item) {
    if (typeof(this.queue[hash]) == "undefined") {
        this.queue[hash] = [];
    }
    this.queue[hash].push(item);
};

/**
 * Get queued items
 */
SkyBlue.prototype.getQueue = function(hash) {
    var queue = this.queue[hash];
    if (typeof(queue) == "undefined") return [];
    return queue;
};

/**
* Accessor to get options from the SkyBlue object
*/

SkyBlue.prototype.getOption = function(name, _default) {
    if (this.options[name]) {
        return this.options[name];
    }
    return _default;
};

/**
* Accessor to get paths from the SkyBlue object
*/

SkyBlue.prototype.getPath = function(name, _default) {
    if (this.paths[name]) {
        return this.paths[name];
    }
    return _default;
};

/**
* Accessor to get options from the SkyBlue object
*/

SkyBlue.prototype.user = function(key, _default) {
    if (this.options["user"]) {
        var User = this.options["user"];
        if (typeof(User[key]) != "undefined") {
            return User[key];
        }
    }
    return _default;
};

/**
* Handy function to add new Script links on the fly so 
* we don't have to clutter the user's template with 
* <script /> tags.
*/

SkyBlue.prototype.include = function(theScript, callback) {
    var head = document.getElementsByTagName('head')[0];
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.onreadystatechange= function () {
      if (this.readyState == 'complete' || this.readyState == 'loaded') {
         callback();
      }
    }
    script.onload = callback;
    script.src = theScript;
    head.appendChild(script);
};

/**
* Initialize the SkyBlue instance
*/

SkyBlue.prototype.init = function(options) {
    
    if (options.context) {
        sbc.addOption("context", options.context);
    }
    if (options.logged_in) {
        sbc.addOption("logged_in", options.logged_in);
    }
    if (options.is_admin) {
        sbc.addOption("is_admin", options.is_admin);
    }
    if (options.ui_path) {
        sbc.addPath("js", options.ui_path + "js/");
        sbc.addPath("css", options.ui_path + "css/");
    }
    
    if (options.user) {
        this.addOption("user", options.user);
    }
    
    sbc.jquery();
    sbc.main();
};

/**
* Include the jQuery object in our SkyBlue object.
*/

SkyBlue.prototype.jquery = function() {
    $ = window.jQuery ? window.jQuery : jQuery.noConlict() ;
};

/**
* Executes the main SkyBlue logic
*/

SkyBlue.prototype.main = function() {
    $(function() {
        $.editor.impl.listen();
        $.editor.impl.tooltip();
        if (typeof(Request) == "function") {
            $.request = new Request();
        }
    });
};

/**
* Displays a modal overlay dialog (Simple Modal)
* @param String  dialogContent  The HTML of the dialog content
* @param Object  options        An Object of options for the dialog
* @return void
*/
SkyBlue.prototype.modal = function(data, options) {
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
    $.modal(data, $.extend(this.options, options));
};

/**
 * Closes the modal overlay
 * @param e Event Object  The event object
 * @return null
 */
SkyBlue.prototype.closeModal = function(e) {
    if (e) { e.preventDefault(); }
    $.modal.close();
};

/**
 * Sets a timer to delay an action
 * @param theExpression  Must be a string such as "myFunction()"
 * @param theDelay       The amount of the delay in micro-seconds
 * @return void
 */
SkyBlue.prototype.setTimeout = function(theExpression, theDelay) {
    if (typeof(window.timers) == "undefined") {
        window.timers = [];
    }
    window.timers.push(setTimeout(theExpression, theDelay));
};

/**
* Store a new instance of the SkyBlue object in windw.sbc (global scope)
*/

if (!window.sbc) window.sbc = new SkyBlue();