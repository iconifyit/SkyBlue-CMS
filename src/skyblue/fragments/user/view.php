<?php defined('SKYBLUE') or die('Bad file name');

global $Authenticate;

add_terms_file("users.ini");
        
/*
* Get the html for the login form.
*/
 
# echo $Authenticate->BuildLoginForm();