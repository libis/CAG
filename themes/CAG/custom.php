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
    $select->where('i.item_type_id = 6');
    $select->order('rand DESC');
    $select->limit($num);

    // If we only want items with derivative image files, join the File table.
    if ($withImage) {
        $select->joinLeft(array('f'=>"$db->DigitoolUrls"), 'f.item_id = i.id', array());

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
	if($tag == ""){
		$exhibits = exhibit_builder_get_exhibits(array('sort_field'=>'id','sort_dir'=>'d'));
	}else{
		$exhibits = exhibit_builder_get_exhibits(array('tags' =>$tag));
		//if there were no exhibits found
		if(empty($exhibits)){
			return "<p>We're sorry but there were no stories found with this tag</p>";
		}
	}
	//tag 'algemeen' has different formatting then the others
	if($tag=="algemeen"){
		$html.= '<table class="exhibit_general_list"><tr>';

		//foreach($exhibits as $exhibit) {

			$html.= '<td>';
			//set current exhibit
			$exhibit = exhibit_builder_get_exhibit_by_id(100010);
		    exhibit_builder_set_current_exhibit($exhibit);

		    if($exhibit->thumbnail){
				$html.= exhibit_builder_link_to_exhibit($exhibit,'<img width="150" src="'.img($exhibit->thumbnail,'images/verhalen_thumbs').'"/>');
		    }
		    //takes care of the link and text
		    $html.= '<p>'.(exhibit_builder_link_to_exhibit($exhibit, $exhibit->title)).'</p>';
			$html.= '</td>';

			$html.= '<td>';
			//set current exhibit
			$exhibit = exhibit_builder_get_exhibit_by_id(100140);
			exhibit_builder_set_current_exhibit($exhibit);

			if($exhibit->thumbnail){
				$html.= exhibit_builder_link_to_exhibit($exhibit,'<img width="150" src="'.img($exhibit->thumbnail,'images/verhalen_thumbs').'"/>');
			}
			//takes care of the link and text
			$html.= '<p>'.(exhibit_builder_link_to_exhibit($exhibit, $exhibit->title)).'</p>';
			$html.= '</td>';

			$html.= '<td>';
			//set current exhibit
			$exhibit = exhibit_builder_get_exhibit_by_id(100150);
			exhibit_builder_set_current_exhibit($exhibit);

			if($exhibit->thumbnail){
				$html.= exhibit_builder_link_to_exhibit($exhibit,'<img width="150" src="'.img($exhibit->thumbnail,'images/verhalen_thumbs').'"/>');
			}
			//takes care of the link and text
			$html.= '<p>'.(exhibit_builder_link_to_exhibit($exhibit, $exhibit->title)).'</p>';
			$html.= '</td>';

		//}

		$html.= '</tr></table>';
		return $html;
	}
	else{
		$html.= '<ul class="exhibit_tag_list">';

		foreach($exhibits as $exhibit) {

			$html.= '<li>';
			//set current exhibit
		    exhibit_builder_set_current_exhibit($exhibit);

		    if($exhibit->thumbnail){
		    	//$item = get_item_by_id($exhibit->thumbnail);
				//set_current_item($item);
		   		//$html.= (item_square_thumbnail(array('alt'=>item('Dublin Core', 'Title'))));
		    	$html.= exhibit_builder_link_to_exhibit($exhibit,'<img width="200" src="'.img($exhibit->thumbnail,'images/verhalen_thumbs').'"/>');
		    }
		    //takes care of the link and text
		    $html.= '<p>'.(exhibit_builder_link_to_exhibit($exhibit, $exhibit->title)).'</p>';
			$html.= '<p>'.truncate(exhibit('description', array(), $exhibit),280).'</p>';

		    $html.= '</li>';

		}
		$html.= '</ul>';
		return $html;
	}
}
/**
* Custom function to retrieve a thumb of an exhibit
*
* Expects a hack in plugin.php of the exhibitBuilder plugin
*
* @return html formatting (with images) of the thumb (same as the item thumbnail function)
*/
function Libis_get_exhibit_thumb($exhibit,$props=array()){

	if($exhibit->thumbnail){

		//$item = get_item_by_id($exhibit->thumbnail);
		//set_current_item($item);
		$html= '<img class="carousel-image" width="200" src="'.img($exhibit->thumbnail,'images/verhalen_thumbs').'"/>';

	    //$html= item_square_thumbnail($props);
	   // $html = digitool_get_thumb($item,true,false,$props["width"],$props["class"],item('Dublin Core', 'Title'));
		//$html= digitool_thumbnail($item,true,$props["width"],$props["class"],item('Dublin Core', 'Title',"",$item));

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



function Libis_get_children($current,$treeAnchor,$childtree,$three){

	$html="";

	$children = $three[$current];

	if(!empty($children)){

		$html="<ul>";
		foreach($children as $child){
			//add the next set of children

			if($child['title'] == $treeAnchor){
				$html.='<li><span class="plus-min"><a href="'.uri('werktuigen?id='.$child['id']).'"><img src="'.img('min.gif').'"/></span>'.$child['title'].'</a></li>';

				$html.= $childtree;

			}else{
				$html.='<li><span class="plus-min"><a href="'.uri('werktuigen?id='.$child['id']).'"><img src="'.img('plus.gif').'"/></span>'.$child['title'].'</a></li>';
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
	$tree ='<li><a href="'.uri('werktuigen?id=48380').'">Landbouw</a></li>'.$tree;

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

	$currentPage = get_current_simple_page();
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
    	//$html .="<li><a href='".uri(simple_page('slug'))."'>".simple_page('title')."</a></li>";
    	//if menu item equal or is a child of current page display children
    	if(simple_page('id') == $currentPage->id || simple_page('id') == $ancestorPage->id){
    		//get links to child pages
    		//echo simple_page('id');

			$childPageLinks = simple_pages_get_links_for_children_pages($ancestorPage->id, null, $sort, $requiresIsPublished, $requiresIsAddToPublicNav);
			//contruct a nav menu
    		//$html .= "<ul class='second'>";
        	$html .= nav($childPageLinks, $currentDepth);
        	//$html .= "</ul>";

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
?>