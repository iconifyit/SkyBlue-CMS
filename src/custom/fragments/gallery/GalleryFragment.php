<?php defined('SKYBLUE') or die('Bad file request');

class GalleryFragment extends Fragment {

    function parseXml($file) {
        global $Core;
        return $Core->xmlHandler->ParserMain(
            SB_XML_DIR . "portfolio/{$file}.xml"
        );
    }

    function getData() {
        return GalleryFragment::parseXml('portfolio');
    }
    
    function getCategories() {
        return GalleryFragment::getPublished(
            GalleryFragment::parseXml('categories')
        );
    }
    
    function getSettings() {
        return GalleryFragment::parseXml('settings');
    }
    
    function getPublished(&$items) {
        $published = array();
        if (!count($items)) return array();
        foreach ($items as $item) {
            if (Filter::get($item, 'published', 0) == 1) {
                array_push($published, $item);
            }
        }
        return $published;
    }
    
    function getLink($pageId, $params=array()) {
        global $Router;
        
        $newsId = Filter::getNumeric($params, 'news_id');
        if (Config::get('sef_urls') == 1) {
            $params = array();
            if (!empty($newsId)) {
                $params['-pg-'] = $pageId;
                $params['-'] = $newsId;
            }
            $link = $Router->GetLink($pageId, $params, 1);
        }
        else {
            $link = "index.php?pid=$pageId";
            if (!empty($aid)) {
                $link .= "&show=$newsId";
            }
        }
        return $link;
    }
    
    function getStory(&$article) {
        if (file_exists(SB_STORY_DIR . Filter::get($article, 'story'))) {
            return FileSystem::read_file(SB_STORY_DIR . Filter::get($article, 'story')); 
        }
        return null;
    }

    function getIntro($item) {
        if (trim(Filter::get($item, 'intro')) == "") return "";
        return base64_decode(Filter::get($item, 'intro'));
    }
    
    function setPageTitle(&$data, $title) {
        if (is_array($data)) {
            if (isset($data['model'])) {
                $model =& $data['model'];
                if (is_object($model) && get_class($model) == 'PageDAO') {
                    if (isset($model->page)) {
                        $Page =& $model->page;
                        if (is_object($Page) && get_class($Page) == 'Page') {
                            $Page->setTitle($title);
                        }
                    }
                }
            }
        }
    }

}