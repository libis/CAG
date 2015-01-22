<!-- Open Graph Meta Tags for Facebook and LinkedIn Sharing !-->
<?php 
    $item = get_current_record('Item');
    $type = $item->getItemType()->name;
    if (digitool_get_thumb_url($item)) : 
    $og="<meta property='og:image' content='".digitool_get_thumb_url($item)."'/>";    
endif; ?>
<?php echo head(array('title' => metadata('item', array('Dublin Core', 'Title')), 'bodyclass' => 'items show')); ?>

<div id="primary">
<p id="simple-pages-breadcrumbs">
<a href="/">Home</a> > 
<?php 
      // TODO why does typeUrl starts with / and tagUrl doesn't? 
      // TODO this tag url screams for a proper Url builder. (idem for typeUrl and creative commons); This includes all the is_array stuff
      $tagUrl = 'solr-search/results?q=&facet=tag:"';
      $tagUrlEnd = '"';
      $typeUrl = '/solr-search/results?facet=itemtype:"'.$type.'"';
      if($type == 'Object'):?>   
        <a href="/beeldbank">Beeldbank</a> > <a href='<?php echo url($typeUrl);?>'>Objecten</a>
        <?php if(metadata('item', array('Item Type Metadata','Objectnaam'))): ?>
        > <?php echo metadata('item', array('Item Type Metadata','Objectnaam')); ?>
        <?php endif;?>
<?php elseif($type == 'Algemene-info'):?> 
        <a href="/beeldbank">Beeldbank</a> > <a href='<?php echo url($typeUrl);?>'>Algemene info</a>
        > <?php echo ucfirst(metadata('item', array('Dublin Core','Title'))); ?>
<?php elseif($type == 'Collectie'):?> 
        <a href='<?php echo url($typeUrl);?>'><?php echo $type; ?> </a>
        > <?php echo ucfirst(metadata($item,array('Item Type Metadata','Naam instelling'))); ?>
<?php else:?>        
        <a href='<?php echo url($typeUrl);?>'><?php echo $type?></a>
        > <?php echo metadata('item', array('Dublin Core','Title')); ?>
<?php endif;?>
</p>   
    
<!-- OBJECT -->
<?php if($type == 'Object'):?>
    <!-- The following returns all of the files associated with an item. -->
    <?php echo libis_get_image($item);?>    
   
    <div class="clearfix"></div>
    <?php if(metadata('item',array('Dublin Core','Identifier')) != ""){?>
    	<h3>CAG-objectnummer:</h3><p><?php echo (metadata('item',array('Dublin Core','Identifier')));?></p>
    <?php } ?>
    <?php if(metadata('item', array('Item Type Metadata','Objectnaam')) != ""){?>
   	 	<h3>Objectnaam:</h3><p><?php echo ucfirst(metadata('item', array('Item Type Metadata','Objectnaam')));?></p>
    <?php } ?>
    <?php if(metadata('item', array('Dublin Core','Title')) != ""){?>
    	<h3>Titel:</h3><p><?php echo (metadata('item', array('Dublin Core','Title')));?></p>
    <?php } ?>
    <?php if(metadata('item', array('Dublin Core','Description')) != ""){?>
    	<h3>Beschrijving:</h3><p><?php echo (metadata('item', array('Dublin Core','Description')));?></p>
    <?php } ?>
    <?php if(metadata('item', array('Dublin Core','Provenance')) != ""){?>
   		<h3>Bewaarinstelling:</h3><p><?php echo (metadata('item', array('Dublin Core','Provenance')));?></p>
    <?php } ?>
    <?php if(metadata('item', array('Item Type Metadata','Collectie')) != ""){?>
   	 	<h3>Collectie:</h3><p><?php echo ucfirst(metadata('item', array('Item Type Metadata','Collectie')));?></p>
    <?php } ?>   
    <?php if(metadata('item', array('Dublin Core','Spatial Coverage')) != ""){?>
    	<h3>Plaats:</h3><p><?php echo (metadata('item', array('Dublin Core','Spatial Coverage')));?></p>
    <?php } ?>
    <?php if(metadata('item', array('Dublin Core','Date')) != ""){?>
    	<h3>Datering:</h3><p><?php echo (metadata('item', array('Dublin Core','Date')));?></p>
    <?php } ?>
    <?php if(metadata('item', array('Dublin Core','Creator')) != ""){?>
    	<h3>Vervaardiger:</h3><p><?php echo (metadata('item', array('Dublin Core','Creator')));?></p>
    <?php } ?>
        
    <?php if (metadata('item', 'has tags')): ?>
    <h3>Trefwoorden</h3>
    <p> <?php
        $tags = $item->Tags;
      
        if(is_array($tags)):
            foreach($tags as $tag):
                echo "<a rel='nofollow' href='".url($tagUrl.$tag.$tagUrlEnd)."'>".$tag."</a>";
                if ($tag !== end($tags))
                    echo ', ';
            endforeach;
        else:
            echo "<a rel='nofollow' href='".url($tagUrl.$tags.$tagUrlEnd)."'>".$tags."</a>";
        endif;
    ?>
    </p>
    <?php endif;?>	
        
    <?php if(metadata('item', array('Dublin Core','References')) != ""){?>
   	 	<h3>Referenties:</h3><p><?php echo ucfirst(metadata('item', array('Dublin Core','References')));?></p>
    <?php } ?> 

    
        
        
	<br>
	<?php
            $nummer= metadata('item', array('Dublin Core','Identifier'));
            $link= url("contact")."/?aanvraag=1&id=".$nummer;
	?>
	<p>Klik <a href="<?php echo $link;?>">hier</a> om een hogeresolutieversie van bovenstaande afbeelding aan te vragen.</p>
	<br>
	

