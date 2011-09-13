<?php defined('SKYBLUE') or die(basename(__FILE__));

/**
 * @version        RC 1.0.3.2 2008-04-24 15:03:43 $
 * @package        SkyBlueCanvas
 * @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license        GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */


global $Core;

Event::register('OnRenderPage', 'plgBBCoder');

function plgBBCoder($html) {
    $bb = new BBCoder($html);
    return $bb->get('result');
}

// Paragraph Tags

define('BB_PAR',    '[p]');
define('BB_EPAR',   '[/p]');
define('HTML_PAR',  '<p>');
define('HTML_EPAR', '</p>');

// Line Break Tags

define('BB_BR',   '[br]');
define('HTML_BR', '<br />');

define('BB_NBSP', '[nbsp]');
define('HTML_NBSP', ' ');

define('BB_LSQUO', '[lsquo]');
define('HTML_LSQUO', '&' . 'lsquo' . ';');

define('BB_RSQUO', '[rsquo]');
define('HTML_RSQUO', '&' . 'rsquo' . ';');

define('BB_LDBLQUOTE', '[ldquot]');
define('HTML_LDBLQUOTE', '&' . 'ldquo' . ';');

define('BB_RDBLQUOTE', '[rdquot]');
define('HTML_RDBLQUOTE', '&' . 'rdquo' . ';');

define('BB_APOS', '[apos]');
define('HTML_APOS', '&' . 'apos' . ';');

define('BB_AMP', '[amp]');
define('HTML_AMP', '&' . 'amp' . ';');

define('BB_DEG', '[deg]');
define('HTML_DEG', '&' . 'deg' . ';');

// Bold Tags

define('BB_BOLD', '[b]');
define('BB_EBOLD', '[/b]');
define('BB_STRONG', '[strong]');
define('BB_ESTRONG', '[/strong]');
define('HTML_STRONG', '<strong>');
define('HTML_ESTRONG', '</strong>');

// Emphasis Tags

define('BB_ITALIC', '[em]');
define('BB_EITALIC', '[/em]');
define('BB_EM', '[em]');
define('BB_EEM', '[/em]');
define('HTML_EM', '<em>');
define('HTML_EEM', '</em>');

// SuperScript Tags

define('BB_QUOTE', '[quote]');
define('BB_EQUOTE', '[/quote]');
define('HTML_QUOTE', '<quote>');
define('HTML_EQUOTE', '</quote>');


// SuperScript Tags

define('BB_SUP', '[sup]');
define('BB_ESUP', '[/sup]');
define('HTML_SUP', '<sup>');
define('HTML_ESUP', '</sup>');

// SubScript Tags

define('BB_SUB', '[sub]');
define('BB_ESUB', '[/sub]');
define('HTML_SUB', '<sub>');
define('HTML_ESUB', '</sub>');

// BB Attribute Tags

define('BB_SCALE', 'scale');

/**
 * BBCoder is a class for add BBCode-style syntax tag support
 * to your web application. BBCoder also includes some basic
 * HTML-Tidying functions such as replacing ampersands with
 * the HTML & entity and making sure that image paths are
 * relative to the root directory of your site.
 *
 * To Use This Class:
 *
 * Include this file in your PHP script. BBCoder is designed
 * to prevent user agents from accessing this file directly.
 * You will need to define a constant at the top of your script
 * to indicate that this file is being included using the syntax below.
 *
 * define('SKYBLUE', 1);
 *
 * BBCoder uses a Singleton for class instance control.
 * To create an object of this class use the syntax below.
 * 
 * <pre>
 * $BBCoder = BBCoder::getInstance();
 * </pre>
 *
 * To convert the BBCode-style tags use the syntax below.
 *
 * <pre>
 * $BBCoder->convertBBCode($html);
 * </pre>
 *
 * Supported Tags (see individual functions for proper syntax):
 *
 * <pre>
 * [p][/p] ................. Paragraph tags
 * [b][/b] ................. Bold tags
 * [strong][/strong] ....... Strong tags
 * [i][/i] ................. Italics tags
 * [em][/em] ............... Emphasis tags
 * [quote][/quote] ......... Quote tags
 * [h1-6][/h1-6] ........... Heading tags
 * [table][/table] ......... Table tags
 * [sup][/sup] ............. Superscript tags
 * [sub][/sub] ............. Subscript tags
 * [url:www.foobar.com] .... URL links
 * [email:foo@bar.com] ..... Email address links
 * [img:image.jpg] ......... Image tags
 * [br] .................... Line breaks
 * [list:o|u] .............. Ordered or unordered lists
 * </pre>
 *
 * @package SkyBlue
 * @subpackage Plugins
 */

