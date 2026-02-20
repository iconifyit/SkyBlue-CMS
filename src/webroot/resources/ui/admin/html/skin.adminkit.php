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
 * AdminKit Bootstrap 5 Admin Template Integration
 */

?>
<!DOCTYPE html>
<html lang="<?php echo Config::get('site_lang', 'en'); ?>">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="SkyBlue CMS Admin" />
    <title><?php __('SBC.ADMIN', 'SkyBlue CMS Admin'); ?></title>

    <!-- AdminKit CSS -->
    <link href="<?php echo SB_UI_DIR; ?>admin/css/adminkit/app.css" rel="stylesheet" />

    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet" />

    <!-- Legacy SkyBlue CSS for form elements -->
    <link rel="stylesheet" type="text/css" href="<?php echo SB_UI_DIR; ?>admin/css/editor.css" />

    <!-- SkyBlue Editor Styles -->
    <?php fragment(array('name' => 'editor', 'view' => 'styles', 'wrapper' => 'no')); ?>

    <style>
        /* SkyBlue Admin Overrides */
        .btn {
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
        }
        .btn:hover {
            text-decoration: none;
        }
        .btn-sm {
            padding: 0.375rem 0.75rem;
        }
        /* Pagination buttons */
        .pagination .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2rem;
            min-height: 2rem;
            padding: 0.25rem 0.5rem;
            line-height: 1;
        }
        /* Media folder tree icons */
        #folder-tree li.folder {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }
        #folder-tree .icon {
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.5rem;
        }
        #folder-tree .icon svg {
            width: 20px;
            height: 20px;
        }
        #folder-tree ul {
            width: 100%;
            padding-left: 1.5rem;
        }
        #folder-tree .folder-label {
            text-transform: capitalize;
        }
        /* Dashboard card sizing */
        .card.h-100 .card-body {
            padding: 0.75rem 1rem;
        }
        .card.h-100 .card-footer {
            padding: 0.5rem 1rem;
        }
        .card.h-100 .card-title {
            margin-bottom: 0;
        }
        .card.h-100 .row {
            margin-bottom: 0.25rem;
        }
        .card.h-100 .mb-0.text-muted {
            font-size: 0.8rem;
            line-height: 1.3;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.5rem;
            height: 1.5rem;
            padding: 2px;
            border-radius: 0.2rem;
            text-transform: uppercase;
        }
        .content {
            padding: 1.5rem;
        }
        .card {
            margin-bottom: 1.5rem;
        }
        /* Ensure forms look good with AdminKit */
        .input-small, .input-medium, .input-big, .input-flex {
            padding: 0.375rem 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }
        .input-small { width: 120px; }
        .input-medium { width: 240px; }
        .input-big { width: 480px; }
        .input-flex { width: 100%; }
        textarea {
            width: 100%;
            min-height: 100px;
        }
        /* Button compatibility */
        .sb-button {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 400;
            text-align: center;
            text-decoration: none;
            border: 1px solid transparent;
            border-radius: 0.25rem;
            cursor: pointer;
        }
        /* Table compatibility */
        #table_liquid, .table_liquid {
            width: 100%;
        }
        /* WYMeditor compatibility - use modern CSS for button toolbar only */
        .wym_skin_silver,
        .wym_skin_silver * {
            box-sizing: content-box;
        }
        .wym_skin_silver ul,
        .wym_skin_silver li {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        /* Use flexbox for button toolbar only (wym_tools with wym_buttons class) */
        .wym_skin_silver .wym_tools.wym_buttons ul {
            display: flex;
            flex-wrap: wrap;
            gap: 2px;
            margin: 0 !important;
            padding: 0 !important;
        }
        .wym_skin_silver .wym_buttons li {
            width: 26px;
            height: 26px;
            overflow: hidden;
            margin: 0 !important;
            padding: 0 !important;
        }
        .wym_skin_silver .wym_buttons a {
            display: block;
            width: 20px;
            height: 20px;
            overflow: hidden;
            padding: 2px !important;
            margin: 0 !important;
            text-decoration: none !important;
            border: 1px solid #666;
            line-height: 1;
        }
        .wym_skin_silver .wym_buttons a:hover {
            text-decoration: none !important;
            border: 1px solid #000;
        }
        /* WYMeditor box styling */
        .wym_box {
            background: #fff;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
        }
        .wym_area_top {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            background: #f8f9fa;
            border-bottom: 1px solid #ced4da;
            padding: 8px;
        }
        .wym_skin_silver .wym_section {
            margin: 0 !important;
            padding: 0 !important;
        }
        /* Tools section uses inline-flex, dropdowns use default block */
        .wym_skin_silver .wym_tools {
            display: inline-flex;
            margin: 0 !important;
        }
        /* Dropdown sections (containers, classes) - Bootstrap-style dropdown */
        .wym_skin_silver .wym_dropdown {
            position: relative;
            margin: 0 !important;
        }
        .wym_skin_silver .wym_dropdown h2 {
            display: block;
            padding: 0.375rem 0.75rem !important;
            margin: 0 !important;
            font-size: 0.875rem;
            font-weight: 400;
            color: #212529;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            cursor: pointer;
        }
        .wym_skin_silver .wym_dropdown h2:hover {
            background-color: #e9ecef;
        }
        .wym_skin_silver .wym_dropdown h2:after {
            content: " ▾";
            font-size: 0.7em;
        }
        .wym_skin_silver .wym_dropdown ul {
            display: none;
            position: absolute;
            flex-direction: column;
            margin: 0 !important;
            padding: 0.5rem 0 !important;
            background: #fff;
            border: 1px solid rgba(0, 0, 0, 0.15);
            border-radius: 0.25rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.175);
            min-width: 10rem;
            z-index: 1000;
        }
        .wym_skin_silver .wym_dropdown:hover ul {
            display: flex;
        }

        /* WYMeditor modal dialog styling */
        .modalCloseImg.simplemodal-close,
        a.simplemodal-close {
            display: none !important;
        }
        #simplemodal-container {
            background: #fff;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5);
            padding: 1.5rem;
            height: auto !important;
            overflow: visible !important;
            width: 550px !important;
            top: 50% !important;
            transform: translateY(-50%);
        }
        #simplemodal-container .simplemodal-data {
            height: auto !important;
            overflow: visible !important;
        }
        #simplemodal-container h2 {
            font-size: 1.25rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #dee2e6;
        }
        #simplemodal-container p {
            font-size: 0.875rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }
        #simplemodal-container div.inputdiv {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 1rem 0;
            padding-bottom: 1rem;
            border-bottom: 1px solid #dee2e6;
        }
        #simplemodal-container div.inputdiv h3 {
            font-size: 0.875rem;
            font-weight: 600;
            margin: 0;
            flex-shrink: 0;
        }
        #simplemodal-container div.inputdiv select,
        #simplemodal-container div.inputdiv input {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            margin: 0;
            margin-left: auto;
        }
        #simplemodal-container div.inputdivlast {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #dee2e6;
            border-bottom: none;
            height: auto;
            width: auto;
            display: flex;
            gap: 0.5rem;
            justify-content: flex-start;
        }
        /* jQuery UI dialog styling */
        .ui-dialog {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5);
            padding: 0;
        }
        .ui-dialog .ui-dialog-titlebar {
            background: #f8f9fa;
            border: none;
            border-bottom: 1px solid #dee2e6;
            border-radius: 0.5rem 0.5rem 0 0;
            padding: 1rem 1.5rem;
        }
        .ui-dialog .ui-dialog-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #212529;
        }
        .ui-dialog .ui-dialog-titlebar-close {
            display: none !important;
        }
        .ui-dialog .ui-dialog-content {
            padding: 1.5rem;
        }
        .ui-dialog .ui-dialog-buttonpane {
            background: #f8f9fa;
            border-top: 1px solid #dee2e6;
            padding: 1rem 1rem 1rem 1.5rem;
            margin: 0;
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
        }
        .ui-dialog .ui-dialog-buttonpane button {
            float: none !important;
            padding: 0.5rem 1rem !important;
            font-size: 0.875rem !important;
            font-weight: 400 !important;
            color: #fff !important;
            background: #dc3545 !important;
            border: 1px solid #dc3545 !important;
            border-radius: 0.375rem !important;
            cursor: pointer !important;
            text-decoration: none !important;
            justify-self: start !important;
        }
        .ui-dialog .ui-dialog-buttonpane button:hover {
            background: #bb2d3b !important;
            border-color: #b02a37 !important;
        }
        .ui-dialog .ui-dialog-buttonpane button + button {
            color: #212529 !important;
            background: #f8f9fa !important;
            border-color: #ced4da !important;
            justify-self: end !important;
        }
        .ui-dialog .ui-dialog-buttonpane button + button:hover {
            background: #e9ecef !important;
        }
        /* Override jQuery UI button styles */
        .ui-state-default,
        .ui-widget-content .ui-state-default,
        #simplemodal-container .sb-button,
        #simplemodal-container input[type="button"] {
            display: inline-block;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 400;
            color: #212529;
            background: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            cursor: pointer;
            text-decoration: none;
        }
        .ui-state-default:hover,
        .ui-widget-content .ui-state-default:hover,
        #simplemodal-container .sb-button:hover,
        #simplemodal-container input[type="button"]:hover {
            background: #e9ecef;
            text-decoration: none;
        }
        #simplemodal-container input[name="save"],
        #simplemodal-container input[value="Ok"] {
            color: #fff;
            background: #0d6efd;
            border-color: #0d6efd;
        }
        #simplemodal-container input[name="save"]:hover,
        #simplemodal-container input[value="Ok"]:hover {
            background: #0b5ed7;
            border-color: #0a58ca;
        }
        .wym_skin_silver .wym_dropdown ul li {
            margin: 0 !important;
            padding: 0 !important;
        }
        .wym_skin_silver .wym_dropdown ul li a {
            display: block;
            padding: 0.35rem 1rem !important;
            margin: 0 !important;
            font-size: 0.875rem;
            color: #212529;
            text-decoration: none;
            white-space: nowrap;
            background: transparent;
            border: 0;
        }
        .wym_skin_silver .wym_dropdown ul li a:hover {
            background-color: #e9ecef;
            color: #1e2125;
        }

        /* Dashboard controls as Bootstrap cards */
        .dashboard-controls {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .dashboard-controls li {
            flex: 0 0 calc(25% - 1rem);
            min-width: 200px;
        }
        @media (max-width: 992px) {
            .dashboard-controls li {
                flex: 0 0 calc(33.333% - 1rem);
            }
        }
        @media (max-width: 768px) {
            .dashboard-controls li {
                flex: 0 0 calc(50% - 1rem);
            }
        }
        @media (max-width: 576px) {
            .dashboard-controls li {
                flex: 0 0 100%;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <?php skyblue_sidebar(); ?>

        <div class="main">
            <?php skyblue_navbar(); ?>

            <main class="content">
                <div class="container-fluid p-0">
                    <?php echo Filter::get($data, 'body', '', 0); ?>
                </div>
            </main>

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-6 text-start">
                            <p class="mb-0">
                                <strong>SkyBlue CMS</strong> &copy; <?php echo date('Y'); ?>
                            </p>
                        </div>
                        <div class="col-6 text-end">
                            <?php fragment(array('name' => 'console', 'view' => 'footer', 'wrapper' => 'no')); ?>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- AdminKit JS -->
    <script src="<?php echo SB_UI_DIR; ?>admin/js/adminkit/app.js"></script>

    <!-- SkyBlue Editor Scripts (includes jQuery) -->
    <?php fragment(array('name' => 'editor', 'view' => 'scripts', 'wrapper' => 'no')); ?>

    <!-- Dynamic head elements (WYSIWYG editor, etc.) -->
    <?php get_head_elements(); ?>
</body>
</html>
