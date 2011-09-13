<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version        1.1 2009-04-19 10:37:00 $
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
 * The Core class contains 'core' functionality that is used throughout
 * the SkyBlue system. This class includes sub-classes to perform various
 * tasks such as XML parsing and generation, mail functions and HTML generation.
 *
 * The Core class also includes functionality to work with the file system, 
 * language support, string manipulation, array manipulation ...
 */

class Core extends Publisher {

    /**
     * Used to pass messages about the result of a user action from one
     * request (page) to another.
     *
     * @access public
     * @var    string
     */
    
    var $MSG = '...';
    
    /** 
     * @var RESET A flag to indicate whether or not to reset the
     * MSG variable. Deprecated.
     */
    
    /**
     * Helper class to handle XML-related tasks. Stores the xmlHandler
     * object as defined by {@link xml.parser.php}.
     *
     * @access public
     * @var    object
     */
    
    var $xmlHandler = null;

    /**
     * Not implemented. DataSource will eventually be the bridge between 
     * the core and the data storage abstraction classes.
     *
     * @access public
     * @var    object
     */
    
    var $datasource = null;

    /**
     * Sub-class to handle Mail-related tasks. 
     *
     * @access public
     * @var    object
     */
    
    var $postmaster = null;
    
    /**
     * Helper class for sorting arrays of objects.
     * {@link plugin.quicksort.php}
     *
     * @access public
     * @var    object
     */
    
    var $quicksort = null;

    /**
     * A unique one-time page-level identifier to make sure that
     * any form submitted to the site, was created by the site.
     *
     * @access public
     * @var    string
     */

    var $token = null;

    /**
     * Array to hold the installed language package.
     *
     * @access public
     * @var    array
     */
    
    var $terms = null;

    /**
     * The relative path of the Core depending on whether
     * the Core is being loaded by the back end or front end.
     *
     * @access public
     * @var    string
     */
        
    var $path = null;
    
    /**
     * SkyBlue can display pop-up dialogs using HTML
     * and the $_SESSION array. This is dependent on the Skin class.
     *
     * @access public
     * @var    string
     */
    
    var $dialog = null;
    
    /**
     * Allowed upload MIME types
     */
    
    var $allowed_types = array();
    
    /**
     * The events on which callbacks can be fired
     */

    var $events = array();
    
    /**
     * The session lifetime
     */
    
    var $lifetime = 360000;
    
    function __construct($options=array()) {
        $this->path = Filter::get($options, 'path', '');
        $this->lifetime = Filter::get($options, 'lifetime', 360000);
        
        if (!defined('SB_BASE_PATH')) {
            define('SB_BASE_PATH', $this->path);
        }
        
        $this->declareEvents($options); 
        $this->LoadConstants();
        # $this->IsValidSite();
        $this->LoadHelperClasses();
        $this->GetActiveSkin();
        # $this->InitSession($this->lifetime);
    }
    
    function declareEvents($config) {
        if (isset($config['events']) && count($config['events'])) {
            $events = $config['events'];
            for ($i=0; $i<count($events); $i++) {
                Event::addEvent($events[$i]);
            }
        }
    }
    
    /**
     * @deprecated Use Event class instead
     */
    
    function register($event, $callback) {
        Event::register($event, $callback);
    }
    
    /**
     * @deprecated Use Event class instead
     */
    
    function RegisterEvent($event, $callback) {
        Event::register($event, $callback);
    }
    
    /**
     * @deprecated Use Event class instead
     */
    
    function trigger($event, $html=null) {
        return Event::trigger($event, $html);
    }

    function IsValidSite() {
        if (!file_exists(SB_SITE_DATA_DIR)) {
            die("No data directory was found for this installation");
        }
    }
    
    function editor($selector, &$html, $editor) {
        $code = null;
        
        $selectors = array($selector);
        if (strpos($selector, ',') !== false) {
            $selectors = explode(',', $selector);
        }
        for ($i=0; $i<count($selectors); $i++) {
            if ($this->hasSelector(trim($selectors[$i]), $html)) {
                $code = FileSystem::buffer(SB_EDITORS_DIR . "$editor/header.php");
            }
        }
        $html = str_replace(TOKEN_EDITOR, $code, $html);
    }
    
    function hasSelector($selector, &$html) {
        $attr = 'class';
        if ($selector{0} == '#') {
            $attr = 'id';
        }
        $selector = substr($selector, 1, strlen($selector)-1);        
        preg_match_all("/$attr=\"([^\"]+)\"/i", $html, $matches);
        if (count($matches) == 2) {
            $elements = array();
            $matches = $matches[1];
            for ($i=0; $i<count($matches); $i++) {
                $bits = explode(" ", $matches[$i]);
                for ($j=0; $j<count($bits); $j++) {
                    if (trim($bits[$j]) != "") {
                        $elements[] = $bits[$j];
                    }
                }
            }
            if (in_array($selector, $elements)) {
                return true;
            }
        }
        return false;
    }
    
    function GetActiveSkin() {
        
        /**
         * get_active_skin_name is defined in includes/hooks.php
         */
        $activeskin = get_active_skin_name();

        defined('ACTIVE_SKIN_DIR') or
        define('ACTIVE_SKIN_DIR', SB_SKINS_DIR.$activeskin.'/');
        
        defined('ACTIVE_SKIN_CSS_DIR') or
        define('ACTIVE_SKIN_CSS_DIR', ACTIVE_SKIN_DIR.'css/');
        
        defined('ACTIVE_SKIN_IMG_DIR') or
        define('ACTIVE_SKIN_IMG_DIR', ACTIVE_SKIN_DIR.'images/');
        
        defined('MEDIA_CSS_DIR') or
        define('MEDIA_CSS_DIR', ACTIVE_SKIN_DIR.'media.styles/');
    }
    
