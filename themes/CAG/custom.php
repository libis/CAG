<?php

/**
* Custom function to retrieve any number of random featured items.
*
* @param int $num The number of random featured items to return
* @param boolean $withImage Whether to return items with derivative images. True by default.
* @return array An array of Omeka Item objects.
*/
function Libis_get_random_featured_items($num = '10', $withImage = true)
{
   // Get the database.
    $db = get_db();

    // Get the Item table.
    $table = $db->getTable('Item');

    // Build the select query.
    $select = $table->getSelect();
    $select->from(array(), 'RAND() as rand');
    $select->where('items.item_type_id = 6');
    $select->order('rand DESC');
    $select->limit($num);

    // If we only want items with derivative image files, join the File table.
    if ($withImage) {
        $select->joinLeft(array('f'=>"$db->DigitoolUrls"), 'f.item_id = items.id', array());
    }

    // Fetch some items with our select.
    $items = $table->fetchObjects($select);

    return $items;
}

 /**
  * Returns the HTML markup for displaying a random featured item.  Most commonly
  * used on the home page of public themes.
  *
  * @since 0.10
  * @param boolean $withImage Whether or not the featured item should have an image associated
  * with it.  If set to true, this will either display a clickable square thumbnail
  * for an item, or it will display "You have no featured items." if there are
  * none with images.
  * @return string HTML
  **/
function Libis_display_random_featured_item($withImage=false)
 {
    $featuredItem = random_featured_item($withImage);
 	$html = '<h2>Object in de kijker</h2>';
 	if ($featuredItem) {
 	    $itemTitle = item('Dublin Core', 'Title', array(), $featuredItem);

 	   //$html .= '<h3>' . link_to_item($itemTitle, array(), 'show', $featuredItem) . '</h3>';
 	   if (item_has_thumbnail($featuredItem)) {
 	       $html .= link_to_item(item_square_thumbnail(array(), 0, $featuredItem), array('class'=>'image'), 'show', $featuredItem);
 	   }
 	   $html .= '<h3>' . link_to_item($itemTitle, array(), 'show', $featuredItem) . '</h3>';

 	   // Grab the 1st Dublin Core description field (first 150 characters)
 	   if ($itemDescription = item('Dublin Core', 'Description', array('snippet'=>150), $featuredItem)) {
 	       $html .= '<p class="item-description">' . $itemDescription . '</p>';
       }
 	} else {
 	   $html .= '<p>No featured items are available.</p>';
 	}

     return $html;
 }

 /**
 * Returns the HTML of a random featured exhibit
 *
 * @return exhibit info in HTML
 **/
function Libis_display_random_featured_exhibit()
{
    $html = '<div id="featured-exhibit">';
    $featuredExhibit = exhibit_builder_random_featured_exhibit();
    $html .= '<h2>Verhaal in de kijker</h2>';
    if ($featuredExhibit) {
       $html .= '<h3>' . exhibit_builder_link_to_exhibit($featuredExhibit) . '</h3>'."\n";
       $html .= '<p>'.snippet_by_word_count(exhibit('description', array(), $featuredExhibit)).'</p>';
    } else {
       $html .= '<p>You have no featured exhibits.</p>';
    }
    $html .= '</div>';
    $html = apply_filters('exhibit_builder_display_random_featured_exhibit', $html);
    return $html;
}

