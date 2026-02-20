<?php defined('SKYBLUE') or die('Bad file request');

ContactsFragment::init(Filter::get($params, 'name'));
$data = ContactsFragment::getData();

if (is_null($data)) return;

if (ContactsFragment::the_action() == "send") {
    ContactsFragment::handle_contact_form(ContactsFragment::the_contact($data));
}

$subject = str_replace('+', ' ', Filter::get($_GET, 'subject', ''));

?>
<!-- CONTACT FORM -->
<div id="contact_form_div">
    <?php echo ContactsFragment::the_message(); ?>
    <?php 
    /*
    Utils::echoValue(Config::get('site_name'), '<h2>', "</h2>\n");
    Utils::echoValue(Config::get('contact_name'), '<h3>', "</h3>\n");
    if (ContactsFragment::check_address()) {
        echo "<address>\n";
        Utils::echoValue(Config::get('contact_address'), '', '<br />');
        Utils::echoValue(Config::get('contact_address_2'), '', '<br />');
        Utils::echoValue(Config::get('contact_city'), '', !Utils::isEmpty(Config::get('contact_state')) ? ',&nbsp;' : '' );
        Utils::echoValue(Config::get('contact_state'), '', '&nbsp;&nbsp;');
        Utils::echoValue(Config::get('contact_zip'), '', "\n");
        Utils::echoValue(Config::get('contact_phone'), '<br />', "\n");
        echo "</address>\n";
    }
    */
    ?>
    <form action="<?php echo ContactsFragment::the_form_action(); ?>" method="post" id="commentform">
        <div id="respond">
            <?php if (count($data) > 1) : ?>
            <!--
            <div class="row">
                <div class="column">
                    <label class="small"><?php __('GLOBAL.TO', 'To'); ?></label>
                </div>
                <div class="column">
                    <select name="cid">
                        <?php foreach ($data as $Contact) : ?>
                        <option value="<?php echo $Contact->getId(); ?>"><?php echo $Contact->getName(); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            -->
            <?php endif; ?>
            <div class="row">
                <div class="column">
                    <label class="small"><?php __('GLOBAL.NAME', 'Name'); ?></label>
                </div>
                <div class="column">
                    <input type="text" name="name" size="47" class="textfield" value="" />
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <label class="small"><?php __('GLOBAL.EMAIL', 'Email Address'); ?></label>
                </div>
                <div class="column">
                    <input type="text" name="email" size="47" class="textfield" value="" />
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <label class="small"><?php __('GLOBAL.SUBJECT', 'Subject'); ?></label>
                </div>
                <div class="column">
                    <input type="text" name="subject" size="47" class="textfield" value="<?php echo $subject; ?>" />
                </div>
            </div>
            <div class="row">
                <label class="small"><?php __('GLOBAL.COMMENTS', 'Comments'); ?></label>
                <textarea cols="40" rows="4" name="message" id="comment"></textarea>
            </div> 
            <div id="submitbox">
                <div class="submitbutton">
                    <input type="submit" name="contacts_action" value="<?php __('GLOBAL.SEND', 'Send'); ?>" class="button" id="submit" />
                </div>
            </div>
            <input type="hidden" name="mailinglist" value="0"  />
            <input type="hidden" name="cc" value="0" />
        </div>
    </form>
</div>
<!-- /CONTACT FORM -->