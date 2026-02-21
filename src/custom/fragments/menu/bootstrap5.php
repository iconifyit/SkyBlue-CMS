<?php defined('SKYBLUE') or die('Bad file request');

/**
 * Bootstrap 5 Navigation Fragment
 *
 * Renders a Bootstrap 5 responsive navbar with optional sidebar navigation.
 * Uses the existing MenuFragment class to build navigation from the site structure.
 *
 * @param array $params Fragment parameters including:
 *   - menu_id: Menu ID to render
 *   - brand_text: Brand/logo text (default: site name from config)
 *   - brand_link: Brand link URL (default: site root)
 *   - show_sidebar: Whether to include sidebar nav structure (default: false)
 */

$menuId    = Filter::noInjection($params, 'menu_id');
$brandText = Filter::get($params, 'brand_text', Config::get('site_name', 'IconMason'));
$brandLink = Filter::get($params, 'brand_link', '[[site.url]]');

// Build navigation tree for navbar
MenuFragment::getSiteTree(array(
    'ul_id'    => 'navbarNav',
    'ul_class' => 'navbar-nav ms-auto',
    'li_class' => 'nav-item',
    'a_class'  => 'nav-link',
    'ul/li/ul' => 'dropdown-menu'
));

$navHtml = MenuFragment::getMenu();

?>
<!-- Bootstrap 5 Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo $brandLink; ?>">
            <img src="[[skin.path]]images/logo.svg" alt="Icon Mason" height="32" class="me-2">
            <span><?php echo htmlspecialchars($brandText); ?></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarMain" aria-controls="navbarMain"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="[[site.url]]">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="[[site.url]]installation">Docs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="[[site.url]]blog">Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="[[site.url]]contact">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