/**
* Custom function to retrieve a list of exhibits with a given tag
*
* Expects a hack in plugin.php of the exhibitBuilder plugin
*
* @param $tag, the tag used to filter between exhibits
* @return html formatting (with images) of the list
*/
function Libis_get_exhibits($tag = "")
{
	$html="";
        if($tag=="main"){
		$html= '<center><table class="exhibit_general_list"><tr><td>';
                //get current exhibit
                $exhibit = get_record_by_id('Exhibit',100010,999);               
                $html.= '<p><a href="'.url("verhalen/landbouw").'">Landbouw</a></p>';
                if($exhibit->thumbnail){
                            $html.= '<a href="'.url("verhalen/landbouw").'"><img width="200" src="'.img($exhibit->thumbnail,'images/verhalen_thumbs').'"/></a>';
                }
                               
                $html.= '</td><td>';               
              
                //get current exhibit
                $exhibit = get_record_by_id('Exhibit',100150,999);
                $html.= '<p><a href="'.url("verhalen/voeding").'">Voeding</a></p>';
                if($exhibit->thumbnail){
                        $html.= '<a href="'.url("verhalen/voeding").'"><img width="200" src="'.img($exhibit->thumbnail,'images/verhalen_thumbs').'"/></a>';
                }
                //takes care of the link and text                
		$html.= '</td></tr></table></center>';
                return $html;
	}
        if($tag == ""){
		$exhibits = get_records('Exhibit',array('sort_field'=>'id','sort_dir'=>'d'),999);
	}else{
		$exhibits = get_records('Exhibit',array('tags' =>$tag),999);
		//if there were no exhibits found
		if(empty($exhibits)){
			return "<p>We're sorry but there were no stories found with this tag</p>";
		}
	}
	//tag 'algemeen' has different formatting then the others
	
        if($tag=="algemeen" ){
            $html= '<center><table class="exhibit_general_list"><tr>';

            foreach($exhibits as $exhibit) {                   
                if($exhibit->thumbnail){
                    $html.= '<td><p>'.(exhibit_builder_link_to_exhibit($exhibit, $exhibit->title)).'</p>';
                    $html.= exhibit_builder_link_to_exhibit($exhibit,'<img width="175" src="'.img($exhibit->thumbnail,'images/verhalen_thumbs').'"/></td>');
                }		    
            }
            $html.= '</tr></table></center>';   
            return $html;
        }else{
            $html= '<ul class="exhibit_tag_list">';

            foreach($exhibits as $exhibit) {

                $html.= '<li>';
                //set current exhibit
                //exhibit_builder_set_current_exhibit($exhibit);

                if($exhibit->thumbnail){
                    $html.= exhibit_builder_link_to_exhibit($exhibit,'<img width="200" src="'.img($exhibit->thumbnail,'images/verhalen_thumbs').'"/>');
                }
                //takes care of the link and text
                $html.= '<p>'.(exhibit_builder_link_to_exhibit($exhibit, $exhibit->title)).'</p>';
                //$html.= '<p>'.truncate(exhibit('description', array(), $exhibit),280).'</p>';
                $html.= '<p>'.metadata($exhibit,'description',array('snippet'=>'280')).'</p>';    
                $html.= '</li>';

            }
            $html.= '</ul>';
		
	}
        return $html;
}
/**
* Custom function to retrieve a thumb of an exhibit
*
* Expects a hack in plugin.php of the exhibitBuilder plugin
*
* @return html formatting (with images) of the thumb (same as the item thumbnail function)
*/
function libis_get_exhibit_thumb($exhibit,$props=array()){

	if($exhibit->thumbnail){

		$html= '<img class="carousel-image" width="200" src="'.img($exhibit->thumbnail,'images/verhalen_thumbs').'"/>';

	}else{

		$html=false;
	}

	return $html;
}

/**
* Custom function to retrieve a thumb of an exhibit
*
* Expects a hack in plugin.php of the exhibitBuilder plugin
*
* @return html formatting (with images) of the thumb (same as the item thumbnail function)
*/
function Libis_get_exhibit_thumb_home($exhibit,$props=array()){

	if($exhibit->thumbnail){

		$html= '<img class="carousel-image" src="'.img($exhibit->thumbnail,'images/verhalen_thumbs').'"/>';

	}else{

		$html=false;
	}

	return $html;
}

/**
* Custom function to retrieve a thumb of an exhibit section
*
* Expects a hack in plugin.php of the exhibitBuilder plugin
*
* @return html formatting (with images) of the thumb (same as the item thumbnail function)
*/
function Libis_get_section_thumb($section){

	if($section->thumbnail){
		$item = get_item_by_id($section->thumbnail);

		$html.= digitool_thumbnail($item,true,"100","",item('Dublin Core', 'Title',"",$item));

		//set_current_item($item);
	    //$html= (item_square_thumbnail(array('alt'=>item('Dublin Core', 'Title'))));

	}else{
		$html=false;
	}

	return $html;

}

function libis_get_similar_objects($item_original){
    $parent=metadata($item_original, array('Item Type Metadata','Ouder'));
    $titel = metadata($item_original, array('Dublin Core','Title'));
    if($parent){
        $similars=array();
        $items = get_records('Item',array('type'=>'16'),10000);
        foreach($items as $item){
            if((metadata($item, array('Item Type Metadata','Ouder')) == $parent
                    || metadata($item, array('Dublin Core','Title')) == $parent
                    || metadata($item, array('Item Type Metadata','Ouder')) == $titel
                    ) && $item_original->id != $item->id){
                if(digitool_item_has_digitool_url($item)){
                    $similars[] = $item;
                }    
            }           
        }
        if(!empty($similars))
            return $similars;
        else
            return false;
        
    }else{
        return false;
    }
}

function Libis_get_children($current,$treeAnchor,$childtree,$three){

	$html="";

	$children = $three[$current];

	if(!empty($children)){

		$html="<ul>";
		foreach($children as $child){
			//add the next set of children

			if($child['title'] == $treeAnchor){
				$html.='<li><span class="plus-min"><a href="'.item_uri('show',get_item_by_id($child['id'])).'"><img src="'.img('min.gif').'"/></span>'.$child['title'].'</a></li>';

				$html.= $childtree;

			}else{
				$html.='<li><span class="plus-min"><a href="'.item_uri('show',get_item_by_id($child['id'])).'"><img src="'.img('plus.gif').'"/></span>'.$child['title'].'</a></li>';
			}


		}
		$html.="</ul>";
	}

	return $html;

}