class BBCoder extends SkyBlueObject
{
    var $result;
    
    /**
    * You can apply all BBCode-style tags by calling this function.
    * You need only pass your HTML for pre-processing before sending
    * it on to the user agent.
    *
    * @access public
    * @param string $shred the text blob containing the BBCode-style tags.
    * @return string
    */

    function __construct($shred) {
    
        $shred = $this->BBUrl($shred);
        $shred = $this->BBEmail($shred);
        $shred = $this->BBLineBreaks($shred);
        $shred = $this->BBNBSpace($shred);
        $shred = $this->BBApos($shred);
        $shred = $this->BBAmp($shred);
        $shred = $this->BBDBLQuote($shred);
        $shred = $this->BBSingleQuote($shred);
        $shred = $this->BBPre($shred);
        $shred = $this->BBParagraph($shred);
        $shred = $this->BBImage($shred);
        $shred = $this->BBSuperScript($shred);
        $shred = $this->BBSubScript($shred);
        $shred = $this->BBStrong($shred);
        $shred = $this->BBEmphasis($shred);
        $shred = $this->BBQuote($shred);
        $shred = $this->BBTable($shred);
        $shred = $this->BBHeadings($shred);
        $shred = $this->BBList($shred);
        $shred = $this->BBTM($shred);
        $shred = $this->BBCopyright($shred);
        $shred = $this->BBRegTradeMark($shred);
        $shred = $this->BBLTGT($shred);
        $shred = $this->BBDeg($shred);
        $shred = $this->BBDiv($shred);
        
        // HTML Tidy-ing functions
        
        $shred = $this->encodeAmpersands($shred);
        // $shred = $this->RelativeImagePaths($shred);
        
        $this->result = $shred;
    }

    function BBDeg($shred) {
        return str_replace(BB_DEG, HTML_DEG, $shred);
    }

    function BBLTGT($shred) {
        return str_replace(array('[lt]','[gt]'),array('<','>'),$shred);
    }
    
    function BBTM($shred) {
        return str_replace(
            array('[TM]','[tm]','(tm)','(TM)'),'™',$shred);
    }
    
    function BBCopyright($shred) {
        return str_replace(
            array('[C]','[c]'),'©',$shred);
    }
    
    function BBRegTradeMark($shred) {
        return str_replace(
            array('[R]','[r]'),'®',$shred);
    }
    
    function BBDiv($shred) {
        $regex = "/\[div(.*)\](.*?)\[\/div\]?/si";
        if (preg_match_all($regex, $shred, $matches)) {
            for ($i=0; $i<count($matches); $i++) {
                $bit = preg_replace($regex, "<div$1>$2</div>", $matches[$i]);
                $shred = str_replace($matches[$i], $bit, $shred);
            }
        }
        return $shred;
    }

    function BBPre($shred) {
        $regex = "/[pre([^]]*)](.*?)[/pre]?/si";
        if (preg_match_all($regex, $shred, $matches)) {
            for ($i=0; $i<count($matches); $i++) {
                $bit = preg_replace($regex, "<pre$1>$2</pre>", $matches[$i]);
                $shred = str_replace($matches[$i], $bit, $shred);
            }
        }
        return $shred;
    }

    
    /**
     * BBCoder allows you to code paragraphs directly into
     * your text blob.
     *
     * Syntax:
     *
     * [p]This is my paragraph text[/p]
     *
     * @access public
     * @param string $shred the text blob containing the BBParagraph tags.
     * @return string
     */
    
    function BBParagraph($shred) {
        $regex = "/[p([^]]*)](.*?)[/p]?/si";
        if (preg_match_all($regex, $shred, $matches)) {
            for ($i=0; $i<count($matches); $i++) {
                $bit = preg_replace($regex, "<p$1>$2</p>", $matches[$i]);
                $shred = str_replace($matches[$i], $bit, $shred);
            }
        }
        return $shred;
    }
    
    /**
     * BBCoder allows you to code paragraphs directly into
     * your text blob.
     *
     * Syntax:
     *
     * [p]This is my paragraph text[/p]
     *
     * @access public
     * @param string $shred the text blob containing the BBParagraph tags.
     * @return string
     */
    
