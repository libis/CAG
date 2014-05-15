<?php echo head(); ?>
<div id="primary">
<p id="simple-pages-breadcrumbs">
	<a href="<?php echo url();?>">Home</a>
	> Registreer voor de Nieuwsbrief
	</p>    
        <h1>Nieuwsbrief - Registreer</h1>
<div id="simple-contact">
	<div id="form-instructions">
		<p><?php echo get_option('newsletter_contact_page_instructions'); // HTML ?></p>
	</div>
	<?php echo flash(); ?>
	<form name="contact_form" id="contact-form"  method="post" accept-charset="utf-8">
        
        <fieldset>
            <?php foreach($elements as $element):?>
               
                <?php if($element->name == 'Nieuwsbrief' or $element->name == 'Activiteiten'):?>
                    <div class="field"> 
                        <label><?php echo $element->name; ?></label>
                        <input type="checkbox" name="<?php echo $element->name; ?>"><br>
                    </div>
                <?php else:?>
                    <div class="field">
                        <?php 
                            echo $this->formLabel($element->name, $element->name.': ');
                            echo $this->formText($element->name, $sticky[$element->name], array('class'=>'textinput')); ?>
                    </div>
                <?php endif;?>
            <?php endforeach;?>
        </fieldset>

        <fieldset>
		    
            <div class="field">
              <?php echo $captcha; ?>
            </div>		

            <div class="field">
              <?php echo $this->formSubmit('send', 'Registreer'); ?>
            </div>

        </fieldset>
	</form>
</div>

</div>
<?php echo foot(); ?>