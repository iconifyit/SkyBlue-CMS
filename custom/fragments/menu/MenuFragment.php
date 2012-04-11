<?php defined('SKYBLUE') or die('Bad file request');

class MenuFragment extends Fragment {

    var $doc;
    
    function getMenu() {
        $menu = "";
        if (is_a($this->doc, 'DOMDocument')) {
            $menu = $this->doc->saveHTML();
        }
        return $menu;
    }
    
    function getSiteTree($options=array()) {
        if (! is_a($this->doc, 'DOMDocument')) {
            $this->doc = new DOMDocument();
        }
        if ($ui_id = Filter::get($options, 'ul_id')) {
            unset($options['ui_id']);
        }
        $ul = MenuFragment::buildSiteTree(
            MenuFragment::getNavigation(), $options
        );
        if ($ui_id) {
            $ul->setAttribute('id', $ui_id);
        }
        $this->doc->appendChild($ul);
    }
    
    function getNextPage($node) {
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
    
    function getPreviousPage($node) {
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
    
    function buildSiteTree($pages, $options=array()) {
        if ($pages->length) {
        
            /*
             * Add UL classes and ID
             */
            
            $ul = $this->doc->createElement('ul');
            
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

                $li = $this->doc->createElement('li');
                
                if (Filter::get($options, $li->getNodePath())) {
                    array_push($classes, Filter::get($options, $li->getNodePath()));
                }
                
                $li->setAttribute('class', implode(" ", $classes));
                $a = $this->doc->createElement('a');
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
                
                $a->appendChild($this->doc->createTextNode($PageName));
                $li->appendChild($a);

                if ($Page->childNodes->length) {
                    $li->appendChild(
                        MenuFragment::buildSiteTree(
                            $Page->childNodes, $options
                        )
                    );
                }
                $ul->appendChild($li);
            }
        }
        return $ul;
    }
    
    function getNavigation() {
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