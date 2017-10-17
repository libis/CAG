<?php
echo head(array(
    'title' => metadata('exhibit_page', 'title') . ' &middot; ' . metadata('exhibit', 'title'),
    'bodyclass' => 'exhibits show'));
?>
<div id="primary">
    
    <h1><?php echo metadata('exhibit', 'title'); ?> <span id="print-exhibit"><a href="<?php echo exhibit_builder_exhibit_uri($exhibit).'?action=print'; ?>"><img width="15" src="<?php echo img('print.png');?>"></a></span></h1>


    <div id="nav-left">
    	<?php
        $page = get_current_record('exhibit_page');
        if($page->parent_id){//echo exhibit_builder_section_nav();
            echo "<div id='nav-section'>".exhibit_builder_link_to_parent_page()."</div>";
        }else{
            echo "<div id='nav-section'><a href='".url(metadata('exhibit_page', 'slug'))."'>".metadata('exhibit_page', 'title')."</a></div>";
        }
        ?>

    	<?php
        //get parent
        $parent_page = $page->getParent();
        if(exhibit_builder_child_pages($parent_page) && exhibit_builder_child_pages($page)){
            echo exhibit_builder_child_page_nav($parent_page);
        }
        if(exhibit_builder_child_pages($parent_page) && !exhibit_builder_child_pages($page)){
            echo libis_exhibit_nav();
        }
        ?>

    	<br><p ><?php echo exhibit_builder_link_to_exhibit(null,"Terug naar de inhoudstafel");?></p>
    </div>
		<div id="exhibit-page">

		<h2><?php echo metadata('exhibit_page', 'title'); ?></h2>

		<?php exhibit_builder_render_exhibit_page(); ?>

	</div>
</div>
<?php echo foot(); ?>
