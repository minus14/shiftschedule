<?php


//$ch = curl_init();
$beginn = microtime(true); 


$req = "http://192.168.14.10:8080/webhook?cmd=defmod atTest44 at 2021-09-27T21:20:00 set ht_office desiredTemperature manual off";

$req = str_replace(" ", "%20", $req);

$ch = curl_init($req);
	
// set URL and other appropriate options
// curl_setopt($ch, CURLOPT_URL, urlencode($req));
// curl_setopt($ch, CURLOPT_HEADER, 0);

// grab URL and pass it to the browser
curl_exec($ch);

if(curl_errno($ch) != 0){
	echo "error: ".curl_error($ch);
}

// close cURL resource, and free up system resources
curl_close($ch);

$dauer = microtime(true) - $beginn; 
echo "Verarbeitung des Skripts: $dauer Sek.";
?>
