<?php

require_once 'DigitoolUrl.php';
require_once dirname(__FILE__).'/helpers/CurlHelper.php';

// Add plugin hooks.
add_plugin_hook('install', 'digitool_install');
add_plugin_hook('uninstall', 'digitool_uninstall');
// add plugin hooks (configuration)
add_plugin_hook('config_form', 'digitool_config_form');
add_plugin_hook('config', 'digitool_config');
add_plugin_hook('admin_append_to_items_show_secondary', 'digitool_admin_show_item_map');

//add_plugin_hook('after_save_item', 'digitool_save_url');
add_plugin_hook('after_save_form_item', 'digitool_save_url');
add_filter('admin_items_form_tabs', 'digitool_item_form_tabs');

function digitool_install()
{
   $db = get_db();
    $sql = "
    CREATE TABLE IF NOT EXISTS $db->DigitoolUrl (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    `item_id` BIGINT UNSIGNED NOT NULL ,
    `pid` VARCHAR(100) NOT NULL ,
    INDEX (`item_id`)) ENGINE = MYISAM";
    $db->query($sql);

    set_option('digitool_proxy','');
    set_option('digitool_cgi','');
    set_option('digitool_thumb','');
    set_option('digitool_view','');
}

/**
 * Uninstall the plugin.
 */
function digitool_uninstall(){
    // Drop the url table.
    $db = get_db();
    $db->query("DROP TABLE $db->DigitoolUrl");

    delete_option('digitool_proxy');
    delete_option('digitool_cgi');
    delete_option('digitool_thumb');
    delete_option('digitool_view');
}
//link to config_form.php
function digitool_config_form() {
	include('config_form.php');
}
//process the config_form
function digitool_config() {
	//get the POST variables from config_form and set them in the DB
	if($_POST["proxy"])
		set_option('digitool_proxy',$_POST['proxy']);

	if($_POST["cgi"])
		set_option('digitool_cgi',$_POST['cgi']);

        if($_POST["thumb"])
                set_option('digitool_thumb',$_POST['thumb']);

        if($_POST["view"])
                set_option('digitool_view',$_POST['view']);
}

function digitool_admin_form($item){
    ob_start();
    echo js("jquery.pagination");
    ?><link rel="stylesheet" href="<?php echo css('pagination'); ?>" />
    <?php
    if(digitool_item_has_digitool_url($item)){
    ?>
    <div>
    <b>Digitool images currently associated with this item:</b><br>
    <br><?php echo digitool_get_thumb($item,false,true,'100px');?>
    <br><br><label>Remove current digitool images?</label><input type="checkbox" name="delete" value="yes"/>
    </div>
    <br><br>
    <?php }?>

    <label>Search digitool (case sensitive)</label>
	<br>
    <input name='fileUrl' id='fileUrl' type='text' class='fileinput' />
    <button style="float:none;" class="digi-search">Search</button>
    <br><br>
    <div id="wait" style="display:none;">Please wait, this might take a few seconds.</div>

    <div id="Pagination"></div>
    <br style="clear:both;" />
    <div id="Searchresult">
    This content will be replaced when pagination inits.
    </div>

    <!-- Container element for all the Elements that are to be paginated -->
    <div id="hiddenresult" style="display:none;">
     <div class="result">TEST</div>
    </div>


	<script>
	( function($) {
		jQuery('.digi-search').click(function(event) {
			event.preventDefault();
			jQuery('#Searchresult').hide('slow');
			jQuery('#Pagination').hide('slow');
			jQuery('#wait').show('slow');

			jQuery.get('<?php echo uri("digitool/index/cgi/");?>',{ search: jQuery('#fileUrl').val()} , function(data) {
				jQuery('#wait').hide('slow');
				jQuery('#hiddenresult').html(data);
				initPagination();
				pageselectCallback(0);
				jQuery('#Pagination').show('slow');
				jQuery('#Searchresult').show('slow');
			});

		});

		jQuery('.digi-child').live("click", function(event) {
			event.preventDefault();
			jQuery('#wait').show('slow');
			jQuery.get('<?php echo uri("digitool/index/childcgi/");?>',{ child: jQuery('.digi-child').val()} , function(data) {
				jQuery('#wait').hide('slow');
				jQuery('.result-child').html(data);
			});

		});

		// This demo shows how to paginate elements that were loaded via AJAX
		// It's very similar to the static demo.

		/**
		 * Callback function that displays the content.
		  *
		* Gets called every time the user clicks on a pagination link.
		*
		* @param {int}page_index New Page index
		* @param {jQuery} jq the container with the pagination links as a jQuery object
		*/
		function pageselectCallback(page_index, jq){
			var new_content = jQuery('#hiddenresult div.result:eq('+page_index+')').clone();
			jQuery('#Searchresult').empty().append(new_content);
		                return false;
		}

		/**
		* Callback function for the AJAX content loader.
		*/
		function initPagination() {
			var num_entries = jQuery('#hiddenresult div.result').length;
			// Create pagination element
			jQuery("#Pagination").pagination(num_entries, {
				num_edge_entries: 0,
				num_display_entries: 5,
				callback: pageselectCallback,
			                    items_per_page:4
			});
		}

	} ) ( jQuery );
		// Load HTML snippet with AJAX and insert it into the Hiddenresult element
		// When the HTML has loaded, call initPagination to paginate the elements

	</script>



	<?php
	$ht .= ob_get_contents();
	ob_end_clean();

	return $ht;

}