function Libis_get_parents($current,$parent,$threeArray){

	$tree="";

	while($current != 'landbouw'){
		$tree ="<li>".Libis_get_children($current,$treeAnchor,$tree,$threeArray)."</li>";

		//get next parent
		foreach ($threeArray as $key=>$value){

			foreach($value as $row){
				if($row['title'] == $parent){
					$parentNew = $key;
					break(2);
				}
			}
		}

		$treeAnchor = $current;
		$current = $parent;
		$parent = $parentNew;

	}
	//attach the tree to the top landbouw
	$tree ="<li>".Libis_get_children($current,$treeAnchor,$tree,$threeArray)."</li>";
	$tree ='<li><a href="'.item_uri('show',get_item_by_id(48380)).'">Landbouw</a></li>'.$tree;

	return $tree;
}

function Libis_treeIntoArray(){
	$three;

	$db = get_db();
	$select = $db->query("SELECT `i`.`id`,
	(
	    SELECT text
	    FROM `omeka_element_texts`
	    WHERE  `record_id` =`i`.`id` AND `element_id` ='50'
	) AS `title`,
	(
	    SELECT text
	    FROM `omeka_element_texts`
	     WHERE `record_id`=`i`.`id` AND `element_id` ='217'
	) AS `parent`,
	(
	    SELECT text
	    FROM `omeka_element_texts`
	     WHERE `record_id`=`i`.`id` AND `element_id` ='210'
	) AS `volgorde`
	FROM `omeka_items` AS `i`
	INNER JOIN `omeka_item_types` AS `ty` ON i.item_type_id = ty.id
	WHERE (
	ty.id = '16'
	)
	AND (
	i.id
	IN (

	SELECT i.id
	FROM omeka_items i
	LEFT JOIN omeka_element_texts etx ON etx.record_id = i.id
	LEFT JOIN omeka_record_types rty ON etx.record_type_id = rty.id
	WHERE etx.text IS NOT NULL
	AND rty.name = 'Item'
	AND etx.element_id =217
	)
	)
	GROUP BY `i`.`id`
	ORDER BY `volgorde` ASC
	");

	$items = $select->fetchAll();


	foreach($items as $item){
		$three[$item['parent']][] = array('id' => $item['id'], 'title' => $item['title']);
	}
	//die ($test);

	//exit;
	return $three;

}

function Libis_find_key($values,$parent){
	return ($values['title'] == $parent);
}

/**
* Custom function to retrieve the 'Werktuigen'-tree
*
* @return html formatting of the three
*/
function Libis_get_werktuigen_tree($id = null)
{
	$three="";
	$three = Libis_treeIntoArray();

	$html.="<div id='tree'><h4>Hiërarchiebrowser</h4>";
	$html.= "<ul class='uitgelicht-list'>";

	if($id == null){exit;}
	else {
	$item = get_item_by_id($id);
		if($item == null) {exit;}
		else{
		set_current_item($item);
		//var_dump($id);

		$current = item('Dublin Core','Title');
		$parent = item('Item Type Metadata','Ouder');

		//get parents of the selected item
		$html .= Libis_get_parents($current,$parent,$three)."</ul></div>";
		}
	}
	return $html;
}

/**
* Custom function to retrieve the left navigation (simple pages)
*
* @return html formatting of the three
*/
function Libis_get_simple_pages_nav($parentId = 0, $currentDepth = null, $sort = 'order', $requiresIsPublished = true)
{
	$html = '';

	$currentPage = get_current_record('simple page');
	$ancestorPage = simple_pages_earliest_ancestor_page($currentPage->id);

	//gets all toplevel pages
	$pages = get_db()->getTable('SimplePagesPage')->findBy(array('parent_id' => 0, 'sort' => $sort));
	set_simple_pages_for_loop($pages);
	$html .= "<ul class='first'>";

	$id = $currentPage->id;
	//var_dump($id);
	if($id == 5){
		$html .= "<li><a href='".uri('verhalen/')."'>Per tijdvak of per thema</a></li>";
	}
	//loop through all toplevel pages
	while (loop_simple_pages()):
           
            //if menu item equal or is a child of current page display children
            if(simple_page('id') == $currentPage->id || simple_page('id') == $ancestorPage->id){
                $childPageLinks = simple_pages_get_links_for_children_pages($ancestorPage->id, null, $sort, $requiresIsPublished, $requiresIsAddToPublicNav);
                $html .= nav($childPageLinks, $currentDepth);
            }
    endwhile;

    $html .="</ul>";

    simple_pages_set_current_page($currentPage);

    if($html=="<ul class='first'></ul>")
    	return false;
    else
    	return $html;
}

 /**
 * Returns the HTML of a carousel of items, exhibits or themes
 *
 * @param $type string, "exhibit", "items" or "themes", default "items"
 * @return exhibit info in HTML
 **/
function Libis_display_carousel($type, $description)
{
	if($type == 'item'){
		$html.="<div id='item-block'>
	        <h2>Recente objecten</h2>
	        <table><tr><td width='300'>";


	    set_items_for_loop($items = recent_items('10'));
		if (has_items_for_loop()):

	    	$html.="<div id='carousel-container'>
	      <div id='carousel'>";
	        while (loop_items()):

				if (item_has_thumbnail()):
					$html.="<div class='carousel-feature'>";
					$html.= link_to_item(item_square_thumbnail(array('alt'=>item('Dublin Core', 'Title'),'class'=>'thumbnail carousel-image')));
					$html.="</div>";
		        endif;

	      	endwhile;
	      	$html.="</div></div>";
		endif;
	}
	if($type == 'exhibit'){
		$html="<div id='item-block'>
	        <h2>Recente verhalen</h2>
	        <table><tr><td width='300'>";

	    $exhibits = exhibit_builder_recent_exhibits(10);
		$html.="<div id='carousel-container'>
	      <div id='carousel'>";
	    foreach($exhibits as $exhibit) {




				if (Libis_get_exhibit_thumb($exhibit)):
					$html.="<div class='carousel-feature'>";
					$html.= exhibit_builder_link_to_exhibit($exhibit,LIBIS_get_exhibit_thumb($exhibit,array('alt'=>item('Dublin Core', 'Title'),'class'=>'thumbnail carousel-image')));

					$html.="</div>";
		        endif;



		}
		$html.="</div></div>";
	}
	$html.="</td><td><p>";
	$html.=$description;
	$html.="</p></td></tr></table></div>";

	return $html;
}

//translated version of the select function (helpers/FormFunctions.php
function Libis_select($attributes, $values = null, $default = null, $label=null, $labelAttributes = array())
{
    $html = '';
    //First option is always the "Select Below" empty entry
    $values = (array) $values;
    $values = array('' => 'Selecteer') + $values;
    //Duplication
	if ($label) {
	    $labelAttributes['for'] = $attributes['name'];
	    $html .= __v()->formLabel($attributes['name'], $label, $labelAttributes);
	}
    $html .= __v()->formSelect($attributes['name'], $default, $attributes, $values);
    return $html;
}

//temp function to convert the pids table to digitool_url objects
function Libis_set_images(){
	set_time_limit(400);
	//get items with digitool urls in type afbeelding
	$elementId = get_db()->getTable('Element')->findByElementSetNameAndElementName('Item Type Metadata', 'digi1')->id;
	$params = array('advanced_search' =>
			array(
				array('element_id' => $elementId,
				'type' => 'is not empty'

					)
			)
	);
	$db = get_db();
	$items = get_items($params,12780);
	//return(sizeof($items));
	$i=0;
	foreach($items as $item){
		$i++;
		//check if item has digitool has digitool ids
		set_current_item($item);
		//echo item('Item Type Metadata','Digiwerk1');
			//get pid from db

			//$select = $db->query("SELECT item_id FROM omeka_pids WHERE digitool = '".item('Item Type Metadata','Digiwerk1',array(),$item)."'");

			//$pid = $select->fetch();

			//save as digitool obj
			//if(!empty($pid)){

				$bind = array(
				  'item_id'=>$item->id,
				  'pid'=> item('Item Type Metadata','digi1')
				);

				$db->insert('digitool_urls', $bind);
			//}



	}
	return $i;


}

//temp function to convert the pids table to digitool_url objects
function Libis_set_page_images(){
	set_time_limit(400);

	$db = get_db();
	$select = $db->query("SELECT * FROM omeka_items_section_pages");

	$pages = $select->fetchAll();

	//return(sizeof($items));
	$i=0;
	foreach($pages as $page){
		$i++;
		//check if item has digitool has digitool ids

		//get pid from db

		$select = $db->query("SELECT item_id FROM omeka_digitool_urls WHERE pid = '".$page['item_id']."'");

		$itemid = $select->fetch();

		//save as digitool obj
		if(!empty($itemid)){
			//update
			$db->update('omeka_items_section_pages', array('item_id'=>$itemid['item_id']), "page_id =".$page['page_id']);

			echo $i."   ".$itemid['item_id']."<br>";
		}



	}

	//return $i."   ".$itemid['item_id']."<br>";

}

/*
@param string $text String to truncate.
@param integer $length Length of returned string, including ellipsis.
@param string $ending Ending to be appended to the trimmed string.
@param boolean $exact If false, $text will not be cut mid-word
@param boolean $considerHtml If true, HTML tags would be handled correctly
@return string Trimmed string.
*/
function truncate($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) {
        if ($considerHtml) {
                // if the plain text is shorter than the maximum length, return the whole text
                if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                        return $text;
                }
                // splits all html-tags to scanable lines
                preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
                $total_length = strlen($ending);
                $open_tags = array();
                $truncate = '';
                foreach ($lines as $line_matchings) {
                        // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                        if (!empty($line_matchings[1])) {
                                // if it's an "empty element" with or without xhtml-conform closing slash
                                if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                                        // do nothing
                                        // if tag is a closing tag
                                } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                                        // delete tag from $open_tags list
                                        $pos = array_search($tag_matchings[1], $open_tags);
                                        if ($pos !== false) {
                                                unset($open_tags[$pos]);
                                        }
                                        // if tag is an opening tag
                                } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                                        // add tag to the beginning of $open_tags list
                                        array_unshift($open_tags, strtolower($tag_matchings[1]));
                                }
                                // add html-tag to $truncate'd text
                                $truncate .= $line_matchings[1];
                        }
                        // calculate the length of the plain text part of the line; handle entities as one character
                        $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
                        if ($total_length+$content_length> $length) {
                                // the number of characters which are left
                                $left = $length - $total_length;
                                $entities_length = 0;
                                // search for html entities
                                if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                                        // calculate the real length of all entities in the legal range
                                        foreach ($entities[0] as $entity) {
                                                if ($entity[1]+1-$entities_length <= $left) {
                                                        $left--;
                                                        $entities_length += strlen($entity[0]);
                                                } else {
                                                        // no more characters left
                                                        break;
                                                }
                                        }
                                }
                                $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
                                // maximum lenght is reached, so get off the loop
                                break;
                        } else {
                                $truncate .= $line_matchings[2];
                                $total_length += $content_length;
                        }
                        // if the maximum length is reached, get off the loop
                        if($total_length>= $length) {
                                break;
                        }
                }
        } else {
                if (strlen($text) <= $length) {
                        return $text;
                } else {
                        $truncate = substr($text, 0, $length - strlen($ending));
                }
        }
        // if the words shouldn't be cut in the middle...
        if (!$exact) {
                // ...search the last occurance of a space...
                $spacepos = strrpos($truncate, ' ');
                if (isset($spacepos)) {
                        // ...and cut the text in this position
                        $truncate = substr($truncate, 0, $spacepos);
                }
        }
        // add the defined ending to the text
        $truncate .= $ending;
        if($considerHtml) {
                // close all unclosed html-tags
                foreach ($open_tags as $tag) {
                        $truncate .= '</' . $tag . '>';
                }
        }
        return $truncate;
}

