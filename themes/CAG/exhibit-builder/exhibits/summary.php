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

<h1><?php echo html_escape($exhibit['title']); ?> <span id="print-exhibit"><a href="<?php echo exhibit_builder_exhibit_uri($exhibit).'?action=print'; ?>"><img width="15" src="<?php echo img('print.png');?>"></a></span>
</h1>
<div id="exhibit_description">
 <?php
 if($exhibit->thumbnail){
    echo '<img width="350" src="'.img($exhibit->thumbnail,'images/verhalen_thumbs').'"/>';
}?>
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
