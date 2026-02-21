<!DOCTYPE html>
<html lang="[[site.site_lang]]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php the_page_title(); ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom Skin Styles -->
    <link rel="stylesheet" href="[[skin.path]]css/main.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="[[skin.path]]images/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="[[skin.path]]images/favicon-32x32.png">

    [[page.metadata]]
    [[page.head_elements]]
</head>
<body class="[[page.css_class]]" id="[[page.css_id]]">

<?php fragment(array('name' => 'menu', 'view' => 'bootstrap5-nav', 'menu' => 1, 'brand_text' => 'SkyBlue CMS')); ?>

<!-- Hero Section -->
<section class="hero-section bg-dark text-white py-5">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-3">SkyBlue CMS</h1>
                <p class="lead mb-4">
                    A lightweight, flexible content management system built for developers
                    who value simplicity, control, and clean architecture.
                </p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="[[site.url]]about" class="btn btn-primary btn-lg px-4 me-md-2">
                        <i class="bi bi-person me-2"></i>Learn More
                    </a>
                    <a href="[[site.url]]contact" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-envelope me-2"></i>Get In Touch
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section py-5">
    <div class="container">
        <h2 class="text-center mb-5">Why SkyBlue?</h2>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="feature-icon bg-primary bg-gradient text-white rounded-circle mb-3 mx-auto"
                             style="width: 60px; height: 60px; line-height: 60px;">
                            <i class="bi bi-feather"></i>
                        </div>
                        <h5 class="card-title">Lightweight</h5>
                        <p class="card-text text-muted">
                            Minimal footprint with fast load times. No bloat, just what you need.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="feature-icon bg-success bg-gradient text-white rounded-circle mb-3 mx-auto"
                             style="width: 60px; height: 60px; line-height: 60px;">
                            <i class="bi bi-database"></i>
                        </div>
                        <h5 class="card-title">Flexible Storage</h5>
                        <p class="card-text text-muted">
                            Choose SQLite or flat-file XML storage. No complex database setup required.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="feature-icon bg-info bg-gradient text-white rounded-circle mb-3 mx-auto"
                             style="width: 60px; height: 60px; line-height: 60px;">
                            <i class="bi bi-markdown"></i>
                        </div>
                        <h5 class="card-title">Markdown Support</h5>
                        <p class="card-text text-muted">
                            Write content in Markdown with automatic rendering via Parsedown.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="feature-icon bg-warning bg-gradient text-white rounded-circle mb-3 mx-auto"
                             style="width: 60px; height: 60px; line-height: 60px;">
                            <i class="bi bi-sliders"></i>
                        </div>
                        <h5 class="card-title">Fully Customizable</h5>
                        <p class="card-text text-muted">
                            Complete control over templates, skins, and fragments. Build exactly what you need.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Content Section -->
<section class="content-section py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <article class="bg-white p-5 rounded shadow-sm doc-content">
                    <?php fragment(array('name' => 'page')); ?>
                </article>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section bg-primary text-white py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <h2 class="mb-3">Stay Updated</h2>
                <p class="lead mb-4">Subscribe to our newsletter for updates and tips.</p>
                <form class="row g-2 justify-content-center" onsubmit="return false;">
                    <div class="col-8">
                        <input type="email" class="form-control form-control-lg" placeholder="Enter your email">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-light btn-lg">
                            <i class="bi bi-envelope me-1"></i>Subscribe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-light py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> [[site.name]]. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="[[site.url]]privacy" class="text-light text-decoration-none me-3">Privacy</a>
                <a href="[[site.url]]disclaimer" class="text-light text-decoration-none">Disclaimer</a>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="[[skin.path]]js/main.js"></script>
</body>
</html>
