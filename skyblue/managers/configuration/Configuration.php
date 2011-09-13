<?php defined('SKYBLUE') or die('Bad file request');

/**
* @version      2.0 2009-06-16 12:42:00 $
* @package      Canvas Lightweight PHP Framework
* @copyright    Copyright (C) 2008 Scott Edwin Lewis. All rights reserved.
* @license      GNU/GPL, see COPYING.txt
* SkyBlueCavnas is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYING.txt for copyright notices and details.
*/

/**
 * @author Scott Lewis
 * @date   June 20, 2009
 */
class Configuration extends TransferObject {

    var $id      = 1;
    var $type    = 'configuration';
    var $objtype = 'configuration';
    
    /**
     * @var string  The name of the site
     */
    var $site_name;
    
    /**
     * @var string  The site tag line
     */
    var $site_slogan;
    
    /**
     * @var string  The site URL
     */
    var $site_url;
    
    /**
     * @var string  The WYSIWYG editor to use for the site admin
     */
    var $site_editor;
    
    /**
     * @var string  The site localalization language
     */
    var $site_lang;
    
    /**
     * @var int  Boolean flag whether or not to use SEF URLs
     */
    var $sef_urls;
    
    /**
     * @var int  Boolean flas whether or not to enable page caching
     */
    var $use_cache;
    
    /**
     * @var string  The default site contact (admin) name
     */
    var $contact_name;
    
    /**
     * @var string  The default site contact (admin) title
     */
    var $contact_title;
    
    /**
     * @var string  The default site contact (admin) address
     */
    var $contact_address;
    
    /**
     * @var string  The default site contact (admin) supplemental address
     */
    var $contact_address_2;
    
    /**
     * @var string  The default site contact (admin) city
     */
    var $contact_city;
    
    /**
     * @var string  The default site contact (admin) state/province
     */
    var $contact_state;
    
    /**
     * @var string  The default site contact (admin) zip/postal code
     */
    var $contact_zip;
    
    /**
     * @var string  The default site contact (admin) email address
     */
    var $contact_email;
    
    /**
     * @var string  The default site contact (admin) phone number
     */
    var $contact_phone;
    
    /**
     * @var string  The default site contact (admin) facsimile number
     */
    var $contact_fax;
    
    /**
     * @var string  The jQuery UI theme name
     */
    var $ui_theme;
    
    /**
     * @var string  The active site template
     */
    var $active_skin;
    
    /**
     * Gets the site name
     * @return string
     */
    function getSite_name() {
        return $this->site_name;
    }
    
    /**
     * Sets the site name
     * @param string $site_name  The name of the site
     * @return void
     */
    function setSite_name($site_name) {
        $this->site_name = $site_name;
    }
    
    /**
     * Gets the site URL
     * @return string  The site URL
     */
    function getSite_url() {
        return $this->site_url;
    }
    
    /**
     * Sets the site URL
     * @param string $site_url  The site URL
     * @return void
     */
    function setSite_url($site_url) {
        $this->site_url = $site_url;
    }
    
    /**
     * Gets the site slogan
     * @return string The site tag line (slogan)
     */
    function getSite_slogan() {
        return $this->site_slogan;
    }
    
    /**
     * Sets the site slogan
     * @param string $site_slogan The site tag line (slogan)
     */
    function setSite_slogan($site_slogan) {
        $this->site_slogan = $site_slogan;
    }
    
    /**
     * Gets the site localization language
     * @return string  The site language
     */
    function getSite_lang() {
        return $this->site_lang;
    }
    
    /**
     * Sets the site localization language
     * @param string $site_lang  The site language
     * @return void
     */
    function setSite_lang($site_lang) {
        $this->site_lang = $site_lang;
    }
    
    /**
     * Gets the site's admin WYSIWYG editor
     * @return string The site's admin WYSIWYG editor
     */
    function getSite_editor() {
        return $this->site_editor;
    }
    
    /**
     * Sets the site's admin WYSIWYG editor
     * @parm string $site_editor The site's admin WYSIWYG editor
     * @return void
     */
    function setSite_editor($site_editor) {
        $this->site_editor = $site_editor;
    }
    
    /**
     * Gets a boolean flag whether or not to use SEF URLs
     * @return boolean Whether or not to use SEF URLs
     */
    function getSef_urls() {
        return $this->sef_urls;
    }
    
    /**
     * Sets a boolean flag whether or not to use SEF URLs
     * @param boolean $sef_urls Whether or not to use SEF URLs
     * @retur void
     */
    function setSef_urls($sef_urls) {
        $this->sef_urls = $sef_urls;
    }
    
    /**
     * Gets a boolean flag whether or not to use page caching
     * @return boolean Whether or not to use page caching
     */
    function getUse_cache() {
        return $this->use_cache;
    }
    
