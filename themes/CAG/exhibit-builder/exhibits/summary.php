<?php
if(isset($_GET['action'])){
    if($_GET['action']=='print'){
        queue_css_file('print_exhibit');                
        echo head_css();
        echo libis_get_exhibit_for_print($exhibit);
        exit();
    }
}
?>
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
        <div class="exhibit-sections-item">
        <ul id="inhoudstafel">
            <?php set_exhibit_pages_for_loop_by_exhibit(); ?>
            <?php foreach (loop('exhibit_page') as $exhibitPage): ?>
            
            <?php echo exhibit_builder_page_summary($exhibitPage); ?>
           
            <?php endforeach; ?>
        </ul>	
        </div>        
</div>
<?php //addThis_add(); ?>
</div>
<?php echo foot(); ?>