<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2009-06-22 23:50:00 $
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
class XmlDAO extends DAO {

    var $type;
    var $dataSubPath;
    var $source;

    function __construct($options) {
        $this->setType(Filter::getAlphaNumeric($options, 'type'));
        $this->setBeanClass(Filter::get($options, 'bean_class'));
        $this->initDataSource(
            Filter::get($options, 'data_sub_path'),
            "{$this->type}.xml"
        );
    }
    
    function getType() {
        return $this->type;
    }
    
    function setType($type) {
        $this->type = $type;
    }
    
    function setSource($source) {
        $this->source = SB_XML_DIR . $source;
    }
    
    function getSource() {
        return $this->source;
    }

    function index($refresh=false) {
        global $Core;
        return parent::bindTransferObjects(
            $Core->xmlHandler->ParserMain($this->getSource()),
            strtolower($this->getBeanClass())
        );
    }
    
    function insert($TransferObject) {
        $TransferObject->setType($this->type);
        $TransferObject->setObjtype($this->type);
        if (!is_callable(array($TransferObject, 'getValueObject'))) {
            trigger_error(__(
                'DAO.TRANSFER_OBJECT_REQUIRED', 
                'DAO::insert($TransferObject) expects the first argument to be a TransferObject.'
            ));
        }
        $Bean = $TransferObject->getValueObject();
        $data = $this->index(true);
        $count = count($data);
        $inserted = false;
        for ($i=0; $i<$count; $i++) {
            $Item = $data[$i];
            if ($Item->getId() == $Bean->getId()) {
                $data[$i] = $Bean;
                $inserted = true;
            }
        }
        if (! $inserted) {
            array_push($data, $Bean);
        }
        return $this->save($data);
    }
    
    function copy($id) { /* May be defined by child class */ }
    
    /**
     * For the XML implementation, update is just an alias for insert.
     */
    function update($Bean) {
        return $this->insert($Bean);
    }
    
    function getItem($id) {
        return Utils::selectObject($this->index(), $id);
    }
    
    function getByKey($key, $value) {
        return Utils::findObjByKey($this->index(), $key, $value);
    }
    
    function getByName($name) {
        return $this->getByKey('name', $name);
    }
    
    function save($data=null) {
        return FileSystem::write_file(
            $this->source,
            ArrayUtils::objectsToXml($data, strtolower($this->getType()))
        );
    }
    
    function delete($id) {
        $data = $this->index(true);
        $filtered = array();
        $deleted = false;
        foreach ($data as $Item) {
            if ($Item->getId() != $id) {
                array_push($filtered, $Item);
            }
            else {
                $deleted = true;
            }
        }
        if ($deleted) {
            return $this->save($filtered);
        }
        return false;
    }
    
    function deleteAll() {
        return $this->save(array());
    }
    
    function exists($name) { /* To be defined by derivative class */ }
    
    function getDataSubPath() {
        return SB_XML_DIR . $this->dataSubPath;
    }
    
    function setDataSubPath($dataSubPath) {
        $this->dataSubPath = $dataSubPath;
    }

    function initDataSource($subpath, $fileName) {
        $this->setDataSubPath($subpath);
        $this->setSource("{$subpath}{$fileName}");
        if (!file_exists($this->getSource())) {
            $ClassName = ucwords($this->getType());
            if (trim($this->getDataSubPath()) != "") {
                if (!is_dir($this->getDataSubPath())) {
                    FileSystem::make_dir($this->getDataSubPath());
                }
            }
            FileSystem::write_file(
                $this->getSource(),
                ArrayUtils::objectsToXml(array(), strtolower($ClassName))
            );
        }
    }
}