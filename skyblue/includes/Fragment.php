<?php defined('SKYBLUE') or die('Bad file request');

class Fragment {

    function init($name) {
        if (is_dir(_SBC_APP_ . "managers/{$name}/helpers")) {
            Loader::load("managers.{$name}.helpers.*", true, _SBC_APP_);
        }
        else if (is_dir(_SBC_SYS_ . "managers/{$name}/helpers")) {
            Loader::load("managers.{$name}.helpers.*", true, _SBC_SYS_);
        }
    }
    
    function getPublished(&$items) {
        $published = array();
        foreach ($items as $item) {
            if (! isset($item->published)) {
                array_push($published, $item);
            }
            else if ($item->published) {
                array_push($item->published);
            }
        }
        return $published;
    }
}