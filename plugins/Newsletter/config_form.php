<script type="text/javascript">
jQuery(window).load(function() {
    // Initialize and configure TinyMCE.
    tinyMCE.init({
        // Assign TinyMCE a textarea:
        mode : 'specific_textareas',
        // Add plugins:
        plugins: 'media,paste,inlinepopups',
        // Configure theme:
        theme: 'advanced',
        theme_advanced_toolbar_location: 'top',
        theme_advanced_toolbar_align: 'left',
        theme_advanced_buttons3_add : 'pastetext,pasteword,selectall',
        // Allow object embed. Used by media plugin
        // See http://www.tinymce.com/forum/viewtopic.php?id=24539
        media_strict: false,
        // General configuration:
        convert_urls: false,
    });
    // Add or remove TinyMCE control.
    jQuery('#simple-pages-use-tiny-mce').click(function() {
        if (jQuery(this).is(':checked')) {
            tinyMCE.execCommand('mceAddControl', true');
        } else {
            tinyMCE.execCommand('mceRemoveControl', true);
        }
    });
});
</script>

<?php
$reply_from_email = get_option('newsletter_reply_from_email');
$forward_to_email = get_option('newsletter_forward_to_email');
$admin_notification_email_subject = get_option('newsletter_admin_notification_email_subject');
$user_notification_email_subject = get_option('newsletter_user_notification_email_subject');
$contact_page_instructions = get_option('newsletter_contact_page_instructions');
$thankyou_page_title = get_option('newsletter_thankyou_page_title');
$thankyou_page_message = get_option('newsletter_thankyou_page_message');
$add_to_main_navigation = get_option('newsletter_add_to_main_navigation');
$owner = get_option('newsletter_list_owner');
$mailinglist = get_option('newsletter_mailing_list');
$listserv = get_option('newsletter_listserv');

$view = get_view();
?>

<?php if (!Omeka_Captcha::isConfigured()): ?>
    <p class="alert">You have not entered your <a href="http://recaptcha.net/">reCAPTCHA</a>
        API keys under <a href="<?php echo url('security#recaptcha_public_key'); ?>">security settings</a>. We recommend adding these keys, or the contact form will be vulnerable to spam.</p>
<?php endif; ?>

    

<div class="field">
    <?php echo $view->formLabel('mailinglist', 'Mailinglist'); ?>
    <div class="inputs">
        <?php echo $view->formText('mailinglist', $mailinglist, array('class' => 'textinput')); ?>
        <p class="explanation">
            The address of the mailing list (p.e. test.list@ls.exmaple.com.
        </p>
    </div>
</div>
    

<div class="field">
    <?php echo $view->formLabel('listserv', 'Listserv-address'); ?>
    <div class="inputs">
        <?php echo $view->formText('listserv', $listserv, array('class' => 'textinput')); ?>
        <p class="explanation">
            The address used for admin tasks. (p.e. listserv@ls.example.com)
        </p>
    </div>
</div>
    
<div class="field">
    <?php echo $view->formLabel('owner', 'Owner'); ?>
    <div class="inputs">
        <?php echo $view->formText('owner', $owner, array('class' => 'textinput')); ?>
        <p class="explanation">
            The email address of an owner of the mailing list.
        </p>
    </div>
</div>
    
    
    
<div class="field">
    <?php echo $view->formLabel('reply_from_email', 'Reply-From Email'); ?>
    <div class="inputs">
        <?php echo $view->formText('reply_from_email', $reply_from_email, array('class' => 'textinput')); ?>
        <p class="explanation">
            The address that users can reply to. If blank, your users will not
            be sent confirmation emails of their submissions.
        </p>
    </div>
</div>

<div class="field">
    <?php echo $view->formLabel('forward_to_email', 'Forward-To Email'); ?>
    <div class="inputs">
        <?php echo $view->formText('forward_to_email', $forward_to_email, array('class' => 'textinput')); ?>
        <p class="explanation">
            The email address that receives notifications that someone has
            submitted a message through the contact form. If blank, you will not
            be forwarded messages from your users.
        </p>
    </div>
</div>

 <div class="field">
    <?php echo $view->formLabel('admin_notification_email_subject', 'Email Subject (Admin Notification)'); ?>
    <div class="inputs">
        <?php echo $view->formText('admin_notification_email_subject', $admin_notification_email_subject, array('class' => 'textinput')); ?>
        <p class="explanation">
            The subject line for the email that is sent to the Forward-To email
            address.
        </p>
    </div>
</div>

 <div class="field">
    <?php echo $view->formLabel('user_notification_email_subject', 'Email Subject (Public Notification)'); ?>
    <div class="inputs">
        <?php echo $view->formText('user_notification_email_subject', $user_notification_email_subject, array('class' => 'textinput')); ?>
        <p class="explanation">
            The subject line of the confirmation email that is sent
            to users who post messages through the form.
        </p>
    </div>
</div>

<div class="field">
    <?php echo $view->formLabel('contact_page_instructions', 'Instruction for the Sign Up Page'); ?>
    <div class="inputs">
        <?php echo $view->formTextarea('contact_page_instructions', $contact_page_instructions, array('rows' => '10', 'cols' => '60', 'class' => array('textinput', 'html-editor'))); ?>
        <p class="explanation">
            Any specific instructions to add to the sign up form.
        </p>
    </div>
</div>


<div class="field">
    <?php echo $view->formLabel('thankyou_page_title', 'Thank You Page Title'); ?>
    <div class="inputs">
        <?php echo $view->formText('thankyou_page_title', $thankyou_page_title, array('class' => 'textinput')); ?>
        <p class="explanation">
            The title of the Thank You page (not HTML).
        </p>
    </div>
</div>

<div class="field">
    <?php echo $view->formLabel('thankyou_page_message', 'Thank You Page Message'); ?>
    <div class="inputs">
        <?php echo $view->formTextarea('thankyou_page_message', $thankyou_page_message, array('rows' => '10', 'cols' => '60', 'class' => array('textinput', 'html-editor'))); ?>
        <p class="explanation">
            The text displayed on the Thank You page.
        </p>
    </div>
</div>

