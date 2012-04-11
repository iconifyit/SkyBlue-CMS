<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version        v2.0 2010-01-01 00:00:00 $
 * @package        SkyBlueCanvas
 * @copyright      Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license        GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

/**
 * This file contains functions for executing commonly used SkyBlueCanvas tasks 
 * Without needing to deal with creating objects.
 */

/**
 * Determines if the current page is the home page.
 * @param int  A page ID to test (optional)
 * @return bool
 */
function is_home($pageId=null) {
    if (is_null($pageId)) {
        $pageId = Filter::getNumeric($_GET, 'pid', DEFAULT_PAGE);
    }
    return $pageId === DEFAULT_PAGE;
}

/**
 * Determines if the current page is an admin page
 * @return bool
 */
function is_admin_page() {
    return (get_constant('_ADMIN_') === 1);
}

/**
 * Determines if the current page is in a list of supplied page ids
 * @param Array $pids  The array of page ids to search
 * @return boolean
 */
function in_pagelist($pids=array()) {
    return in_array(Filter::get($_GET, 'pid'), $pids);
}

/**
 * Gets the data object for the current page
 * @param boolean $refresh  Whether or not to refresh the staticallay stored Page object
 * @return object  A reference to the current Page
 */
function & current_page($refresh=false) {
    static $Page;
    if (! is_object($Page) || $refresh) {
        $PageModel = Singleton::getInstance('PageDAO');
        $Page =& $PageModel->getItem(
            Filter::getNumeric($_GET, 'pid', DEFAULT_PAGE)
        );
    }
    return $Page;
}

/**
 * Gets all the page objects
 * @param boolean $refresh  Whether or not to refresh the statically stored data
 * @return array  An array of Page objects
 */
function get_pages($refresh=false) {
    static $pages;
    if (! is_array($pages) || $refresh) {
        Loader::load("managers.page.PageHelper",   true, _SBC_SYS_);
        $pages = PageHelper::getPages($refresh);
    }
    return $pages;
}

/**
 * Gets all the Component objects
 * @param boolean $refresh  Whether or not to refresh the statically stored data
 * @return array  An array of Component objects
 */
function get_components($refresh=false) {
    global $Core;
    static $components;
    if (! is_array($components) || $refresh) {
        $tmp = array();
        $components = array();
        
        $sys = parse_xml(_SBC_SYS_ . "config/components.xml");
        $app = parse_xml(_SBC_APP_ . "config/components.xml");
        
        /**
         * Load the App-specific components first.
         */
         
        $count = count($app);
        for ($i=0; $i<$count; $i++) {
            array_push($components, $app[$i]);
            array_push($tmp, Filter::get($app[$i], 'name'));
        }
        
        /**
         * Now only load the Sys-defined components if the name is not 
         * over-loaded by an App-defined version of the component.
         */
        
        $count = count($sys);
        for ($i=0; $i<$count; $i++) {
            if (! in_array(Filter::get($sys[$i], 'name'), $tmp)) {
                array_push($components, $sys[$i]);
            }
        }
    }
    return $components;
}

function get_component_names() {
    $names = array();
    $components = get_components();
    foreach ($components as $com) {
        array_push($names, Filter::get($com, 'name'));
    }
    return $names;
}

/**
 * Returns a specific Component Object by name
 * @param String $name  The name of the component to get
 * @return Object  The component object
 */
function get_component($name) {
    $components = get_components();
    foreach ($components as $com) {
        if (strtolower($name) == strtolower(@$com->name)) {
            return $com;
        }
    }
    return null;
}

/**
 * Gets a property of the Page object
 *
 * @param string  The name of the property to get
 * @return mixed  The value of the Page property
 */
function page_info($prop) {
    return Filter::get(current_page(), $prop);
}

/**
 * Prints the Meta data for the current page object
 * @return void
 */
function the_page_meta() {
    $meta = page_info('meta');
    $tags = '';
    $count = count($meta);
    if ($count < 1) return null;
    foreach ($meta as $name => $content) {
        echo HtmlUtils::tag(
            'meta',
            array(
                'name' => $name,
                'content' => $content
            ),
            '', 0
        ) . "\n";
    }
}

/**
 * Prints the current page layout name as a CSS-safe string
 * @return void
 */
