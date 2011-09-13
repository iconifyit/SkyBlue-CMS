<?php defined('SKYBLUE') or die('Bad File Request');

/**
 * @version      2.0 2009-04-14 23:50:00 $
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
class HtmlUtils {

    /**
     * Creates a Yes / No selector
     * @param string   The name of the select input
     * @param int      The index of the selected option (1 = yes, 0 = no)
     * @return string  An HTML selector with Yes / No values
     */
    function yesNoList($name, $selected=1) {
        return HtmlUtils::selector(array(
                HtmlUtils::option(__('GLOBAL.YES', 'Yes', 1), 1, intval($selected) == 1 ? $s = 1 : 0),
                HtmlUtils::option(__('GLOBAL.NO', 'No', 1), 0, intval($selected) == 0 ? 1 : 0)
            ), 
            $name
        );
    }
    
    /**
     * Creates an AM/PM (meridian) selector
     * @param string   The selected meridian
     * @return string  An HTML selector with AM/PM values
     */
    function meridianSelector($selected=null) {
        return HtmlUtils::selector(array(
                HtmlUtils::option(__('GLOBAL.AM', 'AM', 1), 'AM', $selected == 'AM' ? 1 : 0),
                HtmlUtils::option(__('GLOBAL.PM', 'PM', 1), 'PM', $selected == 'PM' ? 1 : 0)
            ),
            'meridian'
        );
    }
    
    /**
     * Creates an HTML OPTION element
     * @param string   The text for the option
     * @param string   The value of the option
     * @param int      Whether or not the option is selected
     * @return string  The HTML OPTION element
     */
    function option($title, $value, $selected='') {
        $sel = '';
        if ($selected == 1) {
            $sel = ' selected="selected"';
        }
        return "<option value=\"{$value}\"{$sel}>{$title}</option>\n";
    }
    
    /**
     * Creates an HTML SELECT element
     * @param array    An array of OPTION elements
     * @param string   The name of the HTML input
     * @param int      The size of the element
     * @param array    Optional additional attributes
     * @return string  The HTML SELECT element
     */
    function selector($arr, $name, $size=1, $attrs=array()) {
        $attrs_str = "";
        if (!empty($attrs)) {
            foreach ($attrs as $key=>$value) {
                $attrs_str .= " {$key}=\"{$value}\"";
            }
        }
        $html = "<select name=\"{$name}\" size=\"{$size}\"{$attrs_str}>\n";
        for ($i=0; $i<count($arr); $i++) {
            $html .= $arr[$i];
        }
        $html .= "</select>\n";
        return $html;
    }

    /**
     * Creates an HTML tag
     * @param string   The tag name
     * @param array    An array of attributes as key->value pairs
     * @param string   A string of CDATA for the tag
     * @param bool     Whether or not the tag has an end tag
     * @return string  The HTML tag
     */
    function tag($name, $attrs=array(), $cdata='', $hasCloseTag=1) {
        $attrs_str = null;
        if (count($attrs)) {
            $attrs_str = "";
            foreach($attrs as $k=>$v) {
                if (!empty($k)) $attrs_str .= " $k=\"$v\"";
            }
        } 
        $html = "<$name$attrs_str />";
        if ($hasCloseTag) {
            $html = "<$name$attrs_str>" . trim($cdata) . "</$name>\n";
        } 
        return $html;
    }
    
     /**
     * Creates an A element that behaves like a form button
     * @param string   The button text
     * @param string   The action the button triggers
     * @param array    An array of custom attributes for the button
     * @return string  The button A element
     */
    function mgrButton($value, $action, $customAttrs=array()) {
        $attrs = array(
            'type'  => 'submit',
            'name'  => 'submit',
            'value' => __($value, $value, 1), 
            'class' => 'sb-button ui-state-default ui-corner-all', 
            'onclick' => "return set_action(this, '$action');"
        );
        if (count($customAttrs)) {
            foreach ($customAttrs as $key=>$value) {
                $attrs[$key] = $value;
            }
        }
        echo HtmlUtils::tag(
            'input',
            $attrs,
            '', 
            0
        );
    }
    
    /**
     * Creates an A element that behaves like a form button
     * @param string   The button text
     * @param string   The action the button triggers
     * @param array    An array of custom attributes for the button
     * @return string  The button A element
     */
    function mgrActionLink($text, $href, $customAttrs=array()) {
        $attrs = array(
            'href'    => $href,
            'class'   => 'sb-button ui-state-default ui-corner-all'
        );
        if (count($customAttrs)) {
            foreach ($customAttrs as $key=>$value) {
                $attrs[$key] = $value;
            }
        }
        echo HtmlUtils::tag(
            'a',
            $attrs,
            __($text, $text, 1), 
            1
        );
    }
    
    /**
     * Creates a Save button
     * @return void
     */
    function mgrButtonSave() {
        HtmlUtils::mgrButton(
            __('GLOBAL.SAVE', 'Save', 1), 
            'save', 
            array('class' => 'sb-button ui-state-default ui-corner-all wymupdate')
        );
    }
    
    /**
     * Creates an Apply button - this is the same as save but does not close the editor
     * @return void
     */
    function mgrButtonApply() {
        HtmlUtils::mgrButton(
            __('GLOBAL.APPLY', 'Apply', 1), 
            'apply', 
            array('class' => 'sb-button ui-state-default ui-corner-all wymupdate')
        );
    }
    
    /**
     * Creates a Cancel button
     * @return void
     */
    function mgrButtonCancel() {
        echo HtmlUtils::mgrButton(__('GLOBAL.CANCEL', 'Cancel', 1), 'cancel');
    }
    
    /**
     * Creates a Cancel button
     * @return void
     */
    function mgrButtonAdd() {
        echo HtmlUtils::mgrButton(__('GLOBAL.ADD', 'Add', 1), 'add');
    }
    
    /**
     * Creates a Cancel button
     * @return void
     */
    function mgrButtonBack() {
        echo HtmlUtils::mgrButton(__('GLOBAL.BACK', 'Back', 1), 'back');
    }
    
    /**
     * Creates a Cancel button
     * @return void
     */
    function mgrButtonNew() {
        echo HtmlUtils::mgrButton(__('GLOBAL.NEW', 'New', 1), 'new');
    }
    
    /**
     * Creates the FORM open tag. The purpose of this function is to simplify the 
     * manager form creation. Developers don't need to worry about setting the form ID, 
     * method, etc. They just call this function with the name of the manager class.
     * @param string  The manager name (class)
     * @return void
     */
    function mgrFormOpen($managerName, $customAttrs=array()) {
        $attrs = " ";
        if (count($customAttrs)) {
            foreach ($customAttrs as $key=>$value) {
                $attrs .= "{$key}=\"{$value}\"";
            }
        }
        echo "<form id=\"mgrform\" method=\"post\" action=\"admin.php?com=$managerName\"{$attrs}>\n";
    }
    
    /**
     * Creates the FORM close tag and the 'action' field. The purpose of this function is to 
     * simplify the manager form creation. Developers don't need to remember to add the 'action' 
     * field, they simply call the mgr_form_close function and the field is added automatically.
     * @return void
     */
    function mgrFormClose() {
        echo "<input type=\"hidden\" name=\"action\" value=\"\" id=\"action\" />\n</form>\n";
    }
    
    /**
     * Creates multiple task buttons
     * @param array    An array of the task names to create
     * @param object   The object being edited
     * @return string  An HTML block of Task buttons
     */
    function mgrTasks($i, $objCount, $object, $tasks, $params=array(), $itemsPerPage=10) {
        $pageNum = Filter::get($_GET, 'pageNum', 1);
        ob_start();
            $count = count($tasks);
            for ($j=0; $j<$count; $j++) {
                $class = strtolower($object->getType());
                $name = '';
                if (isset($object->name)) {
                    $name = $object->name;
                }
                else if (isset($object->title)) {
                    $name = $object->title;
                }
                if ($tasks[$j] == 'edit') {
                    HtmlUtils::mgrTaskEdit($class, $object->id, $name, array(), $params);
                }
                else if ($tasks[$j] == 'copy') {
                    HtmlUtils::mgrTaskCopy($class, $object->id, $name, array(), $params);
                }
                else if ($tasks[$j] == 'delete') {
                    HtmlUtils::mgrTaskDelete($class, $object->id, $name, array(), $params);
                }
                else if ($tasks[$j] == 'order') {
                    if ($i > 0 || $pageNum > 1) {
                        HtmlUtils::mgrTaskOrder($class, $object->id, $name, 'up', array(), $params);
                    }
                    else {
                        HtmlUtils::mgrTaskSlug();
                    }
                    if (($pageNum == 1 && $i < $objCount-1) || ((($pageNum * $itemsPerPage) - $itemsPerPage) + $i) < $objCount-1) {
                        HtmlUtils::mgrTaskOrder($class, $object->id, $name, 'down', array(), $params);
                    }
                    else {
                        HtmlUtils::mgrTaskSlug();
                    }
                }
                else if ($tasks[$j] == 'publish') {
                    HtmlUtils::mgrTaskPublish(
                        $class, 
                        $object->id, 
                        $name, 
                        $object->published ? 'down' : 'up',
                        array(),
                        $params
                    );
                }
                else {
                    continue;
                }
            }
            $buffer = ob_get_contents();
        ob_end_clean();
        echo $buffer;
    }

    /**
     * Creates a Delete button for Component tasks
     * @param string  The component name
     * @param mixed   The item id to delete
     * @param string  The name of the item to delete
     * @param array   An optional array of additional attributes
     * @return void
     */
    function mgrTaskDelete($com, $id, $name='', $customAttrs=array(), $params=array()) {
        $url_params = "";
        if (count($params)) {
            foreach ($params as $key=>$value) {
                $url_params .= "&{$key}={$value}";
            }
        }
        $attrs = array(
            'href'    => "admin.php?com={$com}&action=delete&id={$id}",
            'onclick' => "confirm_delete(event, ' \'$name\' ', false, 'admin.php?com={$com}&action=delete&id={$id}{$url_params}');",
            'class'   => "ui-icon ui-icon-closethick tooltip", 
            'title'   => __('TASKS.DELETE', 'Delete', 1) . " $name"
        );
        if (count($customAttrs)) {
            foreach ($customAttrs as $key=>$value) {
                $attrs[$key] = $value;
            }
        }
        echo HtmlUtils::taskIconWrapper(HtmlUtils::tag(
            'a',
            $attrs,
            '<span class="hide">' . __('TASKS.DELETE', 'Delete', 1) . '</span>'
        ));
    } 
    
    /**
     * Creates a Edit button for Component tasks
     * @param string  The component name
     * @param mixed   The item id to edit
     * @param string  The name of the item to edit
     * @param array   An optional array of additional attributes
     * @return void
     */
    function mgrTaskEdit($com, $id, $name='', $customAttrs=array(), $params=array()) {
        $url_params = "";
        if (count($params)) {
            foreach ($params as $key=>$value) {
                $url_params .= "&{$key}={$value}";
            }
        }
        $attrs = array(
            'href'  => "admin.php?com={$com}&action=edit&id={$id}{$url_params}",
            'class' => "ui-icon ui-icon-pencil tooltip", 
            'title' => __('TASKS.EDIT', 'Edit', 1) . " $name"
        );
        if (count($customAttrs)) {
            foreach ($customAttrs as $key=>$value) {
                $attrs[$key] = $value;
            }
        }
        echo HtmlUtils::taskIconWrapper(HtmlUtils::tag(
            'a',
            $attrs,
            '<span class="hide">' . __('TASKS.EDIT', 'Edit', 1) . '</span>'
        ));
    }
    
    /**
     * Creates a Copy button for Component tasks
     * @param string  The component name
     * @param mixed   The item id to copy
     * @param string  The name of the item to copy
     * @param array   An optional array of additional attributes
     * @return void
     */
    function mgrTaskCopy($com, $id, $name='', $customAttrs=array(), $params=array()) {
        $url_params = "";
        if (count($params)) {
            foreach ($params as $key=>$value) {
                $url_params .= "&{$key}={$value}";
            }
        }
        $attrs = array(
            'href'  => "admin.php?com={$com}&action=copy&id={$id}{$url_params}",
            'class'   => "ui-icon ui-icon-copy tooltip", 
            'title'   => __('TASKS.COPY', 'Copy', 1) . " $name"
        );
        if (count($customAttrs)) {
            foreach ($customAttrs as $key=>$value) {
                $attrs[$key] = $value;
            }
        }
        echo HtmlUtils::taskIconWrapper(HtmlUtils::tag(
            'a',
            $attrs,
            '<span class="hide">' . __('TASKS.COPY', 'Copy', 1) . '</span>'
        ));
    }

    /**
     * Creates a Publish button for Component tasks
     * @param string  The component name
     * @param mixed   The item id to puslish/un-publish
     * @param string  The name of the item to puslish/un-publish
     * @param array   An optional array of additional attributes
     * @return void
     */
    function mgrTaskPublish($com, $id, $name='', $direction='up', $customAttrs=array(), $params=array()) {
        $direction = strtolower($direction);
        
        $url_params = "";
        if (count($params)) {
            foreach ($params as $key=>$value) {
                $url_params .= "&{$key}={$value}";
            }
        }
        
        $uiClass   = "ui-icon ui-icon-play";
        $langToken = 'TASKS.PUBLISH';
        $langText  = 'Publish';
        if ($direction == 'down') {
            $langToken = 'TASKS.UN_PUBLISH';
            $langText  = 'Un-Publish';
            $uiClass   = "ui-icon ui-icon-pause";
        }
        $attrs = array(
            'href'  => "admin.php?com={$com}&action=publish&id={$id}&direction={$direction}{$url_params}",
            'class' => $uiClass . ' tooltip',
            'title' => "$langText $name"
        );
        if (count($customAttrs)) {
            foreach ($customAttrs as $key=>$value) {
                $attrs[$key] = $value;
            }
        }
        echo HtmlUtils::taskIconWrapper(HtmlUtils::tag(
            'a',
            $attrs,
            '<span class="hide">' . $langToken . '</span>'
        ));
    }

    /**
     * Creates a Re-order button for Component tasks
     * @param string  The component name
     * @param mixed   The item id to Re-order
     * @param string  The name of the item to Re-order
     * @param array  An optional array of additional attributes
     * @return void
     */
    function mgrTaskOrder($com, $id, $name='', $direction='up', $customAttrs=array(), $params=array()) {
        $direction = strtolower($direction);
        
        $url_params = "";
        if (count($params)) {
            foreach ($params as $key=>$value) {
                $url_params .= "&{$key}={$value}";
            }
        }
        
        $uiClass   = "ui-icon ui-icon-triangle-1-n";
        $langToken = 'TASKS.ORDER_UP';
        $langText  = 'Move Up';
        if ($direction == 'down') {
            $langToken = 'TASKS.ORDER_DOWN';
            $langText  = 'Move Down';
            $uiClass   = "ui-icon ui-icon-triangle-1-s";
        }
        $attrs = array(
            'href'  => "admin.php?com={$com}&action=reorder&id={$id}&direction={$direction}{$url_params}",
            'class' => $uiClass . ' tooltip',
            'title' => "$langText $name"
        );
        if (count($customAttrs)) {
            foreach ($customAttrs as $key=>$value) {
                $attrs[$key] = $value;
            }
        }
        echo HtmlUtils::taskIconWrapper(HtmlUtils::tag(
            'a',
            $attrs,
            '<span class="hide">' . $langText . '</span>'
        ));
    }
    
    /**
     * Creates a Edit button for Component tasks
     * @param string  The component name
     * @param mixed   The item id to edit
     * @param string  The name of the item to edit
     * @param array   An optional array of additional attributes
     * @return void
     */
    function mgrTask($com, $action, $id, $name='', $icon='', $customAttrs=array(), $params=array()) {
        $ucAction = strtoupper($action);

        $url_params = "";
        if (count($params)) {
            foreach ($params as $key=>$value) {
                $url_params .= "&{$key}={$value}";
            }
        }
        $attrs = array(
            'href'  => "admin.php?com={$com}&action={$action}&id={$id}{$url_params}",
            'title' => ucwords($action) . " {$name}",
            'class' => "ui-icon ui-icon-{$icon} tooltip"
        );
        if (count($customAttrs)) {
            foreach ($customAttrs as $key=>$value) {
                $attrs[$key] = $value;
            }
        }
        echo HtmlUtils::taskIconWrapper(HtmlUtils::tag(
            'a',
            $attrs,
            '<span class="hide">' . __("TASKS.{$ucAction}", $action, 1) . '</span>'
        ));
    }
    
    /**
     * Creates a button Slug for Component tasks
     * @param array  An optional array of additional attributes
     * @return void
     */
    function mgrTaskSlug($customAttrs=array()) {
        $attrs = array();
        if (count($customAttrs)) {
            foreach ($customAttrs as $key=>$value) {
                $attrs[$key] = $value;
            }
        }
        echo HtmlUtils::taskIconWrapper(HtmlUtils::tag(
            'span',
            array(
                'class' => "slug"
            ),
            ''
        ), false);
    }
    
    /**
     * Wraps a task button in a SPAN so all task buttons can be styled similarly.
     * @param string   The task button HTML
     * @return string  The task button wrapped in a SPAN
     */
    function taskIconWrapper($button, $showBorder=true) {
        return HtmlUtils::tag(
            'span',
            array('class' => 'task-button' . ($showBorder ? ' ui-state-default ui-corner-all' : '')),
            $button
        );
    }
    
    /**
     * Creates a text input field
     * @param string  The field name
     * @param string  The field value
     * @param array   An optional array of attributes in key=>value pairs
     * @return void
     */
    function field($name, $value, $customAttrs=array()) {
        $attrs = array(
            'type'  => 'text',
            'name'  => $name,
            'value' => $value, 
            'class' => 'textfield',
            'id'    => $name
        );
        if (count($customAttrs)) {
            foreach ($customAttrs as $key=>$value) {
                $attrs[$key] = $value;
            }
        }
        echo HtmlUtils::tag(
            'input',
            $attrs,
            '',
            0
        );
    }
    
    /**
     * Creates a text input field
     * @param string  The field name
     * @param string  The field value
     * @param array   An optional array of attributes in key=>value pairs
     * @return void
     */
    function label($text, $for='', $customAttrs=array()) {
        $attrs = array();
        if (!empty($for)) {
            $attrs = array('for' => $for);
        }
        if (count($customAttrs)) {
            foreach ($customAttrs as $key=>$value) {
                $attrs[$key] = $value;
            }
        }
        echo HtmlUtils::tag(
            'label',
            $attrs,
            $text
        );
    }
    
    /**
     * Formats an HTML block from a Message object
     * @param object $Message  A Message object
     * @return string  The HTML formatted message block
     */
    function formatMessage($Message, $closable=true) {
        return FileSystem::buffer(
            "resources/ui/admin/html/message.php", 
            array('message' => $Message, 'closable' => $closable)
        );
    }
    
    /**
     * Creates a List view table header
     * @param array $headings  An array of table column headings
     * @return string
     */
    function mgrThead($headings) {
        $count = count($headings);
        $thead  = "<thead>\n";
        $thead .= "<tr>\n";
        for ($i=0; $i<$count; $i++) {
            $class = "ui-widget-header";
            if ($i == 0) $class .= " ui-corner-tl";
            if ($i == $count-1) $class .= " ui-corner-tr";
            $thead .= "<th class=\"{$class}\">{$headings[$i]}</th>\n";
        }
        $thead .= "</tr>\n";
        $thead .= "</thead>\n";
        echo $thead;
    }
    
    /**
     * Creates a tool-tip
     * @param string  The tooltip text
     * @return void
     */
    function tooltip($text) {
        // to-do
    }

}