    function LoadHelperClasses() {
        $this->DoInclude(SB_XML_PARSER_FILE,   __LINE__, __FUNCTION__);
        # $this->DoInclude(SB_POSTMASTER_FILE,   __LINE__, __FUNCTION__);
    
        $this->xmlHandler = Singleton::getInstance('xmlHandler');
        
        Loader::load("managers.plugin.PluginHelper", true, _SBC_SYS_);
        Loader::load("managers.page.PageHelper",   true, _SBC_SYS_);
    }
    
    function FileNotFound($missingFile, $line, $func) {
        die('<b>Fatal Error</b><br />'.
             $missingFile.' was not found.<br />'.
             '<b>Line:</b> '.$line.'<br />'.
             '<b>Func:</b> '.$func
       );
    }
    
    function DoInclude($file, $lineNumber, $funcName) {
        if (file_exists($file)) {
            require_once($file);
        } 
        else {
            $this->FileNotFound($file, $lineNumber, $funcName);
        }
    }

    function LoadConstants() {
        define('SB_CONF_DIR',     SB_BASE_PATH . (strpos(SB_BASE_PATH, '/skyblue/') === false ? 'skyblue/' : '') . 'config/');
        define('SB_CONF_SERVER',  SB_CONF_DIR . 'server.consts.php');
        define('SB_CONF_DIRS',    SB_CONF_DIR . 'dirs.consts.php');
        define('SB_CONF_FILES',   SB_CONF_DIR . 'files.consts.php');
        define('SB_CONF_STRINGS', SB_CONF_DIR . 'strings.consts.php');
        define('SB_CONF_TOKENS',  SB_CONF_DIR . 'tokens.consts.php');
        define('SB_CONF_REGEX',   SB_CONF_DIR . 'regex.consts.php');
        define('SB_CONF_HTTP',    SB_CONF_DIR . 'http.consts.php');

        $this->DoInclude(SB_CONF_SERVER,  __LINE__, __FUNCTION__);
        $this->DoInclude(SB_CONF_DIRS,    __LINE__, __FUNCTION__);
        $this->DoInclude(SB_CONF_FILES,   __LINE__, __FUNCTION__);
        $this->DoInclude(SB_CONF_STRINGS, __LINE__, __FUNCTION__);
        $this->DoInclude(SB_CONF_TOKENS,  __LINE__, __FUNCTION__);
        $this->DoInclude(SB_CONF_REGEX,   __LINE__, __FUNCTION__);
        $this->DoInclude(SB_CONF_HTTP,    __LINE__, __FUNCTION__);

        $this->DefineUserURL();
    }
    
    function DefineUserURL() {
        define('SB_MY_URL',   '');
        define('SB_RSS_FEED', SB_RSS_DIR);
        define('SB_ADMIN_URL', '');
    }
    
    /**
     * @deprecated Use the Config class instead
     */
    
    function LoadConfig() {/* Deprecated */}
    
    /**
     * Determines if the instance of SkyBlue is a new or un-configured
     * installation. If true, the "Start" screen is loaded. If no user-installed
     * skin is found this function will create a skin directory tree and install
     * the first basic structural HTML file for the skin. The directory tree
     * is installed automatically so that the system does not encounter any
     * errors arising from trying to access something that does not exist.
     * @access public
     * @return void
     */

    function CheckInstall() {
        if (!$this->isInitialized()) {
            Utils::redirect(SB_SETUP_PAGE);
        }
    }
    
    function isInitialized() {
        global $Core;
        if (!is_dir(SB_XML_DIR . "users")) {
            return false;
        }
        if (!count($Core->xmlHandler->ParserMain(SB_USERS_FILE))) {
            return false;
        }
        if (!count($Core->xmlHandler->ParserMain(SB_GROUPS_FILE))) {
            return false;
        }
        return true;
    }

    function GetUnreadMailCount() {
        $count = 0;
        if (is_dir(SB_SITE_EMAIL_DIR)) {
            $files = $this->ListFilesOptionalRecurse(SB_SITE_EMAIL_DIR, 0, array());
            for ($i=0; $i<count($files); $i++) {
                if ($files[$i] != SB_EMAIL_ERROR_LOG) {
                    $name = basename($files[$i]);
                    if ($name{0} == '~') $count++;
                }
            }
        }
        return $count;
    }
    
    /**
     * @deprecated Use the Language Helper class instead
     */
    
    function LoadLanguage() {/* Deprecated */}
    
    /**
     * Loads the plugin specified by $plugin.
     * All plugins must reside in their own directory in ~/plugins/. The plugin
     * directory name must match the plugin name exactly. The plugin
     * class file name must be named <name>.class.php.
     * @access public
     * @param string $plugin the name of the plugin to load.
     * @return void
     */
    
    function LoadPlugin($plugin) {
        
        if (!empty($plugin)) {
            $path = SB_PLUGIN_DIR.$plugin.'/'.$plugin.'.class.php';
            set_include_path(get_include_path() . PATH_SEPARATOR . $path);
            if (file_exists($path)) {
                require_once($path);
                return (new $plugin);
            } 
            else {
                trigger_error(
                    'SkyBlueCanvas Says: File '.$plugin.' does not exist. '
                    .  __FILE__ . ': on line ' . __LINE__
                );
            }
        } 
        else {
            trigger_error(
                'SkyBlueCanvas Says: No Plugin specified. '
                . __FILE__ . ': on line ' . __LINE__
            );
        }
    }

    function LoadUserPlugins() {
        $plugins = PluginHelper::getPlugins();
        $sort = Core::LoadPlugin('quicksort');
        $sort->_sort($plugins, 'order');
        $count = count($plugins);
        for ($i=0; $i<$count; $i++) {
            $isPublished = intval(Filter::get($plugins[$i], 'published', 1));
            if (file_exists(SB_USER_PLUGINS_DIR . $plugins[$i]->name) && $isPublished) {
                include_once(SB_USER_PLUGINS_DIR . $plugins[$i]->name);
            }
        }
    }
        
    function UpdateSitemap() {
        $this->LoadPlugin(SB_SITEMAPPER_CLASS);
    }
    
