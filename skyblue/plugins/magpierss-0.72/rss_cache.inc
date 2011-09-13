<?php
/*
 * Project:     MagpieRSS: a simple RSS integration tool
 * File:        rss_cache.inc, a simple, rolling(no GC), cache 
 *              for RSS objects, keyed on URL.
 * Author:      Kellan Elliott-McCrea <kellan@protest.net>
 * Version:     0.51
 * License:     GPL
 *
 * The lastest version of MagpieRSS can be obtained from:
 * http://magpierss.sourceforge.net
 *
 * For questions, help, comments, discussion, etc., please join the
 * Magpie mailing list:
 * http://lists.sourceforge.net/lists/listinfo/magpierss-general
 *
 */

class RSSCache {

    var $BASE_CACHE = './cache';    // where the cache files are stored
    var $MAX_AGE    = 3600;         // when are files stale, default one hour
    var $ERROR      = "";           // accumulate error messages
    
    function RSSCache ($base='', $age='') {
        
        if ( $base ) {
            $this->BASE_CACHE = $base;
        }
        if ( $age ) {
            $this->MAX_AGE = $age;
        }
    }
    
    function getCache($cache_file) {
        return new Cache($cache_file, $this->MAX_AGE);
    }
        
/*=======================================================================*\
    Function:   set
    Purpose:    add an item to the cache, keyed on url
    Input:      url from wich the rss file was fetched
    Output:     true on sucess  
\*=======================================================================*/
    function set ($url, $rss) {
        $this->ERROR = "";
        $cache_file = $this->file_name($url);
        if ($Cache = $this->getCache($cache_file)) {
            $Cache->saveCache($this->serialize($rss));
        }
        return $cache_file;
    }
    
/*=======================================================================*\
    Function:   get
    Purpose:    fetch an item from the cache
    Input:      url from wich the rss file was fetched
    Output:     cached object on HIT, false on MISS 
\*=======================================================================*/ 
    function get ($url) {
        $this->ERROR = "";
        $Cache = $this->getCache($this->file_name($url));
        if ($data = $Cache->getCache()) {
            return $this->unserialize($data);
        }
        return 0;
    }

/*=======================================================================*\
    Function:   check_cache
    Purpose:    check a url for membership in the cache
                and whether the object is older then MAX_AGE (ie. STALE)
    Input:      url from wich the rss file was fetched
    Output:     cached object on HIT, false on MISS 
\*=======================================================================*/     
    function check_cache ( $url ) {
        $this->ERROR = "";
        if ($Cache = $this->getCache($this->file_name($url))) {
            if ($Cache->isCached()) {
                if ($this->MAX_AGE > $Cache->getAge()) {
                    return 'HIT';
                }
                else {
                    return 'STALE';
                }
            }
            else {
                return 'MISS';
            }
        }
        return 'MISS';
    }

    function cache_age( $cache_key ) {
        if ($Cache = $this->getCache($filename)) {
            if ($Cache->isCached()) {
                return $Cache->getAge();
            }
            return -1;
        }
        return -1;
    }
    
/*=======================================================================*\
    Function:   serialize
\*=======================================================================*/     
    function serialize ( $rss ) {
        return serialize( $rss );
    }

/*=======================================================================*\
    Function:   unserialize
\*=======================================================================*/     
    function unserialize ( $data ) {
        return unserialize( $data );
    }
    
/*=======================================================================*\
    Function:   file_name
    Purpose:    map url to location in cache
    Input:      url from wich the rss file was fetched
    Output:     a file name
\*=======================================================================*/     
    function file_name ($url) {
        return md5( $url );
    }

/*=======================================================================*\
    Function:   error
    Purpose:    register error
\*=======================================================================*/         
    function error ($errormsg, $lvl=E_USER_WARNING) {
        // append PHP's error message if track_errors enabled
        if ( isset($php_errormsg) ) { 
            $errormsg .= " ($php_errormsg)";
        }
        $this->ERROR = $errormsg;
        if ( MAGPIE_DEBUG ) {
            trigger_error( $errormsg, $lvl);
        }
        else {
            error_log( $errormsg, 0);
        }
    }
    
    function debug ($debugmsg, $lvl=E_USER_NOTICE) {
        if ( MAGPIE_DEBUG ) {
            $this->error("MagpieRSS [debug] $debugmsg", $lvl);
        }
    }

}

?>
