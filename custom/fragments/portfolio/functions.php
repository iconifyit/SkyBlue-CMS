<?php defined('SKYBLUE') or die('Bad file request'); 

global $Core;

function portfolio_scripts($html) {
    $ACTIVE_SKIN_DIR = ACTIVE_SKIN_DIR;
    return str_replace(
          '</head>', 
          make_script_element('window.skin_path = "' . $ACTIVE_SKIN_DIR . '";')
        . "</head>", 
        $html
    );
}

function lightbox_scripts($html) {
    $ACTIVE_SKIN_DIR = ACTIVE_SKIN_DIR;
    $FRAGMENT_DIR = basename(dirname(__FILE__));
    return str_replace(
        '</head>', 
          make_script_link($ACTIVE_SKIN_DIR . "js/jquery-1.3.2.min.js")
        . make_script_link($ACTIVE_SKIN_DIR . "fragments/{$FRAGMENT_DIR}/js/lightbox/jquery.lightbox-0.4.js")
        . make_script_element(
          "(function($) {\n"
            . "$(function() {\n"
            . "    $('#lightbox a').lightBox({\n"
            . "            imageLoading:   '{$ACTIVE_SKIN_DIR}fragments/{$FRAGMENT_DIR}/images/lightbox/lightbox-ico-loading.gif',\n"
            . "            imageBtnPrev:   '{$ACTIVE_SKIN_DIR}fragments/{$FRAGMENT_DIR}/images/lightbox/lightbox-btn-prev.gif',\n"
            . "            imageBtnNext:   '{$ACTIVE_SKIN_DIR}fragments/{$FRAGMENT_DIR}/images/lightbox/lightbox-btn-next.gif',\n"
            . "            imageBtnClose:  '{$ACTIVE_SKIN_DIR}fragments/{$FRAGMENT_DIR}/images/lightbox/lightbox-btn-close.gif',\n"
            . "            imageBlank:       '{$ACTIVE_SKIN_DIR}fragments/{$FRAGMENT_DIR}/images/lightbox/lightbox-blank.gif'\n"
            . "        });\n"
            . "    });\n"
            . "})(jQuery);\n"
        )
        . make_style_link($ACTIVE_SKIN_DIR . "fragments/{$FRAGMENT_DIR}/css/lightbox/jquery.lightbox-0.4.css")
        . "</head>", 
        portfolio_scripts($html)
    );
}

function smoothgallery_scripts($html) {
    $ACTIVE_SKIN_DIR = ACTIVE_SKIN_DIR;
    $FRAGMENT_DIR = basename(dirname(__FILE__));
    return str_replace(
          '</head>', 
          make_script_link($ACTIVE_SKIN_DIR . "fragments/{$FRAGMENT_DIR}/js/smoothgallery/mootools.v1.11.js")
        . make_script_link($ACTIVE_SKIN_DIR . "fragments/{$FRAGMENT_DIR}/js/smoothgallery/jd.gallery.js")
        . make_style_link($ACTIVE_SKIN_DIR . "fragments/{$FRAGMENT_DIR}/css/smoothgallery/jd.gallery.css")
        . "</head>", 
        portfolio_scripts($html)
    );
}

function slider_scripts($html) {
    $ACTIVE_SKIN_DIR = ACTIVE_SKIN_DIR;
    $FRAGMENT_DIR = basename(dirname(__FILE__));
    return str_replace(
          '</head>', 
          make_script_link($ACTIVE_SKIN_DIR . "fragments/{$FRAGMENT_DIR}/js/slider/jquery.js")
        . make_script_link($ACTIVE_SKIN_DIR . "fragments/{$FRAGMENT_DIR}/js/slider/easySlider1.7.js")
        . make_script_link($ACTIVE_SKIN_DIR . "fragments/{$FRAGMENT_DIR}/js/slider/slider.js")
        . make_style_link($ACTIVE_SKIN_DIR  . "fragments/{$FRAGMENT_DIR}/css/slider/screen.css")
        . "</head>", 
        portfolio_scripts($html)
    );
}

function get_portfolio_items($data, $params) {
    global $Core;
    $items = $data;
    $category = Filter::get($params, 'category');
    if (!empty($category)) {
        $items = $Core->SelectObjs($data, 'category', $category);
    }
    return $items;
}

function get_portfolio_groups() {
    global $Core;
    if (!file_exists($Core->path . SB_XML_DIR . 'portfolio/category.xml')) return;
    return $Core->xmlHandler->ParserMain(
        $Core->path . SB_XML_DIR . 'portfolio/category.xml'
    );
}

function get_portfolio_item_description($item) {
    $text = "";
    $fileName = Filter::get($item, 'story');
    if (trim($fileName) != '') {
        $text = FileSystem::read_file(SB_STORY_DIR . $fileName);
    }
    return $text;
}
