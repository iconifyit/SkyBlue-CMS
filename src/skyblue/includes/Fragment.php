<?php defined('SKYBLUE') or die('Bad file request');

class Fragment {

    // PHP 8.2: Made static to fix non-static method call errors
    static function init($name) {
        if (is_dir(_SBC_APP_ . "managers/{$name}/helpers")) {
            Loader::load("managers.{$name}.helpers.*", true, _SBC_APP_);
        }
        else if (is_dir(_SBC_SYS_ . "managers/{$name}/helpers")) {
            Loader::load("managers.{$name}.helpers.*", true, _SBC_SYS_);
        }
    }

    // PHP 8.2: Made static to fix non-static method call errors
    static function getPublished(&$items) {
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