<!-- Open Graph Meta Tags for Facebook and LinkedIn Sharing !-->
<?php 
    $item = get_current_record('Item');
    $type = $item->getItemType()->name;
    if (digitool_get_thumb_url($item)) : 
    $og="<meta property='og:image' content='".digitool_get_thumb_url($item)."'/>";    
endif; ?>
<?php echo head(array('title' => metadata('item', array('Dublin Core', 'Title')), 'bodyclass' => 'items show')); ?>

<div id="primary">
<!-- OBJECT -->
<?php if($type == 'Object'):?>
    <p id="simple-pages-breadcrumbs">
        <a href="/">Home</a> > <a href="/beeldbank">Beeldbank</a> > <a href="/beeldbank">Objecten</a>
        > <?php echo metadata('item', array('Item Type Metadata','Objectnaam')); ?>
    </p>
    

    <!-- The following returns all of the files associated with an item. -->
        <?php if(metadata('item', 'has files') || digitool_item_has_digitool_url($item)){?>

            <div id="itemfiles" class="element">
                <?php if (metadata('item', 'has files')): ?>
                <div class="element-text"><?php echo files_for_item(); ?></div>
                <?php endif; ?>
                <?php if (digitool_item_has_digitool_url($item)):?>
                        <div class="element-text"> <?php //echo digitool_get_thumb(get_current_item(),false,true,500);?>
                                <?php echo digitool_simple_gallery($item,500);?>
                        </div>
                <?php endif; ?>
            </div>
        <?php } ?>

	<div class="clearfix"></div>
    <?php if(metadata('item',array('Dublin Core','Identifier')) != ""){?>
    	<h3>CAG-objectnummer:</h3><p><?php echo (metadata('item',array('Dublin Core','Identifier')));?></p>
    <?php } ?>
    <?php if(metadata('item', array('Item Type Metadata','Objectnaam')) != ""){?>
   	 	<h3>Objectnaam:</h3><p><?php echo ucfirst(metadata('item', array('Item Type Metadata','Objectnaam')));?></p>
    <?php } ?>
    <?php if(metadata('item', array('Dublin Core','Abstract')) != ""){?>
    	<h3>Titel:</h3><p><?php echo (metadata('item', array('Dublin Core','Abstract')));?></p>
    <?php } ?>
    <?php if(metadata('item', array('Dublin Core','Description')) != ""){?>
    	<h3>Beschrijving:</h3><p><?php echo (metadata('item', array('Dublin Core','Description')));?></p>
    <?php } ?>
    <?php if(metadata('item', array('Dublin Core','Provenance')) != ""){?>
   		<h3>Bewaarplaats:</h3><p><?php echo (metadata('item', array('Dublin Core','Provenance')));?></p>
    <?php } ?>
    <!-- moet objectnaam worden -->            
    
    <?php if(metadata('item', array('Dublin Core','Title')) != ""){?>
   	 	<h3>Titel:</h3><p><?php echo ucfirst(metadata('item', array('Dublin Core','Title')));?></p>
    <?php } ?>            

    <?php if(metadata('item', array('Dublin Core','Date')) != ""){?>
    	<h3>Datering:</h3><p><?php echo (metadata('item', array('Dublin Core','Date')));?></p>
    <?php } ?>
	 <?php if(metadata('item', array('Dublin Core','Spatial Coverage')) != ""){?>
    	<h3>Plaats:</h3><p><?php echo (metadata('item', array('Dublin Core','Spatial Coverage')));?></p>
    <?php } ?>

    <!-- The following prints a list of all tags associated with the item -->
	<?php if (metadata('item', 'has tags')): ?>
	<div id="item-tags" class="element">
		 <h3>Trefwoorden</h3>
		<div class="element-text">
                <?php
                    $tags = $item->Tags;
                    //natcasesort($tags);
                 
                    if(is_array($tags)){
                        foreach($tags as $tag){
                            echo "<a href='".url("solr-search/results?q=&facet=tag:\"".$tag."\"")."'>".$tag."</a>";
                            if ($tag !== end($tags))
                                echo ', ';
                        }
                    }else{
                        echo "<a href='".url("solr-search/results?q=&facet=tag:\"".$tags."\"")."'>".$tags."</a>";
                    }
                ?>
                </div>                                              
	</div>
	<?php endif;?>	
        
        
	<br>
	<?php
            $nummer= metadata('item', array('Dublin Core','Identifier'));
            $link= url("contact")."/?aanvraag=true&id=".$nummer;
	?>
	<p>Klik <a href="<?php echo $link;?>">hier</a> om een hogeresolutieversie van bovenstaande afbeelding aan te vragen.</p>
	<br>
	

