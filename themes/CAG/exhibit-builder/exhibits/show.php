<?php head(array('title' => html_escape(exhibit('title') . ' : '. exhibit_page('title')), 'bodyid'=>'exhibit','bodyclass'=>'show')); ?>

<div id="primary">
    <div id="breadcrumb">
        <ul>
            <li>
                <a href="<?php echo html_escape(uri('')); ?>">Home</a> >
            </li>
             <li>
                 <a href="<?php echo html_escape(uri('verhalen'));?>">Verhalen</a> >
            </li>            
                 <?php echo Libis_breadcrumb_tag($exhibit); ?>
            <li>
                 <a href="<?php echo html_escape(uri('exhibits/show/' . $exhibit['slug']));?>"><?php echo html_escape($exhibit['title']); ?></a> >
            </li>
            <li>
                <a href="<?php echo html_escape(uri('exhibits/show/' . $exhibit['slug'].'/' . $exhibitSection['slug']));?>"><?php echo html_escape($exhibitSection['title']); ?></a> >
            </li>
            <li><?php echo html_escape(exhibit_page('title')); ?></li>
        </ul>
    </div>
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