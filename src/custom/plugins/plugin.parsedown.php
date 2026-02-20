<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      1.0 2024-01-01 00:00:00 $
 * @package      SkyBlue CMS
 * @copyright    Copyright (C) 2005 - 2024 Scott Edwin Lewis. All rights reserved.
 * @license      GNU/GPL, see COPYING.txt
 * SkyBlue CMS is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 *
 * Parsedown Markdown Rendering Plugin
 * Converts Markdown content to HTML on page display
 */

global $Core;

// Register for the OnRenderPage event
Event::register('OnRenderPage', 'plgParsedownRender');

/**
 * Renders Markdown content to HTML
 * @param string $html The page HTML content
 * @return string The rendered HTML
 */
function plgParsedownRender($html) {
    // Only run on front-end pages, not admin pages
    if (is_admin_page()) {
        return $html;
    }

    // Get current page to check content format
    $Page = current_page();

    if (!$Page) {
        return $html;
    }

    // Check if content is Markdown format
    $contentFormat = 'html';
    if (method_exists($Page, 'getContent_format')) {
        $contentFormat = $Page->getContent_format() ?: 'html';
    }

    if ($contentFormat !== 'markdown') {
        return $html;
    }

    // Load Parsedown if not already loaded
    if (!class_exists('Parsedown')) {
        $parsedownPath = _SBC_SYS_ . 'libs/Parsedown/Parsedown.php';
        if (file_exists($parsedownPath)) {
            require_once($parsedownPath);
        }
        else {
            // Parsedown not found, return original content
            return $html;
        }
    }

    // Get the page content and convert from Markdown to HTML
    $markdownContent = $Page->getStory_content();

    if (empty($markdownContent)) {
        return $html;
    }

    $parsedown = new Parsedown();
    $parsedown->setSafeMode(true); // Escape HTML for security

    $renderedContent = $parsedown->text($markdownContent);

    // Replace the story content in the HTML with rendered Markdown
    // The content is typically wrapped in a container, so we look for the pattern
    return str_replace($markdownContent, $renderedContent, $html);
}
