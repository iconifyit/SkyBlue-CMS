<?php defined('SKYBLUE') or die('Bad File Request');

/**
 * @version      v 1.2 2009-04-14 23:50:00 $
 * @package      SkyBlue CMS
 * @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license      GNU/GPL, see COPYING.txt
 * SkyBlue CMS is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

class Config {

    static $config = array();
    
    /**
     * Loads the config XML indicated in $file
     * @return boolean  Whether or not the file was loaded.
     */
    
    public static function load() {
        Loader::load("managers.configuration.ConfigurationHelper", true, _SBC_SYS_);
        $config = ConfigurationHelper::getConfiguration();
        if (count($config)) {
            $config = $config[0];
            foreach ($config as $key=>$value) {
                Config::set($key, $value);
            }
            Config::setProtocol();
            Config::setBaseUri();
            Config::setSefUrls();
            Config::setFullUrl();
            Config::setMimeTypes();
            Config::setSiteName();
            Config::setRssDir();
            Config::setSiteSlogan();
            # Config::setLogFile();
            return true;
        }
        return false;
    }
    
    /**
     * gets the private config key=>value pair array
     * @return array  The key=>value config values
     */
    
    public static function getConfig() {
        return Config::$config;
    }
    
    /**
     * Sets the site tag line (slogan) constant (legacy support)
     * @return void
     */
    
    public static function setSiteSlogan() {
        define('SB_SITE_SLOGAN', Config::get('site_slogan'));
    }
    
    /**
     * Sets the site name constant (legacy support)
     * @return void
     */
    
    public static function setSiteName() {
        define('SB_SITE_NAME', Config::get('site_name'));
    }
    
    /**
     * Sets the site base URL constant (legacy support)
     * @return void
     */
    
    public static function setBaseUri() {
        Config::set('base_uri', SB_MY_URL);
        define('BASE_URI', SB_MY_URL);
    }
    
    /**
     * Sets the SEF URLs constant (legacy support)
     * @return void
     */
    
    public static function setSefUrls() {
        if (!defined('USE_SEF_URLS')) {
            define('USE_SEF_URLS', Config::get('sef_urls', 0));
        }
    }
    
    /**
     * Sets the RSS directory constant (legacy support)
     * @return void
     */
    
    public static function setRssDir() {
        Config::set('rss_dir', SB_RSS_DIR);
    }
    
    /**
     * Sets the full URL constant (legacy support)
     * Checks for config file override first, otherwise auto-detects from request.
     * @return void
     */

    public static function setFullUrl() {
        $url = null;

        // Check for config file override
        $siteConfigFile = _SBC_APP_ . 'config/site.php';
        if (file_exists($siteConfigFile)) {
            $siteConfig = include($siteConfigFile);
            if (isset($siteConfig['site_url']) && !empty($siteConfig['site_url'])) {
                $url = $siteConfig['site_url'];
            }
        }

        // Auto-detect from request if not configured
        if (empty($url)) {
            $protocol = Config::get('protocol', 'http');
            $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
            $url = $protocol . '://' . $host . '/';
        }

        // Ensure trailing slash
        if ($url[strlen($url) - 1] !== '/') {
            $url .= '/';
        }

        Config::set('site_url', $url);
        define('FULL_URL', $url);
    }
    
    /**
     * Sets the allowed uploadable mime types
     * @return void
     */
    
    public static function setMimeTypes() {
        Config::set(
            'mime_types', 
            FileSystem::read_config("config/mimetypes.php")
        );
    }
    
    /**
     * Determines and Sets the request protocol (HTTP | HTTPS)
     * @return void
     */
    
    public static function setProtocol() {
        $uri = null;
        $protocol = 'http';
        if (Filter::get($_SERVER, 'SCRIPT_URI')) {
            $uri = Filter::get($_SERVER, 'SCRIPT_URI');
            if (substr($uri, 0, 6) == "https:") {
                $protocol = 'https';
            }
        }
        Config::set('protocol', $protocol);
    }
    
    /**
     * Sets the PHP Error Log file
     * @return void
     */
    
    public static function setLogFile() {
        /*
        if (function_exists('ini_set') && is_callable('ini_set')) {
            $logfile = Config::get('error_log');
            if (!empty($logfile)) {
                ini_set('error_log', SB_LOG_DIR . $logfile);
            }
        }
        */
    }
    
    /**
     * Get a config value
     * @param string  The key of the config setting to get
     * @param mixed   The default value to return if the specified key is not found
     * @return mixed  The value of the key or the default value
     */
    
    public static function get($key, $default=null) {
        if (isset(Config::$config[$key])) {
            return Config::$config[$key];
        }
        return $default;
    }

    /**
     * Set a config value
     * @param string  The key of the config value to set
     * @param mixed   The value to which to set the config setting
     * @return mixed  The previous value of the config setting
     */
    
    public static function set($key, $value) {
        $previous = null;
        if (isset(Config::$config[$key])) {
            $previous = Config::$config[$key];
        }
        Config::$config[$key] = $value;
        return $previous;
    }

}