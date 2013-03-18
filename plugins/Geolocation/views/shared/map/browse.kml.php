<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<kml xmlns="http://earth.google.com/kml/2.2">
    <Document>
        <name>Omeka Items KML</name>
        <?php /* Here is the styling for the balloon that appears on the map */ ?>
        <Style id="item-info-balloon">
            <BalloonStyle>
                <text><![CDATA[
                    <div class="mapsInfoWindow img">

                        $[description]

                    </div>
                ]]></text>
            </BalloonStyle>
        </Style>

        <?php
        while(loop_items()):
        $item = get_current_item();
        $locationR = $locations[$item->id];
        foreach($locationR as $location){
        ?>
        <Placemark>            
            <description><![CDATA[<?php
	            if(digitool_item_has_digitool_url($item)){
	            	echo link_to_item(digitool_get_thumb($item, true, false,100,"bookImg"));
	            }
                    echo "<strong>".link_to_item(item('Item Type Metadata','Objectnaam',array('snippet' => 30)))."</strong>";
	            echo "<strong><br>".item('Dublin Core', 'Title',array('snippet' => 30))."</strong>";
	            echo "<br>".item('Dublin Core', 'Description',array('snippet' => 200))."";
	            //echo "<div class='bookYear'>".item('Item Type Metadata', 'Periode')."";
	            ?>
            ]]></description>
            
            <Point>
                <coordinates><?php echo $location['longitude']; ?>,<?php echo $location['latitude']; ?></coordinates>
            </Point>
            <?php if ($location['address']): ?>
            <address><![CDATA[<?php echo $location['address']; ?>]]></address>
            <?php endif; ?>
        </Placemark>
        <?php } endwhile; ?>
    </Document>
</kml>