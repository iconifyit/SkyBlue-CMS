<?php defined('SKYBLUE') or die('Bad file request');

require_once(dirname(__FILE__) . '/class.menubuilder.php');

class Menu_treeFragment {

    function getBuilder(&$data, &$Page) {
        static $Builder;
        if (! is_object($Builder)) {
            $Builder = new MenuBuilder($data, $Page->getId());
        }
        return $Builder;
    }
    
    function getChildren($Parent) {
        $children = array();
        $pages = Menu_treeFragment::getPublished();
        foreach ($pages as $Page) {
            if ($Page->getParent() == $Parent->getId()) {
                array_push($children, $Page);
            }
        }
        return $children;
    }
    
    function getPublished() {
        static $published;
        if (! is_array($pages) || $refresh) {
            $Dao = Menu_treeFragment::getPageDao();
            $pages = $Dao->index();
            $published = array();
            foreach ($pages as $Page) {
                if (intval($Page->getPublished()) == 1) {
                    array_push($published, $Page);
                }
            }
        }
        return $published;
    }
    
    function getPageDao($refresh=false) {
        static $Dao;
        if (! is_object($Dao) || $refresh) {
            if (! class_exists('PageDAO')) {
                Loader::load('daos.PageDAO', true, _SBC_APP_);
            }
            $Dao = new PageDao;
        }
        return $Dao;
    }
}