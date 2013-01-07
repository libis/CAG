
<?php
//$xml = file_get_contents('http://libis-t-rosetta-1.libis.kuleuven.be/lias/cgi/find_pid?search=%'.$_GET['search'].'%&max_results=50');
//parse xml

 $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml';
$headers[] = 'Connection: Keep-Alive';
$headers[] = 'Content-type: application/xml;charset=UTF-8';
$user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:10.0) Gecko/20100101 Firefox/10.0';

$process = curl_init('http://resolver.lias.be/find_pid?search='.urlencode($_GET['search']).'%25&max_results=50');

curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
curl_setopt($process, CURLOPT_USERAGENT, $user_agent);
curl_setopt($process, CURLOPT_HEADER, 0);
curl_setopt($process, CURLOPT_PROXY, 'icts-http-gw.cc.kuleuven.be:8080');
curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($process, CURLOPT_FOLLOWLOCATION, 0);
$xml = curl_exec($process);
$status = curl_getinfo($process,CURLINFO_HTTP_CODE);
curl_close($process);

$images = new SimpleXMLElement($xml);

//print_r($images);
//create form
$size = sizeof($images);

$i=0;$j=0;
//print_r($images);
foreach ($images->children() as $image) {
	$arr = $image->attributes();   // returns an array
	$i++;
	$j++;
	$text .="<p><img src='".$arr["thumbnail"]."'/><Input type = 'Radio' Name ='pid' value= '".$arr["pid"]."'>
      </p> "; 
	
	if($i==4 || $j == $size){
		$html .= "<div class='result' >".$text."</div>";
		$text="";$i=0;
	}
	
	//check if there are children (complex object)
	if($arr["children"]){
		//$text .="<td class='result-child'><button style='float:none;' class='digi-child' value= '".$arr["children"]."'>Get Children</button></td>";
	}
	
}
if(empty($html))
	echo "No results where found";

echo $html;?>
