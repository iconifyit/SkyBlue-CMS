<?php defined('SKYBLUE') or die('Bad file request');

/**
 * Child Pages Fragment Helper
 *
 * Provides methods for retrieving child pages of a given parent page.
 *
 * @package    SkyBlue CMS
 * @subpackage Fragments
 */

if (class_exists('ChildPagesFragment')) {
    return;
}

class ChildPagesFragment {

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
     * Get child pages of a parent page
     * @param int $parentId Parent page ID
     * @param int $max Maximum number of pages to return (0 = all)
     * @return array Array of Page objects
     */
    static function getChildPages($parentId, $max = 0) {
        $Dao        = self::getPageDao();
        $pages      = $Dao->index();
        $childPages = array();

        foreach ($pages as $Page) {
            if (intval($Page->getParent()) === intval($parentId)
                && intval($Page->getPublished()) === 1
            ) {
                $childPages[] = $Page;
            }
        }

        // Sort by ordinal
        usort($childPages, function($a, $b) {
            $ordA = isset($a->ordinal) ? intval($a->ordinal) : 999;
            $ordB = isset($b->ordinal) ? intval($b->ordinal) : 999;
            return $ordA - $ordB;
        });

        // Apply max limit
        if ($max > 0 && count($childPages) > $max) {
            $childPages = array_slice($childPages, 0, $max);
        }

        return $childPages;
    }

    /**
     * Build the page URL using permalink
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

    /**
     * Get page teaser/intro text from story content
     * @param Page $Page
     * @param int $maxChars Maximum characters for teaser
     * @return string
     */
    static function getTeaser($Page, $maxChars = 300) {
        $encoded = $Page->story_content;

        if (empty($encoded)) {
            return '';
        }

        // Decode base64 story content
        $content = base64_decode($encoded);
        if ($content === false) {
            return '';
        }

        // Strip HTML tags
        $text = strip_tags($content);
        $text = trim($text);

        // Truncate if needed
        if (strlen($text) > $maxChars) {
            $text = substr($text, 0, $maxChars);
            $lastSpace = strrpos($text, ' ');
            if ($lastSpace !== false) {
                $text = substr($text, 0, $lastSpace);
            }
            $text .= '...';
        }

        return $text;
    }
}
