<?php defined('SKYBLUE') or die('Bad file request');

if (function_exists('ob_start')) @ob_start("ob_gzhandler");

/*
Edit
Delete
Create
Move
Rename
Publish

On Editor form:

Update the XML structure when:
	- name changes
	- published changes
	- permalink changes
	- page is deleted
*/

$Controller = 'sbc.PageController';

$EDIT          = __('GLOBAL.EDIT',        "Edit",    1);
$DELETE        = __('GLOBAL.DELETE',      "Delete",  1);
$RENAME        = __('GLOBAL.RENAME',      "Rename",  1);
$CREATE        = __('GLOBAL.ADD',         "New Page", 1);
$PUBLISH       = __('GLOBAL.PUBLISH',     "Publish", 1);
$UNPUBLISH     = __('GLOBAL.UNPUBLISH',   "Un-Publish", 1);
$SHOW_IN_NAV   = __('GLOBAL.SHOW_IN_NAV', "Show In Navigation", 1);
$HIDE_FROM_NAV = __('GLOBAL.UNPUBLISH',   "Hide From Navigation", 1);

$CODE = <<<CODE
{$Controller} = {
    actions: {
        DELETE:      "ajax_delete",
        RENAME:      "ajax_rename",
        CREATE:      "ajax_create",
        PUBLISH:     "ajax_publish",
        UPDATE_NAV:  "ajax_change_nav",
        UPDATE_TREE: "ajax_update_tree"
    },
    getQuery: function(theAction) {
        return new String("admin.php?com=page&action={action}&is_ajax=1").replace("{action}", theAction);
    },
    doGet: function(theAction, theCallback) {
        $.get({$Controller}.getQuery(theAction), function(theData) {
            if (typeof(theCallback) == "function") {
                theCallback(theData);
            }
        });
    },
    doPost: function(theAction, theData, theCallback) {
        $.post({$Controller}.getQuery(theAction), theData, function(theData) {
            if (typeof(theCallback) == "function") {
                theCallback(theData);
            }
        });
    },
    dialog: function(theData, options) {
        showModalDialog(theData, options);
    }
};
{$Controller}.doEdit = function(jstree, theObject) {
    if ($(theObject).attr("id") == "root") return false;
    window.location = $("a", theObject).attr("href");
};
{$Controller}.doDelete = function(jstree, theObject) { 
    if ($(theObject).attr("id") == "root") return false;
    var uniqueId  = "sbc-" + Math.ceil(Math.random() * 100000);
    $(theObject).attr("id", uniqueId);
    confirm_action(
        null, 
        "$DELETE " + $(theObject).text(), 
        TERMS.CONFIRM_DELETE_ITEM.replace('{itemname}', $(theObject).text()),
        function() {
            var thePageId = $.url.setUrl(theObject.context).param("id");
            var theData   = { id: thePageId, nodeId: uniqueId };
                
			{$Controller}.doPost({$Controller}.actions.DELETE, theData, function(theData) {
			
				var json = eval("(" + theData + ")");

				var theMessage = "";
				if (json.message) {
					theMessage = $.base64Decode(json.message);
				}
				
				var nodeId = json.nodeId;
				
				if (json.result) {
				    $("#"+nodeId).remove();
					var theTree = $("#page-tree").clone();
					$("ins", theTree).remove();
					var theData = {tree: $(theTree).html()};
					{$Controller}.doPost({$Controller}.actions.UPDATE_TREE, theData);
				}
				{$Controller}.doInitTree();
				$("#action-result").remove();
				$("#page-tree").before(theMessage);
				delayedReaction("fadeAndRemove('#action-result')", 5000);
			});
        }, 
        null
    );
};
{$Controller}.doCreate = function(jstree, theObject) {
    
    /*
     Create the new node. rename will be called after this event.
     We are binding a callback to the Controller.doRename event which will 
     be triggered when the node is renamed. 
    */ 
    var theNode = jstree.create(theObject);
    
    $(window).bind("{$Controller}.doRename", theNode, function(e) {
        
        /*
          We have created and named the node, now we can create the page 
          in the database.
        */
        
        var uniqueId = "#sbc-" + Math.ceil(Math.random() * 100000);
        
        $("a", e.data[0]).attr("href", uniqueId);
        
        var theData = {name: $("a", e.data[0]).text(), nodeId: uniqueId};
        
        {$Controller}.doPost({$Controller}.actions.CREATE, theData, function(theData) {
            var json = eval("(" + theData + ")");
			var newPageId  = json.pageId;
			var uniqueId   = json.nodeId;
			var permalink  = json.permalink;
			
			$("a[href='"+uniqueId+"']")
			    .attr("rel", $.trim(permalink))
			    .attr("published", "0")
			    .attr("href", "admin.php?com=page&action=edit&id=" + newPageId);

			var theMessage = "";
			if (json.message) {
			    theMessage = $.base64Decode(json.message);
			}

			var theTree = $("#page-tree").clone();
			$("ins", theTree).remove();
			var theData = {tree: $(theTree).html()};
			{$Controller}.doPost({$Controller}.actions.UPDATE_TREE, theData, function(theData) {
			    $("#action-result").remove();
				$("#page-tree").before(theData);
				delayedReaction("fadeAndRemove('#action-result')", 5000);
			});
		});
	});
};
{$Controller}.doRename = function(e, theObject) { 

    if (theObject.args && theObject.args[0]) {
        theObject = theObject.args[0];
    }
    
    var theTree = $("#page-tree").clone();
	$("ins", theTree).remove();
			
    var thePageId = $.url.setUrl(theObject.context).param("id");
    var theData   = { 
        id:   $.url.setUrl(theObject.context).param("id"), 
        name: $(theObject).text(), 
        tree: $(theTree).html() 
    };

    {$Controller}.doPost({$Controller}.actions.RENAME, theData, function(theData) {
            var json = eval("(" + theData + ")");

			var theMessage = "";
			if (json.message) {
			    theMessage = $.base64Decode(json.message);
			}
			$("#action-result").remove();
			$("#page-tree").before(theMessage);
			delayedReaction("fadeAndRemove('#action-result')", 5000);
		});

    $(window).trigger("{$Controller}.doRename");
};
{$Controller}.doPublish = function(jstree, theObject) {
    if ($(theObject).attr("id") == "root") return false;
    
    var theDirection = 'up';
    if ($("a", theObject).attr("published") == 1) {
    	theDirection = 'down';
    	$("a", theObject).attr("published", 0);
    }
    else {
    	theDirection = 'up';
    	$("a", theObject).attr("published", 1);
    }
    
    var theTree = $("#page-tree").clone();
    $("ins", theTree).remove();
    
    var theData = {
        id: $.url.setUrl(theObject.context).param("id"), 
        direction: theDirection, 
        tree: $(theTree).html()
    };
    {$Controller}.doPost({$Controller}.actions.PUBLISH, theData, function(theData) {
        $("#action-result").remove();
        $("#page-tree").before(theData);
        delayedReaction("fadeAndRemove('#action-result')", 5000);
    });
};
{$Controller}.doUpdateNavigation = function(jstree, theObject) {
    if ($(theObject).attr("id") == "root") return false;
    
    var showInNav = 1;
    if ($("a", theObject).attr("show_in_navigation") == 1) {
    	showInNav = 0;
    	$("a", theObject).attr("show_in_navigation", 0);
    }
    else {
    	showInNav = 1;
    	$("a", theObject).attr("show_in_navigation", 1);
    }
    
    var theTree = $("#page-tree").clone();
    $("ins", theTree).remove();
    
    var theData = {
        id: $.url.setUrl(theObject.context).param("id"), 
        show_in_navigation: showInNav, 
        tree: $(theTree).html()
    };
    {$Controller}.doPost({$Controller}.actions.UPDATE_NAV, theData, function(theData) {
        $("#action-result").remove();
        $("#page-tree").before(theData);
        delayedReaction("fadeAndRemove('#action-result')", 5000);
    });
};
{$Controller}.doMove = function(theData) {
    var theTree = $("#page-tree").clone();
    $("ins", theTree).remove();
    var theData = {tree: $(theTree).html()};
    {$Controller}.doPost({$Controller}.actions.UPDATE_TREE, theData);
};
{$Controller}.getNode = function() {
    return $.jstree._reference('#page-tree')._get_node(null, false);
};
{$Controller}.doInitTree = function() {
    $("#page-tree li a").bind("dblclick", function() {
        if ($(this).parents(0).attr("id")) return false;
        window.location = $(this).attr("href");
    });
    $("#page-tree")
        .jstree({ 
            "contextmenu": {
                "select_node" : true,
                "items": {
                    "ccp": false,
                    "edit": {
                        label: "$EDIT",
                        action: function(obj) { 
                            {$Controller}.doEdit(this, obj);
                        }
                    },
                    "remove": {
                        label: "$DELETE",
                        action: function(obj) {
                            {$Controller}.doDelete(this, obj);
                        }
                    },
                    "create": {
                        label: "$CREATE",
                        action: function(obj) {
                            {$Controller}.doCreate(this, obj);
                        }
                    },
                    "rename": {
                        label: "$RENAME",
                        action: function(obj) { 
                            if ($(obj).attr("id") == "root") return false;
                            this.rename(obj);
                        }
                    },
                    "publish": { 
                        label: function() {
                            var obj = {$Controller}.getNode();
                            var isPublished = $("a:eq(0)", obj).attr("published");
                            return (isPublished == 0 ? "$PUBLISH" : "$UNPUBLISH");
                        },
                        action: function(obj) { 
                            {$Controller}.doPublish(this, obj);
                            {$Controller}.doInitTree();
                        }
                    },
                    "navigation": { 
                        label: function() {
                            var obj = {$Controller}.getNode();
                            var showInNav = $("a:eq(0)", obj).attr("show_in_navigation");
                            return (showInNav == 0 ? "$SHOW_IN_NAV" : "$HIDE_FROM_NAV");
                        },
                        action: function(obj) { 
                            {$Controller}.doUpdateNavigation(this, obj);
                            {$Controller}.doInitTree();
                        }
                    }
                }
            },
            "themes": {"theme": "classic","dots": true,"icons": true},
            "core": {"initially_open": ["#page-tree li"]},
            "plugins" : ["themes","html_data","ui","contextmenu","dnd","crrm","cookies"],
        })
        .bind("move_node.jstree", {$Controller}.doMove)
        .bind("rename_node.jstree", {$Controller}.doRename)
        .bind("mouseleave.jstree", function(){ $("#page-tree").jstree("deselect_all"); });
        try {
            $.jstree._reference($('#page-tree')).open_all(-1);
        }
        catch (e) {/*Exit Gracefully*/}
};
$(function() { {$Controller}.doInitTree(); });
CODE;
echo pack_javascript($CODE);
if (function_exists('ob_flush')) @ob_flush();