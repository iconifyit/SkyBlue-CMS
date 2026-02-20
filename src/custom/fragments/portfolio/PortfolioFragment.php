<?php defined('SKYBLUE') or die('Bad file request');

class PortfolioFragment extends Fragment {

    function items($params) {
        global $Core;
        $items = PortfolioFragment::data();
        $category = Filter::get($params, 'category');
        if (!empty($category)) {
            $items = Utils::selectObjects($items, 'category', $category);
        }
        return $items;
    }
    
    function data() {
        global $Core;
        static $items;
        if (! is_array($items)) {
            if (!file_exists(SB_XML_DIR . 'portfolio/portfolio.xml')) return;
            $items = $Core->xmlHandler->ParserMain(
                SB_XML_DIR . 'portfolio/portfolio.xml'
            );
        }
        return $items;
    }
    
    function groups() {
        global $Core;
        if (!file_exists($Core->path . SB_XML_DIR . 'portfolio/category.xml')) return;
        return $Core->xmlHandler->ParserMain(
            $Core->path . SB_XML_DIR . 'portfolio/category.xml'
        );
    }
    
    function description($item) {
        $text = "";
        $fileName = Filter::get($item, 'story');
        if (trim($fileName) != '') {
            $text = FileSystem::read_file(SB_STORY_DIR . $fileName);
        }
        return $text;
    }

}