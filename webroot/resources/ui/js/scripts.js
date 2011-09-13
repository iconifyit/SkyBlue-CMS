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

var g_initialState = '';
var thisnav        = '';
var isIE           = false;
var SB_MAX_LOOP_NUM   = 1000;
var timerID = 0;

/////////////////////////////////////////////////
// DEFINE STRING CONSTANTS
/////////////////////////////////////////////////

var CONFIRM_DISCARD_PAGE_CHANGES = 
    'Do you want to discard the changes to the current page?';
    
var CONFIRM_DISCOARD_STORY_CHANGES = 
    'Do you want to discard the changes to the current story?';

var MENU_BAR_ATTRS = 
    'menubar=1,directories=1,toolbar=1,resizable=1,location=1,scrollbar=1';
    
var PASSWORD_CONFIRM_FAILED = 
    'Password Confirmation failed. ' + 
    'Please re-enter your new password.';
var PLEASE_CONFIRM_PASSWORD = 
    'You must confirm your password.';
    
var PLEASE_ENTER_PASSWORD = 
    'You must enter a password.';
    
var PLEASE_ENTER_USERNAME = 
    'You must enter a username.';

var CONFIRM_DELETE_ITEM = 
    "Are you sure you want to delete {itemname}?\n";
    
var CAN_UNDO = 
    "You can restore this item with the Trash Manager.";
    
var CANNOT_UNDO = 
    "This change cannot be undone.";

var EMPTY_ITEM_ALERT = 
    'You did not provide a(n) ';
    
var NOT_VALID_EMAIL_ALERT = 
    'The email entered does not appear to be a valid format.';
    
var EMPTY_FIELDS_ALERT = 
    'You did not make selections for the following fields: ';

/////////////////////////////////////////////////
// DEFINE FUNCTIONS
/////////////////////////////////////////////////

$(function() {
    var showSection = null;
    var showTab = null;
    $(".section-tabs li").bind('click', function() {
        var _id = $(this).attr("id");
        showSection = _id.replace('tab-', 'mgr-');
        showTab = _id;
        $(this).css("background", "#FFF");
        $('.mgr-section').each(function() {
            var _thisId = $(this).attr("id");
            var _thisId = _thisId.replace("tab-", "mgr-");
            if (_thisId != showSection) {
                $("#"+_thisId).hide();
            }
        });
        $("#"+showSection).show();
        $("#"+showSection).css("background", "#FFF");
        $.cookie("page.last_tab", showSection);
    });
    
    $(".section-tabs li").bind('mouseover', function() {
        $(".section-tabs li").css("background", "#AEC4DB");
        $(this).css("background", "#FFF");
    });
    
    $(".section-tabs li").bind('mouseout', function() {
        if (showSection)
        {
            $("#"+showTab).css("background", "#FFF");
            var _thisId = $(this).attr("id");
            var _thisId = _thisId.replace("tab-", "mgr-");
            if (_thisId != showSection)
            {
                $(this).css("background", "#AEC4DB");
            }
        }
        else
        {
            $(".section-tabs li").css("background", "");
        }
    });
    
    if (last_tab = $.cookie("page.last_tab")) {
        $('.mgr-section').each(function() {
            if ($(this).attr("id") != last_tab) {
                $(this).hide();
            }
        });
        $("#"+last_tab).show();
        $("#"+last_tab).css("background", "#FFF");
        
        var tab_id = last_tab.replace("mgr-", "tab-");
        
        $("#"+tab_id).css("background", "#FFF");

        $(".section-tabs li").each(function() {
            if ($(this).attr("id") != tab_id) {
                $(this).css("background", "#AEC4DB");
            }
        });
    }
    
});
    
function SSM_NewWindow( url, PopTitle )
{
    var attrs = MENU_BAR_ATTRS;
    NewWin = window.open( url, 'Preview: ' + PopTitle );
}

ADMIN_PhotoSelector = function()
{
    var photoSelector;
    if ( photoSelector = document.getElementById( 'photoselector' ) )
    {
        var items = photoSelector.getElementsByTagName( 'img' );
        for ( i=0; i<items.length; i++ )
        {
            var item = items[i];
            if ( !isIE )
            {
                item['onclick'] = function(){ AddPhotoOnClick(this); };
            } 
            else 
            {
                item.attachEvent( 'onclick', 
                    function(){ AddPhotoOnClick(this); } );
            }
        }
    }
}

