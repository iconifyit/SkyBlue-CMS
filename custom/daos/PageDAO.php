<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2010-07-08 21:30:00 $
 * @package      SkyBlueCanvas
 * @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license      GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
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
    
    function getValue($column, $id) {
        return parent::getValue(
            $this->getBeanClass(), $column, 'id', $id
        );
    }
    
    function insert($Page) {
        if ($Page->getStory_content()) {
            $Page->setStory_content(base64_encode($Page->getStory_content()));
        }
        return parent::insert($Page);
    }
    
    function update($Page) {
        if ($Page->getStory_content()) {
            $Page->setStory_content(base64_encode($Page->getStory_content()));
        }
        return parent::update($Page);
    }

    function saveStoryContent($Page) { return false; }
    
    /**
     * TODO: Not sure how to implement this yet
     */
    function reorder($id, $direction) {
        return false;
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
}