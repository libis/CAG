<div class="mapsInfoWindow" style="display:hidden">
    <div class="infoWindow">
    <?php 
    if(isset($_POST['id'])):        
        $item = get_record_by_id('item',$_POST['id']);
       
        if(digitool_item_has_digitool_url($item)){
            echo link_to_item(digitool_get_thumb($item, true, false,100,"bookImg"),array(),'show',$item);
        }
        echo "<strong>".link_to_item(metadata($item,array('Item Type Metadata','Objectnaam'),array('snippet' => 30)),array(),'show',$item)."</strong>";
        echo "<strong><br>".metadata($item,array('Dublin Core', 'Title'),array('snippet' => 30))."</strong>";
        echo "<br>".metadata($item,array('Dublin Core', 'Description'),array('snippet' => 200))."";        
    endif;
    ?>
    </div>    
</div>  
