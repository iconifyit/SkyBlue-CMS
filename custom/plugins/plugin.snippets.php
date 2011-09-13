<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version        2.0 2010-07-08 21:30:00 $
 * @package        SkyBlueCanvas
 * @copyright      Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license        GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

define('REGEX_SNIPPET_INLINE',  "/({snippet\(([^}]*)\)})/i");
define('REGEX_SNIPPET_COMMENT', "/(<!--#snippet\((.*)\)-->)/i");
define('SNIPPETS_DIR',          SB_SITE_DATA_DIR . "snippets/");

global $Core;

Event::register('OnRenderPage', 'doSnippets');

function doSnippets($html) {
    $Snippet = SnippetPlugin::getInstance($html);
    Event::register('snippet.getContent', 'test_snippet_event');
    $Snippet->execute();
    return $Snippet->getHtml();
}

function test_snippet_event($html) {
    return $html;
}

class SnippetPlugin extends SkyBluePlugin {

    var $html;

    function getInstance($html) {
        static $instance;
        if (!is_object($instance)) {
            $instance = new SnippetPlugin;
        }
        $instance->setHtml($html);
        return $instance;
    }
    
    function execute() {
        $this->setHtml(
            $this->parseCommentTokens(
                $this->parseInlineTokens($this->html)
            )
        );
    }
    
    function getHtml() {
        return $this->html;
    }
    
    function setHtml($html) {
        $this->html = $html;
    }
    
    function parseCommentTokens($html) {
        return $this->_parseTokens(
            $html,
            REGEX_SNIPPET_COMMENT,
            "<!--#snippet(_QUERY_)-->"
        );
    }
    
    function parseInlineTokens($html) {
        return $this->_parseTokens(
            $html,
            REGEX_SNIPPET_INLINE,
            "{snippet(_QUERY_)}"
        );
    }
    
    function _parseTokens($html, $token, $replace) {
        global $Core;
        preg_match_all($token, $html, $tokens);
        if (count($tokens) < 3) return $html;
        $tokens = @$tokens[2];
        $count = count($tokens);
        for ($i=0; $i<$count; $i++) {
            $params = $this->_parseParams($tokens[$i]);
            $name = Filter::get($params, 'base');
            $params = Filter::get($params, 'params');
            if (empty($name)) return $html;
            $snippet = Event::trigger(
                'snippet.getContent', 
                $this->_getContent($name, $params)
            );
            $html = str_replace(
                str_replace('_QUERY_', $tokens[$i], $replace),
                $snippet,
                $html
            );
        }
        return $html;
    }
    
    function getBean($name) {
        static $Bean;
        if (! is_object($Bean)) {
            if (! class_exists('SnippetsHelper')) {
                Loader::load("managers.snippets.SnippetsHelper", true, _SBC_APP_);
            }
            $Dao = SnippetsHelper::getDao(true);
            $Bean = $Dao->getByName($name);
        }
        return $Bean;
    }
    
    function _getContent($name, $data=null) {
        $content = "";
        if ($Bean = $this->getBean($name)) {
            if ($Bean->getContent()) {
                $content = base64_decode($Bean->getContent());
            }
        }
        if (! empty($data)) {
            foreach ($data as $key=>$value) {
                $content = str_replace("[[$key]]", $value, $content);
            }
        }
        return $content;
    }

}