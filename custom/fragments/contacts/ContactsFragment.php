<?php defined('SKYBLUE') or die('Bad file request');

class ContactsFragment extends Fragment {
 
    function init($name) {
        add_terms_file(dirname(__FILE__) . "/contacts.ini");
        parent::init($name);
    }

    function getData() {
        Loader::load("managers.contacts.ContactsHelper", true, _SBC_SYS_);
        $Dao = ContactsHelper::getContactsDAO();
        return $Dao->index();
    }
    
    function check_address() {
        $check = 
            Config::get('contact_address')
            . Config::get('contact_city')
            . Config::get('contact_state') 
            . Config::get('contact_zip')
            . Config::get('contact_phone');
        return (trim($check) == "" ? 0 : 1);
    }
    
    function the_contact($contacts) {
        global $Core;
        $contact = $Core->SelectObj($contacts, Filter::get($_POST, 'cid', null));
        return isset($contact->email) ? $contact->email : Config::get('contact_email');
    }
    
    function the_form_action() {
        global $Router;
        return $Router->GetLink(Filter::get($_GET, 'pid', DEFAULT_PAGE));
    }
    
    function the_message() {
        $message = Filter::get($_SESSION, __CLASS__.'_message', array());
        if (empty($message)) return null;
        unset($_SESSION[__CLASS__.'_message']);
        
        $subject = Filter::get($message, 'string', null);
        $regex = "/({list\(([^}]*)\)})/i";
        if (preg_match_all($regex, $subject, $matches)) {
            if (count($matches) === 3) {
                $list = "";
                $arr = explode(",", $matches[2][0]);
                for ($i=0; $i<count($arr); $i++) {
                    $list .= "<li>" . trim($arr[$i]) . "</li>\n";
                }
                $message['string'] = str_replace(
                    $matches[0], 
                    "<ul>{$list}</ul>\n", 
                    $message['string']
                );
            }
        }
        
        return ContactsFragment::get_message_string($message);
    }
    
    function get_message_string($message) {
        return 
        "<div class=\"" . Filter::get($message, 'class', 'none') . "\">\n" . 
        "<h2>" . Filter::get($message, 'title', null) . "</h2>\n" . 
        "<p>{$message['string']}</p>\n" .
        "</div>\n";
    }
    
    function the_action() {
        return strtolower(Filter::get($_POST, 'contacts_action', ''));
    }
    
    function set_message($class, $title, $string) {
         $_SESSION[__CLASS__.'_message'] = array(
             'class'  => $class,
             'title'  => $title,
             'string' => $string
         );
    }
    
    function handle_contact_form($mailto, $defaults=array()) {
        global $Core;
        global $Router;
        
        $form = array();
        $form['name']    = Filter::noInjection($_POST, 'name', '');
        $form['email']   = Filter::noInjection($_POST, 'email', '');
        $form['subject'] = Filter::noInjection($_POST, 'subject', '');
        $form['message'] = Filter::noInjection($_POST, 'message', '');
            
        $errors = array();
        foreach ($form as $k=>$v) {
            if (trim($v) == "") array_push($errors, $k);
            $test = Filter::get($defaults, $k);
            if (is_string($test)) $test = trim($test);
            if (strcasecmp($test, $v) == 0) array_push($errors, $k);
        }
        if (count($errors)) {
            ContactsFragment::set_message(
                'error',
                __('CONTACTSFRAG.MESSAGE_NOT_SENT', 'Your message cannot be sent.<br />Please complete the following fields:', 1),
                "{list(" . implode(', ', $errors) . ")}"
            );
        }
        else {
            $message  = date('d M\, Y l h:i:s A')."\n\n";
            $message .= 'To: '.$mailto."\n";
            $message .= 'From: '.$form['name']."\n";
            $message .= 'Email: '.$form['email']."\n\n";
            $message .= 'Subject: '.$form['subject']."\n\n";
            $message .= $form['message']."\n";
    
            $options = array(
                'recepient' => $mailto,
                'replyto'   => $form['email'],
                'from'      => $form['email'],
                'subject'   => $form['subject'],
                'message'   => $message
            );
    
            if (Utils::mail($options)) {
                ContactsFragment::set_message(
                    'success',
                    __('CONTACTSFRAG.MESSAGE_SENT', 'Your message has been sent', 1),
                    __('CONTACTSFRAG.IN_TOUCH_SOON', 'We will be in touch shortly', 1)
                );
            }
            else {
                ContactsFragment::set_message(
                    'error',
                    __('CONTACTSFRAG.MESSAGE_NOT_SENT2', 'Your message could not be sent', 1),
                    __('CONTACTSFRAG.UNKNOWN_ERROR', 'An unknown error occurred', 1)
                );
            }
            Utils::redirect($Router->GetLink(Filter::getNumeric($_GET, 'pid', DEFAULT_PAGE)));
        }
    }
}