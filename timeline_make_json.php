<?php
    require_once('index.php'); 
        
    file_put_contents(FILES_DIR.'/data_1.json',admin_url().'/neatline-time/timelines/items/1?output=neatlinetime-json');
    
?>
