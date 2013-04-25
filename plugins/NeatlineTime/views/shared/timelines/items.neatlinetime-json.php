<?php
/**
 * The shared neatlinetime-json browse view for Items
 */
$neatlineTimeEvents = array();

while (loop_items()) {
    $itemTitle = neatlinetime_get_item_text('item_title');
    $itemLink = item_uri();
    $itemDescription = item('Dublin Core', 'Description',array('snippet' => 200));

    
    //get dates
    $itemDates = item('Dublin Core', 'Date','all');   

    /*if ($file = get_db()->getTable('File')->findWithImages(item('id'), 0)) {
        $fileUrl = file_display_uri($file, 'square_thumbnail'); 
    }*/
    if (!empty($itemDates)) {
      foreach ($itemDates as $itemDate) {
           
            $neatlineTimeEvent = array();
            $itemDate = preg_replace("/[^0-9]/","", $itemDate);
            if(strlen($itemDate)==4){
            	$date = $itemDate."-01-01";
            }
            else{
            	if(strlen($itemDate)==8){
            		$first_half = substr($itemDate,0,4);
            		$second_half = substr($itemDate,4);
            		$itemDate = $first_half."-01-01/".$second_half."-01-01";
                        $dateArray = explode('/', $itemDate);
                        $date = $dateArray[0];
            	}
            } 
           
           

            if ($date){
                $neatlineTimeEvent['start'] = $date;//$dateArray[0];

                //if (count($dateArray) == 2) {
                    //   $neatlineTimeEvent['end'] = $dateArray[1];
                //}
                  
                $neatlineTimeEvent['title'] = $itemTitle;
                $neatlineTimeEvent['link'] = $itemLink;
                $neatlineTimeEvent['classname'] = neatlinetime_item_class();
                $neatlineTimeEvent['description'] = $itemDescription;
                $neatlineTimeEvents[] = $neatlineTimeEvent;
                //image - Joris
                /*if(digitool_item_has_digitool_url(get_current_item())){
                	$imgUrl = digitool_get_thumb_url(get_current_item());
                }
                   
                $neatlineTimeEvent['image'] = $imgUrl;              

                if ($fileUrl) {
                    $neatlineTimeEvent['image'] = $fileUrl;
                }                
                 
                */
            }           
        }            
    }
}

$neatlineTimeArray = array();
$neatlineTimeArray['dateTimeFormat'] = "iso8601";
$neatlineTimeArray['events'] = $neatlineTimeEvents;
$neatlinetimeJson = json_encode($neatlineTimeArray);

echo $neatlinetimeJson;