ADMIN_setLoginFieldFocus = function()
{
    var loginField;
    if ( loginField = document.getElementById( 'loginform' ) )
    {
        try 
        {
            loginField[0].focus();
        } 
        catch(e) 
        {
            return;
        }
    }
}

AddPhotoOnClick = function( obj )
{
    for( i=0; i<obj.parentNode.childNodes.length; i++ )
    {
        var node = obj.parentNode.childNodes[i];
        if ( node.nodeName == 'INPUT' &&
             node.type == 'checkbox' )
        {
            node.checked = node.checked ? false : true ;
        }
    }
}


// Add the 'onload' event to the window. This technique checks to
// make sure PopLoader does not overwrite any onload events
// defined elsewhere. This approach should be compatible with 
// just about any browser.

if ( typeof window.addEventListener != "undefined" )
{
    // DOM2
    window.addEventListener( "load", initStoryState, false );
    window.addEventListener( "load", ADMIN_PhotoSelector, false );
    window.addEventListener( "load", ADMIN_setLoginFieldFocus, false );
} 
else if ( typeof window.attachEvent != "undefined" ) 
{
    // IE
    isIE = true;
    window.attachEvent( "onload", initStoryState );
    window.attachEvent( "onload", ADMIN_PhotoSelector );
    window.attachEvent( "onload", ADMIN_setLoginFieldFocus );
} 
else 
{
    if ( window.onload != null ) 
    {
        var oldOnload = window.onload;
        window.onload = function (e) 
        {
            oldOnload( e );
            initStoryState();
            ADMIN_PhotoSelector;
            ADMIN_setLoginFieldFocus;
        }
    } 
    else 
    {
        window.onload = initStoryState(); 
                        ADMIN_PhotoSelector; 
                        ADMIN_setLoginFieldFocus;
    }
}

function setStoryName( storyname ) 
{
    document.forms[0].story.value = storyname;
}

function submitForm( idx ) 
{
    document.forms[idx].submit();
}

function checkSelection( idx, selector, msg )
{
    selector = document.forms[idx][selector];
    selection = selector.options[selector.selectedIndex].value;
    if ( selection == '' ) 
    {
        alert( 'Please select a '+msg+'.' );
        return false;
    } 
    else 
    {
        return true;
    }
}

function nonEmptyField( thefield, msg )
{
    theform = document.forms[0];
    if ( trim( theform[thefield].value ) == '' ) 
    {
        alert( msg );
        return false;
    }
    return true;
}

function trim( str ) 
{
   return str.replace(/^\s*|\s*$/g,"");
}

function confirmDelete( what, canUndo )
{
    var msg = CONFIRM_DELETE_ITEM.replace( '{itemname}', what );
    if ( canUndo == 1 || canUndo == true || canUndo == 'true' )
    {
        msg += CAN_UNDO;
    }
    else
    {
        msg += CANNOT_UNDO;
    }
    return confirm( msg );    
}

function validatePasswordChange( myForm )
{
    var myNewUser = myForm['username'].value;
    var myNewPass = myForm['password'].value;
    var myPassConfirm = myForm['confirm'].value;
    
    if ( trim( myNewUser ) == '' )
    {
        alert( PLEASE_ENTER_USERNAME );
        return false;
    } 
    else if ( myNewPass == '' )
    {
        alert( PLEASE_ENTER_PASSWORD );
        return false;
    } 
    else if ( myPassConfirm == '' )
    {
        alert( PLEASE_CONFIRM_PASSWORD );
        return false;
    }
    else if ( myNewPass != myPassConfirm )
    {
        alert( PASSWORD_CONFIRM_FAILED );
        return false;
    }
    else
    {
        return true;
    }
}

function initStoryState()
{
    g_initialState += getFormState();
}

function checkStoryState( whicheditor )
{
    var story = document.getElementById( whicheditor );
    story = story.value;
    return story;
}