function the_page_class() {
    echo Utils::cssSafe(Filter::get(current_page(), 'pagetype'));
}

/**
 * Prints the current page name as a CSS-safe string
 * @return void
 */
function the_page_id() {
    echo Utils::cssSafe(Filter::get(current_page(), 'name'));
}

/**
 * Prints the path to the active skin
 * @return void
 */
function the_skin_path() {
    echo Config::get('site_url') . ACTIVE_SKIN_DIR;
}

/**
 * Gets the active Skin object
 * @return Skin Object
 */
function get_active_skin($refresh=false) {
    static $Skin;
    if (! is_object($Skin) ||  $refresh) {
        require_once(SB_MANAGERS_DIR . "skin/SkinHelper.php");
        $Skin = SkinHelper::getActiveSkin();
    }
    return $Skin;
}

/**
 * Gets the active Skin name
 * @return String
 */
function get_active_skin_name() {
    $Skin = get_active_skin();
    return strtolower($Skin->getName());
}

/**
 * Prints the title of the current page
 * @return void
 */
function the_page_title() {
    echo Filter::get(current_page(), 'title');
}

/**
 * Gets the referring page. If empty, uses $default
 * @param string $default    The default URL to use if referrer is empty
 * @return void
 */
function get_referrer($default="") {
    $default = trim($default) == "" ? BASE_PAGE : $default ;
    $referrer = Filter::get($_SERVER, 'HTTP_REFERER');
    return (trim($referrer) == "" ? $default : $referrer);
}

/**
 * This function is a universal accessor to the Language class. You do not need to 
 * create a new instance of the Language class. Just call the _() funciton and pass 
 * in the string you wish to translate. The function will search the $chars, $entities and 
 * $terms maps and return the translated value if found. If the string is not found in 
 * any of the maps, the original string will be returned un-changed.
 * 
 * @param string   The string identifier to be translated
 * @param string   The default string to use if no translation is found
 * @param bool     Whether or not to print the string immediately
 * @return string  The translated string
 */
function __($str, $default="", $no_print=0) {
    $LanguageHelper = Singleton::getInstance('LanguageHelper');
    $res = "";
    $res = $LanguageHelper->getTerm($str);
    if (empty($res) || $res == $str) {
        $res = empty($default) ? $str : $default ;
    }
    if ($no_print) {
        return $res;
    }
    echo $res;
}

/**
 * Translates all language tokens in a string
 *
 * @param string   The text shred to translate
 * @return string  The text shred with all language tokens translated
 */
function translate($text) {
    $LanguageHelper = Singleton::getInstance('LanguageHelper');
    return $LanguageHelper->terms($text);
}

/**
 * Check out a data object
 * @param object  A data bean
 * @return bool   Whether or not the item was checked out
 */
function checkout($Object) {
    if (! class_exists('CheckoutsDAO')) {
        # require_once(SB_MANAGERS_DIR . "checkouts/helpers/checkouts.php");
        Loader::load("managers.checkouts.CheckoutsHelper", true, _SBC_SYS_);
    }
    return CheckoutsHelper::checkOut($Object);
}

/**
 * Checks in a data object
 * @param object  A data bean
 * @return bool   Whether or not the item was checked out
 */
function checkin($Object) {
    if (! class_exists('CheckoutsDAO')) {
        # require_once(SB_MANAGERS_DIR . "checkouts/helpers/checkouts.php");
        Loader::load("managers.checkouts.CheckoutsHelper", true, _SBC_SYS_);
    }
    return CheckoutsHelper::checkIn($Object);
}

/**
 * Call the PluginParser to parse inline plugin calls
 * 
 * @param string   The page HTML
 * @return string  The updated page HTML
 */
function parse_plugins($html) {
    $PluginParser = Singleton::getInstance('PluginParser');
    $PluginParser->execute($html);
    return $PluginParser->getHtml();
}

/**
 * Call the Fragmentor class to parse inline fragment calls
 * 
 * @param string   The page HTML
 * @return string  The updated page HTML
 */
function parse_fragments($html) {
    $Fragmentor = Singleton::getInstance('FragmentorPlugin');
    $Fragmentor->execute($html);
    return $Fragmentor->getHtml();
}

