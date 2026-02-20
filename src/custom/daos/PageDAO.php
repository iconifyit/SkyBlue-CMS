<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2010-07-08 21:30:00 $
 * @package      SkyBlue CMS
 * @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license      GNU/GPL, see COPYING.txt
 * SkyBlue CMS is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

/**
 * @author Scott Lewis
 * @date   June 22, 2009
 */

class PageDAO extends SqliteDAO {

    function __construct() {
        parent::__construct(array(
            'type'       => 'page',
            'objtype'    => 'page',
            'bean_class' => 'Page'
        ));
    }
    
    function setDefaultPage($Page) {
        if (! $Page) {
            die("Error: page not found in " . __METHOD__);
        }
        try {
            $result = $this->query(
                "UPDATE {$this->getBeanClass()} SET isdefault = 0 WHERE isdefault <> 0"
            );
            if (! $result) {
                die("Error [a]: $query_error in " . __METHOD__);
            }
            $result = $this->doQuery(
                "UPDATE {$this->getBeanClass()} SET isdefault = 1 WHERE id = {$Page->getId()}"
            );
            if (! $result) {
                die("Error [b]: $query_error in " . __METHOD__);
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    function getItem($id) {
        $Page = null;
        if ($Page = parent::getItem($id)) {
            if ($Page->getStory_content()) {
                $Page->setStory_content(base64_decode($Page->getStory_content()));
            }
            $Page->setMeta($this->getMetaData($Page));
        }
        return $Page;
    }
    
    /**
     * Gets the Meta Data for a page
     * @param reference  A reference to a Page object
     * @return array  An array of meta data key=>value pairs
     */
    
    function getMetaData(&$Page) {
        $PageHelper = Singleton::getInstance('PageHelper');
        return $PageHelper->getMeta($Page);
    }
    
    function publish($Page) {
        return parent::query(
            "UPDATE Page SET published = '{$Page->getPublished()}' WHERE id = {$Page->getId()}"
        );
    }
    
    function updateShowInNavigation($Page) {
        return parent::query(
            "UPDATE Page SET show_in_navigation = {$Page->getShow_in_navigation()} WHERE id = {$Page->getId()}"
        );
    }
    
    function getValue($table, $column, $key, $value) {
        return parent::getValue($table, $column, $key, $value);
    }
    
    function dump($doc) {
        ob_start();
        print $doc->saveHTML();
        $buffer = ob_get_contents();
        ob_end_clean();
        die($buffer);
    }
    
    function insert($Page) {
        $result = false;
        if ($Page->getStory_content()) {
            $Page->setStory_content(base64_encode($Page->getStory_content()));
        }
        if (parent::insert($Page)) {
            $lastInsertId = PDO::lastInsertId();
            $doc  = PageHelper::parseStructureXml();
            $node = $doc->createElement("page");
            $node->setAttribute('id',        $lastInsertId);
            $node->setAttribute('name',      $Page->getName());
            $node->setAttribute('published', $Page->getPublished());
            $node->setAttribute('url',       $Page->getPermalink());
            $node->setAttribute('show_in_navigation', $Page->getShow_in_navigation());
            $newnode = $doc->getElementsByTagName('site')->item(0)->appendChild($node);
            if ($newnode instanceof DOMNode) {
                $result = PageHelper::saveStructureXml($doc->saveXml());
            }
        }
        return $result;
    }
    
    function update($Page) {
        $result = false;
        if ($Page->getStory_content()) {
            $Page->setStory_content(base64_encode($Page->getStory_content()));
        }
        if (parent::update($Page)) {
            $doc  = PageHelper::parseStructureXml();
            // PHP 8.2: Check if document was loaded successfully
            if (!($doc instanceof DOMDocument)) {
                error_log("[PageDAO] parseStructureXml() did not return a valid DOMDocument");
                return false;
            }
            $node = PageHelper::getElementById($doc, $Page->getId());
            if (! ($node instanceof DOMNode)) {
                // Page node doesn't exist in structure XML, need to add it
                $newNode = $doc->createElement("page");
                $newNode->setAttribute('id',        $Page->getId());
                $newNode->setAttribute('name',      $Page->getName());
                $newNode->setAttribute('published', $Page->getPublished());
                $newNode->setAttribute('url',       $Page->getPermalink());
                $newNode->setAttribute('show_in_navigation', $Page->getShow_in_navigation());

                $siteNode = $doc->getElementsByTagName('site')->item(0);
                if ($siteNode instanceof DOMNode) {
                    $siteNode->appendChild($newNode);
                    $result = PageHelper::saveStructureXml($doc->saveXml());
                } else {
                    error_log("[PageDAO] Could not find <site> root element in structure XML");
                    return false;
                }
            }
            else {
                $node->setAttribute('id',        $Page->getId());
                $node->setAttribute('name',      $Page->getName());
                $node->setAttribute('published', $Page->getPublished());
                $node->setAttribute('url',       $Page->getPermalink());
                $node->setAttribute('show_in_navigation', $Page->getShow_in_navigation());
                $result = PageHelper::saveStructureXml($doc->saveXml());
            }
        }
        return $result;
    }
       
    
    function copy($id) {
        $pages = $this->index();
        $Page = $this->getItem($id);
        
        $names = array();
        foreach ($pages as $p) {
            array_push($names, str_replace(" ", "", strtolower($p->getName())));
        }

        $n = 0;
        $max = 100;
        
        $pageName = $Page->getName();
        $test = str_replace(" ", "", strtolower($pageName));
        
        while (in_array($test, $names) && $n < $max) {
            $pageName = "{$Page->getName()} Copy" . ($n > 0 ? " {$n}" : "") ;
            $test = str_replace(" ", "", strtolower($pageName));
            $n++;
        }
        $Page->setName($pageName);
        $Page->setIsdefault(0);
        return $this->insert($Page);
    }
    
    function delete($id) {
        $result = false;
        if (parent::delete($id)) {
            try {
                $dom = PageHelper::parseStructureXml();
                if ($dom instanceof DOMDocument) {
                    $doc = $dom->documentElement;
                    if ($node = PageHelper::getElementById($dom, $id)) {
                        $oldnode = $doc->removeChild($node);
                        if ($oldnode instanceof DOMNode) {
                            $result = PageHelper::saveStructureXml($dom->saveXml());
                        }
                    }
                }
            }
            catch (Exception $e) {
                throw new Exception($e);
            }
        }
        return $result;
    }

    function saveStoryContent($Page) { return false; }
    
    /**
     * TODO: Not sure how to implement this yet
     */
    function reorder($id, $direction) {
        return false;
    }
}