    /**
     * Verifies that the object being acted on has a unique ID.
     * This function should be called before any Save or Delete action, 
     * otherwise, unexpected results may occur.
     * @access public
     * @param integer $id the local ID variable.
     * @return void
     */
    
    function RequireID($id, $redirect) {
        if (empty($id)) {
            $this->ExitEvent(3, $redirect);
        }
    }
    
    /**
     * A globally available and generic Cancel function.
     * @access public
     * @param string $redirect The URL to which to redirect the user agent.
     * @return void
     */
    
    function Cancel($redirect) {
        $this->ExitEvent(2, $redirect);
    }
    
    /**
     * Peforms upload and move tasks when uploading multiple files
     * via an HTML form.
     *
     * @param array - the file input value from the HTML form.
     * @param array - the destination for the new file.
     * @param array - the file types to allow to be uploaded
     * @param int   - the maximum filesize to allow to be uploaded.
     */
    
    function UploadMultipleFiles(
        $files=array(), 
        $dest=array(), 
        $allowTypes=array(), 
        $maxsize=5000000,
        $targets=array()
    ) {
        $exitCodes = array();
        
        $count = count($files['upload']['name']);
        
        for ($i=0; $i<$count; $i++) {
            $fname = $files['upload']['name'][$i];
            $ftype = $files['upload']['type'][$i];
            
            if (!in_array($ftype, $allowTypes)) {
                list($exitCodes[], $newFiles[]) = array('-1', '');
            } 
            else {
                $dest[$i] = Utils::addTrailingSlash($dest[$i]);
                
                $file['name']     = $files['upload']['name'][$i];
                $file['type']     = $files['upload']['type'][$i];
                $file['tmp_name'] = $files['upload']['tmp_name'][$i];
                $file['error']    = $files['upload']['error'][$i];
                $file['size']     = $files['upload']['size'][$i];
                
                list($exitCodes[], $newFiles[]) = 
                  $this->UploadFile($file, $dest[$i], $allowTypes, $maxsize, $targets);
            }
        }
        return array($exitCodes, $newFiles);
    }
    
    /**
     * Nearly every class (manager) in SkyBlue uses the SetSessionMessage() function
     * to display the result of any action. This function was created to streamline
     * the code and to have a universal method for exiting an action.
     * 
     * New exitcodes (cases) can be added as long as they are 
     * added to the end of the list.
     * 
     * ExitEvent() should be called when some user action/event is completed
     * and the page needs to be redirected to some location such as the main
     * screen of a manager.
     * 
     * Examples:
     * 
     * - After saving an item
     * - After deleting an item
     * - When some required information is missing and the
     *   action/event should not be allowed to proceed.
     * - When the user Cancels an action/event
     * 
     * Cases (exit codes) 0-3 are fixed and should not be changed.
     *
     * @param int    - the exit code for the last action.
     * @param string - the URL to which to redirect the browser.
     */
    
    function ExitEvent($code, $redirect) {
        $msg = null;
        $class = null;
        $LanguageHelper = Singleton::getInstance('LanguageHelper');
        if (intval($code) > 0)  {
            $term = $LanguageHelper->getTerm("EXITCODE_{$code}");
            $msg   = Filter::get($term, 'str');
            $class = Filter::get($term, 'class');
        }
        $this->SetSessionMessage($msg, $class);
        Utils::redirect($redirect);
    }
    
    function ExitDemoEvent($redirect) {
        $this->SetSessionMessage(MSG_FEATURE_DISABLED_IN_DEMO, 'warning');
        Utils::redirect($redirect);
    }
    
    function ExitRestrictedEvent($redirect, $msg) {
        $this->SetSessionMessage($msg, 'warning');
        Utils::redirect($redirect);
    }
    
    function ExitWithWarning($msg, $redirect) {
        $this->SetSessionMessage($msg, 'warning');
        Utils::redirect($redirect);
    }
    
    function ExitWithErrorMessage($msg, $redirect) {
        $this->SetSessionMessage($msg, 'error');
        Utils::redirect($redirect);
    }
    
    function ExitWithError($msg, $redirect) {
        $Session = Singleton::getInstance('Session');
        $Session->set('LASTERROR', "<div class=\"error\"><p>$msg</p></div>\n");
        Utils::redirect($redirect);
    }
    
    function GetLastError() {
        if (trim($this->MSG) == '...') {
            $this->MSG = null;
        }
        $Session = Singleton::getInstance('Session');
        if ($Session->is_set('LASTERROR')) {
            $this->MSG .= $Session->get('LASTERROR');
            $Session->clear('LASTERROR');
        }
    }

    function DefineDefaultPage() {
        if (defined('DEFAULT_PAGE')) return null;
        $defaultPage = null;
        $pages = get_pages();
        foreach ($pages as $p) {
            if (intval($p->isdefault) == 1) {
                $defaultPage = $p->id;
            }
        }
        if (empty($defaultPage) && count($pages)) {
            $defaultPage = $pages[0]->id;
        }
        define('DEFAULT_PAGE', $defaultPage);
    }
    
    /**
     * Builds an HTML select element for the pages created in the 
     * SkyBlue Admin area.
     *
     * @param string - the currently selected option in the select list.
     */
    
    function PageSelector($selected='') {
        $pages = get_pages();
        $options = array(
            HtmlUtils::option(
                __('CORE.OPTIONS.SELECT_PAGE', ' -- Select Page -- ', 1), 
                '', ''
            )
        );
        foreach ($pages as $p) {
            array_push(
                $options, 
                HtmlUtils::option($p->title, $p->id, $p->id == $selected ? 1 : 0)
            );
        }
        return HtmlUtils::selector($options, 'page');
    }
    
    /**
     * Builds an HTML select element for the files in the specified directory.
     *
     * @param string - the path to the directory for which to create a file selector.
     * @param string - the HTML input name for the selector.
     * @param string - the currently selected option in the select list
     * @param bool   - whether or not to trim any file extensions from the 
     * files in the specified directory.
     */
    
