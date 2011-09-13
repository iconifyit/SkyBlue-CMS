<?php defined('SKYBLUE') or die('Bad file request');

/**
 * Show a View of Data item(s) in a page region indicated by:
 *
 * Comment-style call:
 * 
 *     <!--#fragment(name=fragment_name&view=view_name&param1=value1&...)-->
 *
 * OR In-content call:
 *
 *     {fragment(name=fragment_name&view=view_name&param1=value1&...)}
 * 
 * NOTE: 
 *
 * param1=value1 arguments are optional custom parameters that will be 
 * passed to your fragment in the $params variable.
 *
 * @param  string A key=>value paired query string
 * @return string The html output of the fragment
 */
    
class FragmentorPlugin extends SkyBluePlugin {

    var $view;
    var $html;

    static $output = array();
    static $objs   = array();
    
    function __construct($View) {
        $this->setView($View);
    }
    
    function setView($View) {
        $this->view = $View;
    }
    
    function getView() {
        return $this->view;
    }
    
    function getDao() {
        if (is_callable(array($this->view, 'getDao'))) {
            return $this->view->getDao();
        }
        return null;
    }
    
    function execute($html) {
        $this->html = $this->parse_page($html);
    }

    function parse_page($html) {

        preg_match_all("/(<!--#fragment\((.*)\)-->)/i", $html, $tokens);
        if (count($tokens) < 3) return $html;
        $tokens = $tokens[2];
        for ($i=0; $i<count($tokens); $i++) {
            $html = str_replace(
                "<!--#fragment({$tokens[$i]})-->", 
                $this->execute_fragment($tokens[$i]), 
                $html
            );
        }
        preg_match_all("/({fragment\((.*)\)})/i", $html, $tokens);
        if (count($tokens) < 3) return $html;
        $tokens = $tokens[2];
        for ($i=0; $i<count($tokens); $i++) {
            $html = str_replace(
                "{fragment({$tokens[$i]})}", 
                $this->execute_fragment($tokens[$i]), 
                $html
            );
        }
        
        if (function_exists('plgSiteVars')) {
            $html = plgSiteVars($html);
        }
        
        return $html;
    }
    
    function execute_fragment($query, $data=null) {
    
        global $Core;

        static $output = array();
        static $objs = array();

        /*
        *  If no arguments are passed, return a null value.
        */

        if (empty($query)) return null;
        
        /*
        * If this fragment and view have already been executed, 
        * we can just return the output and skip the rest of 
        * the code.
        */
        
        if (isset($output[$query])) {
            return $output[$query];
        }
        
        $params = Utils::parseQuery($query);
        
        $fragment = Filter::noInjection($params, 'name');
        $view = Filter::noInjection($params, 'view', 'view');
        
        if (empty($fragment)) return null;
        if (empty($view)) return null;
        
        /*
        * Re-name 'menu' to 'page' so we get the right 
        * data objects.
        */
        
        $data_file = $fragment == 'menu' ? 'page' : $fragment;
        
        /*
        * Store the output in our static variable so we don't need to execute 
        * all of the plugin code if the same fragemnt and view are called again.
        */
        
        $output[$query] = $this->buffer_output(
            $fragment, $data, $view, $params
        );
        
        /*
        * Parse any fragment tokens in the fragment output
        */
        
        if (preg_match_all("/(<!--fragment\((.*)\)-->)/i", $output[$query], $tokens) ||  
            preg_match_all("/({fragment\((.*)\)})/i", $output[$query], $tokens)) {
            $output[$query] = $this->parse_page($output[$query]);
        }
        
        /*
        * Output the data.
        */
        
        return $output[$query];
    }
    
    function getFragmentRoot($name) {
        $root = _SBC_SYS_ . "fragments/";
        if (is_dir(_SBC_APP_ . "fragments/{$name}")) {
            $root = _SBC_APP_ . "fragments/";
        }
        return $root;
    }
    
    /**
    * Buffer the fragment output
    */
    
    function buffer_output($name, $data, $view, $params=array()) {
        global $Core;
        
        static $buffer = array();
        
        $key = implode(".", array_values($params));
        
        if (!array_key_exists($key, $buffer)) {
        
            $root = $this->getFragmentRoot($name);
            
            $ucname = ucwords($name);
        
            $fragment = $root . "{$name}/{$view}.php";
            $FragmentClass = $root . "{$name}/{$ucname}Fragment.php";
            
            if (!file_exists($fragment)) return null;
            ob_start();
            if (file_exists($FragmentClass)) {
                require_once($FragmentClass);
            }
            include($fragment);
            if (Filter::get($params, 'wrapper') == 'no') {
                $buffer[$key] = "<!--[fragment.{$name}]-->\n" . ob_get_contents() . "\n <!--[/fragment.{$name}]-->";
            }
            else {
                #$buffer[$key] = "<span class=\"fragment fragment-{$name}\">\n" . ob_get_contents() . "</span>\n";
                $buffer[$key] = ob_get_contents();
            }
            ob_end_clean();
            if (function_exists('plgBBCoder')) {
                $buffer[$key] = plgBBCoder($buffer[$key]);
            }
        }
        return $buffer[$key];
    }

}