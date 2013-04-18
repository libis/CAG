<?php
/**
 * The public show view for Timelines.
 */
queue_timeline_assets();
$head = array('bodyclass' => 'timeline',
              'title' => timeline('title')
              );
head($head);
?>
<div id="primary">
    <?php echo $this->partial('timelines/_timeline.php'); ?>
    
    <div class="clearfix">&nbsp;</div>
</div>               
<?php foot();?>