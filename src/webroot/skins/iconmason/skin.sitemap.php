<?php
/**
 * IconMason Skin - Sitemap Template
 *
 * Displays a sitemap of all published pages with dark theme.
 */
?>
<?php fragment(array('name' => 'segments', 'view' => 'bootstrap5-header')); ?>

<div class="docs-wrapper">

<?php fragment(array('name' => 'menu', 'view' => 'bootstrap5-nav', 'menu' => 1, 'brand_text' => 'SkyBlue CMS')); ?>

<!-- Sitemap Header -->
<section class="sitemap-header bg-dark text-white py-5">
    <div class="container">
        <h1 class="display-5">[[page.title]]</h1>
        <p class="lead mb-0">Complete listing of all pages on this site.</p>
    </div>
</section>

<!-- Sitemap Content -->
<section class="sitemap-content py-5 bg-dark">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <?php fragment(array('name' => 'page')); ?>

                        <h2 class="h4 mb-4">Documentation</h2>
                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item"><a href="[[site.url]]installation">Installation</a></li>
                            <li class="list-group-item"><a href="[[site.url]]open-iconjar">Open IconJar</a></li>
                            <li class="list-group-item"><a href="[[site.url]]import-iconjar">Import IconJar</a></li>
                            <li class="list-group-item"><a href="[[site.url]]export-iconjar">Export IconJar</a></li>
                            <li class="list-group-item"><a href="[[site.url]]search">Search</a></li>
                            <li class="list-group-item"><a href="[[site.url]]menus">Menus</a></li>
                            <li class="list-group-item"><a href="[[site.url]]settings">Settings</a></li>
                        </ul>

                        <h2 class="h4 mb-4">IconJars</h2>
                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item"><a href="[[site.url]]add-iconjar">Add IconJar</a></li>
                            <li class="list-group-item"><a href="[[site.url]]edit-iconjar">Edit IconJar</a></li>
                            <li class="list-group-item"><a href="[[site.url]]delete-iconjar">Delete IconJar</a></li>
                        </ul>

                        <h2 class="h4 mb-4">IconSets</h2>
                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item"><a href="[[site.url]]add-iconsets">Add IconSets</a></li>
                            <li class="list-group-item"><a href="[[site.url]]edit-iconsets">Edit IconSets</a></li>
                            <li class="list-group-item"><a href="[[site.url]]delete-iconsets">Delete IconSets</a></li>
                        </ul>

                        <h2 class="h4 mb-4">Icons</h2>
                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item"><a href="[[site.url]]add-icons">Add Icons</a></li>
                            <li class="list-group-item"><a href="[[site.url]]edit-icons">Edit Icons</a></li>
                            <li class="list-group-item"><a href="[[site.url]]replace-icons">Replace Icons</a></li>
                            <li class="list-group-item"><a href="[[site.url]]add-selection">Add Selection</a></li>
                            <li class="list-group-item"><a href="[[site.url]]delete-icons">Delete Icons</a></li>
                        </ul>

                        <h2 class="h4 mb-4">Resources</h2>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><a href="[[site.url]]free-icons">Free Icons</a></li>
                            <li class="list-group-item"><a href="[[site.url]]roadmap">Roadmap</a></li>
                            <li class="list-group-item"><a href="[[site.url]]disclosures">Disclosures</a></li>
                            <li class="list-group-item"><a href="[[site.url]]blog">Blog</a></li>
                            <li class="list-group-item"><a href="[[site.url]]contact">Contact</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php fragment(array('name' => 'segments', 'view' => 'bootstrap5-footer')); ?>

</div><!-- /.docs-wrapper -->
