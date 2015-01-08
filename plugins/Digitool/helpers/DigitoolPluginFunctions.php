<?php

/**
* @package omeka
* @subpackage digitool plugin
* @copyright 2014 Libis.be
*/

/**
 * 
 * @param type $item
 * @return type
 */
function digitool_admin_form($item){
    ob_start();
    echo queue_js_file("jquery.pagination");
    ?><link rel="stylesheet" href="<?php echo queue_css_file('pagination'); ?>" />
    <?php
    if(digitool_item_has_digitool_url($item)){
    ?>
    <div>
    <b>Digitool images currently associated with this item:</b>
    <br><?php echo digitool_get_thumb_admin($item,true);?>    
    
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

			jQuery.get('<?php echo url("digitool/index/cgi/");?>',{ search: jQuery('#fileUrl').val()} , function(data) {
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
			jQuery.get('<?php echo url("digitool/index/childcgi/");?>',{ child: jQuery('.digi-child').val()} , function(data) {
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
	$ht = ob_get_contents();
	ob_end_clean();

	return $ht;
}

function digitool_get_digitool_urls($item){
    $digis = get_db()->getTable('DigitoolUrl')->findDigitoolUrlByItem($item,false);   
    return $digis;
}

/**
* Shows an item's digitool url thumbnails in the right bar of admin/items/show
* @param Item $item
* @return html of the thumbnails
**/
function digitool_get_thumb_admin($item,$link=false){

	$digis = digitool_get_digitool_urls($item);
        
        if(!$digis){return false;}
	
        $html="<ul>";
        foreach($digis as $digi){
            if($link){
                $html.='<li><a href="/admin/digitool/index/'.$digi->id.'"><img src="'.$digi->get_thumb().'" width="100" /></a><br> ';
            }else{
                $html.='<li><img src="'.$digi->get_thumb().'" width="100" /><br> ';
            }    
            //$html.= button_to(url('digitool/index/delete-confirm/' . $digi->id),
                //null, __('Delete'),array('class' => 'delete-confirm')).'</li>';                

        }
        $html .="</ul>";
        return $html;        
}

/**
* Shows the digitool urls on the admin show page in the secondary column
* @param Item $item
* @return void
**/
function digitool_get_thumb_url($item){
    
    $digis = digitool_get_digitool_urls($item);    
    
    if($digis){
        return $digis[0]->get_thumb();
    }else{
        return false;
    }
}

/**
* Shows an item's digitool url thumbnails
* @param Item $item, boolean $fiondOnlyOne, int $width,int $height
* @return html of the thumbnails
**/
function digitool_get_thumb($item,$findOnlyOne = false,$linkToView = false,$width="",$class="",$alt=""){

    $html="";
    
    $digis = digitool_get_digitool_urls($item);

    if(!$digis){ return false;}
    
    foreach($digis as $digi){
        $thumb = $digi->get_thumb();
        $view = $digi->get_view();
        $resize = digitool_resize_dimensions($width,$width,$thumb);
        if($linkToView){
             $html.='<a href="'.$view.'" target="_blank"><img src="'.$thumb.'" height="'.$resize['height'].'" width="'.$resize['width'].'"></a>';
        }else{
            $html.='<img src="'.$thumb.'" height="'.$resize['height'].'" width="'.$resize['width'].'" /> ';
        }
        if($findOnlyOne){
            return $html;
        }
    }
    
    return $html;
    
}

/**
* Shows an item's digitool url thumbnails
* @param Item $item, boolean $fiondOnlyOne, int $width,int $height
* @return html of the thumbnails
**/
function digitool_get_thumb_for_home($item){
    
    $digis = digitool_get_digitool_urls($item);    

    if(!empty($digis)){
        $thumb =  $digis[0]->get_thumb();
        $view =  $digis[0]->get_view();

        return '<a href="'.url($item).'" ><img src="'.$thumb.'" alt="'.metadata($item,array('Dublin Core','Title')).'"></a>';
    }
}

function digitool_get_thumb_for_browse($item, $width="500",$class="",$alt=""){

	$digis = digitool_get_digitool_urls($item);    

        if(!empty($digis)){
            $thumb =  $digis[0]->get_thumb();
            return '<img src="'.$thumb.'"  width="'.$width.'" class="'.$class.'" alt="'.metadata($item,array('Dublin Core','Title')).'">';
        }

}

/*
 * Creates a simple gallery view for the items/show page
 * */
function digitool_simple_gallery($item,$size=500,$type='object'){

	$i=0;
	
        $digis = digitool_get_digitool_urls($item);    
        
	if(sizeof($digis)==1){
		$thumb =  $digis[0]->get_thumb();
                $view =  $digis[0]->get_view();

                $resize = digitool_resize_dimensions($size,$size,$thumb);
		$html ="<div id='image'><a href='".$view."'><img height='".$resize['height']."' width='".$resize['width']."' src='".$thumb."' /></a></div>";

		return $html;
	}else{
		$html = "<div id='gallery'>";
		foreach($digis as $digi){
			$thumb =  $digi->get_thumb();
                        $link =  $digi->get_view();
                        if($type='concept'){
                            $altItem_id = digitool_find_items_with_same_pid(null,$digi->pid);
                            $altItem = get_record_by_id('item',$altItem_id);
                            $link = url($altItem);
                        }
                        $resize = digitool_resize_dimensions($size,$size,$thumb);
                        
                        
			if($i==0){
                            $html.="<div id='gallery-image'><a href='".$link."'><img height='".$resize['height']."' width='".$resize['width']."' src='".$thumb."'/></a></div>";
                            $html.="<div id='gallery-thumbnails' style='height: 400px;-moz-column-width: 70px;-webkit-column-width:70px;-moz-column-gap: 0px;column-width: 70px;'>";
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
    $html='';   
    
    $digis = digitool_get_digitool_urls($item);
    if(!$digis){ return false;}

    foreach($digis as $digi){
        $view = $digi->get_view();
        $html.='<img src="'.$view.'" width="'.$width.'" height="'.$height.'" />';
        if($findOnlyOne){
            return $html;
        }
    }
    
    return $html;
}

/**
* Checks if item has a digitool url
* @param Item $item
* @return true or false
**/
function digitool_item_has_digitool_url($item = null){
    $digis = digitool_get_digitool_urls($item);
    
    if($digis){
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

/**
 * returns
 * 
 * @param type $item
 * @param type $pid
 * @return boolean
 */
function digitool_find_items_with_same_pid($item=null,$pid=null){
    
    if($item == null){ return false;}

    if($pid == null){
        $digis = digitool_get_digitool_urls($item);
             $pid = $digis[0]->pid;
        }

        $db = get_db();
        //echo $url->pid;
        $select = $db->query("SELECT item_id
                FROM omeka_digitool_urls
                WHERE pid = '".$pid."'
                ORDER BY item_id ASC
        ");

	$s_items = $select->fetchAll();

	foreach($s_items as $s_item){
            if($s_item['item_id'] != $item->id){
                return $s_item['item_id'];
            }
	}

	//if all fails
	return false;
}

/**
 * Calculates restricted dimensions with a maximum of $goal_width by $goal_height
 * 
 * @param type $goal_width
 * @param type $goal_height
 * @param type $imageurl
 * @return type
 */
function digitool_resize_dimensions($goal_width,$goal_height,$imageurl) {
    //using this because curl didn't work
    if(get_option('digitool_proxy')){
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
    
    $return['width'] = $width;
    $return['height'] = $height;        
    
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
    
    
}


/**
* Loads extra javascript needed in admin section
*
* @param bool $pageLoaded Whether or not the page is already loaded.
* If this function is used with AJAX, this parameter may need to be set to true.
* @return string
*/
function digitool_scripts(){       
    queue_js_file('jquery.pagination');
    head_js();       
}

/**
 * Check if an image exists in the folder images/digitool and if not creates one using imageMagick
 * @param pid
 * @return image name
 **/
function digitool_get_image_from_file($pid){
        $settings = array('w'=>800,'scale'=>true);
	return resize($pid,$settings);
}

/**
 * function by Wes Edling .. http://joedesigns.com
 * feel free to use this in any project, i just ask for a credit in the source code.
 * a link back to my site would be nice too.
 *
 *
 * Changes:
 * 2012/01/30 - David Goodwin - call escapeshellarg on parameters going into the shell
 * 2012/07/12 - Whizzkid - Added support for encoded image urls and images on ssl secured servers [https://]
 */

/**
 * SECURITY:
 * It's a bad idea to allow user supplied data to become the path for the image you wish to retrieve, as this allows them
 * to download nearly anything to your server. If you must do this, it's strongly advised that you put a .htaccess file
 * in the cache directory containing something like the following :
 * <code>php_flag engine off</code>
 * to at least stop arbitrary code execution. You can deal with any copyright infringement issues yourself :)
 */

/**
 * @param string $imagePath - either a local absolute/relative path, or a remote URL (e.g. http://...flickr.com/.../ ). See SECURITY note above.
 * @param array $opts (w(pixels), h(pixels), crop(boolean), scale(boolean), thumbnail(boolean), maxOnly(boolean), canvas-color(#abcabc), output-filename(string), cache_http_minutes(int))
 * @return new URL for resized image.
 */
function resize($pid,$opts=null){
    
        $view_url = get_option('digitool_view');

	$imagePath = urldecode($view_url.$pid."&custom_att_3=stream");
	# start configuration
	$cacheFolder = "/".ARCHIVE_DIR.'/files/'; # path to your cache folder, must be writeable by web server
        $remoteFolder = "/".ARCHIVE_DIR.'/files/'; # path to the folder you wish to download remote images into

	$defaults = array('crop' => false, 'scale' => 'false', 'thumbnail' => false, 'maxOnly' => false,
			'canvas-color' => 'transparent', 'output-filename' => false,
			'cacheFolder' => $cacheFolder, 'remoteFolder' => $remoteFolder, 'quality' => 90, 'cache_http_minutes' => 0);

	$opts = array_merge($defaults, $opts);

	$cacheFolder = $opts['cacheFolder'];
	$remoteFolder = $opts['remoteFolder'];

	$path_to_convert = 'convert'; # this could be something like /usr/bin/convert or /opt/local/share/bin/convert

	## you shouldn't need to configure anything else beyond this point

	$purl = parse_url($imagePath);
	$finfo = pathinfo($imagePath);
	$ext = "jpg";//$finfo['extension'];

	# check for remote image..
	if(isset($purl['scheme']) && ($purl['scheme'] == 'http' || $purl['scheme'] == 'https')):
	# grab the image, and cache it so we have something to work with..
	//list($filename) = explode('?',$finfo['basename']);
	$filename = $pid.".jpg";
	$local_filepath = $remoteFolder.$filename;
	$download_image = true;
	if(file_exists($remoteFolder.$pid."_w800.jpg")):
		// Sam: if file exists toegevoegd anders een exception
		if(file_exists($local_filepath)):
		if(filemtime($local_filepath) < strtotime('+'.$opts['cache_http_minutes'].' minutes')):
			//return filemtime($local_filepath).' - '.strtotime('+'.$opts['cache_http_minutes'].' minutes');
			$download_image = false;
		endif;
		$download_image = false;
		endif;
		// Sam: toegevoegd anders werden de bestanden altijd gedownload
		$download_image = false;
	endif;
	if($download_image == true):
		
		$vo_http_client = new Zend_Http_Client();
		$config = array(
				'adapter'    => 'Zend_Http_Client_Adapter_Proxy',
				'proxy_host' => get_option('digitool_proxy'),
				'proxy_port' => 8080
		);
		$vo_http_client->setConfig($config);
		$vo_http_client->setUri($imagePath);

		$vo_http_response = $vo_http_client->request();
		$thumb = $vo_http_response->getBody();
		//die($thumb);

		file_put_contents($local_filepath,$thumb);

	endif;
	$imagePath = $local_filepath;
	endif;

	if(file_exists($imagePath) == false):
            // Sam: toegevoegd anders moet het moeder bestand er altijd staan Er stond Document root + $imagepath
            $imagePath = $remoteFolder.$pid."_w800.jpg";
            if(file_exists($imagePath) == false):
                return 'image not found';
            endif;
	endif;

	if(isset($opts['w'])): $w = $opts['w']; endif;
	if(isset($opts['h'])): $h = $opts['h']; endif;

	$filename = $pid;

	// If the user has requested an explicit output-filename, do not use the cache directory.
	if(false !== $opts['output-filename']) :
	$newPath = $opts['output-filename'];
	else:
	if(!empty($w) and !empty($h)):
	$newPath = $cacheFolder.$filename.'_w'.$w.'_h'.$h.(isset($opts['crop']) && $opts['crop'] == true ? "_cp" : "").(isset($opts['scale']) && $opts['scale'] == true ? "_sc" : "").'.'.$ext;
	elseif(!empty($w)):
	$newPath = $cacheFolder.$filename.'_w'.$w.'.'.$ext;
	elseif(!empty($h)):
	$newPath = $cacheFolder.$filename.'_h'.$h.'.'.$ext;
	else:
	return false;
	endif;
	endif;

	$create = true;

	if(file_exists($newPath) == true):
	$create = false;
	$origFileTime = date("YmdHis",filemtime($imagePath));
	$newFileTime = date("YmdHis",filemtime($newPath));
	if($newFileTime < $origFileTime): # Not using $opts['expire-time'] ??
	$create = true;
	endif;
	endif;

	if($create == true):
	if(!empty($w) and !empty($h)):

	list($width,$height) = getimagesize($imagePath);
	$resize = $w;

	if($width > $height):
	$resize = $w;
	if(true === $opts['crop']):
	$resize = "x".$h;
	endif;
	else:
	$resize = "x".$h;
	if(true === $opts['crop']):
	$resize = $w;
	endif;
	endif;

	if(true === $opts['scale']):
	$cmd = $path_to_convert ." ". escapeshellarg($imagePath) ." -resize ". escapeshellarg($resize) .
	" -quality ". escapeshellarg($opts['quality']) . " " . escapeshellarg($newPath);
	else:
	$cmd = $path_to_convert." ". escapeshellarg($imagePath) ." -resize ". escapeshellarg($resize) .
	" -size ". escapeshellarg($w ."x". $h) .
	" xc:". escapeshellarg($opts['canvas-color']) .
	" +swap -gravity center -composite -quality ". escapeshellarg($opts['quality'])." ".escapeshellarg($newPath);
	endif;

	else:
	$cmd = $path_to_convert." " . escapeshellarg($imagePath) .
	" -thumbnail ". (!empty($h) ? 'x':'') . $w ."".
	(isset($opts['maxOnly']) && $opts['maxOnly'] == true ? "\>" : "") .
	" -quality ". escapeshellarg($opts['quality']) ." ". escapeshellarg($newPath);
	endif;

	$c = exec($cmd, $output, $return_code);
	if($return_code != 0) {
		error_log("Tried to execute : $cmd, return code: $return_code, output: " . print_r($output, true));
		return false;
	}
	endif;

	# return cache file path
	return str_replace($_SERVER['DOCUMENT_ROOT'],'',$newPath);
}


?>