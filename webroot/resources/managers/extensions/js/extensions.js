if (!window.uploadButtons) window.uploadButtons = [];

function initUploadButtons() {
    var buttons = sbc.getQueue("uploadButtons");
    for (i=0; i<buttons.length; i++) {
        var options = buttons[i];
        var btnUpload = $("#"+options.id);
        var status = $("#status_"+options.id);
        new AjaxUpload(btnUpload, {
            action: options.action,
            name: 'uploadfile',
            onSubmit: function(file, ext){
                 if (! (ext && /^(zip)$/.test(ext))) { 
                    status.text(options.message);
                    return false;
                }
                status.text(TERMS.UPLOADING);
            },
            onComplete: function(file, response){
                status.text('');
                $(response).appendTo("#files_"+options.id);
            }
        });
    }
};