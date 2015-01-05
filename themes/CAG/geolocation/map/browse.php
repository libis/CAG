<?php echo head(array('title' => 'Beeldbank Kaart','bodyid'=>'map','bodyclass' => 'browse')); ?>
<?php 
     $session = new Zend_Session_Namespace('style');
     $session->from= 'browse'; 
?>

<?php
$formAttributes['action'] = $_SERVER['REQUEST_URI'];
$formAttributes['method'] = 'GET';
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
   <?php echo libis_get_simple_page_content('info-kaart'); ?>
</div>
<div class="map-right">   
    <form id="beeldbank-search" <?php echo tag_attributes($formAttributes); ?>>        
        <?php
            echo $this->formText(
                'search',
                @$_REQUEST['search'],
                array('id' => 'query', 'size' => '40')
            );
        ?>        
        <input type="submit" class="submit" name="submit_search" id="submit_search_advanced" value="<?php echo __('Search'); ?>" />      
    </form><br>
    <h6>Aantal beelden op de kaart: <?php echo $totalItems; ?> </h6>
</div>

<br>
<div id="map-block">
    <?php echo $this->googleMap('map-display', array('loadKml'=>true));?>    
</div><!-- end map_block -->

</div><!-- end primary -->
<div id="handle"></div>
<div id="search_block">
    <?php //echo search_form(array('form_attributes'=>array('action'=>$_SERVER['REQUEST_URI'])));
//echo items_search_form(array('id'=>'search'), $_SERVER['REQUEST_URI']); ?>
</div><!-- end search_block -->



<?php echo js_tag('items-search'); ?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        Omeka.Search.activateSearchButtons();
    });
</script>

<?php echo foot(); ?>