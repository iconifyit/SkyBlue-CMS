<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2009-06-20 21:41:00 $
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
 * @date   June 20, 2009
 */

class LinksHelper {

    function initialize() {
        if (file_exists(_SBC_APP_ . "daos/LinksDAO.php")) {
            require_once(_SBC_APP_ . "daos/LinksDAO.php");
        }
        else {
            require_once(SB_APP_MANAGERS_DIR . "links/LinksDAO.php");
        }
        if (file_exists(_SBC_APP_ . "daos/LinksgroupsDAO.php")) {
            require_once(_SBC_APP_ . "daos/LinksgroupsDAO.php");
        }
        else {
            require_once(SB_APP_MANAGERS_DIR . "links/LinksgroupsDAO.php");
        }
        require_once(SB_APP_MANAGERS_DIR . "links/Link.php");
        require_once(SB_APP_MANAGERS_DIR . "links/Linksgroup.php");
        require_once(SB_APP_MANAGERS_DIR . "links/LinksController.php");
    }

    function getLinkDAO($refresh=false) {
        static $Dao;
        if (! is_object($Dao) || $refresh) {
            if (! class_exists('LinksDAO')) {
                LinksHelper::initialize();
            }
            $Dao = new LinksDAO();
        }
        return $Dao;
    }
    
    function getLinksgroupDAO($refresh=false) {
        static $Dao;
        if (! is_object($Dao) || $refresh) {
            if (! class_exists('LinksgroupsDAO')) {
                LinksHelper::initialize();
            }
            $Dao = new LinksgroupsDAO();
        }
        return $Dao;
    }

    function getGroups(&$DAO, $refresh=false) {
        global $Core;
        static $groups;
        if (!is_array($groups) || $refresh) {
            $groups = array();
            $items = $DAO->index();
            foreach ($items as $item) {
                array_push(
                    $groups,
                    Utils::bindTransferObject(
                        $item, new Linksgroup
                    )
                );
            }
        }
        return $groups;
    }

    function hasGroup($Item, $Group) {
        return in_array($Group->getId(), $Item->getGroups());
    }
    
    function getRelationships($selected="") {
        $values = array(
            'alternate',
            'stylesheet',
            'start',
            'next',
            'prev',
            'contents',
            'index',
            'glossary',
            'copyright',
            'chapter',
            'section',
            'subsection',
            'appendix',
            'help',
            'bookmark',
            'nofollow',
            'licence',      
            'tag',      
            'friend'
        );
        $options = array();
        $count = count($values);
        for ($i=0; $i<$count; $i++) {
            $attrs = array('value' => $values[$i]);
            if (strcasecmp($values[$i], $selected) == 0) {
                $attrs['selected'] = 'selected';
            }
            array_push(
                $options,
                HtmlUtils::tag(
                    'option',
                    $attrs,
                    $values[$i]
                )
            );
        }
        return implode("\n", $options);
    }
    
}