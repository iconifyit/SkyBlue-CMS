<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2024-01-01 00:00:00 $
 * @package      SkyBlue CMS
 * @copyright    Copyright (C) 2005 - 2024 Scott Edwin Lewis. All rights reserved.
 * @license      GNU/GPL, see COPYING.txt
 * SkyBlue CMS is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 *
 * EasyMDE Markdown Editor Integration
 */

global $Core;

$easymde_path = "resources/editors/easymde/";

?>
<!--[EasyMDE Markdown Editor]-->
<link rel="stylesheet" href="<?php echo $easymde_path; ?>css/easymde.min.css" />
<script src="<?php echo $easymde_path; ?>js/easymde.min.js"></script>

<script type="text/javascript">
var SBC_EasyMDE_Instances = [];
var SBC_EasyMDE_Initialized = false;

jQuery(function() {
    // Check if textarea is in a hidden tab
    var textarea = jQuery(".wysiwyg, .editor, .markdown-editor").first();
    if (textarea.length && textarea.is(":visible")) {
        initEasyMDE();
    } else {
        // Initialize when the Page Text tab is shown (Bootstrap 5 event)
        jQuery('#text-tab').bind('click', function() {
            if (!SBC_EasyMDE_Initialized) {
                setTimeout(initEasyMDE, 50);
            } else {
                // Refresh existing instances
                for (var i = 0; i < SBC_EasyMDE_Instances.length; i++) {
                    SBC_EasyMDE_Instances[i].codemirror.refresh();
                }
            }
        });
    }
});

function initEasyMDE() {
    if (SBC_EasyMDE_Initialized) return;
    SBC_EasyMDE_Initialized = true;

    jQuery(".wysiwyg, .editor, .markdown-editor").each(function(index) {
        var textarea = this;

        var easyMDE = new EasyMDE({
            element: textarea,
            spellChecker: false,
            autosave: {
                enabled: false
            },
            toolbar: [
                "bold", "italic", "heading", "|",
                "quote", "unordered-list", "ordered-list", "|",
                "link", "image", "table", "horizontal-rule", "|",
                "preview", "side-by-side", "fullscreen", "|",
                "guide"
            ],
            status: ["lines", "words", "cursor"],
            minHeight: "400px",
            renderingConfig: {
                singleLineBreaks: false,
                codeSyntaxHighlighting: false
            },
            placeholder: "Write your content in Markdown..."
        });

        SBC_EasyMDE_Instances.push(easyMDE);

        // Sync content back to textarea on form submit
        jQuery(textarea).closest("form").bind("submit", function() {
            easyMDE.codemirror.save();
        });

        // Handle Apply/Save buttons
        jQuery(".wymupdate, .buttonsave, [name='btn_save'], [name='btn_apply']").bind("click", function() {
            easyMDE.codemirror.save();
        });
    });
}

// Utility function to get all EasyMDE instances
function getEasyMDEInstances() {
    return SBC_EasyMDE_Instances;
}
</script>
<!--[/EasyMDE Markdown Editor]-->