function getFormState() 
{
    var myState = '';
    for ( i=0; i<document.forms.length; i++ )
    {
        thisform = document.forms[i];
        for ( j=0; j<thisform.length; j++ )
        {
            var thisvalue = thisform[j].value;
            if ( thisvalue ) {
                thisvalue.toUpperCase();
            }
            if ( thisform[j].type == 'file' ||
                 thisform[j].type == 'text' ||
                 thisform[j].type == 'textarea' )
            {
                myState += thisvalue;
            }
        }
    }
    return myState;
}

function confirmDeleteItem( ItemName, CanUndo )
{
    var Msg = CONFIRM_DELETE_ITEM.replace( '{itemname}', ItemName );
    if ( CanUndo )
    {
        Msg += CAN_UNDO;
    }
    else
    {
        Msg += CANNOT_UNDO;
    }
    return confirm( Msg );
}

function confirmPageChange( myTask, whicheditor ) 
{
    if ( myTask.toLowerCase() != 'editor' )
    {
    var newState = getFormState();
    }
    else
    {
    var newState = checkStoryState( whicheditor );
    }
    if ( newState != g_initialState )
    {
        return confirm( CONFIRM_DISCARD_PAGE_CHANGES );    
    }
}

function confirmDiscard( whicheditor, whichSelector, curSelection ) 
{
    var newSelection = whichSelector.options[whichSelector.selectedIndex].value;
    if ( newSelection == curSelection ) 
    {
        return false;
    }
    if ( newSelection != 0 && 
         newSelection != '0' && 
         newSelection != '' )
    {
        var currentState = checkStoryState( whicheditor )
        if ( currentState != g_initialState ) 
        {
            var myResponse = confirm( CONFIRM_DISCOARD_STORY_CHANGES );    
            if ( myResponse == 'false' || 
                 myResponse == 0 ) 
            {
                return false;
            }
            changeloc( whichSelector );
        }
        changeloc( whichSelector );
    }
}

function toggleImgSrc( which, isrc, fromwhat, towhat ) 
{
    if ( isrc.indexOf(fromwhat) != -1 ) 
    {
        which.src = isrc.replace(fromwhat,towhat);
    } 
    else 
    {
        which.src = isrc.replace(towhat,fromwhat);
    }
}

function changeClassName( which, whichchild, towhat ) 
{
    for(i=1; i< which.childNodes.length; i++) 
    {
        node = which.childNodes[i];
        if( node.nodeName == whichchild ) 
        {
            if ( node.className.indexOf(towhat) != -1 ) 
            {
                node.className=node.className.replace(towhat,'');
            } 
            else 
            {
                node.className+=towhat;
            }
        }
    }
}

function Show( which ) 
{
    clearTimeout(timerID);
    for(i=1; i< which.childNodes.length; i++) 
    {
        node = which.childNodes[i];
        if( node.nodeName == "UL" ) 
        {
            if ( node.className.indexOf("_show") == -1 ) 
            {
                node.className += "_show";
            }
        }
    }
}

function hide( which ) 
{
    for(i=1; i< which.childNodes.length; i++) 
    {
        node = which.childNodes[i];
        if( node.nodeName == "UL" ) 
        {
            node.className = node.className.replace( "_show", "" );
        }
    }
}

function hideCountdown()
{
      timerID = setTimeout( "hide(thisnav)", 1000 );
}

function setBackgroundImage( which, what, w, h ) 
{
    document.getElementById(which).style.backgroundImage = "url(" + what + ")";
    document.getElementById(which).style.width           = w;
    document.getElementById(which).style.height          = h;
}

function setimage( which, what ) 
{
    document.images[which].src = what;
}

function settext ( whichid, whattext ) 
{
    document.getElementById(whichid).innerHTML=whattext;
}

function changeloc( URL_List ) 
{
    var URL = URL_List.options[URL_List.selectedIndex].value;
    if ( URL != 0 && URL != '0' ) 
    {
        window.location.href = URL;
    }
}

function formvalidate( myform, myfields ) 
{
    for ( i=0; i<myfields.length; i++ ) 
    {
        var myfield = myfields[i];
        if ( document.forms[myform][myfield].value == '' ) 
        {
            alert( EMPTY_ITEM_ALERT + myfield );
            return false;
        }
        if ( myfield.indexOf('email') != -1 ) 
        {
            if ( ( document.forms[myform][myfield].value.search("@") == -1 ) || 
                 ( document.forms[myform][myfield].value.search("[.*]" ) == -1 ) ) 
            {
                alert( NOT_VALID_EMAIL_ALERT );
                return false;
            }
        }
    }
    document.forms[myform].submit();
}

