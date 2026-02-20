$(function() {
    var theValue = $("#action_override").val();
    if (theValue == "move" || theValue == "copy") {
        $("#destination_column").show();
    }
    else {
        $("#destination_column").hide();
    }
    $("#action_override").bind("change", function(e) {
        var theValue = $(this).val();
        if (theValue == "move" || theValue == "copy") {
            $("#destination_column").fadeIn("slow");
        }
        else {
            $("#destination_column").fadeOut("slow");
        }
    });
    $("#imagePreview").bind("click", function() {
        var src = $(this).attr("src");
        var img = new Image();
        img.src = src;
        var width = img.width;
        var height = img.height;
        showModalDialog(
            '<img src="' + src + '" width="' + width + '" height="' + height + '" />',
            {minHeight: height, minWidth: width}    
        );
    });   
});