    /**
     * Sets the UI Theme name
     * @param string $ui_theme  The name of the UI theme
     * @return void
     */
    function setUi_theme($ui_theme) {
        $this->ui_theme = $ui_theme;
    }
    
    /**
     * Gets the UI Theme name
     * @return string
     */
    function getUi_theme() {
        return $this->ui_theme;
    }
    
    /**
     * Sets a boolean flag whether or not to use page caching
     * @param boolean $use_cache Whether or not to use page caching
     * @return void
     */
    function setUse_cache($use_cache) {
        $this->use_cache = $use_cache;
    }
    
    /**
     * Gets the default site admin contact name
     * @return string  The default admin contact name
     */
    function getContact_name() {
        return $this->contact_name;
    }
    
    /**
     * Sets the default site admin contact name
     * @param string $contact_name The default admin contact name
     * @return void
     */
    function setContact_name($contact_name) {
        $this->contact_name = $contact_name;
    }
    
    /**
     * Gets the default site admin contact title
     * @return string  The default admin contact title
     */
    function getContact_title() {
        return $this->contact_title;
    }
    
    /**
     * Sets the default site admin contact title
     * @param string $contact_title The default admin contact title
     * @return void
     */
    function setContact_title($contact_title) {
        $this->contact_title = $contact_title;
    }
    
    /**
     * Gets the default site admin contact address
     * @return string  The default admin contact address
     */
    function getContact_address() {
        return $this->contact_address;
    }
    
    /**
     * Sets the default site admin contact address
     * @param string $contact_address The default admin contact address
     * @return void
     */
    function setContact_address($contact_address) {
        $this->contact_address = $contact_address;
    }
    
    /**
     * Gets the default site admin contact supplemental address
     * @return string  The default admin contact supplemental address
     */
    function getContact_address_2() {
        return $this->contact_address_2;
    }
    
    /**
     * Sets the default site admin contact supplemental address
     * @param string $contact_title The default admin contact supplemental address
     * @return void
     */
    function setContact_address_2($contact_address_2) {
        $this->contact_address_2 = $contact_address_2;
    }
    
    /**
     * Gets the default site admin contact city
     * @return string  The default admin contact city
     */
    function getContact_city() {
        return $this->contact_city;
    }
    
    /**
     * Sets the default site admin contact city
     * @param string $contact_title The default admin contact city
     * @return void
     */
    function setContact_city($contact_city) {
        $this->contact_city = $contact_city;
    }
    
    /**
     * Gets the default site admin contact state
     * @return string  The default admin contact state
     */
    function getContact_state() {
        return $this->contact_state;
    }
    
    /**
     * Sets the default site admin contact state
     * @param string $contact_title The default admin contact state
     * @return void
     */
    function setContact_state($contact_state) {
        $this->contact_state = $contact_state;
    }
    
    /**
     * Gets the default site admin contact zip/postal code
     * @return string  The default admin contact zip/postal code
     */
    function getContact_zip() {
        return $this->contact_zip;
    }
    
    /**
     * Sets the default site admin contact zip/postal code
     * @param string $contact_title The default admin contact zip/postal code
     * @return void
     */
    function setContact_zip($contact_zip) {
        $this->contact_zip = $contact_zip;
    }
    
    /**
     * Gets the default site admin contact email
     * @return string  The default admin contact email
     */
    function getContact_email() {
        return $this->contact_email;
    }
    
    /**
     * Sets the default site admin contact email
     * @param string $contact_title The default admin contact email
     * @return void
     */
    function setContact_email($contact_email) {
        $this->contact_email = $contact_email;
    }
    
    /**
     * Gets the default site admin contact phone
     * @return string  The default admin contact phone
     */
    function getContact_phone() {
        return $this->contact_phone;
    }
    
    /**
     * Sets the default site admin contact phone
     * @param string $contact_title The default admin contact phone
     * @return void
     */
    function setContact_phone($contact_phone) {
        $this->contact_phone = $contact_phone;
    }
    
    /**
     * Gets the default site admin contact fax
     * @return string  The default admin contact fax
     */
    function getContact_fax() {
        return $this->contact_fax;
    }
    
    /**
     * Sets the default site admin contact fax
     * @param string $contact_title The default admin contact fax
     * @return void
     */
    function setContact_fax($contact_fax) {
        $this->contact_fax = $contact_fax;
    }
    
    /**
     * Gets the active site skin (template)
     * @return string
     */
    function getActive_skin() {
        return $this->active_skin;
    }
    
    /**
     * Sets the active site skin (template)
     * @param string $active_skin  The name of the active skin directory
     * @return void
     */
    function setActive_skin($active_skin) {
        $this->active_skin = $active_skin;
    }
    
}