    function BBQuote($shred) {
        $regex = '/[quote([^]]*)]/i';
        $this->replaceOpenTag($shred, $regex, 'quote');
        $shred = str_replace(BB_EQUOTE, HTML_EQUOTE, $shred);
        return $shred;
    }
    
    /**
     * BBCoder allows you to code headings directly into
     * your text blob.
     *
     * Syntax:
     *
     * [h1-6]This is my heading text[/h1-6]
     *
     * @access public
     * @param string $shred the text blob containing the BBParagraph tags.
     * @return string
     */

    function BBHeadings($shred) {
        $regex = "/[(h[1-6]+)([^]]*)](.*?)[/(h[1-6]+)]?/si";
        if (preg_match_all($regex, $shred, $matches)) {
            for ($i=0; $i<count($matches); $i++) {
                $bit = preg_replace($regex, "<$1$2>$3</$1>", $matches[$i]);
                $shred = str_replace($matches[$i], $bit, $shred);
            }
        }
        return $shred;
    }
    
    /**
     * BBCoder allows you make text bold (strong).
     *
     * Syntax:
     *
     * Some of this [b]text is bold[/b].
     *
     * Some of this [strong]text is strong[/strong].
     *
     * @access public
     * @param string $shred the text blob containing the BBStrong tags.
     * @return string
     */
    
    function BBStrong($shred) {
    
        $regex = "/[b([^]]*)](.*?)[/b]?/si";
        if (preg_match_all($regex, $shred, $matches)) {
            for ($i=0; $i<count($matches); $i++) {
                $bit = preg_replace($regex, "<strong$1>$2</strong>", $matches[$i]);
                $shred = str_replace($matches[$i], $bit, $shred);
            }
        }
        
        $regex = "/[strong([^]]*)](.*?)[/strong]?/si";
        if (preg_match_all($regex, $shred, $matches)) {
            for ($i=0; $i<count($matches); $i++) {
                $bit = preg_replace($regex, "<strong$1>$2</strong>", $matches[$i]);
                $shred = str_replace($matches[$i], $bit, $shred);
            }
        }
        return $shred;
    }
    
    /**
     * BBCoder allows you make text italic (emphasis).
     *
     * Syntax:
     *
     * This is some [i]emphasized[/i] text.
     *
     * OR
     *
     * This is some [em]emphasized[/em] text.
     *
     * @access public
     * @param string $shred the text blob containing the BBStrong tags.
     * @return string
     */
     
    function BBEmphasis($shred) {
    
        $regex = '/[em([^]]*)]/i';
        $this->replaceOpenTag($shred, $regex, 'em');
        
        $regex = '/[em([^]]*)]/i';
        $this->replaceOpenTag($shred, $regex, 'em');

        $shred = str_replace(BB_EITALIC, HTML_EEM, $shred);
        $shred = str_replace(BB_EEM, HTML_EEM, $shred);
        return $shred;
    }
    
    /**
     * BBCoder allows you to code superscripts directly into
     * your text blob.
     *
     * Syntax:
     *
     * E=MC[sup]2[/sup] was Einstein's brain child.
     *
     * @access public
     * @param string $shred the text blob containing the BBSuperScript tags.
     * @return string
     */
    
    function BBSuperScript($shred) {
        $this->replaceOpenTag($shred, '/[sup([^]]*)]/i', 'sup');
        $shred = str_replace(BB_ESUP, HTML_ESUP, $shred);
        return $shred;
    }
    
    /**
     * BBCoder allows you to code subscripts directly into
     * your text blob.
     *
     * Syntax:
     *
     * H[sub]2[/sub]O is good for you.
     *
     * @access public
     * @param string $shred the text blob containing the BBSubScript tags.
     * @return string
     */
    
    function BBSubScript($shred) {
        $this->replaceOpenTag($shred, '/[sub([^]]*)]/i', 'sub');
        $shred = str_replace(BB_ESUB, HTML_ESUB, $shred);
        return $shred;
    }
    
    /**
     * BBCoder allows you to code images directly into your text
     * blob. You can specify ID and CLASS attributes and even scale
     * the image on the fly.
     *
     * Syntax:
     *
     * [img src="image.jpg" id=myid class=myclass scale=".5" /]
     *
     * Note that attributes are separated by spaces and do not
     * include quote marks.
     *
     * @access public
     * @param string $shred the text blob containing the BBImage tag(s).
     * @return string
     */
    
