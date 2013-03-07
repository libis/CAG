<?php head(array('title' => html_escape('Summary of ' . exhibit('title')),'bodyid'=>'exhibit','bodyclass'=>'summary')); ?>
<div id="primary">
<div id="breadcrumb">
    <ul>
        <li><a href="<?php echo html_escape(uri('')); ?>">Home</a> ></li>
        <li><a href="<?php echo html_escape(uri('verhalen'));?>">Verhalen</a> ></li>
        <?php echo Libis_breadcrumb_tag($exhibit); ?>
        <li><?php echo html_escape($exhibit['title']); ?></li>
    </ul>
</div>
<h1><?php echo html_escape(exhibit('title')); ?></h1>
<div id="exhibit_description">
<?php echo exhibit('description'); ?>
<?php echo "<em>Door ".exhibit('Credits')."</em>"; ?>
</div>
<br>
<div id="exhibit-sections">	
	<?php set_exhibit_sections_for_loop_by_exhibit(get_current_exhibit()); ?>
	<h2>Inhoudstafel</h2>	
	<?php $i=1; ?>
	<?php while(loop_exhibit_sections()): ?>
		<?php if (exhibit_builder_section_has_pages()): ?>
	    	<div class="exhibit-sections-item">
    		<?php echo(Libis_get_section_thumb(get_current_exhibit_section()));?>
    		<h3><a href="<?php echo exhibit_builder_exhibit_uri(get_current_exhibit(), get_current_exhibit_section()); ?>"><?php echo ($i.". ".html_escape(exhibit_section('title'))); ?></a></h3>
			<?php echo exhibit_section('description'); ?>
			<?php $i++;?>
		<?php endif; ?>
		</div>
	<?php endwhile; ?>
</div>
</div>
<?php foot(); ?>