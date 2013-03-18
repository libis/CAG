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
    <?php echo geolocation_google_map('map-display', array('loadKml'=>true, 'list'=>'map-links'));?>
</div><!-- end map_block -->

<!--<div id="link_block">
    <p>Vind een object op de kaart</p>
    <div id="map-links">
</div>-->

</div><!-- end primary -->

<?php foot(); ?>