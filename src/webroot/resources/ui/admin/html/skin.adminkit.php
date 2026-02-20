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

    <!--skyblue_headers-->
    <!--#fragment(name=editor&view=styles&wrapper=no)-->
    <!--/skyblue_headers-->

    <style>
        /* SkyBlue Admin Overrides */
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
                            <!--#fragment(name=console&view=footer)-->
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- AdminKit JS -->
    <script src="<?php echo SB_UI_DIR; ?>admin/js/adminkit/app.js"></script>

    <!--#fragment(name=editor&view=scripts&wrapper=no)-->
    <?php get_head_elements(); ?>
</body>
</html>
