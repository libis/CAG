<?php
/* OMEKA PLUGIN RelateedTagCloud:
 * Adds a Tag cloud made up of related tags to the browse page,
 *
 */
//set the location
add_plugin_hook('public_append_to_items_browse','relatedTagCloud_add');

function relatedTagCloud_get($itemsBrowse,$size=50){

        //print_r($itemsBrowse);
	if($itemsBrowse==null){
            $itemsBrowse = get_items_for_loop();
        }
        
	$hasTags = false;
	//check the tags of every item on the browsepage
	foreach ($itemsBrowse as $item){		
            $item = get_item_by_id($item);
            //don't go further if it doens't have tags
            if(item_has_tags($item)){

                // Sam: Proberen om geheugen te besparen
                release_object($tagsItem);

                //get the item's tags
                $tagsItem = get_tags(array('record'=>$item,'sort'=>'most'));
                //var_dump($tagsItem);
                $hasTags = true;
                //saves all current tags so we can remove them from the cloud later
                $doubleTags = array_merge($tagsItem,(array)$doubleTags);
                //for each tag get items with the same tags
                foreach($tagsItem as $key=>$item_tag){
                        // Sam: Proberen om geheugen te besparen
                        release_object($items);

                        // Sam: aan item_tag [name] toegevoegd om de array kleiner te maken
                        $items = get_items(array('tags'=>$item_tag['name']));
                        set_items_for_loop($items);
                            if (has_items_for_loop() && sizeof($tags)<=($size+sizeof($doubletags))):
                                while (loop_items()):
                                    $tagsNew = get_tags(array('sort'=>'most', 'record'=>get_current_item()));
                                    $tags = array_merge($tagsNew,(array)$tags);
                                    $tags = array_unique($tags);
                                endwhile;
                            endif;
                }
            }
            // Sam: Proberen om geheugen te besparen
            release_object($tagsItem);
            release_object($tagsNew);
            release_object($items);
	}
	release_object($itemsBrowse);
	if(!$hasTags){
            return " ";
	}else{               
            
            $tags = array_diff($tags, $doubleTags);
           
            // Sam: Proberen om geheugen te besparen
            release_object($doubleTags);

            if(empty($tags))
                    return " ";
            $tags = tag_cloud($tags, uri('solr-search/results/?style=gallery&solrfacet=tag:'));
            return $tags;
	}
}

//the plug-in's output
function relatedTagCloud_add($items=null) {
    $tags=relatedTagCloud_get($items);
    echo "<p>";
    echo $tags;
    echo "</p>";
}
?>