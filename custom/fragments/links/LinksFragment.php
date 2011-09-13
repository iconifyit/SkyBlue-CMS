<?php defined('SKYBLUE') or die('Bad file request');

class LinksFragment extends Fragment {

    function getData() {
        Loader::load('managers.links.LinksHelper', true, _SBC_APP_);
        $Dao = LinksHelper::getLinkDAO();
        return LinksFragment::getPublished($Dao->index());
    }
    
    function getGroups($gids) {
        $Dao = LinksHelper::getLinksgroupDAO();
        $groups = $Dao->index();
        if (trim($gids) == "") {
            $result = $groups;
        }
        else {
            $result = LinksFragment::filterItems($groups, $gids);
        }
        return $result;
    }
        
    function getItems(&$group, $items) {
        $items = LinksFragment::getPublished($items);
        $these = array();
        foreach ($items as $item) {
            if (in_array($group->id, LinksFragment::getMyGroups($item))) {
                array_push($these, $item);
            }
        }
        return $these;
    }
    
    function filterItems($items, $ids) {
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
    
    function getMyGroups(&$item) {
        $groups = array();
        $str = $item->groups;
        if (trim($str) != "") {
            $groups = explode(",", $str);
        }
        return $groups;
    }
    
    function getRelationship(&$item) {
        if (isset($item->relationship)) {
            echo " rel=\"{$item->relationship}\"";
        }
        echo null;
    }
}