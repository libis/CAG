<!-- Open Graph Meta Tags for Facebook and LinkedIn Sharing !-->
<?php if (digitool_get_thumb_url(get_current_item())) : 
    $og="<meta property='og:image' content='".digitool_get_thumb_url(get_current_item())."'/>";
    
endif; ?>

<!-- End Open Graph Meta Tags !-->
<?php head(array('title' => item('Dublin Core', 'Title'), 'bodyid'=>'items','bodyclass' => 'show','og'=>$og)); ?>


<div id="primary">
    <?php if(get_current_item()==null){
        set_current_item(get_item_by_id($_GET['id']));
    }?>
    
<!-- IF ITEM IS OF TYPE 'CONCEPT' -->
<?php

if(item_has_type('Concept')){?>
    <?php if(item('Item Type Metadata','Ouder') || get_current_item()->id ==48380){
        echo  Libis_get_werktuigen_tree(get_current_item()->id);
    }    
    ?>
    
    <div id="concept">
        <!--<base target="_parent">
        <h1 id="normal-title">Werktuigen - <?php echo (item('Dublin Core','Title'))?></h1>
        -->    
	    
        <h4><?php echo ucfirst(item('Dublin Core','Title'));?></h4>
        <p><br><?php echo (item('Item Type Metadata','Scope'))?></p>


        <?php if(item('Item Type Metadata','Alternatieve term(en)') != ""){?>
            <h4>Alternatieve term(en)</h4>
            <p><br><?php echo (item('Item Type Metadata','Alternatieve term(en)'))?></p>
        <?php } ?>

        <!-- The following returns all of the images associated with an item. -->
        <?php if(item_has_files()||digitool_item_has_digitool_url(get_current_item())){?>
            <h4>Afbeeldingen</h4><br>
            <div id="itemfiles" class="element">
                <?php if(item_has_files()){ ?>
                <div class="element-text"><?php echo display_files_for_item(); ?></div>
                <?php } ?>
                <?php if (digitool_item_has_digitool_url(get_current_item())){?>
                        <?php
                                $altItem_id = digitool_find_items_with_same_pid(get_current_item());
                                $altItem = get_item_by_id($altItem_id);
                        ?>
                        <div class="element-text"><?php echo link_to_item(digitool_get_thumb(get_current_item(),false,false,400),array(),'show',$altItem);?></div>
                <?php } ?>
            </div>
        <?php } ?>

        <?php if(item('Item Type Metadata','Algemene beschrijving')){?>
            <h4>Algemene Beschrijving</h4>
            <span class="werktuigblock"><p >
            <?php
                $text1= item('Item Type Metadata','Algemene beschrijving');
                $text1= str_replace("<br />
<br />","<br>",$text1);
                echo $text1;
            ?>
            </p></span>
        <?php } ?>
        <?php if(item('Item Type Metadata','Technische beschrijving')){?>
            <br><h4>Technische Beschrijving</h4>
            <span class="werktuigblock"><p >
            <?php
                $text2= item('Item Type Metadata','Technische beschrijving');
                $text2= str_replace("<br />
<br />","<br>",$text2);
                echo $text2;
            ?>
            </p></span>
        <?php } 
        
        $items = Libis_get_similar_objects(get_current_item());
        
        if($items){?>
            <br><h4>Gelijkaardige concepten</h4>
                        
            <div class="cycle" id="cycle2">
            <ul class="rotator2">    
                
            <?php foreach($items as $item){?>           
                <li>
                <?php echo link_to_item(digitool_get_thumb_for_home($item),array(),'show',$item);?>
                </li>
            <?php }; ?>
            </ul>
            <a href="#" class="prev p2"></a>
            <a href="#" class="next n2"></a>
            <div class="description"></div>
            <div class="thumbnail"></div>
            </div>
            <script>
            jQuery(document).ready(function($) {
                $('.rotator2').roundabout({
                    btnNext: ".n2",
                    btnPrev: ".p2",
                    minScale:0.2,
                    maxScale:0.8
                });
            });
            </script>
        <?php } ?>
        
          
        <?php if(item('Item Type Metadata','Referenties') != ""){?>
            <br><h4>Referenties</h4>
            <span class="werktuigblock"><p >
            <?php
                $text3= item('Item Type Metadata','Referenties');
                $text3= str_replace("<br />
<br />","<br>",$text3);
                echo $text3;
            ?>
            </p></span>
        <?php } ?>
            <?php echo plugin_append_to_items_show(); ?>
</div>


<?php } ?>
<!-- END ITEM TYPE CONCEPT -->

<!-- IF ITEM TYPE OBJECT -->
<?php if(item_has_type('Object')){?>
    <h1>Beeldbank</h1>

    <ul class="items-nav navigation" id="secondary-nav">
            <?php //echo custom_nav_items(); ?>
            <?php
            	echo nav(
             		array(
               			'Zoeken' => uri('beeldbank/'),
               			'Kaart' => uri('items/map/')

             		)
           		);
            ?>
	</ul>
    

    <!-- The following returns all of the files associated with an item. -->
        <?php if(item_has_files()||digitool_item_has_digitool_url(get_current_item())){?>

            <div id="itemfiles" class="element">
                <?php if(item_has_files()){ ?>
                <div class="element-text"><?php echo display_files_for_item(); ?></div>
                <?php } ?>
                <?php if (digitool_item_has_digitool_url(get_current_item())){?>
                        <div class="element-text"> <?php //echo digitool_get_thumb(get_current_item(),false,true,500);?>
                                <?php echo digitool_simple_gallery($item,500);?>
                        </div>
                <?php } ?>
            </div>
        <?php } ?>

	<div class="clearfix"></div>
    <?php if(item('Dublin Core','Identifier') != ""){?>
    	<h3>CAG-objectnummer:</h3><p><?php echo (item('Dublin Core','Identifier'));?></p>
    <?php } ?>
    <?php if(item('Dublin Core','Abstract') != ""){?>
    	<h3>Titel:</h3><p><?php echo (item('Dublin Core','Abstract'));?></p>
    <?php } ?>
    <?php if(item('Dublin Core','Description') != ""){?>
    	<h3>Beschrijving:</h3><p><?php echo (item('Dublin Core','Description'));?></p>
    <?php } ?>
    <?php if(item('Dublin Core','Provenance') != ""){?>
   		<h3>Instelling:</h3><p><?php echo (item('Dublin Core','Provenance'));?></p>
    <?php } ?>
    <?php if(item('Dublin Core','Title') != ""){?>
   	 	<h3>Objectnaam:</h3><p><?php echo (item('Dublin Core','Title'));?></p>
    <?php } ?>

    <?php if(item('Dublin Core','Date') != ""){?>
    	<h3>Datering:</h3><p><?php echo (item('Dublin Core','Date'));?></p>
    <?php } ?>
	 <?php if(item('Dublin Core','Spatial Coverage') != ""){?>
    	<h3>Plaats:</h3><p><?php echo (item('Dublin Core','Spatial Coverage'));?></p>
    <?php } ?>

    <!-- The following prints a list of all tags associated with the item -->
	<?php if (item_has_tags()): ?>
	<div id="item-tags" class="element">
		<h3>Trefwoorden</h3>
		<div class="element-text">
                <?php
                    $tags = get_tags(array('record' => $item), 20);
                    natcasesort($tags);
                    echo Libis_tag_string($tags,uri('solr-search/results/?style=gallery&solrfacet=tag:'));
                ?></div>
	</div>
	<?php endif;?>

	<?php echo plugin_append_to_items_show(); ?>
	<br>
	<?php
		$nummer= item('Dublin Core','Identifier');
		$text= "Graag, had ik een hoge resolutieversie van object ".$nummer. " en motiveer deze aanvraag als volgt: (publicatie, verzameling, commercieel, niet-commercieel…). Door deze aanvraag te verzenden, beloof ik de wetgeving over intellectuele eigendomsrechten te respecteren.";

		$link= uri("contact")."/?text=".$text;

	?>
	<p>Klik <a href="<?php echo $link;?>">hier</a> om een hogeresolutieversie van bovenstaande afbeelding aan te vragen.</p>
	<br>
	<ul class="item-pagination navigation">
	    <li id="previous-item" class="previous"><?php echo link_to_previous_item('Vorige'); ?></li>
	    <li id="next-item" class="next"><?php echo link_to_next_item('Volgende'); ?></li>
	</ul>

<?php }?>
</div><!-- end primary -->

<?php foot(); ?>