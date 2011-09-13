<?php

global $Core;

$Core->RegisterEvent('OnRenderPage', 'plgLoadAds');

function plgLoadAds($html)
{
    global $Core;
    preg_match_all("/(<!--#ads\((.*)\)-->)/i", $html, $tokens);
    if (count($tokens) < 3) return $html;
    $tokens = $tokens[2];
    for ($i=0; $i<count($tokens); $i++)
    {
        $html = ReplaceAdToken($tokens[$i], "<!--#ads({$tokens[$i]})-->", $html);
    }
    preg_match_all("/({ads\((.*)\)})/i", $html, $tokens);
    if (count($tokens) < 3) return $html;
    $tokens = $tokens[2];
    for ($i=0; $i<count($tokens); $i++)
    {
        $html = ReplaceAdToken($tokens[$i], "{ads:" . $tokens[$i]. "}", $html);
    }
    return $html;
}

function ReplaceAdToken($name, $token, $html)
{
    global $Core;
    $file = RotateAds($name);
    if (file_exists($file))
    {
        $html = str_replace(
            $token,
            "<div id=\"ads-$name\">\n" .  $Core->SBReadFile($file) . "\n</div>\n",
            $html
        );
    }
    return $html;
}

function IdFormat($str)
{
    $id = null;
    $str = strtolower($str);
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789-_";
    for ($i=0; $i<strlen($str); $i++)
    {
        $id .= strpos($chars, $str{$i}) === false ? "-" : $str{$i} ;
    }
    return $id;
}

function RotateAds($filter)
{
    global $Core;
    $ads = array();
    $files = $Core->ListFiles(SB_SITE_DATA_DIR . "ads/");
    for ($i=0; $i<count($files); $i++)
    {
        $name = basename($files[$i]);
        if (strpos($files[$i], $filter) !== false && 
            $name{0} != '_')
        {
            $ads[] = $files[$i];
        }
    }
    if (!count($ads)) return null;
    if (count($ads) <= 1)
    {
        return $ads[0];
    }
    return $ads[mt_rand(0, count($ads)-1)];
}
?>