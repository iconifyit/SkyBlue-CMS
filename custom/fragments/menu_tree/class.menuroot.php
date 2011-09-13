<?php defined('SKYBLUE') or die('Bad file request');

/**
* Special class which objects are intended to serve as root
* element for all MenuItem-based trees.
*/
 
class MenuRoot extends MenuItem {

    var $currentItem = null;

    /**
     * Constructor
     */
    function __construct() {
        $this->id = '';
        $this->setActive();
    }

    /**
     * Possebility to store an item
     */
    function setCurrentItem(&$item) {
        if (! $item->isCurrent()) {
            $item->setCurent();
        }
        $this->currentItem = $item;
    }

    /**
     * Returns the item stored via MenuRoot#setCurrentItem
     *
     * @return  MenuItem       the current MenuItem object or +null+ 
     */
    function getCurrentItem() {
        return $this->currentItem;
    }
}