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
<div class="grid_8">
	<?php echo ContactsFragment::the_message(); ?>
    <form action="<?php echo ContactsFragment::the_form_action(); ?>" method="post" id="comment-form">
		<fieldset>
			<label class="grid_2"><?php __('GLOBAL.NAME', 'Name'); ?></label>
			<input type="text" name="name" class="grid_5" value="" />
		</fieldset>
		<fieldset>
			<label class="grid_2"><?php __('GLOBAL.EMAIL', 'Email Address'); ?></label>
			<input type="text" name="email" class="grid_5" value="" />
		</fieldset>
		<fieldset>
			<label class="grid_2"><?php __('GLOBAL.SUBJECT', 'Subject'); ?></label>
			<input type="text" name="subject" class="grid_5" value="<?php echo $subject; ?>" />
		</fieldset>
		<fieldset>
			<label class="grid_2"><?php __('GLOBAL.COMMENTS', 'Comments'); ?></label>
            <textarea rows="8" cols="55" name="comments" class="grid_5"></textarea>
		</fieldset>
		<fieldset class="buttons">
			<div class="grid_2">&nbsp;</div>
			<input type="submit" name="submit" value="<?php __('GLOBAL.SEND', 'Send'); ?>" class="button" id="comment-form-button" />
		</fieldset>
	</form>
</div>