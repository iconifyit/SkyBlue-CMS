<?php defined('SKYBLUE') or die('Bad file request');

class LinksFragment extends Fragment {

    // PHP 8.2: Made all methods static to fix non-static method call errors
    static function getData() {
        Loader::load('managers.links.LinksHelper', true, _SBC_APP_);
        $Dao = LinksHelper::getLinkDAO();
        return LinksFragment::getPublished($Dao->index());
    }

    static function getGroups($gids) {
        $Dao = LinksHelper::getLinksgroupDAO();
        $groups = $Dao->index();
        // PHP 8.2: Check if $gids is a string before using trim()
        if ((is_array($gids) && empty($gids)) || (is_string($gids) && trim($gids) == "")) {
            $result = $groups;
        }
        else {
            $result = LinksFragment::filterItems($groups, $gids);
        }
        return $result;
    }

    static function getItems(&$group, $items) {
        $items = LinksFragment::getPublished($items);
        $these = array();
        foreach ($items as $item) {
            if (in_array($group->id, LinksFragment::getMyGroups($item))) {
                array_push($these, $item);
            }
        }
        return $these;
    }

    static function filterItems($items, $ids) {
        $result = array();
        $count = count($ids);
        if ($count) {
            for ($i=0; $i<$count; $i++) {
                $itemCount = count($items);
                for ($j=0; $j<$itemCount; $j++) {
                    if (Filter::get($items[$j], 'id') == $ids[$i]) {
                        array_push($result, $items[$j]);
                    }
                }
            }
        }
        else {
            $result = $items;
        }
        return $result;
    }

    static function getMyGroups(&$item) {
        $groups = array();
        $str = $item->groups;
        if (trim($str) != "") {
            $groups = explode(",", $str);
        }
        return $groups;
    }

    static function getRelationship(&$item) {
        if (isset($item->relationship)) {
            echo " rel=\"{$item->relationship}\"";
        }
        echo null;
    }
}