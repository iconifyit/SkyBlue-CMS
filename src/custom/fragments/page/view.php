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
    <?php echo Event::trigger('OnAfterFragments', $Page->getStory_content()); ?>
<?php endif; ?>