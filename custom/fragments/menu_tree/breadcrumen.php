<?php defined('SKYBLUE') or die('Bad file request');

/**
 * Attempt to get the PageDAO object from the $View object
 * passed by the FragmentorPlugin
 */
$Dao = $this->getDao();

/**
 * Make sure we have a PageDAO object
 */
if (! is_a($Dao, 'PageDAO')) {
    trigger_error(
        __('SYSTEM.MENUTREE.CALLBY', 'The menu_tree fragment can only be called from a PHP funciton call.', 1),
        E_USER_ERROR
    );
}

/**
 * Get the instance of the current Page object
 */
$Page = $Dao->getItem(
    Filter::getNumeric($_GET, 'pid', DEFAULT_PAGE)
);

/**
 * Get the full page list so we can build the navigation tree
 */
$pages = $Dao->index();

/**
 * Build the navigation tree.
 */
$Builder = Menu_treeFragment::getBuilder($pages, $Page);

$Builder->getBreadcrumenHTML($Page->menu, $params);
