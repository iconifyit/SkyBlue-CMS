<?php defined('SKYBLUE') or die('Bad file request');

Event::register('OnRenderPage', 'patch_wym_paths');

function patch_wym_paths($html) {
    return str_replace(WYM_RELATIVE_PATH, FULL_URL, $html);
}