/**
 * Adds a language file to parse. This function can be used to load a specific 
 * language file from a skin, plugin, fragment, etc. By default, SBC loads the global 
 * terms.ini file and the terms file for the current component (for instance, page.ini). 
 * To tell SBC to load a specific file, simply pass the name of the language file to parse.
 * You can pass a fully qualified path or just the file name for language files stored in 
 * the current language directory. Fully-qualified paths will take precendence over 
 * files stored in the current langauge directory.
 *
 * @param string  The name of the language file to add to be parsed
 * @return void
 */
function add_terms_file($languageFileName) {
    if (file_exists($languageFileName)) {
        $languageFilePath = $languageFileName;
    }
    else {
        $languageFilePath = SB_LANG_DIR . Config::get('site_lang') . "/{$languageFileName}";
    }
    $LanguageHelper = Singleton::getInstance('LanguageHelper');
    $LanguageHelper->parse_file($languageFilePath);
}

/**
 * Gets the value of a constant if it is set
 * @param string  The name of the constant
 * @param mixed   The default value to return if the constant is not set
 * @return mixed  The value of the constant or default value
 */
function get_constant($name, $default=null) {
    if (defined($name)) {
        return constant($name);
    }
    return $default;
}

/**
 * Prints any registered head elements to the browser
 * @param string  The key of elements to get (optional)
 * @return void
 */
function get_head_elements($name='') {
    
    /*
     * Get the head elements
     */
    
    $elements =& head_elements();
    
    /*
     * Reverse the array so the elements are loaded in the 
     * order in which they were added (FIFO).
     */
    
    array_reverse($elements);
    
    /*
     * Store the onload callbacks and un-set the key. 
     * We need to wrap these in a SCRIPT tag so they 
     * are treated slightly differently than most elements.
     */
    
    $onload = "";
    if (isset($elements['page.onload'])) {
        $onload = $elements['page.onload'];
        unset($elements['page.onload']);
    }
    
    /*
     * Store the style rules and un-set the key. 
     * We need to wrap these in a STYLE tag so they 
     * are treated slightly differently than most elements.
     */
    
    $styles = "";
    if (isset($elements['page.style'])) {
        $styles = $elements['page.style'];
        unset($elements['page.style']);
    }
    
    /*
     * If the call is to get a specific head element,
     * get the element and print it to the browser.
     */
    
    if (! empty($name) && isset($elements[$name])) {
        $elements = $elements[$name];
        if (is_array($elements)) {
            $count = count($elements);
            for ($i=0; $i<$count; $i++) {
                echo $elements[$i];
            }
        }
        else if (is_string($elements)) {
            echo $elements;
        }
    }
    
    /*
     * Esle if no key is specified, print all the head elements 
     * to the browser.
     */
    
    else if (empty($name)) {
        foreach ($elements as $key=>$value) {
            if (is_array($value)) {
                $count = count($value);
                for ($i=0; $i<$count; $i++) {
                    echo $value[$i];
                }
            }
            else if (is_string($value)) {
                echo $value;
            }
        }
    }
    
    /*
     * If any onload callbacks were added, wrap them in a SCRIPT
     * tag and jQuery onload equivalent function.
     */
    
    if (! empty($onload)) {
        echo HtmlUtils::tag(
            'script',
            array('type' => 'text/javascript'),
            "$(function() {" . $onload . "});"
        );
    }
    
    /*
     * If any styles were added, wrap them in STYLE tags
     * and print them to the browser.
     */
    
    if (! empty($styles)) {
        echo HtmlUtils::tag(
            'style',
            array('type'=>'text/css'),
            $styles
        );
    }
}

function get_head_elements_str() {
    ob_start();
    get_head_elements();
    $buffer = ob_get_contents();
    ob_end_clean();
    return $buffer;
}

/**
 * Includes a header element (PHP file)
 * @param string   The path to the file to include
 * @param boolean  Whether or not the file is required
 * @return void
 */
function include_head_element($name, $path='') {
    return FileSystem::buffer(
        get_system_head_element($name)
    );
}

/**
 * Adds a pre-defined (by the system) header element.
 * @param string  The pre-defined head element to add
 * @return void
 */
function get_system_head_element($name) {
    $assets = parse_ini_file(SB_UI_INI);
    if (! empty($assets) && isset($assets[$name])) {
        return $assets[$name];
    }
    return $name;
}

