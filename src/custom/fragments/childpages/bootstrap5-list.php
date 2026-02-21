<?php defined('SKYBLUE') or die('Bad file request');

/**
 * Bootstrap 5 Child Pages List View
 *
 * Renders child pages as a simple list with title, teaser, and link.
 *
 * Usage in skin template:
 *   <?php fragment(array('name' => 'childpages', 'view' => 'bootstrap5-list', 'parent' => 38)); ?>
 *
 * Parameters:
 *   - parent: Parent page ID (required)
 *   - max: Maximum number of items to show (default: 10)
 *   - teaser_length: Maximum characters for teaser (default: 200)
 *
 * @package    SkyBlue CMS
 * @subpackage Fragments
 */

require_once(dirname(__FILE__) . '/ChildPagesFragment.php');

// Get parameters
$parentId     = Filter::getNumeric($params, 'parent', 0);
$maxItems     = Filter::getNumeric($params, 'max', 10);
$teaserLength = Filter::getNumeric($params, 'teaser_length', 200);

// Get child pages
$childPages = ChildPagesFragment::getChildPages($parentId, $maxItems);

?>
<?php if (count($childPages)) : ?>
    <?php foreach ($childPages as $Page) : ?>
        <?php
        $pageUrl = ChildPagesFragment::getPageUrl($Page);
        // Inline teaser logic
        $teaser = '';
        if (!empty($Page->story_content)) {
            $decoded = base64_decode($Page->story_content);
            if ($decoded !== false) {
                $teaser = trim(strip_tags($decoded));
                if (strlen($teaser) > $teaserLength) {
                    $teaser = substr($teaser, 0, $teaserLength);
                    $lastSpace = strrpos($teaser, ' ');
                    if ($lastSpace !== false) {
                        $teaser = substr($teaser, 0, $lastSpace);
                    }
                    $teaser .= '...';
                }
            }
        }
        ?>
        <div class="mb-4">
            <h4 class="mb-2">
                <a href="<?php echo $pageUrl; ?>">
                    <?php echo htmlspecialchars($Page->getName()); ?>
                </a>
            </h4>
            <?php if (!empty($teaser)) : ?>
            <p class="mb-2"><?php echo htmlspecialchars($teaser); ?></p>
            <?php endif; ?>
            <a href="<?php echo $pageUrl; ?>">Read More &rarr;</a>
        </div>
    <?php endforeach; ?>
<?php else : ?>
    <p>No articles to display.</p>
<?php endif; ?>
