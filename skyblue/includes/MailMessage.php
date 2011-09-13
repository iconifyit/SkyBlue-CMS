<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2009-04-19 10:37:00 $
 * @package        SkyBlueCanvas
 * @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license        GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

/**
 * The PostMaster class is used for sending mail via the web server.
 * postmaster provides two methods for sending mail:
 * PHP's mail() function and the Linux sendmail process.
 *
 * The mail message is put together the same regardless of which method is
 * used. Mail messages can optionally be written to a text file for backup
 * purposes as well.
 *
 * @package SkyBlue
 */

class MailMessage {

    /**
     * The email address to send mail to
     * @var unknown_type
     */
    var $recepient;
    
    /**
     * The name of the sender
     * @var unknown_type
     */
    var $from;
    
    /**
     * The email address of the sender
     * @var unknown_type
     */
    var $replyto;
    
    /**
     * Email addresses of additional recepients (comma-delimited)
     * @var unknown_type
     */
    var $cc;
    
    /**
     * Email addresses of blind copies (comma-delimited)
     * @var unknown_type
     */
    var $bc;
    
    /**
     * Mail subject
     * @var unknown_type
     */
    var $subject;
    
    /**
     * Mail body
     * @var unknown_type
     */
    var $message;
    
    /**
     * Path to any attachments
     * @var unknown_type
     */
    var $attachment;
    
    /**
     * The complete Mail headers
     * @var unknown_type
     */
    var $headers;
    
    /**
     * Use this for monitoring success or failure
     * @var unknown_type
     */
    var $errorcode      = 1;
    
    /**
     * An array of errors - also use for form validation
     * @var unknown_type
     */
    var $errors         = array();
    
    // You should not modify these properties unless you
    // know what you are doing. Otherwise your mail may
    // not get sent by the mailer daemon.
    
    var $mimeversion     = '1.0';
    var $contenttype     = 'text/plain; charset=iso-8859-1';
    var $contenttransfer = '8bit';
    var $priority        = 1;
    var $msmailpriority  = 'High';
    var $mailer          = null;
    
    function __construct($options=array()) {
        Event::trigger('MailMessage.beforeConstruct', $this);
        if (count($options)) {
            foreach ($options as $key=>$value) {
                $method = "set" . ucwords($key);
                if (is_callable(array($this, $method))) {
                    $this->$method($value);
                }
            }
        }
        Event::trigger('MailMessage.afterConstruct', $this);
    }
    
    function getRecepient() {
        return $this->recepient;
    }
    
    function setRecepient($recepient) {
        $this->recepient = $recepient;
    }
    
    function getReplyto() {
        return $this->replyto;
    }
    
    function setReplyto($replyto) {
        $this->replyto = $replyto;
    }
    
    function getFrom() {
        return $this->from;
    }
    
    function setFrom($from) {
        $this->from = $from;
    }
    
    function getCc() {
        return $this->cc;
    }
    
    function setCc($cc) {
        $this->cc = $cc;
    }
    
    function getBc() {
        return $this->bc;
    }
    
    function setBc($bc) {
        $this->bc = $bc;
    }
    
    function getSubject() {
        return $this->subject;
    }
    
    function setSubject($subject) {
        $this->subject = $subject;
    }
    
    function getMessage() {
        return $this->message;
    }
    
    function setMessage($message) {
        $this->message = $message;
    }
    
    function getAttachment() {
        return $this->attachment;
    }
    
    function setAttachment($attachment) {
        $this->attachment = $attachment;
    }
    
    function getMimeversion() {
        return $this->mimeversion;
    }
    
    function setMimeversion($mimeversion) {
        $this->mimeversion = $mimeversion;
    }
    
    function getContenttype() {
        return $this->contenttype;
    }
    
    function setContenttype($contenttype) {
        $this->contenttype = $contenttype;
    }
    
    function getContenttransfer() {
        return $this->contenttransfer;
    }
    
    function setContenttransfer($contenttransfer) {
        $this->contenttransfer = $contenttransfer;
    }
    
    function getPriority() {
        return $this->priority;
    }
    
    function setPriority($priority) {
        $this->priority = $priority;
    }
    
    function addHeader($name, $value) {
        $this->headers .= "{$name}: {$value}\r\n";
    }
    
    function getHeaders() {
        return $this->headers;
    }
    
    function setErrorCode($errorCode) {
        $this->errorCode = $errorCode;
    }
    
    function getErrorCode() {
        return $this->errorCode;
    }
    
    function buildHeaders() {
        Event::trigger('MailMessage.beforeBuildHeaders', $this);
        $this->addHeader("MIME-Version", $this->getMimeversion());
        $this->addHeader("Content-type", $this->getContenttype());
        $this->addHeader("Content-Transfer-Encoding", $this->getContenttransfer());
        $this->addHeader("From", $this->getReplyto());
        $this->addHeader("Date", date("r"));
        $this->addHeader("Reply-To", $this->getReplyto());
        $this->addHeader("X-Priority", $this->getPriority());
        $this->addHeader("X-Mailer", "PHP/" . phpversion());
        Event::trigger('MailMessage.afterBuildHeaders', $this);
    }
    
    function send() {
        Event::trigger('MailMessage.beforeSend', $this);
        $this->buildHeaders();
        if (!empty($this->replyto) && Utils::callable('ini_set')) {
            ini_set('sendmail_from', $this->replyto);
        }
        if (mail($this->getRecepient(), $this->getSubject(), $this->getMessage(), $this->getHeaders())) {
            $this->setErrorcode(1);
        }
        else {
            $this->setErrorcode(-1);
        }
        Event::trigger('MailMessage.afterSend', $this);
        return $this->getErrorCode() === 1;
    }
}