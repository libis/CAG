<?php
echo head(array('bodyclass' => 'timelines primary', 
              'title' => 'Reload Timeline'));
?>
<p>Click the button below to load new records into the timeline. (this might take a few minutes)</p>
<button class="green" value="Reload JSON" id="reload">Update Timeline (json)</button>
<div id="data-reload" style="display:none;"></div>
<div id="result-reload"></div>
<div id="saved">
    <?php
        if($saved):
            echo "Update complete";
        endif;
    ?>
</div>


<script>
    jQuery(document).ready(function(){
        jQuery("#reload").click(function(){
            i = 0;
            interval = setInterval(function() {
                 i = ++i % 4;
                 jQuery("#result-reload").html("loading"+Array(i+1).join("."));
            }, 500);
            var url = "<?php echo url('/neatline-time/timelines/items/'.$timeline->id.'?output=neatlinetime-json'); ?>"; 
            
            jQuery("#data-reload").load(url,function(data){                
                var json = data;              
                json_array = {json:data};
                jQuery("#result-reload").load("<?php echo url('/neatline-time/timelines/reload/'.$timeline->id.' #saved'); ?>",json_array,function(){
                    clearInterval(interval);
                });
            });
        });    
    });
</script>
<?php 
echo foot();
?>
