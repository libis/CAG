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
                <tr class="publication-tr">
                <?php 
                    $titel = link_to_item(metadata($pub,array('Dublin Core','Title')), array('target'=>'_blank'),'show', $pub);
                    $prijs = metadata($pub,array('Item Type Metadata','Prijs'));
                    echo "<td>".$titel."</td>";
                    echo "<td><div class='bestel-prijs'>".$prijs."</div> &euro;<input type='hidden' name='publicatie[".$titel."][prijs]' value='".$prijs."'></td>";
                    echo "<td>".$this->formText('publicatie['.$titel.'][aantal]', '0', array('class'=>'textinput bestel-input','size'=>'3'))."</td>";
                    echo "<td><div class='bestel-bedrag'>0</div> &euro;</td>";
                ?>
                </tr>  
                
                <?php } ?>               
            </table>    
            <br><b>Totaal: <div id="bestel-totaal">0</div> &euro;</b>
    </div>    
        
    <script>
        jQuery( document ).ready(function() {
            var totaal;
            jQuery(".publication-tr").each(function(){              
               var prijs = jQuery(this).find('.bestel-input');               
               var bedrag =  jQuery(this).find(".bestel-bedrag");
               var nieuwBedrag;
               
               var ppe = jQuery(this).find(".bestel-prijs").html();
               prijs.data('oldVal',prijs.val());

               prijs.bind("propertychange keyup input paste",function(event){
                    // If value has changed...
                    if (prijs.data('oldVal') != prijs.val()) {
                        // Updated stored value
                        prijs.data('oldVal', prijs.val());
                        nieuwBedrag = prijs.val()*ppe;               
                        bedrag.html(nieuwBedrag);
                    }
                    getTotal();
               });          
               
            });
                       
            function getTotal(){
                var totaal = 0;
                jQuery(".bestel-bedrag").each(function(){
                    var number = jQuery(this).text();
                    totaal = totaal + Number(number);
                });
                jQuery("#bestel-totaal").html(totaal);
            }
            
        });
    </script>

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