    function BuildFileSelector($dir, $selector_name, $selected='', $trimext=0, $fullpath=1) {
        $files = $this->ListFilesOptionalRecurse($dir);
        $options = array();
        $options[] = $this->MakeOption(' -- Select A File -- ', '', 0);
        for ($i=0; $i<count($files); $i++) {
            $name = basename($files[$i]);
            $name = $trimext == 1 ? $this->TrimExtension($name) : $name ;
            if (!$fullpath) {
                $files[$i] = basename($files[$i]);
            }
            $s = $files[$i] == $selected ? 1 : 0 ;
            array_push($options, $this->MakeOption($name, $files[$i], $s));
        }
        return $this->SelectList($options, $selector_name);
    }    
    
    /**
     * Validates a piece of data for being a specified type. This function
     * is useful for validating FORM fields.
     *
     * @param string - the data string to validate.
     * @param string - the type of validation to perform.
     */
    
    function ValidateField($value, $validation) {
        switch ($validation) {
            case 'notempty':
            case 'notnull':
                return trim($value) == "" ? false : true ;
                break;
            case 'number':
                return ereg (SB_REGEX_NUM, $value);
                break;
            case 'email':
                return eregi(SB_REGEX_EMAIL, $value);
                break;
            case 'url':
                return preg_match(SB_REGEX_URL, $value);
                break;
            default:
                return true;
                break;
        }
    }
    
    /**
     * Calculates the number of subsets needed to hold the data
     * set as determined by the maximum number of items allowed
     * in a subset.
     *
     * @param array - the original data set.
     * @param int   - the number of items in the data set.
     */
    
    function CalcNumOfPages(&$items, $items_per_page) {
        return ceil(count($items) / $items_per_page);
    }
    
    /**
     * Verifies that a value is comprised only of alpha-numeric characters. 
     *
     * @param string - the text shred to check for alpha-numeracy.
     */
    
    function AlphaNumericFilter($value) {
        return ctype_alnum($value);
    }
    
    /**
     *  SBRedirect redirects the browser to a page
     *  specified by the $url argument.
     *
     * @param string - the URL to which to redirect the browser.
     */
    
    function SBRedirect($url) {
        Utils::redirect($url);
    }

    /**
     * GetLink returns the HREF URL in the proper format depending on whether or not
     * USE_SEF_URLS is set to true or not.
     *
     * @param string $title  The text for the SEF_URL
     * @param int    $PageID The id of the page to display the object.
     * @param int    $ObjID  The id of the individual object to be displayed.
     */
    
    function SafeURLFormat($str) {
        for ($i=0; $i<strlen($str); $i++) {
            if (strpos(SB_SAFE_URL_CHARS, $str{$i}) === false) {
                $str{$i} = '-';
            }
        }
        return $str;
    }
    
    /*
     * @Deprecated  Use Router::GetLink()
     * @date  04-21-2009
     */
    
    function GetLink($title, $PageID, $ObjID, $useFullURL=0) {
        if (defined('USE_SEF_URLS') && USE_SEF_URLS == 1) {
            $search = array('[amp]', '&amp;', '&');
            $replace = array('-and-');
            $title = str_replace($search, $replace, $title);
            $title = str_replace(' - ', '-', $title);
            $title = $this->SafeURLFormat($title);
            $link  = $title.'-pg-'.$PageID.(!empty($ObjID)?'-'.$ObjID:null).'.htm';
        } 
        else {
            $link = 'index.php?pid=' . $PageID . (!empty($ObjID) ? '&amp;show='.$ObjID : null);
        }
        return ($useFullURL ? FULL_URL . $link : $link );
    }
    
    /**
     * initialize a session
     * @param int - the session lifetime in seconds.
     */
    
    function InitSession($lifetime=SB_SESSION_LIFETIME) {
        $Session =& Singleton::getInstance('Session');
    }
    
    /**
     * This function is used to set a message for the result of a user action
     * in a SkyBlue Admin Component.
     *
     * @param string - the message to store in the $_SESSION for use
     * on the subsequent page.
     * @param string - the type of message. The message type should correspond
     * to a CSS selector class name.
     */
    
    function SetSessionMessage($msg, $type='confirm') {
        $class = 'generic';
        $heading = null;

        switch ($type) {
            case 'error':
                $class   = 'error';
                $heading = 'Error!';
                break;
            case 'confirm':
                $class   = 'success';
                $heading = 'Success!';
                break;
            case 'warning':
                $class   = 'warning';
                $heading = 'Warning!';
                break;
            case 'info':
                $class   = 'info';
                $heading = 'Note:';
                break;
            default:
                break;
        }
        $Session =& Singleton::getInstance('Session');
        $Session->addMessage(
            $class, 
            $heading, 
            $msg
        );
    }

    /**
     * This function clears the $_SESSION array and redirects the
     * user to the login page. This function is only used by the Admin
     * section of SkyBlue.
     */
    
    function ForceLogin() {
        $Session = Singleton::getInstance('Session');
        $Session->destroy();
        Utils::redirect(BASE_PAGE);
    }
    
    /**
     * Loads the the Admin Component.
     * 
     * @param string - the name of the component to load.
     */
    
    function LoadContent($mgr) {
        $Manager = $mgr;
        if ($this->ValidatePath(SB_MANAGERS_DIR . "{$mgr}/{$mgr}.class.php")) {
            include(SB_MANAGERS_DIR . "{$mgr}/{$mgr}.class.php");
            $component = new $Manager(new RequestObject);
        }
    }
    
    /**
     * Loads an admin module.
     */
    
    function LoadModuleAdmin2($mod) {
        if ($this->ValidatePath(SB_INC_DIR.'mod.'.$mod.'.php')) {
            include(SB_INC_DIR.'mod.'.$mod.'.php');
        }
    }
    
    
    /* =============================================  */
       // FILE, DIRECTORY & PATH HANDLING FUNCTIONS
    /* =============================================  */
        
