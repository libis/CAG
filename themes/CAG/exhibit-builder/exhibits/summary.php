<?php echo head(array('title' => html_escape($exhibit->title),'bodyid'=>'exhibit','bodyclass'=>'summary')); ?>
<div id="primary">
<div id="breadcrumb">
    <ul>
        <li><a href="<?php echo html_escape(url('')); ?>">Home</a> ></li>
        <li><a href="<?php echo html_escape(url('verhalen'));?>">Verhalen</a> ></li>
        <?php echo libis_breadcrumb_tag($exhibit); ?>
        <li><?php echo html_escape($exhibit->title); ?></li>
    </ul>
</div>
<h1><?php echo html_escape($exhibit['title']); ?></h1>
<div id="exhibit_description">
<?php echo $exhibit->description; ?>
<?php echo "<em>Door ".$exhibit->credits."</em>"; ?>
</div>
<br>
<div id="exhibit-sections">	
	
	<h2>Inhoudstafel</h2>	
	<?php $i=1; ?>
	    <?php set_exhibit_pages_for_loop_by_exhibit(); ?>
        <?php foreach (loop('exhibit_page') as $exhibitPage): ?>
	    	<div class="exhibit-sections-item">
    		<?php //echo(Libis_get_section_thumb(get_current_exhibit_section()));?>
              
        <?php echo exhibit_builder_page_summary($exhibitPage); ?>
          
    		<!--<h3><a href="<?php echo exhibit_builder_exhibit_uri(get_current_exhibit(), get_current_exhibit_section()); ?>"><?php echo ($i.". ".html_escape(exhibit_section('title'))); ?></a></h3>
			<?php echo exhibit_section('description'); ?>
			<?php $i++;?>
		-->
		</div>
	<?php endforeach; ?>
</div>
<?php addThis_add(); ?>
</div>
<?php echo foot(); ?>