    function BBImage($shred) {
        $shred = str_replace('', NULL, $shred);
        if (strpos($shred, '[img ') !== FALSE) {
            $addrs = array();
            $texts = array();
            
            if (strpos($shred, 'scale=') !== FALSE) {
                $regex = '/scale="([^"]*)"/i';
                preg_match_all($regex, $shred, $matches);
                $scale = $matches[1][0];
            }
            if (isset($matches)) {
                $shred = str_replace($matches[0][0], NULL, $shred);
            }
            
            $regex = '/[img(.*)/]/i';
            preg_match_all($regex, $shred, $matches);
            $replace = "<img$1width='{w}' height='{h}' />";
            $shred = preg_replace($regex, $replace, $shred);

            $src = $matches[1][0];
            $regex = '/src="([^"]*)"/i';
            preg_match_all($regex, $src, $matches);
            
            $src = str_replace(PATH_PREFIX,null,$matches[1][0]); // $matches[1][0];
            $shred = str_replace($matches[1][0],$src,$shred);
            
            list($w, $h) = array(20, 20);
            if (file_exists($src)) {
                list($w, $h) = $this->ImageDims($src);
                if (!empty($scale) && $scale > 0.09) {
                    $w *= $scale;
                    $h *= $scale;
                }
            }
            
            $shred = str_replace('{w}', $w, $shred);
            $shred = str_replace('{h}', $h, $shred);
        }
        return $shred;
    }
    
    /**
     * BBCoder allows you to code tables directly into your text
     * blob. You can specify attributes for the top-level table
     * element.
     *
     * Syntax:
     *
     * <pre>
     * [table cols=3 cellpadding=0 cellspacing=0 width=100]
     * @! + X + Y + Z
     * @ + a1 + a2
     * @ + b1 + b2 + b3
     * [/table]
     *
     * @! signifies a table heading row
     * @ signifies a table row
     * + signifies a table cell
     * </pre>
     *
     * @access public
     * @param string $shred the text blob containing the BBTable tag(s).
     * @return string
     */
    
    function BBTable($shred) {
        global $Core;
        if (strpos($shred, '[table') !== FALSE) {
        
            // [table cols=3 cellpadding=0 cellspacing=0 width=480]
        
            $pattern = "/[table([^]]*)](.*?)[/table]?/si";
            preg_match_all($pattern, $shred, $matches);
            
            if (count($matches)) {
                for ($i=0; $i<count($matches[1]); $i++) {
                    $match = $matches[1][$i];
                    list($foo, $attrs) = $this->getAttributes($match);
    
                    $html  = '<table';
                    if (count($attrs)) {
                        foreach($attrs as $k=>$v) {
                            if ($k != 'cols') {
                                $html .= ' '.$k.'="'.$v.'"';
                            } 
                            else {
                                $cols = $v;
                            }
                        }
                    }
                    $html .= '>'."rn";
                    
                    $tableData = NULL;
                    $cols = isset($cols) ? $cols : 1 ;
                    if (isset($matches[2][0])) {
                        $tableData = $matches[2][0];
                        if (preg_match_all('/@([^@[]*)/i', $tableData, $rows)) {
                            $rows = $rows[1];
                            for ($r=0; $r<count($rows); $r++) {
                                if (!empty($rows[$r])) {
                                    $html .= str_repeat(' ', 4).'<tr>'."rn";
                                    if (preg_match_all('/+([^+[]*)/i', $rows[$r], $cells)) {
                                        $cells = $cells[1];
                                        for ($j=0; $j<$cols; $j++) {
                                            $tag   = $rows[$r]{0} == '!' ? 'th' : 'td' ;
                                            $html .= str_repeat(' ', 8).'<'.$tag.' align="left">';
                                            $html .= isset($cells[$j]) ? trim($cells[$j]) : ' ' ;
                                            $html .= '</'.$tag.'>'."rn";
                                        }
                                    }
                                    $html .= str_repeat(' ', 4).'</tr>'."rn";
                                }
                            }
                        }
                    }
                    $html .= '</table>'."rn";
                    $shred = str_replace($matches[0][$i], $html, $shred);
                }
            }
        }
        return $shred;
    }
    