    /**
     * Creates the data source if it does not already exist.
     *
     * @param string $src  The file path for the data source
     * @param array  $objs An array of the data objects to be saved
     * @param string $type The name of the object type being saved
     * @return int The boolean integer result of the data source creation
     */
    
    function InitDataSource($src, $objs, $type) {
        if (!file_exists($src)) {
            $xml = $this->xmlHandler->ObjsToXML($objs, $type);
            return $this->WriteFile($src, $xml, 1);
        }
        return true;
    }

    /**
     * This functio strips the file extension from a file name or path.
     * 
     * @param string - the name or path of the file.
     */
    
    function TrimExtension($file) {
        $file_arr = explode('.', $file);
        return $file_arr[0];
    }
    
    /**
     * Returns the file extension of a file (an example of bad function
     * naming. It should be 'getFileExtension()'.
     *
     * @param string - the name or path of the file.
     */
    
    function GetImageExtension($file) {
        $file_arr = explode('.', $file);
        if (count($file_arr) > 1) {
            return $file_arr[(count($file_arr)-1)];
        }
        return null;
    }
    
    /**
     * Returns the name of a sub-directory withing a path specified
     * by the position of the sub-dir within the path. The positions begin
     * at zero (same as arrays).
     *
     * @param string - the full path.
     * @param int    - the offset of the desired sub-directory within the path.
     */

    function SubDirFromPath($path, $index) {
        $dirs = explode('/', $path);
        
        if ($dirs[count($dirs)-1] == '') {
            $dirs = array_slice($dirs, 0, count($dirs)-1);
        }
        
        if ($index == 'last') {
            $index = count($dirs)-1;
            return $dirs[$index];
        }
        if ($index == 'first') {
            return $dirs[0];
        }
        $index--;
        return $dirs[$index];
    }

    /**
     * Lists all of the directories within a directory tree down to a specified
     * depth.
     *
     * @param string - the top-level directory to read.
     * @param bool   - whether or not to recurse the directory tree.
     * @param int    - the depth to which to recurse the directory tree.
     * @param array  - an array of directory paths. Typically left null when called.
     */
    
    function ListDirsToLevel($dir, $dirs=array(), $depth='', $lvl=0) {
        $lvl++;
        ini_set('max_execution_time', 10);
        if (!is_dir($dir)) {
            die ('No such directory.');
        }
        if ($root = @opendir($dir)) {
            while ($file = readdir($root)) {
                if ($file{0} == '.') {
                    continue;
                }
                if (is_dir($dir.$file)) {
                    $dirs[] = $dir.$file.'/';
                    if ($depth > 1 && $depth < $lvl) {
                        $dirs = array_merge(
                            $dirs, 
                            $this->ListDirs($dir.$file.'/', $depth, $lvl)
                        );
                    }
                } 
                else {
                    continue;
                }
            }
        }
        return $dirs;
    }
    
    /**
     * Returns the property of a parent object matching the search string.
     *
     * @param array  - the array of objects to search.
     * @param object - the object of whose parent the property is needed.
     * @param string - the name of the property to return.
     * @param string - the default value to return if no match is found.
     */
    
    function GetParentProperty($objs, $obj, $key, $default) {
        $property = $default;
        if (isset($obj->$key) && 
             $obj->$key != 'null' && 
             trim($obj->$key) != '')
        {
            $pid       = $obj->parent;
            $parentObj = $this->SelectObj($objs, $pid);
            $property  = $parentObj->$key;
        }
        return $property;
    }

    function RadioOption($name, $value, $label, $checked=0) {
        $option = '<input type="radio" name="'.$name.'" ';
        $option .= 'value="'.$value.'" ';
        if ($checked) {
            $option .= ' checked="checked" ';
        }
        $option .= '/>&nbsp;'.$label;
        return $option;
    }
    
    function RadioSelector($options) {
        if (!isset($options) || empty($options)) {
            die('Core Says: No radio options given in RadioSelector()');
        }
        return implode("\r\n", $options);
    }

    /**
     * Makes an <option> element for an HTML select list.
     *
     * @param string - the innerHTML of the option element.
     * @param string - form input value of the option element.
     * @param string - the currently selected option.
     */

    function MakeOption($title, $value, $selected='') {
        $sel = '';
        if ($selected == 1) {
            $sel = ' selected="selected"';
        }
        return str_repeat(' ', 8) .
            '<option value="'.$value.'"'.$sel.'>'.$title.'</option>';
    }
    
    function SelectorOptions($opts, $selected=null) {
        $res = array();
        foreach ($opts as $k=>$v) {
            $s = $k == $selected ? ' selected="selected"' : null ;
            array_push($res, '<option value="'.$k.'"'.$s.'>'.$v.'</option>');
        }
        return $res;
    }
    
    /**
     * Makes an <option> group element for an HTML select list.
     *
     * @param array  - an array of option elements.
     * @param string - the label for the option group.
     */
    
    function MakeOptionGroup($options, $label) {
        $html = str_repeat(' ', 4).'<optgroup label="'.$label.'">'."\r\n";
        for ($i=0; $i<count($options); $i++) {
            $html .= $options[$i];
        }
        $html .= str_repeat(' ', 4).'</optgroup>';
        return $html;
    }

    function Selector($name, $keyValuePairs, $selected=null) {
        $html = "<select name=\"$name\">\n";
        for ($i=0; $i<count($keyValuePairs); $i++) {
            $value = $keyValuePairs[$i]['value'];
            $text  = $keyValuePairs[$i]['text'];
            $s = $value == $selected ? " selected=\"selected\"" : null ;
            $html .= "<option value=\"$value\"$s>$text</option>\n";
        }
        $html .= "</select>\n";
        return $html;
    }
    
    /**
     * Makes an HTML select list
     *
     * @param array  - an array of option elements.
     * @param string - the name value of the select list.
     * @param int    - the number of visible options (size) of the select list.
     * @param string - optional JavaScript code for the select list.
     */
    