/**
 * Adds a header element to be loaded when the page is rendered
 * @param string  The element name
 * @param string  The element value
 * @return void
 */
function add_head_element($name, $value='', $type='link') {
    if ($type == 'link') {
        $value = trim($value) == '' ? get_system_head_element($name) : $value ;
    }
    else if ($type == 'include') {
        $value = include_head_element($name);
    }
    if (! empty($value)) {
        $elements =& head_elements();
        if ($name == 'page.onload' || $name == 'page.style') {
            if (! isset($elements[$name])) {
                $elements[$name] = "";
            }
            $elements[$name] .= "$value\n";
        }
        else {
            $elements[$name] = $value;
        }
    }
}

/**
 * Adds a stylesheet head element
 * @param $name  The name of the head element
 * @param $href  The path to the stylesheet
 * @return void
 */
function add_stylesheet($name, $href) {
    add_head_element(
        $name,
        "\n<link rel=\"stylesheet\" type=\"text/css\" href=\"{$href}\" />\n"
    );
}

/**
 * Adds a script head element
 * @param $name  The name of the head element
 * @param $src   The path to the script
 * @return void
 */
function add_script($name, $src) {
    add_head_element(
        $name,
        "\n<script type=\"text/javascript\" src=\"{$src}\"></script>\n"
    );
}

/**
 * Adds an in-line javascript shred
 * @param $name  The name of the head element
 * @param $src   The script code
 * @return void
 */
function add_scriptlet($name, $code) {
    add_head_element(
        $name,
        "\n<script type=\"text/javascript\">{$code}</script>\n"
    );
}

/**
 * Adds an in-line javascript shred to execute on pageload
 * @param $name  The name of the head element
 * @param $src   The script code
 * @return void
 */
function add_onload_scriptlet($name, $code) {
    add_head_element(
        $name,
        "\n<script type=\"text/javascript\">$(function(){try{" . $code . "}catch(e){/*Exit Gracefully*/}});</script>\n"
    );
}

/**
 * Removes a header element that was previously set. You can use this to remove any 
 * head elements set by your or another script that may need to be over-loaded 
 * under certain circumstances.
 * @param string  The element name
 * @return void
 */
function remove_head_element($name) {
    $elements =& head_elements();
    if (isset($elements[$name])) {
        unset($elements[$name]);
    }
}

/**
 * Static container for header elements
 * @param string  The name (key) within the container to retrieve
 */
function & head_elements() {
    static $elements;
    if (! is_array($elements)) {
        $elements = array();
    }
    $reference =& $elements;
    return $reference;
}

/**
 * Reads in and parses an XML file
 * @param String $file  The file path to the XML file
 * @return Array        An array of Objects
 */
function parse_xml($file) {
    global $Core;
    if (! file_exists($file)) {
        trigger_error(
            __('GLOBAL.FILE_NOT_EXISTS', "{$file} does not exist", 1),
            E_USER_ERROR
        );
    }
    else {
        return $Core->xmlHandler->ParserMain($file);
    }
}

/**
 * Converts an array of Objects to an XML document.
 * @param Array $objects   The Array of objects to convert
 * @return String          The XML document.
 */
function objects_to_xml($objects, $type='') {
    global $Core;
    return $Core->xmlHandler->ObjsToXML($objects, $type);
}

/**
 * Gets the current context (admin, front, etc.)
 */
function get_context() {
    $context = CONTEXT_UNKNOWN;
    if (constant('_ADMIN_') == 1) {
        $context = CONTEXT_ADMIN;
    }
    else {
        $context = CONTEXT_FRONT;
    }
    return $context;
}

/**
 * Echoes the WYSIWYG editor CSS selectors
 */
function editor_selectors() {
    echo "#story_content, .editor, .wysiwyg, .wymeditor";
}

/**
 * Checks if a user is logged in
 */
function is_logged_in() {
    global $Authenticate;
    $User = $Authenticate->user();
    if (! is_object($User)) return false;
    return intval($User->getId()) > 0 ? true : false ;
}

/**
 * Checks if a user is a site admin
 */

function is_admin() {
    global $Authenticate;
    return $Authenticate->IsAdmin();
}

/**
 * Gets the SkyBlue header elements
 */
