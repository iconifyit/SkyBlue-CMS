<?php defined('SKYBLUE') or die('Bad file request');

class MenuFragment extends Fragment {

    var $doc;
    
    // PHP 8.2: Made static - now acts as a static registry for the menu document
    static $_menuDoc = null;

    // PHP 8.2: Made static to fix non-static method call errors
    static function getMenu() {
        $menu = "";
        if (MenuFragment::$_menuDoc instanceof DOMDocument) {
            $menu = MenuFragment::$_menuDoc->saveHTML();
        }
        return $menu;
    }

    // PHP 8.2: Made static to fix non-static method call errors
    static function getSiteTree($options=array()) {
        $doc = new DOMDocument();
        if ($ui_id = Filter::get($options, 'ul_id')) {
            unset($options['ui_id']);
        }
        $ul = MenuFragment::buildSiteTree(
            MenuFragment::getNavigation(), $options, $doc
        );
        if ($ui_id && $ul) {
            $ul->setAttribute('id', $ui_id);
        }
        if ($ul) {
            $doc->appendChild($ul);
        }
        // PHP 8.2: Store in static property so getMenu() can access it
        MenuFragment::$_menuDoc = $doc;
        return $doc;
    }

    // PHP 8.2: Made static to fix non-static method call errors
    static function getNextPage($node) {
        $n = 0;
        $max = 100;
        $sibling = $node->nextSibling;
        while ($sibling->nodeName != $node->nodeName && $n < $max) {
            $n++;
            $sibling = $sibling->nextSibling;
            return $sibling;
        }
        return false;
    }

    // PHP 8.2: Made static to fix non-static method call errors
    static function getPreviousPage($node) {
        $n = 0;
        $max = 100;
        $sibling = $node->previousSibling;
        while ($sibling->nodeName != $node->nodeName && $n < $max) {
            $n++;
            $sibling = $sibling->previousSibling;
            return $sibling;
        }
        return false;
    }

    // PHP 8.2: Made static and added $doc parameter to avoid $this->doc usage
    static function buildSiteTree($pages, $options=array(), $doc=null) {
        if ($doc === null) {
            $doc = new DOMDocument();
        }

        // PHP 8.2: Initialize $ul to prevent null return
        $ul = $doc->createElement('ul');

        if ($pages->length) {

            /*
             * Add UL classes and ID
             */
            
            $classes = array();
            
            if (Filter::get($options, $ul->getNodePath())) {
                array_push($classes, Filter::get($options, $ul->getNodePath()));
            }
                
            if (Filter::get($options, 'ul_class')) {
                array_push($classes, Filter::get($options, 'ul_class'));
            }
            
            $ul->setAttribute('class', implode(" ", $classes));
            
            foreach ($pages as $Page) {
                if ($Page->nodeType != XML_ELEMENT_NODE) continue;
                
                $PageId   = $Page->attributes->getNamedItem('id')->nodeValue;
                $PageName = $Page->attributes->getNamedItem('name')->nodeValue;
                $PageUrl  = $Page->attributes->getNamedItem('url')->nodeValue;
                
                /*
                 * Add LI classes
                 */
                
                $classes = array();
                if (! MenuFragment::getPreviousPage($Page)) {
                    array_push($classes, 'firstChild');
                }
                else if (! MenuFragment::getNextPage($Page)) {
                    array_push($classes, 'lastChild');
                }
                
                if ($li_class = Filter::get($options, 'li_class')) {
                    array_push($classes, $li_class);
                }

                $li = $doc->createElement('li');

                if (Filter::get($options, $li->getNodePath())) {
                    array_push($classes, Filter::get($options, $li->getNodePath()));
                }

                $li->setAttribute('class', implode(" ", $classes));
                $a = $doc->createElement('a');
                $a->setAttribute('href', $PageUrl);

                /*
                 * Add A classes
                 */
                $classes = array();

                if (Filter::get($options, $a->getNodePath())) {
                    array_push($classes, Filter::get($options, $a->getNodePath()));
                }
                if (Filter::get($options, 'a_class')) {
                    array_push($classes, Filter::get($options, 'a_class'));
                }
                $a->setAttribute('class', implode(" ", $classes));

                $a->appendChild($doc->createTextNode($PageName));
                $li->appendChild($a);

                if ($Page->childNodes->length) {
                    $li->appendChild(
                        MenuFragment::buildSiteTree(
                            $Page->childNodes, $options, $doc
                        )
                    );
                }
                $ul->appendChild($li);
            }
        }
        return $ul;
    }

    // PHP 8.2: Made static to fix non-static method call errors
    static function getNavigation() {
        $navigation = null;
        try {
            $DaoClass = ucwords(DB_TYPE) . "DAO";
            $Dao = new $DaoClass(array(
                'type' => 'structure', 
                'bean_class' => 'Structure'
            ));
            $Statement = $Dao->query("select structure from Structure where site_id = 'sbc'");
            if ($result = $Statement->fetch()) {
                $Dom = load_xml_string(Filter::getRaw($result, 'structure'));
                $xpath = new DOMXPath($Dom);
                $remove = array();
                $nodes = $xpath->query("//*[@show_in_navigation='0']");
                foreach ($nodes as $node) {
                    try {
                        $Dom->documentElement->removeChild($node);
                    }
                    catch (DOMException $e) {
                        /* What to do here? */
                    }
                }
                $nodes = $xpath->query("//*[@published='0']");
                foreach ($nodes as $node) {
                    try {
                        $Dom->documentElement->removeChild($node);
                    }
                    catch (DOMException $e) {
                        /* What to do here? */
                    }
                }
            }
            $navigation = $Dom->getElementsByTagName('site')->item(0)->childNodes;
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
        return $navigation;
    }
    
    function getElementById($doc, $id) {
        $xpath = new DOMXPath($doc);
        return $xpath->query("//*[@id='$id']")->item(0);
    }
}