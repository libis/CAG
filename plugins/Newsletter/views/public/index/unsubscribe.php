<?php
echo head();
?>
<div id="primary">
<p id="simple-pages-breadcrumbs">
	<a href="<?php echo url("/");?>">Home</a>
	> Nieuwsbrief - Uitschrijven
	</p>    
        <h1>Nieuwsbrief - Uitschrijven</h1>
        <?php if($html):
            echo $html;
        else:?>
        <?php echo flash(); ?>
        <p>Gebruik onderstaand formulier om je uit te schrijven.</p>
	<form name="uitschrijf_form" id="contact-form"  method="post" accept-charset="utf-8">
        
        <fieldset>
            <div class="field"> 
                <label>E-mail:</label>
                <input size="50" type="text" name="email">
            </div>           
        </fieldset>

        <fieldset>
		    
            <div class="field">
              <?php //echo $captcha; ?>
            </div>		

            <div class="field">
              <?php echo $this->formSubmit('send', 'Uitschrijven'); ?>
            </div>

        </fieldset>
	</form>
        <?php endif;?>
</div>
<?php
echo foot();
?>
