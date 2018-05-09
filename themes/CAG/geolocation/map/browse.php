<?php
queue_js_url("https://maps.google.com/maps/api/js?sensor=false");
queue_js_file('map');


$css = "
            #map-display {
                height: 436px;
            }
            .balloon {width:400px !important; font-size:1.2em;}
            .balloon .title {font-weight:bold;margin-bottom:1.5em;}
            .balloon .title, .balloon .description {float:left; width: 220px;margin-bottom:1.5em;}
            .balloon img {float:right;display:block;}
            .balloon .view-item {display:block; float:left; clear:left; font-weight:bold; text-decoration:none;}
            #link_block {
                display:none;
            }
            #search_block {
                clear: both;
            }";
queue_css_string($css);

echo head(array('title' => __('Browse Map'),'bodyid'=>'map','bodyclass' => 'browse')); ?>

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
    <?php echo $this->googleMap('map-display', array('loadKml'=>true, 'list'=>'map-links'));?>
</div><!-- end map_block -->

<div id="link_block">
    <div id="map-links"><h2><?php echo __('Find An Item on the Map'); ?></h2></div><!-- Used by JavaScript -->
</div><!-- end link_block -->

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
