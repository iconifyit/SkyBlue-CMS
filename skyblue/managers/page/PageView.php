<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version        v 1.1 2009-04-19 10:37:00 $
 * @package        SkyBlueCanvas
 * @copyright      Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license        GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

class PageView extends View {

    function getView() {
        
        $html = parent::getView();

        /**
         * Trigger in-line fragment calls.
         */
        
        $html = parse_fragments($html);
        
        /**
         * Execute callbacks for OnRenderPage
         */

        $html = Event::trigger('OnRenderPage', $html);
        
        /**
         * Parse in-line plugin calls
         */

        $html = parse_plugins($html);
        
        /**
         * Add any script or stylesheet links that were queued.
         */
         
        $html = str_replace('[[page.head_elements]]', get_head_elements_str(), $html);
        
        /**
         * Translate and print the current page
         */
        
        return translate($html);
    }
    
    function get_meta_data() {
        $meta = Filter::get($this->data, 'meta', array());
        $tags = '';
        $count = count($meta);
        if ($count < 1) return null;
        foreach ($meta as $name=>$content) {
            $tags .= HtmlUtils::tag(
                'meta',
                array(
                    'name' => $name,
                    'content' => $content
                ),
                '', 0
            ) . "\n";
        }
        return $tags;
    }
    
    function get_path() {
        return ACTIVE_SKIN_DIR;
    }
    
    function get_page_class() {
        return Utils::cssSafe(Filter::get($this->data, 'pagetype'));
    }
    
    function get_page_id() {
        return Utils::cssSafe(Filter::get($this->data, 'name'));
    }
    
    function fix_wym_paths() {
        $this->html = str_replace(WYM_RELATIVE_PATH, FULL_URL, $this->html);
    }

    function resolveVars() {
        parent::resolveVars();
        $this->set_var(VAR_SITE_NAME,     SB_SITE_NAME);
        $this->set_var(VAR_SITE_SLOGAN,   SB_SITE_SLOGAN);
        $this->set_var(VAR_SITE_URL,      SB_MY_URL);
        $this->set_var(VAR_SITE_RSS,      SB_RSS_FEED);
        $this->set_var(VAR_SITE_XHTML,    SB_VALIDATE_XHTML);
        $this->set_var(VAR_SITE_CSS,      SB_VALIDATE_CSS);
        $this->set_var(VAR_SITE_NOSCRIPT, SB_NOSCRIPT);
        $this->set_var(VAR_SB_PROD_NAME,  SB_PROD_NAME);
        $this->set_var(VAR_SB_VERSION,    SB_VERSION);
        $this->set_var(VAR_SB_TAGLINE,    SB_TAGLINE);
        $this->set_var(VAR_SB_INFO_LINK,  SKYBLUE_INFO_LINK);
        $this->set_var('[skinclass]', Filter::get($this->data, 'pagetype'));
        $this->set_var(
            '{page:bodyid}',
            ' id="'. $this->get_page_id() .'"'
        );
        $this->set_var('{doc:lang}', ' lang="' . SB_LANGUAGE . '"');
        $this->set_var('{page:base_uri}', BASE_URI);
        $this->set_var('{page:title}', $this->get_page_title());
    }
    
    function get_home_url() {
        return Config::get('site_url', '/');
    }
    
    function get_page_title() {
        return Filter::get($this->data, 'title');
    }
    
    function get_page_name() {
        return $this->get_page_id();
    }
    
    function set_var($token, $value) {
        $this->view = str_replace($token, $value, $this->view);
    }
    
}