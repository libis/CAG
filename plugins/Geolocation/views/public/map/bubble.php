<?php
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$path = parse_url($url, PHP_URL_PATH);
$pathFragments = explode('/', $path);
$end = end($pathFragments);
$id = $end;

 ?>
<div class="mapsInfoWindow" style="display:hidden">
    <div class="infoWindow">
    <?php
    if(isset($id)):
        $item = get_record_by_id('item',$id);
        echo "<h4>".link_to_item(metadata($item,array('Item Type Metadata','Objectnaam'),array('snippet' => 30)),array(),'show',$item)."</h4>";
        if(digitool_item_has_digitool_url($item)){
            echo link_to_item(digitool_get_thumb($item, true, false,100,"bookImg"),array(),'show',$item);
        }
        echo "<strong>".metadata($item,array('Dublin Core', 'Title'),array('snippet' => 30))."</strong>";
        echo "<br>".metadata($item,array('Dublin Core', 'Description'),array('snippet' => 200))."";
        echo "<br>".link_to_item("Lees meer",array(),'show',$item);
    endif;
    ?>
    </div>
</div>
