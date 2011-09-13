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
 * Generates a unique ID, using str as the base of the ID
 * @param string str  A base string for the ID
 * @return string     A unique element ID
 */

/**
 * $.extend() adds a jQuery method that accepts arguments.
 * @example $.myCustomMethod(arg1, arg2, ...);
 */
$.extend({
    
    /**
     * Generates a unique ID using str as the base and a random number
     * @param string   The base of the ID
     * @return string  The unique ID
     */
    uniqueId: function(str) {
        return str + "-" + Math.ceil(Math.random() * 100000);
    },
    
    /**
     * Determines if a value is empty
     * @param string    The string to test
     * @return boolean  Whether or not the string is empty
     */
    isempty: function(subject) {
        if (typeof(subject) == "undefined") return true;
        if (subject == null) return true;
        if (typeof(subject) == "string") {
            return $.trim(subject) == "";
        }
        if (typeof(subject) == "object" || typeof(subject) == "array") {
            for (key in subject) {
                return false;
            }
            return true;
        }
        return false;
    },
    
    /**
     * Trims leading and trailing whitespace from a string
     * @param string   The string to trim
     * @return string  The trimmed string
     */
    trim: function(str) {
        if (typeof(str) != "string") {
            throw "Argument must be a string in $.trim(str)";
            return false;
        }
        str = str.replace(/^\s*|\s*$j/g,"");
        str = str.replace(/^\t*|\t*$j/g,"");
        str = str.replace(/^\r*|\r*$j/g,"");
        str = str.replace(/^\n*|\n*$j/g,"");
        return str;
    },
    
    /**
     * Determines if n is a number
     * @param number  The string or number to test
     */
    isnumber: function (n) {
        try {
            if (isNaN(n)) return false;
            return true;
        }
        catch (e) {
            return false;
        }
    }
    
});

/**
 * $.fn.extend() adds a jQuery method that operates on some element.
 * @example $(selector).myCustomMethod();
 */
$.fn.extend({
    
    /**
     * Determines if a checkbox or radio button is checked
     * @param HtmlElement  The HTML element
     * @return boolean     Whether or not the element is checked
     */
    checked: function() {
        var nodeName = $(this)[0].nodeName;
        var type = $(this).attr("type");
        if (nodeName != "INPUT" || (type != "radio" && type != "checkbox")) {
            throw "Element must be an input of type checkbox or radio in $(el).checked()";
            return false;
        }
        return $(this).attr("checked") == true;
    },
    
    
    /**
     * Sets the checked state of an element to true
     * @param HtmlElement  The HTML element to check
     * @return void
     */
    check: function() {
        var nodeName = $(this)[0].nodeName;
        var type = $(this).attr("type");
        if (nodeName != "INPUT" || (type != "radio" && type != "checkbox")) {
            throw "Element must be an input of type checkbox or radio in $(el).check()";
            return false;
        }
        $(this).attr({"checked": "checked"});
    },
    
    /**
     * Sets the checked state of an element to false
     * @param HtmlElement  The HTML element to un-check
     * @return void
     */
    uncheck: function() {
        var nodeName = $(this)[0].nodeName;
        var type = $(this).attr("type");
        if (nodeName != "INPUT" || (type != "radio" && type != "checkbox")) {
            throw "Element must be an input of type checkbox or radio in $(el).uncheck()";
            return false;
        }
        $(this).attr({"checked": false});
    },
    
    /**
     * Disables an HTML input
     * @param HtmlElement  The HTML element to disable
     * @return void
     */
    disable: function() {
        var nodeName = $(this)[0].nodeName;
        if (nodeName != "INPUT" && nodeName != "SELECT" && nodeName != "TEXTAREA") {
            throw "Element must be an INPUT, SELECT or TEXTAREA in $(el).disable()";
            return false;
        }
        $(this).attr({"disabled": true});
    },
    
    /**
     * Enables an HTML input
     * @param HtmlElement  The HTML element to enable
     * @return void
     */
    enable: function() {
        var nodeName = $(this)[0].nodeName;
        if (nodeName != "INPUT" && nodeName != "SELECT" && nodeName != "TEXTAREA") {
            throw "Element must be an INPUT, SELECT or TEXTAREA in $(el).enable()";
            return false;
        }
        $(this).attr({"disabled": false});
    },
    
    /**
     * Makes an HTML input readonly
     * @param HtmlElement  The HTML element to make readonly
     * @return void
     */
    readonly: function() {
        var nodeName = $(this)[0].nodeName;
        if (nodeName != "INPUT" && nodeName != "SELECT" && nodeName != "TEXTAREA") {
            throw "Element must be an INPUT, SELECT or TEXTAREA in $(el).disable()";
            return false;
        }
        $(this).attr({"readonly": "readonly"});
    },
    
    /**
     * Makes an HTML input that is readonly, writable
     * @param HtmlElement  The HTML element to make writable
     * @return void
     */
    writable: function() {
        var nodeName = $(this)[0].nodeName;
        if (nodeName != "INPUT" && nodeName != "SELECT" && nodeName != "TEXTAREA") {
            throw "Element must be an INPUT, SELECT or TEXTAREA in $(el).disable()";
            return false;
        }
        $(this).removeAttr("readonly");
    }
});