function skyblue_headers() {
    fragment(array('name'=>'editor','view'=>'head','wrapper'=>'no'));
}

/**
 * Gets the SkyBlue toolbar (if logged in)
 */
function skyblue_toolbar() {
    global $Authenticate;
    $userid   = "";
    $username = "";
    $lastLogin = "";
    if (is_logged_in()) {
        $User = $Authenticate->user();
        $firstname = $User->getName();
        $username  = $User->getUsername();
        $userid    = $User->getId();
        $lastlogin = date("D M j,  Y G:i:s T", $User->getLastlogin());
    }
    fragment(array(
        'name'      => 'editor',
        'view'      => 'toolbar',
        'wrapper'   => 'no',
        'userid'    => $userid,
        'username'  => $username,
        'lastlogin' => $lastlogin
    )); 
}

/**
 * This file contains functions for executing commonly used SkyBlueCanvas tasks 
 * Without needing to deal with creating objects.
 */
function fragment($options, $data=null) {
    $queryString = "";
    if (is_array($options)) {
        $queryString = Utils::buildQuery($options);
        if (isset($options['model'])) {
            $data = $options;
            unset($options['model']);
        }
    }
    else {
        $queryString = $options;
    }
    $Fragmentor = Singleton::getInstance('FragmentorPlugin');
    echo $Fragmentor->execute_fragment($queryString, $data);
}

/**
 * Packs JavaScript source
 */
function pack_javascript($source) {
    $packer = new JavaScriptPacker($source, 'Normal', true, false);
    return $packer->pack();
}

/**
 * Outputs a JavaScript Header
 * @return void
 */
function http_header_javascript() {
    http_header("application/javascript; charset: UTF-8");
}

/**
 * Outputs an XML Header
 * @return void
 */
function http_header_xml() {
    http_header("text/xml; charset: UTF-8");
}

/**
 * Outputs an XML Header
 * @return void
 */
function http_header_css() {
    http_header("text/css; charset: UTF-8");
}

/**
 * Outputs an HTTP Header
 * @param string $contentType The HTTP content-type
 * @return void
 */
function http_header($headers=array()) {
    $contentType  = "text/html";
    $cacheControl = "no-cache, must-revalidate";
    $expires      = "Mon, 26 Jul 1997 05:00:00 GMT";
    if ($contentType = Filter::get($headers, 'content-type', false)) {
        unset($headers['content-type']);
    }
    if ($cacheControl = Filter::get($headers, 'cache-control', false)) {
        unset($headers['cache-control']);
    }
    if ($expires = Filter::get($headers, 'expires', false)) {
        unset($headers['expires']);
    }
    if (! headers_sent()) {
        header("Cache-Control: {$cacheControl}");
        header("Expires: {$expires}");
        header("content-type: {$contentType}");
        if (count($headers)) {
            try {
                foreach ($headers as $header=>$value) {
                    header("{$header}: {$value}");
                }
            }
            catch(Exception $ex) {
                throw new Exception($ex);
            }
        }
    }
}

/**
 * Strips comments and new lines from a CSS file
 */
function compress_css($source) {
    return preg_replace(
        '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', 
        str_replace(
            array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', 
            $source
        )
    );
}

/**
 * Load a DOMDocument object from a file
 */
function load_xml_file($file) {
    return load_xml_string(FileSystem::read_file($file));
}

/**
 * Load a DOMDocument object from a string
 */
function load_xml_string($string) {
    $Dom = new DOMDocument("1.0", "UTF-8");
    $Dom->loadXML($string);
    return $Dom;
}

/**
 * Creates an Ajax Upload button
 */
function upload_button($options) {
    $theId   = Filter::get($options, 'id');
    $message = Filter::get($options, 'message');
    $action  = Filter::get($options, 'action');
    $button  = Filter::get($options, 'button_text');
?>
<span id="status_<?php echo $theId; ?>"></span>
<div id="<?php echo $theId; ?>" class="uploadButton"><span><?php echo $button; ?><span></div>
<ul class="file_list" id="files_<?php echo $theId; ?>"></ul>
<script type="text/javascript">
sbc.enqueue("uploadButtons", {
    id:      "<?php echo $theId; ?>",
    message: "<?php echo $message; ?>",
    action:  "<?php echo $action; ?>"
});
</script>
<?php } ?>