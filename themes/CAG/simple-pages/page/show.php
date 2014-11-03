<?php echo head(array(
    'title' => metadata('simple_pages_page', 'title'),
    'bodyclass' => 'page simple-page',
    'bodyid' => metadata('simple_pages_page', 'slug')
    )); 
    $title = metadata('simple_pages_page', 'title');
?>
<div id="primary">
     <p id="simple-pages-breadcrumbs"><?php echo simple_pages_display_breadcrumbs(); ?></p>
	<?php if($title != "Home" && $title != "Beeldbank" && $title != "Werktuigen" && $title != "Alle verhalen"){ ?>
            <div id="nav-left" class="nav-left-simple">		
                <?php 
                    if(metadata('simple_pages_page', 'parent_id')==0){
                        echo simple_pages_navigation(null);
                    }else{
                        echo simple_pages_navigation(metadata('simple_pages_page', 'parent_id'));
                    }
                    
                ?>
            </div>	
	<?php } ?>
        <?php if($title == "Beeldbank"){ ?>
            <div id="nav-left">		
                <div class="simple-pages-navigation">
                    <ul class="navigation">
                        <li class="active"><a href="<?php echo url("/beeldbank") ?>">Beeldbank</a></li>   
                        <li><a href="<?php echo url("/geolocation/map/browse") ?>">Kaart</a></li>
                        <!--<li><a href="<?php //echo url("/beeldbank/tijdlijn") ?>">Tijdlijn</a></li>-->                   
                </div>
            </div>	
	<?php } ?>
	<div id="page">	 
            
	    <div id="page-content">
	    <h1><?php echo $title; ?></h1>
	    <?php
            $text = metadata('simple_pages_page', 'text', array('no_escape' => true));
            if (metadata('simple_pages_page', 'use_tiny_mce')) {
                echo $text;
            } else {
                echo eval('?>' . $text);
            }
            ?>
           
            </div>
    </div>
</div>
<?php echo foot(); ?>