var SNIPPET_EDITOR_ID = "#snippetType";

/**
 * Attaches onload events for the Snippets component
 */
$(function() {
    $(SNIPPET_EDITOR_ID).bind("change", function(e) {
        toggleSnippetEditor();
    });
});

/**
 * Toggles the WYSIWYG editor depending on whether or not the 
 * Snippet type is HTML.
 * @return void
 */
function toggleSnippetEditor() {
    if ($(SNIPPET_EDITOR_ID).val() == "html") {
        
    }
    else {
        
    }
};