<?php echo head(array('title' => 'Beeldbank Kaart','bodyid'=>'map','bodyclass' => 'browse')); ?>
<?php 
     $session = new Zend_Session_Namespace('style');
     $session->from= 'browse'; 
?>

<div id="primary">
<p id="simple-pages-breadcrumbs">
    <a href="/">Home</a> > <a href="/beeldbank">Beeldbank</a>
    > Erfgoed op de kaart
</p>
<h1>Erfgoed op de kaart</h1>

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
<div class="map-left">
    <div id="info"></div>
    <script>jQuery("#info").load('<?php echo url('info');?> #kaart');</script>
</div>
<div class="map-right">
    <form id="beeldbank-search" method="get" action="/solr-search/results/" name="search-form">
    <input id="query" type="text" title="Search" value="" name="q">
    <input type='hidden' name='facet' value='itemtype:"Object"'>
    <input type="submit" value="Zoeken" name="">
    </form><br>
    <h6>Aantal beelden op de kaart: <?php echo $totalItems; ?> </h6>
</div>

<br>
<div id="map-block">
    <?php echo $this->googleMap('map-display', array('loadKml'=>true));?>    
</div><!-- end map_block -->

</div><!-- end primary -->
<div id="handle"></div>


<?php echo foot(); ?>