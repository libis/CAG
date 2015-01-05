<?php echo head(); ?>
<div id="primary">
    <h1><?php echo html_escape(get_option('simple_contact_form_contact_page_title')); ?></h1>
    
<div id="simple-contact">
    
    <div id="form-instructions">
            <?php echo get_option('simple_contact_form_contact_page_instructions'); // HTML ?>
    </div>
    <?php echo flash(); ?>
    
    
    <form name="contact_form" id="contact-form"  method="post" enctype="multipart/form-data" accept-charset="utf-8">
    <div id="publicatie-persoon">
    <fieldset>

    <div class="field">
            <?php 
                echo $this->formLabel('name', 'Naam:* ');
                echo $this->formText('naam', $naam, array('class'=>'textinput')); ?>
            </div>
    <div class="field">
            <?php 
                echo $this->formLabel('name', 'Voornaam:* ');
                echo $this->formText('voornaam', $voornaam, array('class'=>'textinput')); ?>
            </div>		
    <div class="field">
        <?php 
                echo $this->formLabel('E-mail', 'E-mail:* ');
                echo $this->formText('email', $email, array('class'=>'textinput'));  ?>
    </div>        
    <div class="field">
        <?php 
                echo $this->formLabel('Straat + nr', 'Straat + nr:* ');
                echo $this->formText('straat', $straat, array('class'=>'textinput'));  ?>
    </div> 
    <div class="field">
        <?php 
                echo $this->formLabel('Postcode', 'Postcode:* ');
                echo $this->formText('postcode', $postcode, array('class'=>'textinput'));  ?>
    </div>     
    <div class="field">
        <?php 
                echo $this->formLabel('Gemeente', 'Gemeente:* ');
                echo $this->formText('gemeente', $gemeente, array('class'=>'textinput'));  ?>
    </div> 
    <div class="field">
        <?php 
                echo $this->formLabel('Land', 'Land:* ');
                echo $this->formText('land', $land, array('class'=>'textinput'));  ?>
    </div>	
    </fieldset>
</div>
    <div id="publicatie-bestelling">
        <h4>Bestelt:</h4>
            <table>
                <tr><th>Titel</th><th>Prijs</th><th>Aantal</th><th>Bedrag</th></tr>
                <?php
                $pubs = get_records('Item',array('featured'=>true,'type'=>'Publicatie'),5000);
                foreach($pubs as $pub){?>
                <tr>
                <?php 
                    $titel = metadata($pub,array('Dublin Core','Title'));
                    $prijs = metadata($pub,array('Item Type Metadata','Prijs'));
                    echo "<td>".$titel."</td>";
                    echo "<td>".$prijs. "&euro;</td>";
                    echo "<td>".$this->formText('publicatie['.$titel.'][aantal]', '0', array('class'=>'textinput','size'=>'3'))."</td>";
                    echo "<td>".$this->formText('publicatie['.$titel.'][bedrag]', '0', array('class'=>'textinput','size'=>'3'))." &euro;</td>";
                ?>
                </tr>                  
                <?php } ?>
            </table>    
                  
    </div>    

    <fieldset>
        <?php if ($captcha): ?>
        <div class="field">
            <?php echo $captcha; ?>
        </div>
        <?php endif; ?>

        <div class="field">
          <?php echo $this->formSubmit('send', 'Verzend bestelling'); ?>
        </div>	    
    </fieldset>
    </form>
</div>

</div>
<?php echo foot();