    function SelectList($arr, $name, $size=1, $js='') {
        $html = '<select name="'.$name.'" size="'.$size.'" '.$js.'>'."\r\n";
        for ($i=0; $i<count($arr); $i++) {
            $html .= $arr[$i]."\r\n";
        }
        $html .= '</select>'."\r\n";
        return $html;
    }
    
    /**
     * Creates an HTML select list with Yes and No values.
     *
     * @param string - the name value of the select list.
     * @param int    - the selected option.
     */
    
    function YesNoList($name, $selected=1) {
        $options = array();
        $selected = intval($selected);
        $s = $selected == 1 ? $s = 1 : 0 ;
        array_push($options, $this->MakeOption('Yes', 1, $s));
        $s = $selected == 0 ? 1 : 0 ;
        array_push($options, $this->MakeOption('No', 0, $s));
        $selector = $this->SelectList($options, $name);
        return $selector;
    }
    
    /**
     * Creates an HTML select list with AM and PM values.
     *
     * @param string - the name value of the select list.
     * @param string - the selected option.
     */
    
    function MeridianSelector($meridian='') {
        $opts = array();
        $s = $meridian == 'AM' ? 1 : 0 ;
        array_push($opts, $this->MakeOption('AM', 'AM', $s));
        $s = $meridian == 'PM' ? 1 : 0 ;
        array_push($opts, $this->MakeOption('PM', 'PM', $s));
        $merSelector = $this->SelectList($opts, 'meridian');
        return $merSelector;
    }
    
    /**
     * Returns an array of tokens of form {token} found within a 
     * text blob (for instance a skin).
     *
     * @param string - the text blob to search for tokens.
     */
    
    function GetTokenList($str) {
          preg_match_all(
              SB_REGEX_TOKEN, $str, $tokens, PREG_SPLIT_DELIM_CAPTURE
          );
          $result = array();
          for ($i=0; $i<count($tokens); $i++) {
              if (!in_array($tokens[$i][0], $result)) {
                  array_push($result, $tokens[$i][0]);
              }
          }
          return $result;
    }
    
    /**
     * NOTE: This function will likely be moved to the Skin class in 
     * a future version of SkyBlue.
     *
     * Returns an array of region tokens of form {region:token} found within a 
     * SkyBlue skin.
     *
     * @param string - the skin to search for tokens.
     */
    
    function GetPageRegions($str) {
          preg_match_all(
              SB_REGEX_REGION_TOKEN, $str, $tokens, PREG_SPLIT_DELIM_CAPTURE
          );
          $result = array();
          for ($i=0; $i<count($tokens); $i++) {
              if (!in_array($tokens[$i][0], $result)) {
                  array_push($result, $tokens[$i][0]);
              }
          }
          return $result;
    }
    
    function RegexGetTokens($pattern, $str) {
          preg_match_all(
              $pattern, $str, $tokens, PREG_SPLIT_DELIM_CAPTURE
          );
          $result = array();
          for ($i=0; $i<count($tokens); $i++) {
              if (!in_array($tokens[$i][0], $result)) {
                  array_push($result, $tokens[$i][0]);
              }
          }
          return $result;
    }
    
    /**
     * Creates an HTML selector for skin tokens. This function parses the skin
     * to find all the tokens, then creates teh HTML select element.
     *
     * @param string - the skin for which to create the token list.
     * @param string - the name of the HTML select element.
     * @param string - the currently selected token.
     */
        
    function MakeTokenSelector($skin, $name, $selected='') {
        $skinoutput = $this->SBReadFile($skin);
        $tokens     = $this->GetTokenList($skinoutput);
        $list       = array();
        array_push($list, $this->MakeOption(' -- Select Token -- ', ''));
        for ($i=0; $i<count($tokens); $i++) {
            $s = $selected == $tokens[$i] ? 1 : 0 ;
            array_push($list, $this->MakeOption($tokens[$i], $tokens[$i], $s));
        }
        return $this->SelectList($list, $name);
    }

    /**
     * Creates an HTML selector for ordering objects in an array. The difference
     * between this function and OrderSelector() is that this function can
     * match the omitted object on any property.
     *
     * @param array  - the array of objects for the selector.
     * @param string - the obj->property on which to match the omitted object.
     * @param string - the value to match on the omitted object.
     */
    
    function OrderSelector($objs, $key, $value) {
        $selector     = '';
        if (count($objs) > 1) {
            $options = array();
            array_push($options, $this->MakeOption(' -- Select Order -- ', '', 0));
            array_push($options, $this->MakeOption('1&nbsp;&nbsp;&nbsp;- First', 1, 0));
            $ticker = 0;
            foreach ($objs as $obj) {
                if ($value != $obj->$key) {
                    $pad = $ticker + 2 < 10 ? '&nbsp;&nbsp;' : null ;
                    array_push(
                        $options,
                        $this->MakeOption(
                            ($ticker + 2).$pad.' - '.$obj->$key, 
                            ($ticker + 2), ''
                        )
                    );
                    $ticker++;
                }
            }
            array_push(
                $options,
                $this->MakeOption(
                    ($ticker + 2) . ' - Last', ($ticker + 2), 0
                )
            );
            
            $selector  .= $this->SelectList($options, 'order');
            $selector  .= "\r\n";
        }
        else {
            $selector = NO_ITEMS_TO_ORDER_STRING;
        }
        return $selector;
    }
    
    /**
     * Creates an HTML selector for all images in a directory tree.
     *
     * @param string - the name of the HTML select element.
     * @param string - the path to the directory of images.
     * @param string - the currently selected image in the select list.
     * @param string - JavaScript code for selector behaviour.
     */
    
    function ImageSelector($selname, $subdir, $match='', $js='') {
        
        $imgs = $this->ListFiles(SB_MEDIA_DIR.$subdir);
        $options = array();
        array_push($options, $this->MakeOption(' -- Select An Image -- ', '', 0));
        for ($i=0; $i<count($imgs); $i++) {
            $name = basename($imgs[$i]);
            $path = str_replace('../', '', $imgs[$i]);
            $selected = 0;
            if ($match == $path) {
                $selected = 1;
            }
            array_push($options, $this->MakeOption($name, $path, $selected));
        }
        return $this->SelectList($options, $selname, 1, $js);
    }    

