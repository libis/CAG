<?php echo head(array(
    'title' => metadata('simple_pages_page', 'title'),
    'bodyclass' => 'page simple-page',
    'bodyid' => metadata('simple_pages_page', 'slug')
    )); 
    $title = metadata('simple_pages_page', 'title');
?>
<div id="primary">
	<?php if($title != "Home" && $title != "Beeldbank" && $title != "Werktuigen" && $title != "Alle verhalen"){ ?>
            <div id="nav-left">		
                <?php 
                    //if(Libis_get_simple_pages_nav())
                        //echo Libis_get_simple_pages_nav();
                    echo simple_pages_navigation(metadata('simple_pages_page', 'parent_id'));
                ?>
            </div>	
	<?php } ?>
	<div id="page">	    
	    <p id="simple-pages-breadcrumbs"><?php echo simple_pages_display_breadcrumbs(); ?></p>
	    
            <div id="page-content">
	    <h1><?php echo metadata('simple_pages_page', 'title'); ?></h1>
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