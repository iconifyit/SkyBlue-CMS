<?php if (function_exists('ob_start')) @ob_start("ob_gzhandler");
require_once("../php/functions.php");
httpHeaderCss();
echo compressSource(
    readFileContents("./reset_text_960.css") .
    readFileContents("./main.css")
);
if (function_exists('ob_flush')) @ob_flush();