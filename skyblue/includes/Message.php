<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version     2.0 2009-04-19 10:37:00 $
 * @package        SkyBlueCanvas
 * @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license        GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General License, and as distributed it includes or
 * is derivative of works licensed under the GNU General License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

class Message {
    
    /**
     * The type (class) of the message (error, info, warning, success)
     * @var string
     */
    var $type;
    
    /**
     * The title/heading of the Message
     * @var string
     */
    var $title;
    
    /**
     * The body of the Message
     * @var string
     */
    var $message;
    
    function __construct($options=array()) {
        $this->setType(Filter::get($options, 'type'));
        $this->setTitle(Filter::get($options, 'title'));
        $this->setMessage(Filter::get($options, 'message'));
    }
    
    /**
     * Gets the Message type
     * @return string  The Message type
     */
    function getType() {
        return $this->type;
    }
    
    /**
     * Sets the type of the Message
     * @param $type  The message type
     * @return void
     */
    function setType($type) {
        switch ($type) {
            case 'error':
                $type = UI_STATE_ERROR;
                break;
            case 'success':
                $type = UI_STATE_SUCCESS;
                break;
            case 'info':
                $type = UI_STATE_INFO;
                break;
            default:
                $type = UI_STATE_HIGHLIGHT;
                break;
        }
        $this->type = $type;
    }
    
    /**
     * Gets the Message title
     * @return string  The Message title
     */
    function getTitle() {
        return $this->title;
    }
    
    /**
     * Sets the title of the Message
     * @param $title  The message title
     * @return void
     */
    function setTitle($title) {
        $this->title = $title;
    }
    
    /**
     * Gets the Message body
     * @return string  The Message body
     */
    function getMessage() {
        return $this->message;
    }
    
    /**
     * Sets the body of the Message
     * @param $message  The message body
     * @return void
     */
    function setMessage($message) {
        $this->message = $message;
    }
}