function digitool_save_url($item){

    //handle delete first
    if(isset($_POST['delete']) && ($_POST['delete'] == 'yes'))
    {
            $urlToDelete = get_db()->getTable('DigitoolUrl')->findDigitoolUrlByItem($item, false);
            foreach($urlToDelete as $u){
                    $u->delete();
            }

    }

    if(!$_POST['pid']){
            return;
    }

    $post = $_POST;

    //TODO:zie files-form.php voor code meerdere digitool files

    //create view url out of thumb url


    //save to db
    $url = new DigitoolUrl;
    $url->item_id = $item->id;

    $url->saveForm($post);
}



/**
* Add a Map tab to the edit item page
* @return array
**/
function digitool_item_form_tabs($tabs){
    // insert the map tab before the Miscellaneous tab
    $item = get_current_item();
    $ttabs = array();
    foreach($tabs as $key => $html) {
            if ($key == 'Tags') {
                    $ht = '';
                    $ht .= digitool_admin_form($item);
                    $ttabs['Digitool'] = $ht;
            }
            $ttabs[$key] = $html;
    }
    $tabs = $ttabs;
    return $tabs;
}

/**
* Returns the html for loading the javascripts used by the plugin.
*
* @param bool $pageLoaded Whether or not the page is already loaded.
* If this function is used with AJAX, this parameter may need to be set to true.
* @return string
*/
function digitool_scripts(){
	$ht = '';
	$ht .= js('jquery.pagination');
	//$ht .= css('pagination');
	return $ht;
}

/**
* Shows the digitool urls on the admin show page in the secondary column
* @param Item $item
* @return void
**/
function digitool_admin_show_item_map($item){
	$html = digitool_scripts()
	. '<div class="info-panel">'
	. '<h2>Digitool</h2>'
	. digitool_get_thumb($item,false,true,'100px')
	. '<br><br></div>';
	echo $html;
}


/**
* Shows the digitool urls on the admin show page in the secondary column
* @param Item $item
* @return void
**/
function digitool_get_thumb_url($item){
    $url = get_db()->getTable('DigitoolUrl')->findDigitoolUrlByItem($item, true);

    if(!empty($url)){
        return get_option('digitool_thumb').$url->pid;
    }else{
        return false;
    }
}

