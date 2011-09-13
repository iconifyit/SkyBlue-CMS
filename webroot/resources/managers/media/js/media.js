/*
$(function() {
    $(".folder").contextMenu({
        menu: "mediaDirsContextMenu",
        moveX: 20,
        moveY: -10
    }, function(action, el, pos) {
        // alert(action);
    });
    $("#root-folder a").disableContextMenuItems("#delete");
    
    $(".folder-icon").bind("click", function(e) {
        e.preventDefault();
        e.stopPropagation();
        var targetNode = e.target;
        while (targetNode.nodeName != "LI" && 
               targetNode.nodeName != "BODY" && 
               targetNode.className != "folder") {
            
            targetNode = targetNode.parentNode;
        }
        $(targetNode).find("UL").toggle(250);
    });
    
});
*/