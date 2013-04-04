<?php head(array('title' => 'Beeldbank Kaart','bodyid'=>'map','bodyclass' => 'browse')); ?>


<div id="primary">
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
<br>
<h3>Aantal beelden op de kaart: <?php echo $totalItems; ?> </h3>
<br>



<div id="map-block">
    <?php echo geolocation_google_map('map-display', array('loadKml'=>true));?>
</div><!-- end map_block -->

<!--<div id="link_block">
    <p>Vind een object op de kaart</p>
    <div id="map-links">
</div>-->

</div><!-- end primary -->

<div class="mapsInfoWindow" style="display:hidden">
    <div class="infoWindow">
    <?php 
    if($_POST['id']):
        
        set_current_item(get_item_by_id($_POST['id']));
        $item = get_current_item();
        if(digitool_item_has_digitool_url($item)){
            echo link_to_item(digitool_get_thumb($item, true, false,100,"bookImg"));
        }
        echo "<strong>".link_to_item(item('Item Type Metadata','Objectnaam',array('snippet' => 30)))."</strong>";
        echo "<strong><br>".item('Dublin Core', 'Title',array('snippet' => 30))."</strong>";
        echo "<br>".item('Dublin Core', 'Description',array('snippet' => 200))."";        
    endif;
    ?>
    </div>
    
</div>    

<?php foot(); ?>