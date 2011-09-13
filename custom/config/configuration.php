<?php defined('SKYBLUE') or die('Bad file request');

$type = "sqlite";

/**
 * To enable MySQL data storage, create a new MySQL database and source the enclosed 
 * file named '/sbc/custom/daos-mysql/mysql-schema.sql'. 
 * Next, un-comment the following 6 sb_conf() statements. 
 * Finally, comment out the SQLITE DB Settings section.
 */

if ($type == "mysql") {

    # ###################################################################################
	# MYSQL DB SETTINGS
	# ###################################################################################

	sb_conf('DB_TYPE', 'mysql');
	sb_conf('DB_NAME', 'sbc');
	sb_conf('DB_USER', 'root');
	sb_conf('DB_PASS', '');
	sb_conf('DB_HOST', 'localhost');
	sb_conf('DB_PORT', '3306');

} 
else {

	# ###################################################################################
	# SQLITE DB SETTINGS
	# ###################################################################################
	
	sb_conf('DB_TYPE', 'sqlite');
	sb_conf('DB_HOST', _SBC_APP_ . 'data/data.sqlite');
	sb_conf('DB_NAME', 'sbc.sqlite');
	sb_conf('DB_USER', 'root');
	sb_conf('DB_PASS', 'password');

}