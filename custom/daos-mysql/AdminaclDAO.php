<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2009-06-22 17:37:00 $
 * @package      SkyBlueCanvas
 * @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license      GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

/**
 * @author Scott Lewis
 * @date   June 22, 2009
 */

class AdminaclDAO extends MysqlDAO {

    function __construct() {
        parent::__construct(array(
            'type' => 'adminacl', 
            'data_sub_path' => 'adminacl/',
            'bean_class' => 'Adminacl'
        ));
    }
    
    function getItem($id) {
        $Statement = $this->query("SELECT * FROM {$this->getBeanClass()} WHERE id = {$id} LIMIT 1");        
        return (new ACO($Statement->fetch(PDO::FETCH_ASSOC)));
    }
        
	function index() {
		$objects = array();
		$Statement = $this->query("SELECT * FROM {$this->getBeanClass()}");
		if ($result = $Statement->fetchAll(PDO::FETCH_ASSOC)) {
			$count = count($result);
			for ($i=0; $i<$count; $i++) {
				$objects[$i] = new ACO(array(
					'id'      => Filter::get($result[$i], 'id'),
					'name'    => Filter::get($result[$i], 'name'),
					'users'   => Filter::get($result[$i], 'users'),
					'groups'  => Filter::get($result[$i], 'groups'),
					'acl'     => Filter::get($result[$i], 'acl'),
					'objtype' => 'adminacl',
					'type'    => 'adminacl'
				));
			}
		}
        return $objects;
    }
}