function showImagePreview( theId, img )
{
    $(function() {
    
       // Create a new Image object and tell the browser 
       // to load it.
    
        var preLoad = new Image();
            preLoad.src = $(img).val();
            
       // We want to wait for the image to be downloaded by 
       // the browser before we try to change its source.
            
        preLoad.onload = function() {
        
            // Resize the image so the page doesn't jump up and down 
            // when the image size changes.
            
            var NewDims = maxDims( 72, preLoad.width, preLoad.height );
            
            // Hide the image so the user does not see it change width & height
            
            $("#"+theId).hide();
            
            // Now we can actually change the attributes of the image
            
            $("#"+theId).attr({
                "src": preLoad.src,
                "width": NewDims[0],
                "height": NewDims[1]
            });
            
            // A nice little fade-in effect to display the new image
            
            $("#"+theId).show("fast");
        };
    });
}

function maxDims( max, w, h )
{
    if ( w > max || h > max )
    {
        var widthratio = 1;
        if ( w > max )
        {
            widthratio = max / w;
        }
        
        var heightratio = 1;
        if ( h > max )
        {
            heightratio = max / h;
        }
        
        var ratio = heightratio;
        if ( widthratio < heightratio )
        {
            ratio = widthratio;
        }
        
        // Scale the image
        w = Math.ceil( w * ratio );
        h = Math.ceil( h * ratio );
        
        // Tweak the new dims to match max exactly
        
        if ( ratio == heightratio && ratio != 1 )
        {
            if ( h < max )
            {
                while ( h < max )
                {
                    ratio = ratio * 1.01;
                    h = Math.ceil( h * ratio );
                    w = Math.ceil( w * ratio );
                }
            }
        }
        
        if ( ratio == widthratio && ratio != 1 )
        {
            if ( w < max )
            {
                var Ticker = 0;
                while ( w < max && Ticker < SB_MAX_LOOP_NUM )
                {
                    ratio = ratio * 1.01;
                    h = Math.ceil( h * ratio );
                    w = Math.ceil( w * ratio );
                    Ticker++;
                }
            }
        }
    }
    return new Array( w, h );
}

function setSelectOptions( page, region, g_regions )
{
    var PageSelector = document.forms[0].page;
    var idx = PageSelector.options[PageSelector.selectedIndex].text;
    var bits = idx.split( ' ' );
    idx = bits.join( '_' );
    if (!idx) return;
    var list = g_regions[idx];
    var regionSelector = document.forms[0].region;
    regionSelector.options.length = 0;
    for( i=0; i<list.length; i+=2 )
    {
        regionSelector.options[i/2] = new Option(list[i],list[i+1]);
    }
    updateSelection( idx, page, region );
}

function updateSelection( idx, page, region )
{
    try 
    {
        var regionSelector = document.forms[0].region;
        for ( i=0; i<regionSelector.options.length; i++ )
        {
            if ( regionSelector.options[i].value == region )
            {
                regionSelector.options[i].selected = true;
            }
        }
    }
    catch(e)
    {
        ;
    }
}

function enforceSelection()
{
    var argc = enforceSelection.arguments.length;
    var argv = enforceSelection.arguments;
    
    var mustSelect = '';
    
    for ( i=0; i<argc; i++ )
    {
        if ( document.forms[0][argv[i]].value == '' ||
             document.forms[0][argv[i]].value == null || 
             typeof document.forms[0][argv[i]].value == undefined )
        {
            mustSelect += mustSelect != '' ? ', ' : '' ;
            mustSelect += argv[i];
        }
    }
    if ( mustSelect == '' )
    {
        return true;
    } 
    else 
    {
        alert( EMPTY_FIELDS_ALERT + mustSelect );
        return false;
    }
}

function ToggleDisplay(_ID)
{
    var item;
    if (item = document.getElementById(_ID))
    {
        var _class = item.className;
        if ( _class == "show")
        {
            item.className = "hide";
        }
        else
        {
           item.className = "show";
        }
    }
};