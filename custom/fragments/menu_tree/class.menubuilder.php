<?php defined('SKYBLUE') or die('Bad file request');

require_once(dirname(__FILE__) . '/class.menuitem.php');
require_once(dirname(__FILE__) . '/class.menuroot.php');

/**
 * This class offers an interface to work with SkyBlue's page tree
 */

class MenuBuilder {

    var $DEFAULT_OPTIONS  = array(
        'depth'         => 100,
        'offset'        => 0,
        'link_current'  => false,
        'show_all'      => false,
        'active_class'  => 'active',
        'separator'     => ' &gt; ',
        'li_class'      => 'page_item',
        'a_class'       => 'nav-item',
        'submenu_class' => 'submenu'
    );

    var $menus     = null;
    var $pages     = null;
    var $pid       = null;

    /**
     * Constructor
     *
     * @param   array   pages   array of stdClass objets storing page info
     * @param   int     pid     current page id
     */
    
    function __construct(&$pages, $pid) {
        $this->pid   = $pid;
        $this->pages = $pages;
        $this->menus = array();
    }

    /**
     * Recursively creates a tree from the pages array
     *
     * @param   MenuItem parent         MenuItem object under which the tree is build
     * @param   array    pageIdx        indices of pages for this tree
     */
     
    function buildTree(&$parent, $pageIdx) {
        foreach ($pageIdx as $i) {
            if (intval($this->pages[$i]->parent) == intval($parent->get('id'))) {
                $child = $parent->addChild($this->pages[$i]);
                $this->buildTree($child, array_diff($pageIdx, array($i)));

                if ($this->pid == $this->pages[$i]->id) {
                    $root = $parent->getRoot();
                    $root->setCurrentItem($child);
                }
            }
        }
    }

    /**
     * Returns an unordered HTML list with linked items.
     * Active list entries (li) have a css-class 'active'
     * The item belonging to the current page is not a link,
     * istaead the item-title is wrapped in a <strong> tag.
     *
     * @param   int     menuId
     * @param   array   options see DEFAULT_OPTIONS
     * @return  str             an unorded HTML list with links
     */
     
    function getHTML($menuid, $options) {

        $options = array_merge($this->DEFAULT_OPTIONS, $options);

        $this->loadTree($menuid);
        
        $attrs = array();
        if ($menuStyleId = Filter::get($options, 'style_id')) {
            $attrs = array('id' => $menuStyleId);
        }

        return HtmlUtils::tag(
            'ul',
            $attrs,
            $this->renderTree(
                $this->menus[$menuid], 
                Filter::get($options, 'offset'), 
                Filter::get($options, 'depth'), 
                $options
            )
        );
    }

    /**
     * Returns current branch of links as HTML ("You're here: ...").
     * The current item again is not a link, instead is wrapped in
     * a <span> tag.
     *
     * @param   int     menuId
     * @param   array   options         valid keys are separator and link_current
     * @return  str                     HTML links
     */
     
    function getBreadcrumenHTML($menuid, $options=array()) {

        global $Router;

        $options = array_merge($this->DEFAULT_OPTIONS, $options);

        $this->loadTree($menuid);
        $currentItem = $this->menus[$menuid]->getCurrentItem();
        $activeItems = $currentItem->getParents(true);

        $html = '';

        foreach (array_reverse($activeItems) as $item) {
            if ($item->isCurrent() && !$options['link_current']) {
                $html .= HtmlUtils::tag(
                    'span',
                    array(),
                    $item->get('title')
                );
            } else {
                $html .= HtmlUtils::tag(
                    'a',
                    array('href' => $Router->GetLink($item->get('id'))),
                    $item->get('title')
                ) . Filter::get($options, 'separator');
            }
        }

        return $html;
    }

    /**
     * Ensure the tree is build only once for each menu
     */
     
    function loadTree($menuid) {
        global $Authorize;
        if (! array_key_exists($menuid, $this->menus)) {
            $this->menus[$menuid] = new MenuRoot();
            
            $idx = array();
            for ($i = 0; $i < count($this->pages); $i++) {
                if ($this->pages[$i]->menu == $menuid 
                    && $this->pages[$i]->published == 1
                    && $Authorize->checkDataAccess($this->pages[$i])) {
                    
                    array_push($idx, $i);
                }
            }
            $this->buildTree($this->menus[$menuid], $idx);
        }
    }

    /**
     * Core routine to transform a tree into HTML
     *
     * @see #getHtml
     */
     
    function renderTree($item, $offset, $depth, $options) {
        global $Core;
        global $Router;
        
        # Core::Dump(array($item, $offset, $depth, $options));

        $attribs = array();
        $sublist = '';
        $active  = $options['show_all'] ? true : $item->isActive();

        if ($active && $item->hasChildren() && ($offset + $depth + 1) > 0) {

            $n=0;
            foreach ($item->getChildren() as $child) {
                $subListOptions = $options;
                if ($offset === -1) {
                    $subListOptions['li_class'] = '';
                    $subListOptions['a_class'] = '';
                    if ($n == 0) {
                        $subListOptions['li_class'] .= ' firstChild';
                    }
                    if ($n == count($item->getChildren())-1) {
                        $subListOptions['li_class'] .= ' lastChild';
                    }
                    $subListOptions['li_class'] = trim($subListOptions['li_class']);
                }
                $sublist .= $this->renderTree($child, $offset-1, $depth-1, $subListOptions);
                $n++;
            }

            if (is_null($item->get('title')) || $offset > 0) return $sublist;

            $sublist = HtmlUtils::tag(
                'div',
                array(
                    'class' => Filter::get(
                        $options, 
                        'submenu_class', 
                        $DEFAULT_OPTIONS['submenu_class']
                )),
                HtmlUtils::tag(
                    'ul',
                    array(),
                    $sublist
                )
            );
        }

        if ($offset > 0) return '';

        // add css class 'active' to the LI containing current link
        
        if (Filter::get($options, 'li_class', $DEFAULT_OPTIONS['li_class'])) {
            $attribs['class'] .= Filter::get($options, 'li_class', $DEFAULT_OPTIONS['li_class']);
        }
        if ($item->isActive()) {
            $attribs['class'] .= ($attribs['class'] ? ' ' : '') . Filter::get($options, 'active_class');
        }
        
        // don't create a to the page we're currently viewing

        $attrs = array('href' => $Router->GetLink($item->get('id')));
        if ($item->get('id') == DEFAULT_PAGE) {
            $attrs['class'] = 'home';
            $attrs['class'] .= ($attrs['class'] ? ' ' : '') . Filter::get($options, 'active_class');
        }
        if (Filter::get($options, 'a_class', $DEFAULT_OPTIONS['a_class'])) {
            $attrs['class'] .= ($attrs['class'] ? ' ' : '') . Filter::get($options, 'a_class', $DEFAULT_OPTIONS['a_class']);
        }
        $link = HtmlUtils::tag(
            'a',
            $attrs,
            HtmlUtils::tag(
                'span',
                array('class'=>'linktext'),
                ucwords($item->get('title'))
            )
        );

        return HtmlUtils::tag('li', $attribs, "{$link}\n" . $sublist);
    }
}