    /**
     * BBCoder allows you to code maito: (email) links directly
     * into your text blob. BBEmail tags support 2 different syntax
     * options so that the display text does not have to be the email
     * address itself.
     *
     * Syntax:
     *
     * [email:scott@bright-crayon.com]
     *
     * OR
     * 
     * [email:scott@bright-crayon.com]Scott Lewis[/email]
     *
     * @access public
     * @param string $shred the text blob containing the BBEmail tag(s).
     * @return string
     */
    
    function BBEmail($shred) {
        $shred = str_replace('', NULL, $shred);
        if (strpos($shred, '[email ') !== FALSE) {
            $addrs = array();
            $texts = array();
            
            $regex = '/[email([^]]*)]([^[]+)[/email]/i';
            $shred = preg_replace($regex, "<a$1>$2</a>", $shred);
            $shred = str_replace('', NULL, $shred);
        }
        return $shred;
    }
    
    /**
     * BBCoder allows you to code URL links directly
     * into your text blob. BBUrl tags support 2 different syntax
     * options so that the display text does not have to be the email
     * address itself.
     *
     * Syntax:
     *
     * [url:www.bright-crayon.com]
     *
     * OR
     * 
     * [url:www.bright-crayon.com]Scott Lewis[/url]
     *
     * @access public
     * @param string $shred the text blob containing the BBUrl tag(s).
     * @return string
     */

    function BBUrl($shred) {
        $shred = str_replace('', NULL, $shred);
        if (strpos($shred, '[url ') !== FALSE) {
            $regex = '/[url([^]]*)]([^[]+)[/url]/i';
            $shred = preg_replace($regex, "<a$1>$2</a>", $shred);
            $shred = str_replace('', NULL, $shred);
        }
        return $shred;
    }
    
    /**
     * BBCoder allows you to code hard breaks directly into
     * your text blob.
     *
     * Syntax:
     *
     * This is some text with a hard break [br]
     * coded inline.
     *
     * @access public
     * @param string $shred the text blob containing the BBLineBreaks tags.
     * @return string
     */
    
    function BBLineBreaks($shred) {
        return str_replace(BB_BR, HTML_BR, $shred);
    }
    
    function BBNBSpace($shred) {
        return str_replace(BB_NBSP, HTML_NBSP, $shred);
    }
    
    function BBApos($shred) {
        return str_replace(BB_APOS, HTML_APOS, $shred);
    }
    
    function BBAmp($shred) {
        return str_replace(BB_AMP, HTML_AMP, $shred);
    }
    
    function BBDBLQuote($shred) {
        return str_replace(
            array(BB_RDBLQUOTE, BB_LDBLQUOTE),
            array(HTML_RDBLQUOTE, HTML_LDBLQUOTE),
            $shred
        );
    }
    
    function BBSingleQuote($shred) {
        $shred = str_replace(BB_RSQUO, HTML_RSQUO, $shred);
        return str_replace(BB_LSQUO, HTML_LSQUO, $shred);
    }
    
    /**
     * BBCoder allows you to code ordered and unordered lists 
     * directly into your text blob.
     *
     * Syntax:
     *
     * <pre>
     * [list:u|o]
     * + Indicate list 
     * + items with
     * + plus signs
     * [/list]
     * </pre>
     *
     * [list:u] indicates an uordered list.
     * [list:o] indicates an ordered list.
     * [list] will default to an unordered list.
     *
     * @access public
     * @param string $shred the text blob containing the BBList tags.
     * @return string
     */
    
    function BBList($shred) {
        if (strpos($shred, '[list') !== FALSE) {
            $pattern = "/[list([^]]*)](.*?)[/list]?/si";
            preg_match_all($pattern, $shred, $lists);
            
            global $Core;
            
            for ($x=0; $x<count($lists[1]); $x++) {
                if (strpos($lists[0][$x], '[list:o]') !== FALSE) {
                    $listtag = 'ol';
                } 
                else {
                    $listtag = 'ul';
                }
                
                $attrs = str_replace(array(':u',':o',''), NULL, $lists[1][0]);

                $blob = $lists[2][$x];
                $shreds = explode('+', $blob);
                $list  = "rn";
                $list .= '<'.$listtag.' '.$attrs.'>'."rn";
                for ($i=0; $i<count($shreds); $i++) {
                    if (trim($shreds[$i]) != '') {
                        $list .= str_repeat(' ', 4).'<li>'.trim($shreds[$i]).'</li>'."rn";
                    }
                }
                $list .= '</'.$listtag.'>'."rn";
                $shred = str_replace($lists[0][$x], $list, $shred);
            }
            $shred = $this->BBList($shred);
        }
        return $shred;
    }
    
