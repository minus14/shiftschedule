<?php

$beginn = microtime(true); 
//$ch = curl_init();

$req = "http://192.168.14.10:8080/webhook?cmd=defmod atTest45 at 2021-09-27T21:20:00 set ht_office desiredTemperature manual off";

$req = str_replace(" ", "%20", $req);

$ch = curl_init($req);
	
$mh = curl_multi_init();
curl_multi_add_handle($mh,$ch);


do {
    $status = curl_multi_exec($mh, $active);
    if ($active) {
        curl_multi_select($mh);
    }
} while ($active && $status == CURLM_OK);

curl_multi_remove_handle($mh, $ch);
curl_multi_close($mh);

// grab URL and pass it to the browser
// curl_exec($ch);

// if(curl_errno($ch) != 0){
	// echo "error: ".curl_error($ch);
// }

// close cURL resource, and free up system resources
// curl_close($ch);
$dauer = microtime(true) - $beginn; 
echo "Verarbeitung des Skripts: $dauer Sek.";
?>
