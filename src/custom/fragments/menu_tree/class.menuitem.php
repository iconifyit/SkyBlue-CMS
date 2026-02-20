<?php defined('SKYBLUE') or die('Bad file request');

/**
* MenuItem objects represent a page in SkyBlue's page tree.
*/
 
class MenuItem {

    var $id    = null;
    var $title = null;

    var $isActive  = false;
    var $isCurrent = false;

    var $parent   = null;
    var $children = array();

    /**
     * Constructor
     *
     * @param   MenuItem        parent  a reference to the item's parent item
     * @param   stdClass        page    the page this item wraps
     */
     
    function __construct(&$parent, &$page) {
        $this->id     = $page->id;
        $this->title  = $page->name;
        $this->parent =& $parent;
    }

    /**
     * Adds a new item created from +page+ to the item's children
     *
     * @param   stdClass        page    the page the child item wraps
     * @return  MenuItem                the new child item
     */
     
    function addChild(&$page) {
        $child =& new MenuItem($this, $page);
        array_push($this->children, $child);
        return $child;
    }


    /**
     * Returns +true+ if the item has children
     *
     * @return  boolean                 true if child-count > 0
     */
     
    function hasChildren() {
        return count($this->children) > 0;
    }

    /**
     * Returns +true+ if the parent is not +null+
     *
     * @return  boolean                 true if the item has a parent item
     */
     
    function hasParent() {
        return !is_null($this->parent);
    }

    /**
     * Getter
     */
     
    function get($prop, $default=null) {
        if (isset($this->$prop)) return $this->$prop;
        return $default;
    }

    /**
     * Returns array of the item's children items
     *
     * @return  array                   array of MenuItem objects
     */
     
    function getChildren() {
        return $this->children;
    }

    /**
     * Returns the item's parent item unless overridden
     *
     * @see MenuRoot
     * @return  MenuItem                a MenuItem object
     */
     
    function getParent() {
        return $this->parent;
    }

    /**
     * Returns all parent items
     *
     * @param   boolean         includeSelf     if true the item itself
     * @return  array                           array of MenuItem objects
     */
     
    function getParents($includeSelf = false) {
        $parents = array();
        $item    = $includeSelf ? $this : $this->getParent();

        $max = 1000;
        $n = 0;
        while (is_object($item) && $item->hasParent() && $n < $max) {
            array_push($parents, $item);
            $item = $item->getParent();
            $n++;
        }

        return $parents;
    }

    /**
     * Returns the root element
     *
     * @return  MenuItem        the tree's root element (with no parent)
     */
     
    function getRoot() {
        $item = $this;

        $n = 0;
        $max = 1000;
        while($item->hasParent() && $n < $max) {
            $item = $item->getParent();
            $n++;
        }

        return $item;
    }

    /**
     * Sets isActive token to true
     */
     
    function setActive() {
        $this->isActive = true;
    }

    /**
     * Sets isCurrent token to true and marks
     * all its parents as active
     */
     
    function setCurent() {
        $this->setActive();
        $this->isCurrent = true;

        foreach ($this->getParents() as $item) {
            $item->setActive();
        }
    }

    function isActive() {
        return $this->isActive;
    }

    function isCurrent() {
        return $this->isCurrent;
    }
}
