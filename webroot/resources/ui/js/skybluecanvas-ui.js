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

/**
 * jQuery-ui helpers
 */
 
$(function() {
    /**
     * Add the hover feedback behavior to buttons
     */
    $(".ui-state-default").hover(
        function() { 
            $(this).addClass("ui-state-hover"); 
        },
        function() { 
            $(this).removeClass("ui-state-hover"); 
        }
    );
    
    /**
     * Add the click feedback behavior to buttons
     */
    $(".ui-state-default").mousedown(function() { 
        $(this).addClass("ui-state-active"); 
        $(this).mouseup(function() {
            $(this).removeClass("ui-state-active"); 
        });
    });
    
    /**
     * Add image reflections
     */
    $(".reflect").reflect();
});