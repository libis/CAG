<?php head(); ?>
<h1><?php echo settings('simple_contact_form_contact_page_title'); ?></h1>
<div id="primary">
    
<div id="simple-contact">
	<div id="form-instructions">
		<?php echo get_option('simple_contact_form_contact_page_instructions'); // HTML ?>
	</div>
	<?php echo flash(); ?>
	<form name="contact_form" id="contact-form"  method="post" enctype="multipart/form-data" accept-charset="utf-8">
        
        <fieldset>
            
        <div class="field">
		<?php 
		    echo $this->formLabel('name', 'Naam: ');
		    echo $this->formText('name', $name, array('class'=>'textinput')); ?>
		</div>
		
        <div class="field">
            <?php 
            echo $this->formLabel('email', 'E-mail: ');
		    echo $this->formText('email', $email, array('class'=>'textinput'));  ?>
        </div>
        
		<div class="field">
		  <?php 
		    echo $this->formLabel('message', 'Bericht: ');
		    echo $this->formTextarea('message', $message, array('class'=>'textinput')); ?>
		</div>    
		
		</fieldset>
		
		<fieldset>
		    
		<div class="field">
		  <?php echo $captcha; ?>
		</div>		
		
		<div class="field">
		  <?php echo $this->formSubmit('send', 'Send'); ?>
		</div>
	    
	    </fieldset>
	</form>
</div>

</div>
<?php foot(); ?>