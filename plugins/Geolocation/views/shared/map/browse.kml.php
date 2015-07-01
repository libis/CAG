<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<kml xmlns="http://earth.google.com/kml/2.0">
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
        //Zend_Session::start();
        $session = new Zend_Session_Namespace('pagination_help');
       
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $locationsArray = array();
        if ($session->from == 'solr' || $session->from == 'show') {
            $locations = $session->locations;
        }else{
            foreach(loop('item',$items) as $item):        
                $locationsArray[] = $locations[$item->id];    
            endforeach;
            $locations = $locationsArray;
        }
       
        foreach($locations as $location):
       
        ?>
        <Placemark>            
            <description><![CDATA[<?php echo $location['item_id']; ?>]]></description>
            
            <Point>
                <coordinates><?php echo $location->longitude; ?>,<?php echo $location->latitude; ?></coordinates>
            </Point>
            <?php if ($location->address): ?>
            <address><![CDATA[<?php echo $location->address; ?>]]></address>
            <?php endif; ?>
        </Placemark>
        <?php endforeach; ?>
       
    </Document>
</kml>