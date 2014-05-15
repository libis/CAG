<?php echo head(); ?>
<h1><?php echo htmlspecialchars(get_option('newsletter_thankyou_page_title')); // Not HTML ?></h1>

<div id="primary">
<?php echo get_option('newsletter_thankyou_page_message'); // HTML ?>
</div>

<?php echo foot(); ?>