    /**
     * DEPRECATED METHODS - USE THESE AT YOUR OWN RISK
     */
    
    /**
     * @deprecated DO NOT USE
     * @date  04-21-2009
     */
    
    function OrderSelector2($objs, $key, $value) {
        return $this->OrderSelector($objs, $key, $value);
    }
    
    /**
     * @deprecated DO NOT USE
     * @date  04-21-2009
     */

    function ValidatePath($path) {
        if (file_exists($path)) {
            return true;
        } 
        return false;
    }
    
    /**
     * @deprecated Use Uplaoder class
     * @date  04-21-2009
     */
    
    function UploadFile($file, $dest, $allowtypes, $maxsize=5000000, $targets=array()) {
        $Uploader = new Uploader($allowtypes, $targets);
        return $Uploader->upload($file, $dest);
    }
    
    /**
     * @deprecated  Use Utils::getStartOfRange($offset, $item_count, $min)
     * @date  04-21-2009
     */
    
    function CalcStartOfRange($offset, $item_count, $min=0) {
        return Utils::getStartOfRange($offset, $item_count, $min);
    }
    
    /**
     * @deprecated  Use Utils::getNumInRange($num, $max, $min)
     * @date  04-21-2009
     */
    
    function GetNumInRange($num, $max, $min=1) {
        return Utils::getNumInRange($num, $max, $min);
    }
    
    /*
     * @deprecated  User Filter class
     * @date  04-21-2009
     */
    
    function GetVar($arr, $index, $default, $htmlfilter=1, $alphafilter=0) {
        return Filter::get($arr, $index, $default, $htmlfilter);
    }
    
    /**
     * @deprecated  User Filter class
     * @date  04-21-2009
     */

    function SafeValue($var) {
        return Filter::SafeValue($var);
    }
    
    /**
     * @deprecated  User Utils::stripslashes_deep($value)
     * @date  04-21-2009
     */
    
    function stripslashes_deep($value) {
       return Utils::stripslashes_deep($value);
    }
    
    /*
     * @deprecated Use FileSystem class
     * @date  04-21-2009
     */
    
    function OutputBuffer($input) {
        return FileSystem::buffer($input);
    }

    /*
     * @deprecated Use FileSystem class
     * @date  04-21-2009
     */
    
    function SBReadFile($file) {
        return FileSystem::read_file($file);
    }

    /*
     * @deprecated Use FileSystem class
     * @date  04-21-2009
     */
    
    function WriteFile($file, $str, $append=0) {
        return FileSystem::write_file($file, $str, 'w+');
    }

    /*
     * @deprecated Use FileSystem class
     * @date  04-21-2009
     */
    
    function MoveFile($from, $to) {
        FileSystem::move_file($from, $to);
    }

    /*
     * @deprecated Use FileSystem class
     * @date  04-21-2009
     */

    function ListFiles($dir, $files=array()) {
        return $this->ListFilesOptionalRecurse($dir, 1, $files);
    }
    
    /*
     * @deprecated Use FileSystem class
     * @date  04-21-2009
     */
    
    function ListFilesOptionalRecurse($dir, $recurse=1, $files=array()) {
        return FileSystem::list_files($dir, $recurse, $files);
    }

    /*
     * @deprecated Use FileSystem class
     * @date  04-21-2009
     */
    
    function ListDirs($dir, $dirs=array()) {
        return $this->ListDirsOptionalRecurse($dir, 1, $dirs);
    }
    
    /*
     * @deprecated Use FileSystem class
     * @date  04-21-2009
     */
    
    function ListDirsOptionalRecurse($dir, $recurse=1, $dirs=array()) {
        return FileSystem::list_dirs($dir, $recurse, $dirs);
    }
    
    /*
     * @deprecated Use FileSystem class
     * @date  04-21-2009
     */
    
    function InitDir($dir) {
        if (is_dir($dir)) return true;
        return FileSystem::make_dir($dir);
    }
    
    /*
     * @deprecated Use FileSystem class
     * @date  04-21-2009
     */
    
    function NukeDir($dir) {
        return FileSystem::delete_dir($dir);
    }
    
    /*
     * @deprecated Use FileSystem class
     * @date  04-21-2009
     */
    
    function ExecNukeDir($dir, $ContentsOnly=1) {
        return FileSystem::delete_dir($dir, $ContentsOnly);
    }
    
    /*
     * @deprecated Use FileSystem class
     * @date  04-21-2009
     */
    
    function CopyDir($from, $to) {
        return FileSystem::copy_dir($from, $to);
    }
    
    /*
     * @deprecated Use FileSystem class
     * @date  04-21-2009
     */
    
    function ExecCopyDir($from, $to) {
        return FileSystem::copy_dir($from, $to);
    }
    
    /**
     * @deprecated  Use Utils::unzip($file, $destination)
     * @date  04-21-2009
     */
    
    function Unzip($file, $destination) {
        return Utils::unzip($file, $destination);
    }
    
    /**
     * @deprecated  Use ArrayUtils::arrayToMatrix($arr, $colcount)
     * @date  04-21-2009
     */
    
    function ArrayToMatrix($arr, $colcount=3) {
        return ArrayUtils::arrayToMatrix($arr, $colcount);
    }
    
    /**
     * @deprecated  Use ArrayUtils::arrayToGrid($arr, $colcount)
     * @date  04-21-2009
     */
    
    function ArrayToGrid($arr, $colcount=3) {
        return ArrayUtils::arrayToGrid($arr, $colcount);
    }
    
    /**
     * @deprecated  Use Utils::countMatching($objs, $key, $value)
     * @date  04-21-2009
     */
    
    function CountObjs($objs, $key, $value) {
        return Utils::countMatching($objs, $key, $value);
    }
    
