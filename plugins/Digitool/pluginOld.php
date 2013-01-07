<?php 

//ZIE GEOLOCATION (uitgebreide plugin nodig zoals geolocation, met eigen edit stuk, anders niet langer overzichtelijk)

// Add plugin hooks.
add_plugin_hook('install', 'digitool_install');
add_plugin_hook('uninstall', 'digitool_uninstall');

add_plugin_hook('admin_append_to_items_form_files','digitool_admin_form');
add_plugin_hook('after_save_item', 'digitool_save_url');


function digitool_install()
{
    // Create a new column 'url' in table 'files'.
    $db = get_db();
    $sql = "AlTER TABLE {$db->prefix}files ADD url CHAR(250)";
    $db->query($sql);   
}

/**
 * Uninstall the plugin.
 */
function digitool_uninstall(){        
    // Drop the url column in table 'files'.
    $db = get_db();
    $sql = "AlTER TABLE {$db->prefix}files DROP COLUMN url";
    $db->query($sql);    
}

function digitool_admin_form($item){
		
	//$html.="<form method='post' action=''>";
	?>
	<div class='field'>
    <label>Add or change a digitool url</label>
	<div class='files inputs'>	
    <input name='fileUrl' id='fileUrl' type='text' class='fileinput' />         
    <!-- <input type='button' id='digitool-search' name='Search' value='Search'></div> -->
    <div style="height:100px;width:100px;" class="digi-search">Search</div>
    <div  class="result"></div> 
    <?php    
    
	if(digitool_get_image($item)){?>
		$html.="<p><strong>Current digitool image:</strong> ".digitool_get_image($item)."</p>";
	<?php } ?>
	<script>
		jQuery('.digi-search').click(function() {
			jQuery.get('<?php echo uri("digitool/index/cgi");?>',{ search: jQuery('#fileUrl').val()} , function(data) {
				jQuery('.result').html(data);
			});
		});	
	</script>
	<?php 

	
}

function digitool_save_url($item){
	
	$url=$_POST['thumb'];
	if(!$_POST['thumb']){
		return;
	}
	
	$id= $item->id;
	
	//voorzie dat er een file object gemaakt wordt
	//TODO:zie files-form.php voor code meerdere digitool files
	
	//save to the database
	$db = get_db();	
    $sql = "UPDATE {$db->prefix}files SET url = '".$url."' WHERE item_id = '".$id."'";
	$db->query($sql); 
	//item('Item type metadata','Digitool thumbnail',$item) = $thumb;
}

function digitool_get_image($item = null){

	$db = get_db();	
    $sql = "SELECT url FROM {$db->prefix}files WHERE item_id = '".$item->id."'";
	$result = $db->query($sql); 
	
	while ($row = $result->fetch()) {
    	return $row['url'];
	}
	
	return false;

}	


?>