<?php endif;?>

<!-- CONCEPT -->
<?php if($type == 'Concept'):?>
    <p id="simple-pages-breadcrumbs">
        <a href="/">Home</a> > <a href="/beeldbank">Beeldbank</a> > <a href="/beeldbank">Concepten</a>
        > <?php echo metadata('item', array('Dublin Core','Title')); ?>
    </p>
       
    <h3><?php echo ucfirst(metadata($item, array('Dublin Core','Title')));?></h3>       
    <br>
        <!-- The following returns all of the images associated with an item. -->
        <?php if(metadata('item', 'has files') || digitool_item_has_digitool_url($item)){?>
          
            <div id="itemfiles" class="element">
                <?php if (metadata('item', 'has files')): ?>
                <div class="element-text"><?php echo files_for_item(); ?></div>
                <?php endif; ?>
                <?php if (digitool_item_has_digitool_url($item)){?>
                     <?php
                        $altItem_id = digitool_find_items_with_same_pid($item);
                        $altItem = get_record_by_id('item',$altItem_id);
                    ?>
                    <div class="element-text"> <?php //echo digitool_get_thumb(get_current_item(),false,true,500);?>
                        <?php echo digitool_simple_gallery($item,500);?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>    
            <div class="clearfix"></div>    
        <?php if(metadata($item, array('Item Type Metadata','Algemene beschrijving'))){?>
            <h3>Algemene Beschrijving</h3>
            <span class="werktuigblock"><p >
            <?php
                $text1= metadata($item, array('Item Type Metadata','Algemene beschrijving'));
                $text1= str_replace("<br />
<br />","<br>",$text1);
                echo $text1;
            ?>
            </p></span>
        <?php } ?>
        <?php if(metadata('item', array('Item Type Metadata','Technische beschrijving'))){?>
            <br><h3>Technische Beschrijving</h3>
            <span class="werktuigblock"><p >
            <?php
                $text2= metadata($item, array('Item Type Metadata','Technische beschrijving'));
                $text2= str_replace("<br />
<br />","<br>",$text2);
                echo $text2;
            ?>
            </p></span>
        <?php } 
        
        $items = libis_get_similar_objects($item);
        
        if($items){?>
            <br><h3>Gelijkaardige concepten</h3>
                        
            <div class="cycle" id="cycle2">
            <ul class="rotator2">    
                
            <?php foreach($items as $c_item){?>           
                <li>
                <?php echo link_to_item(digitool_get_thumb_for_home($c_item),array(),'show',$c_item);?>
                <span class="similar-title"><?php echo link_to_item(metadata($c_item, array('Dublin Core','Title'),array()),array(),'show',$c_item);?></span>
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
        
          
        <?php if(metadata('item', array('Item Type Metadata','Referenties')) != ""){?>
            <h3>Referenties</h3>
            <span class="werktuigblock"><p >
            <?php
                $text3= metadata('item', array('Item Type Metadata','Referenties'));
                $text3= str_replace("<br /><br />","<br>",$text3);
                echo $text3;
            ?>
            </p></span>
        <?php } ?>
<?php endif;?>

<!-- COLLECTIE -->
<?php if($type == 'Collectie'):?>

<?php endif;?>

<!-- NIEUWSBERICHT -->
<?php if($type == 'Nieuwsbericht'):?>

<?php endif;?>

<!-- AGENDAPUNT -->
<?php if($type == 'Agendapunt'):?>

<?php endif;?>

<!-- PUBLICATIE -->
<?php if($type == 'Publicatie'):?>

<?php endif;?>

<!-- PROJECT -->
<?php if($type == 'Project'):?>

<?php endif;?>



<?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>

<nav>
<ul class="item-pagination navigation">
    <li id="previous-item" class="previous"><?php echo link_to_previous_item_show('&larr; Vorige'); ?></li>
    <li id="next-item" class="next"><?php echo link_to_next_item_show('Volgende &rarr;'); ?></li>
</ul>
</nav>
</div>
<?php echo foot(); ?>
