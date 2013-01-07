<?php head(array('title' => html_escape(exhibit('title') . ' : '. exhibit_page('title')), 'bodyid'=>'exhibit','bodyclass'=>'show')); ?>

<div id="primary">
	<h1><?php echo exhibit('title'); ?></h1>


    <div id="nav-left">
    	<?php //echo exhibit_builder_section_nav();?>
  		<div id="nav-section"><?php echo exhibit_builder_link_to_exhibit(null,exhibit_section('title')); ?></div>
    	<?php echo exhibit_builder_page_nav();?>
    	<br><p ><?php echo exhibit_builder_link_to_exhibit(null,"Terug naar de inhoudstafel");?></p>
    </div>
		<div id="exhibit-page">

		<h2><?php echo exhibit_page('title'); ?></h2>
		<?php //echo exhibit_builder_link_to_next_exhibit_page("Volgende"); ?>
		<?php exhibit_builder_render_exhibit_page(); ?>


	</div>
</div>
<?php foot(); ?>