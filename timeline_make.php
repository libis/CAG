<?php
    require_once('index.php'); 
        
    $contents =file_get_contents('http://www.hetvirtueleland.be/cag_test/neatline-time/timelines/items/1?output=neatlinetime-json');
    file_put_contents(FILES_DIR.'/data_1.json',$contents); 
?>