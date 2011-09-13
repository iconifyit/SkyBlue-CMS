<?php if (function_exists('ob_start')) @ob_start("ob_gzhandler");
require_once("../php/functions.php");
httpHeaderJavascript();
echo readFileContents("./combined.js") . "\n";
echo compressSource(
    readFileContents("./main.js")
);
if (function_exists('ob_flush')) @ob_flush();