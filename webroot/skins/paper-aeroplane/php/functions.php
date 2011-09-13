<?php

require_once('class.JavaScriptPacker.php');

/**
 * Reads the contents of a file from disk
 */
function readFileContents($file) {
    if (is_dir($file)) return null;
    if (! file_exists($file)) return null;
    if (! is_readable($file)) return null;
    $str = "";
    $fp = fopen($file, 'r');
    if (!$fp) return false;
    if (filesize($file) > 0) {
        $str = fread($fp, filesize($file));
    }
    return $str;
}

/**
 * Outputs a JSON Header
 */
function httpHeaderJson() {
    if (! headers_sent()) {
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Content-type: application/json");
    }
}

/**
 * Outputs a JavaScript Header
 */
function httpHeaderJavascript() {
    if (! headers_sent()) {
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Content-type: application/javascript");
    }
}

/**
 * Outputs an XML Header
 */
function httpHeaderXml() {
    if (! headers_sent()) {
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("content-type: text/xml");
    }
}

/**
 * Outputs an XML Header
 */
function httpHeaderCss() {
    if (! headers_sent()) {
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("content-type: text/css; charset: UTF-8");
    }
}

/**
 * Strips comments and new lines from a CSS file
 */
function compressSource($source) {
    return preg_replace(
		'!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', 
		str_replace(
		    array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', 
		    $source
		)
	);
}

/**
 * Packs JavaScript source
 */
function packJavaScript($source) {
    $packer = new JavaScriptPacker($source, 'Normal', true, false);
    return $packer->pack();
}