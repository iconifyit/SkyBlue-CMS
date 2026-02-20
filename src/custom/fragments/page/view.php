<?php defined('SKYBLUE') or die('Unauthorized file request');

global $Authorize;

/**
 * Attempt to get the PageModel object from the $View object
 * passed by the FragmentorPlugin
 */
$Dao = PageHelper::getPageDao();

/**
 * Get the instance of the current Page object
 */
$Page = $Dao->getItem(
    Filter::getNumeric($_GET, 'pid', DEFAULT_PAGE)
);

/**
 * Make sure the current User is authorized to view this resource
 */

$isAuthorized = $Authorize->checkDataAccess($Page);

?>
<?php if (! $isAuthorized) : ?>
    <p><?php __('PAGE.NOT_AUTORIZED', 'You are not authorized to view the requested resource.'); ?></p>
<?php else : ?>
    <?php
    // Render Markdown content using Parsedown
    $content = $Page->getStory_content();
    if (!empty($content)) {
        if (!class_exists('Parsedown')) {
            $parsedownPath = _SBC_SYS_ . 'libs/Parsedown/Parsedown.php';
            if (file_exists($parsedownPath)) {
                require_once($parsedownPath);
            }
        }
        if (class_exists('Parsedown')) {
            $parsedown = new Parsedown();
            $parsedown->setSafeMode(true);
            $content = $parsedown->text($content);
        }
    }
    echo Event::trigger('OnAfterFragments', $content);
    ?>
<?php endif; ?>