<?php endif;?>

<!-- CONCEPT -->
<?php if($type == 'Algemene-info'):?>   
       
    <h3><?php echo ucfirst(metadata($item, array('Dublin Core','Title')));?></h3>       
    <br>
        <!-- The following returns all of the images associated with an item. -->
        <?php if(metadata('item', 'has files') || digitool_item_has_digitool_url($item)){?>
          
            <div id="itemfiles" class="element">
                <?php if (metadata('item', 'has files')): ?>
                <div class="element-text"><?php echo files_for_item(array("imageSize"=>"fullsize")); ?></div>
                <?php endif; ?>
                <?php if (digitool_item_has_digitool_url($item)){?>
                     <?php
                        $altItem_id = digitool_find_items_with_same_pid($item);
                        $altItem = get_record_by_id('item',$altItem_id);
                    ?>
                    <div class="element-text"> <?php //echo digitool_get_thumb(get_current_item(),false,true,500);?>
                        <?php echo digitool_simple_gallery($item,500);?>
                    </div>
                <?php } 
                if(metadata('item',array('Dublin Core','License')) != ""){
                    $link = metadata('item',array('Dublin Core','License'));
                    $img = str_replace("http://creativecommons.org/licenses/","https://i.creativecommons.org/l/",$link);
                    $img .= "88x31.png";
                    echo "<p><a href='".$link."'><img src='".$img."'></a></p>";
                }   
            ?>
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
            <br><h3>Meer algemene info</h3>
                        
            <div class="cycle" id="cycle2">
            <ul class="rotator2">    
                
            <?php foreach($items as $c_item){?>           
                <li>
                <a href="<?php echo url("items/show/".$c_item->id);?>"><div class="cycle-container"><img class="carousel-image" src="<?php echo digitool_get_thumb_url($c_item);?>"></div></a>

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
    <!-- The following returns all of the files associated with an item. -->
    <?php echo libis_get_image($item);?> 

    <div class="clearfix"></div>
    
    <?php if(metadata($item,array('Item Type Metadata','Naam instelling')))?>
        <h1><?php echo metadata($item,array('Item Type Metadata','Naam instelling'),array('delimiter'=>'; '))?></h1>
    
    <table id="collecties">
        <tr>       
        <?php if(metadata($item,array('Item Type Metadata','Type Organisatie'))):?>
            <td><label>Collectietype:</label></td><td><?php echo metadata($item,array('Item Type Metadata','Type Organisatie'),array('delimiter'=>'; '))?></td>
        </tr>
         <?php endif; ?>
        <tr>       
        <?php if(metadata($item,array('Item Type Metadata','Collectiefocus'))):?>
            <?php                   
                $focus = str_replace(array('<br/>', '<br>'), '',  metadata($item,array('Item Type Metadata','Collectiefocus'),array('delimiter'=>'; ')));        
            ?>
            <td><label>Collectiefocus:</label></td><td><?php echo $focus;?></td>
        </tr>
        <?php endif; ?>
        <tr>       
        <?php if(metadata($item,array('Item Type Metadata','Soort erfgoed'))):?>
            <td><label>Soort erfgoed:</label></td><td><?php echo metadata($item,array('Item Type Metadata','Soort erfgoed'),array('delimiter'=>'; '))?></td>
        </tr>
        <?php endif; ?>
        <tr>       
        <?php if(metadata($item,array('Item Type Metadata','Beschrijving'))):?>
            <td><label>Beschrijving:</label></td><td><?php echo metadata($item,array('Item Type Metadata','Beschrijving'),array('delimiter'=>'; '))?></td>
        </tr>
        <?php endif; ?>
        <tr>
            <?php if(metadata($item,array('Item Type Metadata','Straat + Nr')))?>
            <td><label>Adresgegevens:</label></td>
            <td><?php echo metadata($item,array('Item Type Metadata','Straat + Nr'))?><br>
                <?php echo metadata($item,array('Item Type Metadata','Postcode'))?>, <?php echo metadata($item,array('Item Type Metadata','Stad'))?><br>
                <?php echo metadata($item,array('Item Type Metadata','Provincie'))?><br>
            </td>
        </tr><tr>       
        <?php if(metadata($item,array('Item Type Metadata','Telefoon'))):?>
            <td><label>Telefoon:</label></td><td><?php echo metadata($item,array('Item Type Metadata','Telefoon'),array('delimiter'=>'; '))?></td>
        </tr>
        <?php endif; ?>
        <tr>
        <?php if(metadata($item,array('Item Type Metadata','Website'))):?>
            <td><label>Website:</label></td><td><?php echo metadata($item,array('Item Type Metadata','Website'),array('delimiter'=>'; '))?></td>
        </tr>
        <?php endif; ?>
        <tr>
        <?php if(metadata($item,array('Item Type Metadata','E-mail'))):?>
            <td><label>E-mail:</label></td><td><?php echo metadata($item,array('Item Type Metadata','E-mail'),array('delimiter'=>'; '))?></td>
        </tr>
        <?php endif; ?>
    </table>
<?php endif;?>

<!-- NIEUWSBERICHT -->
<?php if($type == 'Nieuwsbericht'):?>
    <h1><?php echo metadata('item', array('Dublin Core', 'Title')); ?></h1>
    
    <!-- The following returns all of the files associated with an item. -->
    <?php if (metadata('item', 'has files')): ?>
    <div id="itemfiles" class="element">       
        <div class="element-text"><?php echo files_for_item(array("imageSize"=>"fullsize")); ?></div>
    </div>
    <?php endif; ?>

    <?php if(metadata('item', array('Dublin Core','Description')) != ""){?>
    	<p><?php echo (metadata('item', array('Dublin Core','Description')));?></p>
    <?php } ?>
<?php endif;?>

<!-- AGENDAPUNT -->
<?php if($type == 'Agendapunt'):?>
    <h1><?php echo metadata('item', array('Dublin Core', 'Title')); ?></h1>
    
    <!-- The following returns all of the files associated with an item. -->
    <?php if (metadata('item', 'has files')): ?>
    <div id="itemfiles" class="element">       
        <div class="element-text"><?php echo files_for_item(array("imageSize"=>"fullsize")); ?></div>
    </div>
    <?php endif; ?>

    <?php if(metadata('item', array('Dublin Core','Description')) != ""){?>
    	<p><?php echo (metadata('item', array('Dublin Core','Description')));?></p>
    <?php } ?>
<?php endif;?>

<!-- PUBLICATIE -->
<?php if($type == 'Publicatie'):?>
     <h1><?php echo metadata('item', array('Dublin Core', 'Title')); ?></h1>
    
    <!-- The following returns all of the files associated with an item. -->
    <?php if (metadata('item', 'has files')): ?>
    <div id="itemfiles" class="element">       
        <div class="element-text"><?php echo files_for_item(array("imageSize"=>"fullsize")); ?></div>
    </div>
    <?php endif; ?>
    <?php if(metadata('item', array('Dublin Core','Description')) != ""){?>
    <h3>Beschrijving</h3><p><?php echo (metadata('item', array('Dublin Core','Description')));?></p>
    <?php } ?>
    <?php if(metadata('item', array('Dublin Core','Creator')) != ""){?>
    <h3>Auteur</h3><p><?php echo (metadata('item', array('Dublin Core','Creator'),array('delimiter'=>', ')));?></p>
    <?php } ?>
    <?php if(metadata('item', array('Dublin Core','Contributor')) != ""){?>
    <h3>Medewerker</h3><p><?php echo (metadata('item', array('Dublin Core','Contributor'),array('delimiter'=>', ')));?></p>
    <?php } ?>
    <?php if(metadata('item', array('Dublin Core','Publisher')) != ""){?>
    <h3>Uitgever</h3><p><?php echo (metadata('item', array('Dublin Core','Publisher')));?></p>
    <?php } ?>
    <?php if(metadata('item', array('Dublin Core','Date')) != ""){?>
    <h3>Datum</h3><p><?php echo (metadata('item', array('Dublin Core','Date')));?></p>
    <?php } ?>
    <?php if(metadata('item', array('Dublin Core','Format')) != ""){?>
    <h3>Formaat</h3><p><?php echo (metadata('item', array('Dublin Core','Format')));?></p>
    <?php } ?>
    <?php if(metadata('item', array('Item Type Metadata','Prijs')) != ""){?>
    <h3>Prijs</h3><p><?php echo (metadata('item', array('Item Type Metadata','Prijs')));?> &euro;</p>
    <?php }?>    
    

<?php endif;?>

<!-- PROJECT -->
<?php if($type == 'Project'):?>
     <h1><?php echo metadata('item', array('Dublin Core', 'Title')); ?></h1>
    
    <!-- The following returns all of the files associated with an item. -->
    <?php if (metadata('item', 'has files')): ?>
    <div id="itemfiles" class="element">       
        <div class="element-text"><?php echo files_for_item(array("imageSize"=>"fullsize")); ?></div>
    </div>
    <?php endif; ?>

    <?php if(metadata('item', array('Dublin Core','Description')) != ""){?>
    	<p><?php echo (metadata('item', array('Dublin Core','Description')));?></p>
    <?php } ?>
<?php endif;?>


<!-- The following prints a list of all tags associated with the item -->
<!-- The following prints a list of all tags associated with the item -->
<?php if (metadata('item', 'has tags') && $type != "Object"): ?>
<div id="item-tags" class="element">
    <h3>Trefwoorden</h3>
    <div class="element-text">
    <?php
        $tags = $item->Tags;
      
        if(is_array($tags)):
            foreach($tags as $tag):
                echo "<a rel='nofollow' href='".url($tagUrl.$tag.$tagUrlEnd)."'>".$tag."</a>";
                if ($tag !== end($tags))
                    echo ', ';
            endforeach;
        else:
            echo "<a rel='nofollow' href='".url($tagUrl.$tags.$tagUrlEnd)."'>".$tags."</a>";
        endif;
    ?>
    </div>                                              
</div>
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
