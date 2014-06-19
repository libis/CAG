<?php echo head(); ?>
<h1><?php echo html_escape(get_option('simple_contact_form_contact_page_title')); ?></h1>
<div id="primary">
    
<div id="simple-contact">
	<div id="form-instructions">
             <img width="250" src="<?php echo (img("cag_logo.png"));?>">
            <?php
                echo get_option('simple_contact_form_contact_page_instructions');                
            ?>           
	</div>
	<?php echo flash(); ?>
	<form name="contact_form" id="contact-form"  method="post" enctype="multipart/form-data" accept-charset="utf-8">
        
            <fieldset>
                <div class="field">
                    <?php 
                      echo $this->formLabel('message', 'Bericht: ');
                      if(isset($_GET['text']))
                          $message = $_GET['text'];
                     if(isset($_GET['aanvraag'])&& $_GET['aanvraag'] == 1){
                         $message = "Graag, had ik een hoge resolutieversie van object ".html_escape($_GET['id'])." en motiveer deze aanvraag als volgt: (publicatie, verzameling, commercieel, niet-commercieel…). Door deze aanvraag te verzenden, beloof ik de wetgeving over intellectuele eigendomsrechten te respecteren.";
                     }
                      echo $this->formTextarea('message', $message, array('class'=>'textinput','cols'=>'50','rows'=>'20')); ?>
                </div>   
                
                <?php if(isset($_GET['aanvraag'])&& $_GET['aanvraag'] == 1){?>
                <div class="field">
                    <input type="hidden" name="aanvraag" value="true">
                    <table id="motivatie-tabel" style="border:none;background:none;">
                    <?php 
                        echo $this->formLabel('motivatie', 'Motivatie: (verplicht) ');
                        ?>
                    <tr>
                        <td><input type="checkbox" name="motivatie[]" value="Publicatie">Publicatie</td>
                        <td><input type="checkbox" name="motivatie[]" value="Niet-commercieel">Niet-commercieel</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="motivatie[]" value="Verzameling">Verzameling</td>
                         <td><input type="checkbox" name="motivatie[]" value="Commercieel">Commercieel</td>
                    <tr>
                        <td><input type="checkbox" name="motivatie[]" value="Anders">Anders, namelijk:</td>
                       
                        <td><input type="text" name="motivatie-other" size="14" ></td>
                    </tr>
                    </table>   
                </div>
                <?php } ?>  
                
                <div class="field">
                    <?php 
                        echo $this->formLabel('name', 'Naam: (verplicht) ');
                        echo $this->formText('name', $name, array('class'=>'textinput')); ?>
                </div>                
                
                <div class="field">
                    <?php
                    echo $this->formLabel('email', 'E-mail: (verplicht) ');
                    echo $this->formText('email', $email, array('class'=>'textinput')); ?>
                </div>		
            </fieldset>

            <fieldset>		    
                <div class="field">
                  <?php echo $captcha; ?>
                </div>		

                <div class="field">
                  <?php echo $this->formSubmit('send', 'Verzenden'); ?>
                </div>	    
            </fieldset>
	</form>
</div>

</div>
<?php echo foot(); ?>