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

class Snippet extends TransferObject {

    const TYPE_WYSIWYG = "wysiwyg";
    const TYPE_PHP     = "php";
    const TYPE_XML     = "xml";
    const TYPE_TEXT    = "text";
    const TYPE_JSON    = "json";
     
    /**
     * The type of snippet (html, css, php, etc.)
     * @var string
     */
    var $snippetType;
    
    /**
     * Wehther or not the snippet file is currently checked out
     * @var boolean
     */
    var $isCheckedOut;
    
    /**
     * Who currently has the snippet file checked out
     * @var string
     */
    var $checkedOutBy;
    
    /**
     * The date and time the snippet was checked out
     * @var string (datetime)
     */
    var $checkedOutDate;
    
    /**
     * The Snippet code
     * @var string
     */
    var $content;
    
    /**
     * The Snippet data file
     * @var string
     */
    var $datafile;
    
    var $type = "snippets";
    
    var $objtype = "snippet";
    
    /**
     * Gets the snippet type
     * @return string  The type of snippet
     */
    function getSnippetType() {
        return $this->snippetType;
    }
    
    /**
     * Sets the snippet type
     * @param string $snippetType  The type of snippet
     * @return void
     */
    function setSnippetType($snippetType) {
        $this->snippetType = $snippetType;
    }
    
    /**
     * Gets whether or not the snippet is checked out
     * @return boolean
     */
    function getIsCheckedOut() {
        return $this->isCheckedOut;
    }
    
    /**
     * Sets the flag indicating whether or not the snippet is checked out
     * @param boolean $isCheckedOut
     * @return void
     */
    function setIsCheckedOut($isCheckedOut) {
        $this->isCheckedOut = $isCheckedOut;
    }
    
    /**
     * Returns the username of whoever has the snippet checked out
     * @return string
     */
    function getCheckedOutBy() {
        return $this->checkedOutBy;
    }
    
    /**
     * Sets the username of whoever has the snippet checked out
     * @param string $checkedOutBy
     * @return void
     */
    function setCheckedOutBy($checkedOutBy) {
        $this->checkedOutBy = $checkedOutBy;
    }
    
    /**
     * Gets the timestamp for the date & time the file was checked out
     * @return string (datetime)
     */
    function getCheckedOutDate() {
        return $this->checkedOutBy;
    }
    
    /**
     * Sets the date and time the snippet was checked out
     * @param string $checkedOutBy
     * @return void
     */
    function setCheckedOutDate($checkedOutBy) {
        $this->checkedOutBy = $checkedOutBy;
    }
    
    /**
     * Gets the Snippet content (code)
     * @return string
     */
    function getContent() {
        return $this->content;
    }
    
    /**
     * Sets the Snippet content (code)
     * @param string $content  The Snippet content (code)
     * @return void
     */
    function setContent($content) {
        $this->content = $content;
    }
    
    /**
     * Returns the name of the data file
     * @return string
     */
    function getDatafile() {
        return $this->datafile;
    }
    
    /**
     * Sets the name of the Snippet data file
     * @param string $datafile
     * @return void
     */
    function setDatafile($datafile) {
        $this->datafile = $datafile;
    }
    
}