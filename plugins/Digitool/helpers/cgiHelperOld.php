<?php
$xml = file_get_contents('http://libis-t-rosetta-1.libis.kuleuven.be/lias/cgi/find_pid?search='.$search.'%&max_results=50
	');
//parse xml
$images = new SimpleXMLElement($xml);

//create form
$html .="<table>";
foreach ($images->children() as $image) {
	$arr = $image->attributes();   // returns an array
	$html .="<tr><td><img src=".$arr["thumbnail"]."/></td>
        <td><Input type = 'Radio' Name ='thumb' value= '-".$arr["thumbnail"]."></td></tr>"; 
	//print ("ID=".$arr["view"]);    // get the value of this attribute

}
$html .="</table>";
return $html;
?>