    /**
     * @deprecated  Use ArrayUtils::bindArrayToObject($arr)
     * @date  04-21-2009
     */
    
    function ArrayToObj($obj, $arr) {
        return ArrayUtils::bindArrayToObject($obj, $arr);
    }
    
    /**
     * @deprecated  Use ArrayUtils::updateObjectFromArray($obj, $arr)
     * @date  04-21-2009
     */
    
    function UpdateObjFromArray($obj, $arr) {
        return ArrayUtils::updateObjectFromArray($obj, $arr);
    }
    
    /**
     * @deprecated  Use Utils::selectObject($objs, $id)
     * @date  04-21-2009
     */
    
    function SelectObj($objs, $id) {
        return Utils::selectObject($objs, $id);
    }
    
    /**
     * @deprecated  Use Utils::findObjByKey($objs, $key, $match)
     * @date  04-21-2009
     */
    
    function SelectObjByKey($objs, $key, $match) {
        return Utils::findObjByKey($objs, $key, $match);
    }
    
    /**
     * @deprecated  Use Utils::findAllByKey($objs, $key, $match)
     * @date  04-21-2009
     */
    
    function SelectObjsByKey($objs, $key, $match) {
        return Utils::findAllByKey($objs, $key, $match);
    }
    
    /**
     * @deprecated  Use Utils::insertObjByKey($obj, $objs, $key, $match)
     * @date  04-21-2009
     */
    
    function InsertObjByKey($obj, $objs, $key, $match) {
        return Utils::insertObjByKey($obj, $objs, $key, $match);
    }
    
    /**
     * @deprecated  Use Utils::finItemInTree($objs, $key, $match, $children)
     * @date  04-21-2009
     */
    
    function SelectItemFromTree($objs, $key, $match, $children) {
        return Utils::findItemInTree($objs, $key, $match, $children);
    }
    
    /**
     * @deprecated  Use ArrayUtils::objectInArray($obj, $array, $key)
     * @date  04-21-2009
     */
    
    function ObjInArray($obj, $array, $key) {
        return ArrayUtils::objectInArray($obj, $array, $key);
    }
    
    /**
     * @deprecated  Use Utils::findAllByKey($objs, $k, $v)
     */
    
    function SelectObjs($objs, $k, $v) {
        return Utils::findAllByKey($objs, $k, $v);
    }
    
    /**
     * @deprecated  Use Utils::insertObject($objs, $obj, $match)
     * @date  04-21-2009
     */
    
    function InsertObj($objs, $obj, $match) {
        return Utils::insertObject($objs, $obj, $match);
    }
    
    /**
     * @deprecated  Use Utils::orderObjects($objs, $id, $index)
     * @date  04-21-2009
     */
    
    function OrderObjs($objs, $id, $index) {
        return Utils::orderObjects($objs, $id, $index);
    }
    
    /**
     * @deprecated  Use Utils::deleteObject($objs, $id)
     * @date  04-21-2009
     */
    
    function DeleteObj($objs, $id) {
        return Utils::deleteObject($objs, $id);
    }
    
    /**
     * @deprecated  Use ArrayUtils::stringReplace($haystack, $needle, $replace)
     * @date  04-21-2009
     */
    
    function SBStrReplace($haystack, $needle, $replace) {
        return ArrayUtils::stringReplace($haystack, $needle, $replace);
    }
    
    /**
     * @deprecated  Use ImageUtils::imageDimsToMaxDims($dims, $maxwidth, $maxheight)
     */
    
    function ImageDimsToMaxDim($dims, $maxwidth, $maxheight) {
        return ImageUtils::imageDimsToMaxDims($dims, $maxwidth, $maxheight);
    }
    
    /**
     * @deprecated  Use ImageUtils::imageDims($fp)
     * @date  04-21-2009
     */
    
    function ImageDims($fp) {
        return ImageUtils::dimensions($fp);
    }
    
    /**
     * @deprecated  Use ImageUtils::width($fp)
     * @date  04-21-2009
     */
    
    function ImageWidth($fp) {
        return ImageUtils::width($fp);
    }
    
    /**
     * @deprecated  Use ImageUtils::height($fp)
     * @date  04-21-2009
     */
    
    function ImageHeight($fp) {
        return ImageUtils::height($fp);
    }    
    
    /**
     * @deprecated  Use ArrayUtils::trimItems($arr)
     * @date  04-21-2009
     */
        
    function TrimArrayItems($arr) {
        return ArrayUtils::trimItems($arr);
    }
    
    /**
     * @deprecated  Use ArrayUtils::getItemOffset($arr, $match)
     * @date  04-21-2009
     */
    
    function OffsetInArray($arr, $match) {
        return ArrayUtils::getItemOffset($arr, $match);
    }
    
    /**
     * @deprecated  Use Utils::getNextId(&$objs)
     * @date  04-21-2009
     */

    function GetNewID(&$objs) {
        return Utils::getNextId(&$objs);
    }
    
    /**
     * @deprecated DO NOT USE
     * @date  04-21-2009
     */
    
    function GetArraySubset(&$arr, $offset, $item_count) {
        return array_slice($arr, $offset, $item_count);
    }

    /**
     * @deprecated DO NOT USE
     * @date  04-21-2009
     */
    
    function StateSelector($selected=null) {
        require_once(SB_LIB_DIR . 'lib.states.php');
        $options = HtmlUtils::tag(
            'option', array('value'=>''), ' -- Select State -- '
        );
        foreach ($states as $value=>$text) {
            if (strlen($value) > 2) continue;
            $attrs = array('value'=>strtoupper($value));
            if (strtoupper($value) == $selected) {
                $attrs['selected'] = 'selected';
            }
            $options .= HtmlUtils::tag(
                'option', $attrs, ucwords($text)
            );
        }
        return HtmlUtils::tag(
            'select',
            array('name'=>'state'),
            $options
        );
    }
    
    function Dump($obj) {
        die('<pre>' . print_r($obj, true) . '</pre>');
    }

}