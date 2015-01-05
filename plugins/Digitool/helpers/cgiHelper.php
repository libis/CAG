<?php
require_once("Curl.php");
$vs_base = get_option('digitool_cgi');

$va_items = array();
if (unicode_strlen($search) > 0) {
$html .="<table>";
	try {		
		$cc = new cURL();
                $proxy = get_option('digitool_proxy');
                if($proxy){
                    $cc->setproxy($proxy);
                }    
		$vs_data = $cc->get($vs_base."find_pid?search=".urlencode($ps_query).'%25&max_results=50&filter=partitionc:CAG,%20standaardcollectie');
		
		if ( $cc->getHttpStatus() == "200"){
			if ($vs_data) {
				$images = new SimpleXMLElement($vs_data);
				//create form
				foreach ($images->children() as $image) {
					$arr = $image->attributes();   // returns an array
					$html .="<tr><td><img src=".$arr["thumbnail"]."/></td>
					<td><Input type = 'Radio' Name ='thumb' value= '-".$arr["thumbnail"]."></td></tr>"; 
				//print ("ID=".$arr["view"]);    // get the value of this attribute
				}
			}
		}
	} catch (Exception $e) {
		$html .= "<p>Er is een fout opgetreden</p";
	}

}
$html .="</table>";
return $html;
?>