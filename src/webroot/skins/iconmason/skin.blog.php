<?php
/**
 * IconMason Skin - Blog Listing Template
 *
 * Blog/news page using Bootstrap 5 card layout with dark theme.
 */
?>
<?php fragment(array('name' => 'segments', 'view' => 'bootstrap5-header')); ?>

<div class="docs-wrapper">

<?php fragment(array('name' => 'menu', 'view' => 'bootstrap5-nav', 'menu' => 1, 'brand_text' => 'SkyBlue CMS')); ?>

<!-- Blog Header -->
<section class="blog-header bg-dark text-white py-5">
    <div class="container">
        <h1 class="display-5">[[page.title]]</h1>
        <p class="lead mb-0">Articles and insights on software architecture and development.</p>
    </div>
</section>

<!-- Blog Content -->
<section class="blog-content py-5 bg-dark">
    <div class="container">
        <div class="row">
            <!-- Main Blog Posts -->
            <div class="col-lg-8">
                <?php fragment(array('name' => 'childpages', 'view' => 'bootstrap5-list', 'parent' => 38, 'max' => 10)); ?>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- About Widget -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="bi bi-info-circle me-2"></i>About SkyBlue CMS
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            SkyBlue CMS is a lightweight, flexible content management system
                            designed for developers who value simplicity and control.
                        </p>
                        <a href="[[site.url]]" class="btn btn-sm btn-outline-primary">Learn More</a>
                    </div>
                </div>

                <!-- Quick Links Widget -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="bi bi-link-45deg me-2"></i>Quick Links
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <a href="[[site.url]]about">
                                <i class="bi bi-person me-2"></i>About
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="[[site.url]]contact">
                                <i class="bi bi-envelope me-2"></i>Contact
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Widget -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="bi bi-envelope-paper me-2"></i>Get In Touch
                    </div>
                    <div class="card-body">
                        <p class="card-text small">
                            Have questions or want to learn more about SkyBlue CMS?
                        </p>
                        <a href="[[site.url]]contact" class="btn btn-primary w-100">
                            <i class="bi bi-chat-dots me-2"></i>Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php fragment(array('name' => 'segments', 'view' => 'bootstrap5-footer')); ?>

</div><!-- /.docs-wrapper -->
