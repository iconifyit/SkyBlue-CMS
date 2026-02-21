<?php defined('SKYBLUE') or die('Bad file request');

/**
 * Bootstrap 5 Contact Form Fragment
 *
 * Renders a contact form using Bootstrap 5 styling with form validation.
 * Handles form submission via the ContactsFragment class.
 */

ContactsFragment::init(Filter::get($params, 'name'));
$data = ContactsFragment::getData();

if (is_null($data)) {
    return;
}

if (ContactsFragment::the_action() == "send") {
    ContactsFragment::handle_contact_form(ContactsFragment::the_contact($data));
}

$subject = str_replace('+', ' ', Filter::get($_GET, 'subject', ''));
$message = ContactsFragment::the_message();

?>
<!-- Bootstrap 5 Contact Form -->
<div class="contact-form-container">
    <?php if (!empty($message)) : ?>
        <div class="alert alert-info" role="alert">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo ContactsFragment::the_form_action(); ?>" method="post"
          class="needs-validation" novalidate>

        <div class="mb-3">
            <label for="contact-name" class="form-label">
                <?php __('GLOBAL.NAME', 'Name'); ?> <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" id="contact-name" name="name"
                   required placeholder="Your name">
            <div class="invalid-feedback">
                Please enter your name.
            </div>
        </div>

        <div class="mb-3">
            <label for="contact-email" class="form-label">
                <?php __('GLOBAL.EMAIL', 'Email Address'); ?> <span class="text-danger">*</span>
            </label>
            <input type="email" class="form-control" id="contact-email" name="email"
                   required placeholder="your.email@example.com">
            <div class="invalid-feedback">
                Please enter a valid email address.
            </div>
        </div>

        <div class="mb-3">
            <label for="contact-subject" class="form-label">
                <?php __('GLOBAL.SUBJECT', 'Subject'); ?>
            </label>
            <input type="text" class="form-control" id="contact-subject" name="subject"
                   value="<?php echo htmlspecialchars($subject); ?>"
                   placeholder="What is this about?">
        </div>

        <div class="mb-3">
            <label for="contact-message" class="form-label">
                <?php __('GLOBAL.COMMENTS', 'Message'); ?> <span class="text-danger">*</span>
            </label>
            <textarea class="form-control" id="contact-message" name="message"
                      rows="5" required placeholder="Your message..."></textarea>
            <div class="invalid-feedback">
                Please enter your message.
            </div>
        </div>

        <input type="hidden" name="mailinglist" value="0">
        <input type="hidden" name="cc" value="0">

        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
            <button type="submit" name="contacts_action" value="send"
                    class="btn btn-primary btn-lg">
                <i class="bi bi-send me-2"></i>
                <?php __('GLOBAL.SEND', 'Send Message'); ?>
            </button>
        </div>
    </form>
</div>

<script>
// Bootstrap 5 form validation
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>
