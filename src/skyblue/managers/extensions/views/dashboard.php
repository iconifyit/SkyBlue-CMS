<?php defined('SKYBLUE') or die(basename(__FILE__));

/**
 * @version      2.0 2009-06-20 21:41:00 $
 * @package      SkyBlue CMS
 * @copyright    Copyright (C) 2005 - 2024 Scott Edwin Lewis. All rights reserved.
 * @license      GNU/GPL, see COPYING.txt
 * SkyBlue CMS is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 *
 * Updated to use Bootstrap 5 styling
 */

$Request = new RequestObject;
$com = 'extensions';

add_head_element('jquery.utils');
add_head_element('jquery.simplemodal');
add_head_element('jquery.ajaxuploader');

add_stylesheet('extensions.css', SB_MANAGER_RESOURCES . 'extensions/css/extensions.css');
add_script('extensions.js',  SB_MANAGER_RESOURCES . 'extensions/js/extensions.js');

?>
<script type="text/javascript">
$(function() {
    // Load content into tab panes via AJAX
    function loadTabContent(tabId, url) {
        var pane = $('#' + tabId + '-panel');
        if (pane.data('loaded')) return;

        pane.html('<div class="text-center py-4"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');

        $.get(url, function(data) {
            pane.html(data);
            pane.data('loaded', true);
            // Re-initialize feather icons if available
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }).fail(function() {
            pane.html('<div class="alert alert-danger">Failed to load content.</div>');
        });
    }

    // Handle tab switching
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
        var target = $(e.target).data('bs-target');
        var url = $(e.target).data('ajax-url');
        if (url) {
            var tabId = target.replace('#', '').replace('-panel', '');
            loadTabContent(tabId, url);
        }
    });

    // Load first tab content on page load
    var firstTab = $('button[data-bs-toggle="tab"].active');
    if (firstTab.length && firstTab.data('ajax-url')) {
        var target = firstTab.data('bs-target');
        var tabId = target.replace('#', '').replace('-panel', '');
        loadTabContent(tabId, firstTab.data('ajax-url'));
    }

    /**
     * NOTE: This function over-rides the function with the same name
     * in /ui/js/actions.js
     */
    function set_action(button, action) {
        $("#install-form").find("input[name='action']").val(action);
    };
});
</script>

<h1 class="h3 mb-3">
    <a href="admin.php?com=console" class="text-muted text-decoration-none"><?php __('COM.CONSOLE', 'Dashboard'); ?></a>
    <span class="text-muted">/</span>
    <a href="admin.php?com=settings" class="text-muted text-decoration-none"><?php __('COM.SETTINGS', 'Settings'); ?></a>
    <span class="text-muted">/</span>
    <?php __('COM.EXTENSIONS', 'Extensions'); ?>
</h1>

<?php echo HtmlUtils::formatMessage($this->getMessage()); ?>

<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" id="extensionsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="managers-tab" data-bs-toggle="tab" data-bs-target="#managers-panel"
                        data-ajax-url="admin.php?com=extensions&context=managers&action=list_managers&is_ajax=1"
                        type="button" role="tab" aria-controls="managers-panel" aria-selected="true">
                    <?php __('EXTENSIONS.MANAGERS', 'Managers'); ?>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="fragments-tab" data-bs-toggle="tab" data-bs-target="#fragments-panel"
                        data-ajax-url="admin.php?com=extensions&context=fragments&action=list_fragments&is_ajax=1"
                        type="button" role="tab" aria-controls="fragments-panel" aria-selected="false">
                    <?php __('EXTENSIONS.FRAGMENTS', 'Fragments'); ?>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="skins-tab" data-bs-toggle="tab" data-bs-target="#skins-panel"
                        data-ajax-url="admin.php?com=extensions&context=skins&action=list_skins&is_ajax=1"
                        type="button" role="tab" aria-controls="skins-panel" aria-selected="false">
                    <?php __('EXTENSIONS.SKINS', 'Skins'); ?>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="plugins-tab" data-bs-toggle="tab" data-bs-target="#plugins-panel"
                        data-ajax-url="admin.php?com=extensions&context=plugins&action=list_plugins&is_ajax=1"
                        type="button" role="tab" aria-controls="plugins-panel" aria-selected="false">
                    <?php __('EXTENSIONS.PLUGINS', 'Plugins'); ?>
                </button>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="extensionsTabsContent">
            <div class="tab-pane fade show active" id="managers-panel" role="tabpanel" aria-labelledby="managers-tab">
                <div class="text-center py-4">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="fragments-panel" role="tabpanel" aria-labelledby="fragments-tab">
            </div>
            <div class="tab-pane fade" id="skins-panel" role="tabpanel" aria-labelledby="skins-tab">
            </div>
            <div class="tab-pane fade" id="plugins-panel" role="tabpanel" aria-labelledby="plugins-tab">
            </div>
        </div>
    </div>
</div>
