<?php
/**
 * IconMason Skin - Contact Page Template
 *
 * Contact form page with Bootstrap 5 styling and dark theme.
 */
?>
<?php fragment(array('name' => 'segments', 'view' => 'bootstrap5-header')); ?>

<div class="docs-wrapper">

<?php fragment(array('name' => 'menu', 'view' => 'bootstrap5-nav', 'menu' => 1, 'brand_text' => 'SkyBlue CMS')); ?>

<!-- Contact Header -->
<section class="contact-header bg-dark text-white py-5">
    <div class="container">
        <h1 class="display-5">[[page.title]]</h1>
        <p class="lead mb-0">We'd love to hear from you.</p>
    </div>
</section>

<!-- Contact Content -->
<section class="contact-content py-5 bg-dark">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <div class="mb-4">
                            <?php fragment(array('name' => 'page')); ?>
                        </div>

                        <?php fragment(array('name' => 'contacts', 'view' => 'bootstrap5')); ?>
                    </div>
                </div>
            </div>

            <!-- Contact Info Sidebar -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="bi bi-info-circle me-2"></i>About SkyBlue CMS
                    </div>
                    <div class="card-body">
                        <p class="card-text small">
                            SkyBlue CMS is a lightweight, flexible content management system
                            built with PHP and designed for developers.
                        </p>
                        <a href="[[site.url]]about" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-person me-1"></i>Learn More
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-github me-2"></i>Open Source
                    </div>
                    <div class="card-body">
                        <p class="card-text small">
                            SkyBlue CMS is open source and available on GitHub.
                        </p>
                        <a href="https://github.com/skybluecanvas" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-github me-1"></i>View on GitHub
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php fragment(array('name' => 'segments', 'view' => 'bootstrap5-footer')); ?>

</div><!-- /.docs-wrapper -->