//returns the proper name/title of an exhibit tag
function libis_breadcrumb_tag($exhibit){    
    $tag = tag_string($exhibit,null);

    if($tag == 'algemeen'){
        return false;
    }  

    $namen = array(
        "middenveld" => "Middenveld en beleid",
        "oogst" => "Een rijke oogst",
        "mensen" => "Boer & Co",
        "landschap" => "Boerderij en landschap",        
        "identiteit" => "Identiteit en beeldvorming",
        "eetcultuur" => "Eetcultuur",
        "industrie" => "Industrie en wetenschap"
    );            
    return "<li><a href='/verhalen/".$tag."'>".$namen[$tag]."</a> ></li>";    
}

//get current url
function libis_curPageURL() {
	$pageURL = 'http';
	if ( isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on" ) {
            $pageURL .= "s";
        }
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["HTTP_HOST"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

function libis_get_type_organisations(){
    $items = get_records('Item',array('type'=>'Collectie'),10000);
    
    $types = array();
   
    foreach($items as $item){
        $type = metadata($item,array("Item Type Metadata","Type Organisatie"));
        if($type !="")        
            $types[] = $type;        
    }    
    echo "<ul>";
    foreach($types as $type){        
        echo "<li><a href='".url("/solr-search/results/?facet=226_s:%22".$type."%22 AND itemtype:%22Collectie%22")."'>".$type."</li>";
    }
        echo "</ul>";
     
}

function libis_get_simple_page_three(){
    //get page id
    $parentPage = simple_pages_get_current_page();
    $parentId = $parentPage->id;
    
    //find children pages
    $findBy = array('parent_id' => $parentId, 'sort' => 'order');
    $findBy['is_published'] = true;
    $pages = get_db()->getTable('SimplePagesPage')->findBy($findBy);

    $navLinks = array();

    //loop pages
    foreach ($pages as $page) {
        //make url
        $uri = uri($page->slug);

        $navLinks[0][] = array(
            'id' => $page->id,
            'label' => $page->title,
            'uri' => $uri
        );
    }
    //search children
    $j=0;
    while($j<20){
        $check=false;        
        for($i=0;$i <= sizeof($navLinks[$j]);$i++){
            $k=0; 
            $parent = $navLinks[$j][$i];  
            if($parent['id']!=''){
                
                $findBy = array('parent_id' => $parent['id'], 'sort' => 'order');
                $findBy['is_published'] = true;
                $pages=null;
                $pages = get_db()->getTable('SimplePagesPage')->findBy($findBy);
                echo "<br>".$parent['id']." - ".sizeof($pages);
                if($pages){
                                   
                    foreach ($pages as $page) {                    
                        //make url
                        $uri = uri($page->slug);

                        $navLinks[$j+1][$parent['id']] = array(
                            'id' => $page->id,
                            'label' => $page->title,
                            'uri' => $uri
                        );
                        $k++;
                    }
                    //extra level of pages found -> continue the loop
                    //$check = true;
                }
            }
            
        }
        $j++;
    }
    return $navLinks;
}

/**
 * Output a tag string given an Item, Exhibit, or a set of tags. -> AANGEPAST VOOR FLANDRICA
 *
 * @internal Any record that has the Taggable module can be passed to this function *
 * @param string|null $link The URL to use for links to the tags (if null, tags aren't linked) *
 * @return string HTML
 */
function Libis_tag_string($recordOrTags = null, $link=null)
{
	if (!$recordOrTags) {
		$recordOrTags = array();
	}

	if ($recordOrTags instanceof Omeka_Record) {
		$tags = $recordOrTags->Tags;
	} else {
		$tags = $recordOrTags;
	}

	$tagString = '';
	if (!empty($tags)) {
		$tagStrings = array();
		foreach ($tags as $key=>$tag) {
			if (!$link) {
				$tagStrings[$key] = html_escape($tag['name']);
			} else {
				$tagStrings[$key] = "<a href='" . html_escape($link.urlencode('"'.$tag['name'].'"')) . "' rel='tag'>".html_escape($tag['name'])."</a>, ";
			}
		}
		$tagString = join("",$tagStrings);
                $tagString = substr_replace($tagString ,"",-2);
	}
	return $tagString;
}

function libis_get_featured_news(){
    $html="";
    $nieuws = get_records('Item',array('type'=>'Nieuwsbericht','featured'=>true,'sort_field'=>'added','sort_dir'=>'d'),3);
    $agenda = get_records('Item',array('type'=>'Agendapunt','featured'=>true,'sort_field'=>'added','sort_dir'=>'d'),3);
    $items = array_merge($nieuws,$agenda);
    usort($items, function($a, $b)
    {
        return strcmp($b->added,$a->added);
    });
    $items = array_slice($items , 0 , 3 );
    foreach($items as $item){
        $html .= "<div class='in_de_kijker'>";
        if($item->hasThumbnail()):
            $html .= link_to_item(item_image('thumbnail', array('width'=>'80'), 0, $item), array('class' => 'item-thumbnail'), 'show', $item);
        endif;
                      
        $html .= "<div class='in_de_kijker_text'>".link_to_item("<h4>".metadata($item,array('Dublin Core','Title'))."</h4>", array(), 'show', $item).
                "<p>".metadata($item,array('Dublin Core','Description'),array('snippet'=>110))."</p></div>";
        $html .= "<div class='lees_meer'>".link_to_item(__("Lees verder.."),array(),'show', $item)."</div></div>";
        
    }
    
    return $html;
}

function libis_get_news(){
    $html = "<div class='wegwijs-block'>";   
    $html .="<h2>Recente Nieuwsberichten</h2>";
    $nieuws = get_records('Item',array('type'=>'Nieuwsbericht','recent'=>true,'sort_field'=>'added','sort_dir'=>'d'),5);
    foreach($nieuws as $item){
        $html .= "<div class='wegwijs-item'>";
        if($item->hasThumbnail()):
            $html .= link_to_item(item_image('thumbnail', array('width'=>'60'), 0, $item), array('class' => 'item-thumbnail'), 'show', $item);
        endif;
                      
        $html .= link_to_item("<h4>".metadata($item,array('Dublin Core','Title'))."</h4>",array(), 'show', $item);
        $html .= "<p>".metadata($item,array('Dublin Core','Description'),array('snippet'=>50))."</p>";
        $html .= "</div>";
        
    }
    $html .= "<div class='lees_meer'><a href='".url('solr-search/results?q=&facet=itemtype:("Nieuwsbericht" OR "Agendapunt")')."'>Lees meer..</a></div></div>";
    return $html;
}

function libis_get_agenda(){
    $html = "<div class='wegwijs-block'>";   
    $html .="<h2>Agenda</h2>";
    $agenda = get_records('Item',array('type'=>'Agendapunt','featured'=>true,'sort_field'=>'added','sort_dir'=>'d'),3);
    foreach($agenda as $item){
        $html .= "<div class='wegwijs-item'>";
        if($item->hasThumbnail()):
            $html .= link_to_item(item_image('thumbnail', array('width'=>'60'), 0, $item), array('class' => 'item-thumbnail'), 'show', $item);
        endif;
                      
        $html .= link_to_item("<h4>".metadata($item,array('Dublin Core','Title'))."</h4>",array(), 'show', $item);
        $html .= "<p>".metadata($item,array('Dublin Core','Description'),array('snippet'=>50))."</p>";
        $html .= "</div>";
        
    }
    $html .= "<div class='lees_meer'><a href='".url('solr-search/results?q=&facet=itemtype:("Nieuwsbericht" OR "Agendapunt")')."'>Lees meer..</a></div></div>";
    return $html;
}

function libis_get_projects($lopend = true){
    $html="<table class='thema-table'><tr>";
    $i=0;
    $items = get_records('Item',array('type'=>'Project','featured'=>$lopend,'sort_field'=>'added','sort_dir'=>'d'),100);
    foreach($items as $item){
        if($i==2):
            $html .="</tr><tr>";
            $i=0;
        endif;
        $html .= "<td style='text-align: left;'><p>";
        if($item->hasThumbnail()):
            $html .= link_to_item(item_image('thumbnail', array('width'=>'95'), 0, $item), array('class' => 'item-thumbnail'), 'show', $item);
        elseif(digitool_item_has_digitool_url()):
            $html .= link_to_item(digitool_get_thumb($item,true,false,95), array('class' => 'item-thumbnail'), 'show', $item);
        endif;
        $html .= link_to_item("<strong>".metadata($item,array('Dublin Core','Title'))."</strong>",array(), 'show', $item)."</p></td>";       
        $i++;
        
    }
    $html .= "</tr></table><div class='lees_meer'><a href='".url('solr-search/results?q=&facet=itemtype:"Project"')."'>Lees meer..</a></div>";
    return $html;
}

function libis_get_publicaties($pub_tag=null){
    $html="<ul>";
    $items = get_records('Item',array('type'=>'Publicatie','featured'=>'true','sort_field'=>'Dublin Core,Date','sort_dir'=>'d'),400);
    if($pub_tag):
        foreach($items as $item){  
            $tags = $item->Tags;
            foreach($tags as $tag):
                if(strtolower($pub_tag) == strtolower($tag->name)):
                    $html .= "<li>".link_to_item("<strong>".metadata($item,array('Dublin Core','Title'))."</strong>",array(), 'show', $item)."</li>";       
                endif;
            endforeach;
        
        }
        $html .= "</ul><div class='lees_meer'><a href='".url('solr-search/results?q=&facet=itemtype:"Publicatie" AND tag:"'.$pub_tag.'"')."'>Lees meer..</a></div>";
    else:
        foreach($items as $item):  
            $html .= "<li>".link_to_item("<strong>".metadata($item,array('Dublin Core','Title'))."</strong>",array(), 'show', $item)."</li>";       
        endforeach;       
        $html .= "</ul><div class='lees_meer'><a href='".url('solr-search/results?q=&facet=itemtype:"Publicatie"')."'>Lees meer..</a></div>";
   endif;
        
        
    return $html;
}

function libis_get_simple_page_content($title){
    $page = get_record('SimplePagesPage',array('title'=>$title));
    return $page->text;
}

/**
 * Create a tag cloud made of divs that follow the hTagcloud microformat
 *
 * @package Omeka\Function\View\Tag
 * @param Omeka_Record_AbstractRecord|array $recordOrTags The record to retrieve 
 * tags from, or the actual array of tags
 * @param string|null $link The URI to use in the link for each tag. If none 
 * given, tags in the cloud will not be given links.
 * @param int $maxClasses
 * @param bool $tagNumber
 * @param string $tagNumberOrder
 * @return string HTML for the tag cloud
 */
function libis_tag_cloud($recordOrTags = null, $link = null, $maxClasses = 20, $tagNumber = false, $tagNumberOrder = null)
{
    if (!$recordOrTags) {
        $tags = array();
    } else if (is_string($recordOrTags)) {
        $tags = get_current_record($recordOrTags)->Tags;
    } else if ($recordOrTags instanceof Omeka_Record_AbstractRecord) {
        $tags = $recordOrTags->Tags;
    } else {
        $tags = $recordOrTags;
    }
    
    if (empty($tags)) {
        return '<p>' . __('No tags are available.') . '</p>';
    }
    
    //Get the largest value in the tags array
    $largest = 0;
    foreach ($tags as $tag) {
        if($tag["tagCount"] > $largest) {
            $largest = $tag['tagCount'];
        }
    }
    $html = '<div class="hTagcloud">';
    $html .= '<ul class="popularity">';
    
    if ($largest < $maxClasses) {
        $maxClasses = $largest;
    }
    
    foreach( $tags as $tag ) {
        $size = (int)(($tag['tagCount'] * $maxClasses) / $largest - 1);
        $class = str_repeat('v', $size) . ($size ? '-' : '') . 'popular';
        $html .= '<li class="' . $class . '">';
        if ($link) {
            $html .= '<a href="' . html_escape(url($link.'"'.$tag['name'].'"')) . '">';
        }
        if($tagNumber && $tagNumberOrder == 'before') {
            $html .= ' <span class="count">'.$tag['tagCount'].'</span> ';
        }
        $html .= html_escape($tag['name']);
        if($tagNumber && $tagNumberOrder == 'after') {
            $html .= ' <span class="count">'.$tag['tagCount'].'</span> ';
        }
        if ($link) {
            $html .= '</a>';
        }
        $html .= '</li>' . "\n";
    }
    $html .= '</ul></div>';
    
    return $html;
}

function libis_get_image($item){
    if(metadata('item', 'has files') || digitool_item_has_digitool_url($item)):
        echo '<div id="itemfiles" class="element">';
        if (metadata('item', 'has files')):
            echo '<div class="element-text">'.files_for_item(array("imageSize"=>"fullsize")).'</div>';
        endif;
        if (digitool_item_has_digitool_url($item)):
            echo '<div class="element-text">';
                echo digitool_simple_gallery($item,500);
            echo '</div>';
        endif;
        echo "</div>";
     
        if(metadata('item',array('Dublin Core','License')) != ""){
            $link = metadata('item',array('Dublin Core','License'));
            $img = str_replace("http://creativecommons.org/licenses/","http://i.creativecommons.org/l/",$link);
            $img .= "88x31.png";
            echo "<p style='clear:both;'><a href='".$link."'><img alt='Creative Commons Licentie' src='".$img."'></a></p>";
        }       
    endif;
}

function libis_exhibit_nav($exhibitPage=null){
    if (!$exhibitPage) {
        if (!($exhibitPage = get_current_record('exhibit_page', false))) {
            return;
        }
    }
    $exhibit = $exhibitPage->getExhibit();
    $html = '<ul class="exhibit-page-nav navigation">' . "\n";
    //$pagesTrail = $exhibitPage->getAncestors();
    $pagesTrail[] = $exhibitPage;
    
    foreach ($pagesTrail as $page) {
        $linkText = $page->title;
        $pageExhibit = $page->getExhibit();
        $pageParent = $page->getParent();
        $pageSiblings = ($pageParent ? exhibit_builder_child_pages($pageParent) : $pageExhibit->getTopPages()); 

       
        foreach ($pageSiblings as $pageSibling) {
            $html .= '<li' . ($pageSibling->id == $page->id ? ' class="current"' : '') . '>';
            $html .= '<a class="exhibit-page-title" href="' . html_escape(exhibit_builder_exhibit_uri($exhibit, $pageSibling)) . '">';
            $html .= html_escape($pageSibling->title) . "</a></li>\n";
        }
       
    }
    $html .= '</ul>' . "\n";
    $html = apply_filters('exhibit_builder_page_nav', $html);
    return $html;
}

function libis_get_exhibit_for_print($exhibit){    
?>
    <h1><?php echo html_escape($exhibit['title']); ?></h1>
    <div id="exhibit_description">
    <?php echo $exhibit->description; ?>
    <?php echo "<em>Door ".$exhibit->credits."</em>"; ?>
    </div>
    <br>
    <div id="exhibit-sections">	
	<h3>Inhoudstafel</h3>	
        <div class="exhibit-sections-item">
        <ul id="inhoudstafel">
            <?php set_exhibit_pages_for_loop_by_exhibit(); ?>
            <?php foreach (loop('exhibit_page') as $exhibitPage): ?>
            
            <?php echo exhibit_builder_page_summary($exhibitPage); ?>
           
            <?php endforeach; ?>
        </ul>	
        </div>        
    </div>
    <div id="exhibit-bonanza">
        <?php set_exhibit_pages_for_loop_by_exhibit(); ?>
        <?php foreach (loop('exhibit_page') as $exhibitPage): ?>
            <h2><span class="exhibit-page"><?php echo metadata('exhibit_page', 'title'); ?></h2>
            <div class="image-left">
            <?php if ($attachment = exhibit_builder_page_attachment(1)):?>
            <div class="exhibit-item">
                <?php echo exhibit_builder_attachment_markup($attachment, array('imageSize' => 'fullsize'), array('class' => 'permalink')); ?>
            </div>
            <?php endif; ?>
            </div>
            <?php echo exhibit_builder_page_text(1); ?>
            
            <?php 
                $exhibit_children = exhibit_builder_child_pages();
                //var_dump($exhibit_children);
                if($exhibit_children):
                   // set_exhibit_pages_for_loop_by_exhibit($exhibit_children);
                    foreach (loop('exhibit_page',$exhibit_children) as $exhibitPage_child): ?>

                    <h3><span class="exhibit-page"><?php echo metadata('exhibit_page', 'title'); ?></h3>
                    <div class="image-left" width="250">
                    <?php if ($attachment = exhibit_builder_page_attachment(1)):?>
                    <div class="exhibit-item">
                        <?php echo exhibit_builder_attachment_markup($attachment, array('imageSize' => 'fullsize'), array('class' => 'permalink')); ?>
                    </div>
                    <?php endif; ?>
                    </div>
                    <?php echo exhibit_builder_page_text(1); ?>
                    <?php endforeach;?>  
                <?php endif;?>    
            <br><br>
            <?php //echo exhibit_builder_page_summary($exhibitPage); ?>           
        <?php endforeach; ?>
    </div>
<?php
}
?>