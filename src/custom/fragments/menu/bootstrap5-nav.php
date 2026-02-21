<?php defined('SKYBLUE') or die('Bad file request');

/**
 * Bootstrap 5 Navigation View
 *
 * Renders a Bootstrap 5 navbar from pages assigned to a specific menu.
 *
 * Usage in skin template:
 *   <?php fragment(array('name' => 'menu', 'view' => 'bootstrap5-nav', 'menu' => 1)); ?>
 *
 * Parameters:
 *   - menu: Menu ID to render (required)
 *   - brand_text: Brand/logo text (default: site name)
 *   - brand_link: Brand link URL (default: site root)
 *   - show_brand: Show brand in navbar (default: true)
 *   - navbar_class: Additional navbar classes (default: 'navbar-dark bg-dark')
 *
 * @package    SkyBlue CMS
 * @subpackage Fragments
 */

require_once(dirname(__FILE__) . '/Bootstrap5NavFragment.php');

// Get parameters
$menuId     = Filter::getNumeric($params, 'menu', 1);
$brandText  = Filter::get($params, 'brand_text', Config::get('site_name', 'SkyBlue CMS'));
$brandLink  = Filter::get($params, 'brand_link', '[[site.url]]');
$showBrand  = Filter::get($params, 'show_brand', true);
$navbarClass = Filter::get($params, 'navbar_class', 'navbar-dark bg-dark');

// Get pages for this menu
$pages      = Bootstrap5NavFragment::getMenuPages($menuId);
$topLevel   = Bootstrap5NavFragment::getTopLevel($pages);
$currentPid = Bootstrap5NavFragment::getCurrentPageId();

?>
<!-- Bootstrap 5 Navbar - Menu ID: <?php echo $menuId; ?> -->
<nav class="navbar navbar-expand-lg <?php echo htmlspecialchars($navbarClass); ?> sticky-top">
    <div class="container">
        <?php if ($showBrand) : ?>
        <a class="navbar-brand d-flex align-items-center" href="<?php echo $brandLink; ?>">
            <span><?php echo htmlspecialchars($brandText); ?></span>
        </a>
        <?php endif; ?>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarMain" aria-controls="navbarMain"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php foreach ($topLevel as $Page) : ?>
                    <?php
                    $pageId   = $Page->getId();
                    $pageName = $Page->getName();
                    $pageUrl  = Bootstrap5NavFragment::getPageUrl($Page);
                    $isActive = (intval($currentPid) === intval($pageId)) ? ' active' : '';
                    $hasKids  = Bootstrap5NavFragment::hasChildren($pages, $pageId);
                    ?>

                    <?php if ($hasKids) : ?>
                        <?php $children = Bootstrap5NavFragment::getChildren($pages, $pageId); ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle<?php echo $isActive; ?>" href="#" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo htmlspecialchars($pageName); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <?php foreach ($children as $Child) : ?>
                                    <?php
                                    $childId   = $Child->getId();
                                    $childName = $Child->getName();
                                    $childUrl  = Bootstrap5NavFragment::getPageUrl($Child);
                                    $childActive = (intval($currentPid) === intval($childId)) ? ' active' : '';
                                    ?>
                                    <li>
                                        <a class="dropdown-item<?php echo $childActive; ?>"
                                           href="<?php echo $childUrl; ?>">
                                            <?php echo htmlspecialchars($childName); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php else : ?>
                        <li class="nav-item">
                            <a class="nav-link<?php echo $isActive; ?>" href="<?php echo $pageUrl; ?>">
                                <?php echo htmlspecialchars($pageName); ?>
                            </a>
                        </li>
                    <?php endif; ?>

                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</nav>
