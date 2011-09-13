<?php defined('SKYBLUE') or die('Bad file request');

class Downloader {
    
    /**
    * @constructor
    * @description  The class constructor
    * @return void
    */
    
    function __construct() {
        ;
    }
    
    /**
    * @description  Performs the file upload
    * @param mixed  $source  A file or directory path to download
    * @param String $name    The name of the resulting ZIP Archive
    * @return void
    */
    
    function download($source, $name, $redirect, $options=array()) {

        $zip = Utils::zip($source, $name);
        
        $errorCode = Filter::getNumeric($zip, 'errorCode', -1);
        $filename  = Filter::get($zip, 'location', 'null');
        
        $optons = array(
            '',
            'DownloadWindow',
            'location=0,status=0,scrollbars=0,width=500,height=300'
        );
        
        $options = implode(",", $options);
        
        if (($errorCode === 0 || $errorCode === 1) && file_exists($filename)) {
            echo "<script type=\"text/javascript\">\n";
            echo "    var w = window.open({$options});\n";
            echo "    w.document.write('<a href=\"{$filename}\">Download</a>');\n";
            echo "    w.document.close();\n";
            echo "    window.location.href='{$redirect}'";
            echo "</script>";
        }
    }

}