/**
* Shows an item's digitool url thumbnails
* @param Item $item, boolean $fiondOnlyOne, int $width,int $height
* @return html of the thumbnails
**/
function digitool_get_thumb($item, $findOnlyOne = false, $linkToView = false,$width="",$class="",$alt="",$resize=true){

	$url = get_db()->getTable('DigitoolUrl')->findDigitoolUrlByItem($item, $findOnlyOne);

	if(!empty($url)){
            if(!$linkToView){
                    if($findOnlyOne){
                            $thumb = get_option('digitool_thumb').$url->pid;
                            $resize = digitool_resize_dimensions($width,$width,$thumb);
                            return '<img src="'.$thumb.'" height="'.$resize['height'].'" width="'.$resize['width'].'" class="'.$class.'" alt="'.item('Dublin Core','Title',array(),$item).'">';
                    }
                    //if more then one thumbnail was found
                    else{
                            foreach($url as $u){
                                    $thumb = get_option('digitool_thumb').$u->pid;
                                    $resize = digitool_resize_dimensions($width,$width,$thumb);
                                    $html.='<img src="'.$thumb.'" height="'.$resize['height'].'" width="'.$resize['width'].'" /> ';
                            }
                            return $html;
                    }
            }else{
                    if($findOnlyOne){
                            $thumb =  get_option('digitool_thumb').$url->pid;
                            $view =  get_option('digitool_view').$url->pid;
                            $resize = digitool_resize_dimensions($width,$width,$thumb);
                            return '<a href="'.$view.'" target="_blank"><img src="'.$thumb.'"  height="'.$resize['height'].'" width="'.$resize['width'].'" class="'.$class.'" alt="'.item('Dublin Core','Title',array(),$item).'"></a>';
                    }
                    //if more then one thumbnail was found
                    else{
                            foreach($url as $u){
                                    $thumb = get_option('digitool_thumb').$u->pid;
                                    $view = get_option('digitool_view').$u->pid;
                                    $resize = digitool_resize_dimensions($width,$width,$thumb);
                                    $html.='<a href="'.$view.'" target="_blank"><img src="'.$thumb.'" height="'.$resize['height'].'" width="'.$resize['width'].'"></a>';
                            }
                            return $html;
                    }

            }
	}

}

/**
* Shows an item's digitool url thumbnails
* @param Item $item, boolean $fiondOnlyOne, int $width,int $height
* @return html of the thumbnails
**/
function digitool_get_thumb_for_home($item){

    $url = get_db()->getTable('DigitoolUrl')->findDigitoolUrlByItem($item, $findOnlyOne);

    if(!empty($url)){
        $thumb =  get_option('digitool_thumb').$url[0]->pid;
        $view =  get_option('digitool_view').$url[0]->pid;

        return '<a href="'.item_uri("show",$item).'" ><img src="'.$thumb.'" alt="'.item('Dublin Core','Title',array(),$item).'"></a>';
    }
}

function digitool_get_thumb_for_browse($item, $width="500",$class="",$alt=""){

	$url = get_db()->getTable('DigitoolUrl')->findDigitoolUrlByItem($item, $findOnlyOne);

	if(!empty($url)){
            $thumb = get_option('digitool_thumb').$url[0]->pid;
            return '<img src="'.$thumb.'"  width="'.$width.'" class="'.$class.'" alt="'.item('Dublin Core','Title',array(),$item).'">';
        }

}

/*
 * Creates a simple gallery view for the items/show page
 * */
function digitool_simple_gallery($item,$size=500,$type='object'){

	$i=0;
	$url = get_db()->getTable('DigitoolUrl')->findDigitoolUrlByItem($item, false);
	if(sizeof($url)==1){
		$thumb = get_option('digitool_thumb').$url[0]->pid;
		$link = get_option('digitool_view').$url[0]->pid;

                $resize = digitool_resize_dimensions($size,$size,$thumb);
		$html.="<div id='image'><a href='".$link."'><img height='".$resize['height']."' width='".$resize['width']."' src='".$thumb."' /></a></div>";

		return $html;
	}else{
		$html .= "<div id='gallery'>";
		foreach($url as $u){
			$thumb = get_option('digitool_thumb').$u->pid;
			$link = get_option('digitool_view').$u->pid;
                        if($type='concept'){
                            $altItem_id = digitool_find_items_with_same_pid(null,$u->pid);
                            $altItem = get_item_by_id($altItem_id);
                            $link = item_uri('show',$altItem);
                        }
                        $resize = digitool_resize_dimensions($size,$size,$thumb);
                        
                        
			if($i==0){
                            $html.="<div id='gallery-image'><a href='".$link."'><img height='".$resize['height']."' width='".$resize['width']."' src='".$thumb."'/></a></div>";
                            $html.="<div id='gallery-thumbnails' style='height: 400px;-moz-column-width: 70px;
 -moz-column-gap: 0px;column-width: 70px;'>";
			}
			$width = 50;
			$html.= "<a href='#' rel='".$thumb."' name='".$link."' class='image'><img src='".$thumb."' class='thumb' width='".$width."' border='0'/></a>";
                        
			$i++;
		}
		$html .= "</div></div>";
	}
	?>

	<script>
	jQuery(document).ready(function() {
		jQuery(function() {
			jQuery(".image").click(function() {
				var image = jQuery(this).attr("rel");
				var url = jQuery(this).attr("name");
				jQuery('#gallery-image').hide();
				jQuery('#gallery-image').fadeIn('slow');
				jQuery('#gallery-image').html('<a href="'+url+'"><img src="' + image + '"/></a>');
				return false;
			});
		});
	});
	</script>

	<?php
	return $html;
}

