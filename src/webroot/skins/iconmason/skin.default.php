<?php
/**
 * SkyBlue CMS Demo - Default Template
 *
 * Single-column layout for standard content pages.
 */
?>
<?php fragment(array('name' => 'segments', 'view' => 'bootstrap5-header')); ?>

<div class="docs-wrapper">

<?php fragment(array('name' => 'menu', 'view' => 'bootstrap5-nav', 'menu' => 1, 'brand_text' => 'SkyBlue CMS')); ?>

<!-- Main Content -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <article class="doc-content">
                <h1 class="mb-4">[[page.title]]</h1>

                <?php fragment(array('name' => 'page')); ?>

                <!-- Page Footer -->
                <div class="doc-footer mt-5 pt-4 border-top text-muted">
                    <small>
                        Last updated: [[page.modified(F j, Y)]]
                    </small>
                </div>
            </article>
        </div>
    </div>
</div>

<?php fragment(array('name' => 'segments', 'view' => 'bootstrap5-footer')); ?>

</div><!-- /.docs-wrapper -->
