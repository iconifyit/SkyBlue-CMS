<?php defined('SKYBLUE') or die('Bad file request');

/**
 * Bootstrap 5 Navigation Fragment
 *
 * Renders a Bootstrap 5 navbar from pages assigned to a specific menu.
 * Supports hierarchical menus using Page.parent for dropdowns.
 *
 * @package    SkyBlue CMS
 * @subpackage Fragments
 */

class Bootstrap5NavFragment {

    /**
     * Get the PageDAO instance
     * @param bool $refresh Force refresh of DAO instance
     * @return PageDAO
     */
    static function getPageDao($refresh = false) {
        static $Dao;
        if (!is_object($Dao) || $refresh) {
            if (!class_exists('PageDAO')) {
                Loader::load('daos.PageDAO', true, _SBC_APP_);
            }
            $Dao = new PageDAO();
        }
        return $Dao;
    }

    /**
     * Get pages assigned to a specific menu
     * @param int $menuId The menu ID to query
     * @return array Array of Page objects
     */
    static function getMenuPages($menuId) {
        $Dao   = self::getPageDao();
        $pages = $Dao->index();
        $menuPages = array();

        foreach ($pages as $Page) {
            if (intval($Page->getMenu()) === intval($menuId)
                && intval($Page->getPublished()) === 1
                && intval($Page->getShow_in_navigation()) === 1
            ) {
                $menuPages[] = $Page;
            }
        }

        // Sort by ordinal if set (DB column is 'ordinal')
        usort($menuPages, function($a, $b) {
            $ordA = isset($a->ordinal) ? intval($a->ordinal) : 999;
            $ordB = isset($b->ordinal) ? intval($b->ordinal) : 999;
            return $ordA - $ordB;
        });

        return $menuPages;
    }

    /**
     * Get top-level pages (no parent)
     * @param array $pages Array of Page objects
     * @return array Array of top-level Page objects
     */
    static function getTopLevel($pages) {
        $topLevel = array();
        foreach ($pages as $Page) {
            $parent = $Page->getParent();
            if (empty($parent) || intval($parent) === 0) {
                $topLevel[] = $Page;
            }
        }
        return $topLevel;
    }

    /**
     * Get child pages of a parent
     * @param array $pages All pages in the menu
     * @param int $parentId Parent page ID
     * @return array Array of child Page objects
     */
    static function getChildren($pages, $parentId) {
        $children = array();
        foreach ($pages as $Page) {
            if (intval($Page->getParent()) === intval($parentId)) {
                $children[] = $Page;
            }
        }
        return $children;
    }

    /**
     * Check if a page has children
     * @param array $pages All pages in the menu
     * @param int $pageId Page ID to check
     * @return bool
     */
    static function hasChildren($pages, $pageId) {
        foreach ($pages as $Page) {
            if (intval($Page->getParent()) === intval($pageId)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get the current page ID
     * @return int
     */
    static function getCurrentPageId() {
        return Filter::getNumeric($_GET, 'pid', DEFAULT_PAGE);
    }

    /**
     * Build the page URL
     * @param Page $Page
     * @return string
     */
    static function getPageUrl($Page) {
        global $Router;
        if (is_object($Router) && method_exists($Router, 'GetLink')) {
            return $Router->GetLink($Page->getId());
        }
        $permalink = $Page->getPermalink();
        if (!empty($permalink)) {
            return '[[site.url]]' . $permalink;
        }
        return '[[site.url]]?pid=' . $Page->getId();
    }
}
