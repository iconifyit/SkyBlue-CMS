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

var Request = function() {
    
    this.hash     = window.location.hash;
    this.host     = window.location.host;
    this.hostname = window.location.hostname;
    this.href     = window.location.href;
    this.pathname = window.location.pathname;
    this.port     = window.location.port;
    this.protocol = window.location.protocol;
    this.search   = window.location.search;
    
    this.get = function(key, _default) {
        if (this[key]) {
            return this[key];
        }
        return _default;
    }
    
    this.getParam = function(key, _default) {
        if (!this.params[key]) return _default;
        return this.params[key];
    }
    
    this.setParams = function(query) {
        this.params = new Array();
        query = query.replace("?", "");
        var parts = query.split("&");
        for (i=0; i<parts.length; i++) {
            var bits = parts[i].split("=");
            if (bits.length != 2) continue;
            this.params[bits[0]] = bits[1];
        }
    }
    
    this.stripParam = function(keyToStrip) {
        var newUrl = 
            this.protocol 
            + "//"
            + this.host 
            + (this.port ? this.port : '') 
            + this.pathname ;
            
        var search = [];
        for (key in this.params) {
            if (key == keyToStrip) continue;
            search.push(key + "=" + this.params[key]);
        }
        return newUrl + (search.length ? "?" + search.join("&") : "" ); 
    }
    
    this.setParams(this.search);
};