/**
* Shows an item's digitool url views
* @param Item $item, boolean $fiondOnlyOne, int $width,int $height
* @return html of the views
**/
// Sam: De default waarden toegevoegd voor Internet Explorer
function digitool_get_view($item, $findOnlyOne = false,$width="500",$height="100%"){

	$url = get_db()->getTable('DigitoolUrl')->findDigitoolUrlByItem($item, $findOnlyOne);

	if(!empty($url)){
		if($findOnlyOne){
			$view = get_option('digitool_view').$url->pid;
			return '<img src="'.$view.'" width="'.$width.'" height="'.$height.'" />';
		}
		//if more then one thumbnail was found
		else{
			foreach($url as $u){
				$view = get_option('digitool_view').$u->pid;
				$html.='<img src="'.$view.'" width="'.$width.'" height="'.$height.'" />';
			}
			return $html;
		}
	}
	else{
		return "<p>There are no digitool images associated with this item.</p>";
	}

}

/**
* Checks if item has a digitool url
* @param Item $item
* @return true or false
**/
function digitool_item_has_digitool_url($item){

	$url = get_db()->getTable('DigitoolUrl')->findDigitoolUrlByItem($item, $findOnlyOne);

	if(!empty($url)){
		return true;

	}
	else{
		return false;
	}

}

/**
* Returns a digitool thumbnail
* @param Item $item, boolean $fileFirst, int $size
* $fileFirst indicates which type of thumbnail will be returned, $size will set the width of the image
* @return true or false
**/
function digitool_thumbnail($item,$fileFirst = true, $size = "150",$class="",$alt=""){
	//show the thumbnail of a file object if present
	if($fileFirst && item_has_thumbnail($item)){
		//return the thumbnail (default size as in omeka settings)
		return item_thumbnail(array("class" => $class, "alt" => $alt, "width" => $size),"",$item);
	}
	//show the digitool url if there is one
	if(digitool_item_has_digitool_url($item))
		return digitool_get_thumb($item, true,false, $size," ", $class, $alt);

	//return false if there are no thumbnails
	return false;
}

function digitool_find_items_with_same_pid($item=null,$pid=null){

	$db=get_db();
        if($pid == null){
            $url = $db->getTable('DigitoolUrl')->findDigitoolUrlByItem($item, true);
            $pid = $url->pid;
        }
	//echo $url->pid;
	$select = $db->query("SELECT item_id
		FROM omeka_digitool_urls
		WHERE pid = '".$pid."'
		ORDER BY item_id ASC
	");

	$items = $select->fetchAll();


	foreach($items as $item){
		if($item['item_id'] != $item->id){
			return $item['item_id'];
		}
	}

	//if all fails
	return false;
}

// Calculates restricted dimensions with a maximum of $goal_width by $goal_height
function digitool_resize_dimensions($goal_width,$goal_height,$imageurl) {
    //using this because curl didn't work
    if($_SERVER['REMOTE_ADDR'] != '127.0.0.1'){
        $vo_http_client = new Zend_Http_Client();
        $config = array(
                        'adapter'    => 'Zend_Http_Client_Adapter_Proxy',
                        'proxy_host' => get_option('digitool_proxy'),
                        'proxy_port' => 8080
        );
        $vo_http_client->setConfig($config);
        $vo_http_client->setUri($imageurl);

        $vo_http_response = $vo_http_client->request();
        $image = $vo_http_response->getBody();
        //echo($image);    

        $new_image = imageCreateFromString($image);
        
        // Get new dimensions
        $width = imagesx($new_image);
        $height = imagesy($new_image);
    }else{
        $size = getimagesize($imageurl);
        //var_dump($size);
        $width = $size[0];
        $height = $size[1];
    }    
    // If the ratio > goal ratio and the width > goal width resize down to goal width
    if ($width/$height > $goal_width/$goal_height && $width > $goal_width) {
        $return['width'] = $goal_width;
        $return['height'] = $goal_width/$width * $height;
    }
    // Otherwise, if the height > goal, resize down to goal height
    else if ($height > $goal_height) {
        $return['width'] = $goal_height/$height * $width;
        $return['height'] = $goal_height;
    }
    
    return $return;
}

?>