    /**
     * Enables tag attributes to be added to BBCode-style tags.
     *
     * @access private
     * @param string $elm the element to parse for attributes.
     * @return array
     */
    
    function getAttributes($elm) {
        if (strpos($elm, '=') === FALSE) {
            return array($elm, NULL);
        } 
        else {
            $elm = preg_replace('/s+=s+/','=', $elm);
            $elm = str_replace('', NULL, $elm);

            $regex = '/w+="[^"]+"|[^s]+/';
            preg_match_all($regex, $elm, $bits);
            $bits = $bits[0];
            
            $attrs = array();
            for ($i=0; $i<count($bits); $i++) {
                if (strpos($bits[$i], '=') !== FALSE) {
                    list($k, $v) = explode('=', $bits[$i]);
                    $attrs[trim($k)] = trim(str_replace('"', NULL, $v));
                }
            }
            
            $src = !empty($bits[0]) ? $bits[0] : NULL ;
            return array($src, $attrs);
        }
    }
    
    /**
     * Adds attributes to the HTML tag.
     *
     * @access private
     * @param string $type the type of tag to create.
     * @param array $attrs the array of attributes to add.
     * @return string
     */

    function makeTagWithAttributes($type, $attrs) {
        $tag = '<'.$type;
        if (count($attrs)) {
            foreach ($attrs as $k=>$v) {
                $tag .= ' '.$k.'="'.$v.'"';
            }
        }
        $tag .= '>';
        return $tag;
    }
    
    /**
     * Replaces the BBCode open tag with the appropriate HTML
     * open tag with or without attributes.
     *
     * @access private
     * @param reference $shred a reference to the text shred.
     * @param string $regex the regular expression for the BBCode tag.
     * @return void
     */
    
    function replaceOpenTag(&$shred, $regex, $htmlTag) {
        preg_match_all($regex, $shred, $matches);
        for ($i=0; $i<count($matches[0]); $i++) {
            $BBTag = $matches[0][$i];
            $HTag  = str_replace(array('[',']'), array('<','>'), $BBTag);
            $shred = str_replace($BBTag, $HTag, $shred);
        }
    }
    
    function _replaceOpenTag(&$shred, $regex, $htmlTag) {
        if (preg_match_all($regex, $shred, $matches)) {
            list($foo, $attrs) = $this->getAttributes($matches[1][0]);
        } 
        else {
            $attrs = array();
        }

        if (isset($matches[0][0])) {
            $shred = str_replace(
                $matches[0][0], 
                $this->makeTagWithAttributes($htmlTag, $attrs), 
                $shred
            );
        }
    }
    
    /**
     * Finds all BBCode open/close tags in the text shred.
     *
     * @access private
     * @param string $shred The text shred to search for BBCode tags.
     * @return array
     */
    
    function getAllTags($shred) {
        preg_match_all('/[[^]]+]/s', $shred, $tags);
        $this->debug($tags);
    }
    
    
    /**
     * Replaces ampersands with the HTML-safe &
     *
     * @access public
     * @param string $shred the text blob in which to replace & with &
     * @return string
     */
    
    function encodeAmpersands($shred=NULL) {
        $search  = "/&(?!(?i:#((x([dA-F]){1,5})|";
        $search .= "(104857[0-5]|10485[0-6]d|";
        $search .= "1048[0-4]dd|104[0-7]d{3}|";
        $search .= "10[0-3]d{4}|0?d{1,6}))|";
        $search .= "([A-Za-zd.]{2,31}));)/";
        return preg_replace($search, '&', $shred);
    }
    
    /**
     * When images are placed in an article using MCImageManager, the paths
     * are relative to the backend. This function removes the '../' so that
     * images are relative to the frontend.
     *
     * @access public
     * @param string $html the entire HTML output.
     * @return string
     */
        
    function RelativeImagePaths($html) {
        return str_replace('src="../', 'src="', $html);
    }
    
    // Helper Functions
    
    /**
     * Gets the width and height of an image.
     *
     * @access private
     * @param string $fp the path to the image file.
     * @return array
     */
    
    function ImageDims($fp) {
        if (!file_exists($fp) || is_dir($fp)) {
            return array(0, 0);
        }
        return getimagesize($fp);
    }

}