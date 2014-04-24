<?php
echo head(array(
    'title' => metadata('exhibit_page', 'title') . ' &middot; ' . metadata('exhibit', 'title'),
    'bodyclass' => 'exhibits show'));
?>
<div id="primary">
    <div id="breadcrumb">
        <ul>
            <li>
                <a href="<?php echo html_escape(url('/')); ?>">Home</a> >
            </li>
             <li>
                 <a href="<?php echo html_escape(url('verhalen'));?>">Verhalen</a> >
            </li>            
                 <?php echo Libis_breadcrumb_tag($exhibit); ?>
            <li>
                 <a href="<?php echo html_escape(url('exhibits/show/' . metadata('exhibit', 'slug')));?>"><?php echo metadata('exhibit', 'title'); ?></a> >
            </li>
            <li>
                <?php echo exhibit_builder_link_to_parent_page();?> > 
            </li>
            <li><?php echo metadata('exhibit_page', 'title'); ?></li>
        </ul>
    </div>
    <h1><?php echo metadata('exhibit', 'title'); ?></h1>


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
        if(exhibit_builder_child_pages($parent_page)){
            echo exhibit_builder_child_page_nav($parent_page); 
        }?>
        
    	<br><p ><?php echo exhibit_builder_link_to_exhibit(null,"Terug naar de inhoudstafel");?></p>
    </div>
		<div id="exhibit-page">

		<h2><?php echo metadata('exhibit_page', 'title'); ?></h2>
		<?php //echo exhibit_builder_link_to_next_exhibit_page("Volgende"); ?>
		<?php exhibit_builder_render_exhibit_page(); ?>


	</div>
</div>
<?php echo foot(); ?>