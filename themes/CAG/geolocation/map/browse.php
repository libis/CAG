<?php echo head(array('title' => 'Beeldbank Kaart','bodyid'=>'map','bodyclass' => 'browse')); ?>
<?php 
     $session = new Zend_Session_Namespace('style');
     $session->from= 'browse'; 
?>

<div id="primary">
<h1>Beeldbank</h1>

<ul class="items-nav navigation" id="secondary-nav">
	<?php //echo custom_nav_items(); ?>
 	<?php /*
 		echo nav(
     		array(
            	'Zoeken' => url('beeldbank'),
            	'Kaart' => url('items/map/')

            )
		);*/
	?>
</ul>
<br>
<h3>Aantal beelden op de kaart: <?php echo $totalItems; ?> </h3>
<br>
<div id="map-block">
    <?php echo $this->googleMap('map-display', array('loadKml'=>true));?>    
</div><!-- end map_block -->

</div><!-- end primary -->
<div id="handle"></div>


<?php echo foot(); ?>