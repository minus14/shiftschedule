<?php

$db = new SQLite3('shiftschedule.sqlite', SQLITE3_OPEN_READWRITE);

$dateStr = $_POST['dateStr'];
$shift = $_POST['shift'];

// key exists?
$statement1 = $db->prepare('SELECT EXISTS(SELECT 1 FROM "shifts" WHERE "dateStr" = :dateStr )');
$statement1->bindValue(':dateStr', $dateStr);
$statement1->bindValue(':shift', $shift);

$result = $statement1->execute();

if($result->fetchArray()[0] == 0)
{
	$statement2 = $db->prepare('INSERT INTO "shifts" ("dateStr", "shift") VALUES (:dateStr, :shift) ');
}
else
{
	$statement2 = $db->prepare('UPDATE "shifts" set "shift" = :shift WHERE "dateStr" = :dateStr');
}
$statement2->bindValue(':dateStr', $dateStr);
$statement2->bindValue(':shift', $shift);

$result = $statement2->execute();

//clean-up
$db->query('DELETE FROM "shifts" WHERE "shift" = " "');

$result->finalize();


/*** create FHEM devices ***/
// 04:25
$on_EARLYEARLY = "4H";
$off_EARLYEARLY = "5H";
// 05:25
$on_EARLYMID = "5H";
$off_EARLYMID = "6H";
// 06:15
$on_WEEKENDEARLY = "5H50M";
$off_WEEKENDEARLY = "6H50M";

$tplBadAn = "set ht_bad desiredTemperature auto 23";
$tplBadAn = str_replace(" ", "%20", $tplBadAn);
$tplBadAus = "set ht_bad desiredTemperature auto 17";
$tplBadAus = str_replace(" ", "%20", $tplBadAus);
$tplKuecheAn = "set ht_kueche desiredTemperature auto 21";
$tplKuecheAn = str_replace(" ", "%20", $tplKuecheAn);
$tplKuecheAus = "set ht_kueche desiredTemperature auto 17";
$tplKuecheAus = str_replace(" ", "%20", $tplKuecheAus);
			
$cmdPart = "http://fhempi:8080/webhook?cmd=defmod ##atname## at ##datetimeStr## ";
$cmdPart = str_replace(" ", "%20", $cmdPart);

/*
$data = file_get_contents('http://tunnelpi/cal/getdata.php');
$arr = json_decode($data, true);

if(sizeof($arr)==0){
	// no context
	http_response_code(204);
	return;
}
*/

//http_response_code(404);

// create DateTime object
// $dateArr = date_parse_from_format("m-d-Y", $arr[0]['dateStr']);
$dateArr = date_parse_from_format("m-d-Y", $dateStr);
$d = new DateTime($dateArr['year'].'-'.$dateArr['month'].'-'.$dateArr['day'], new DateTimeZone('Europe/Berlin'));

$dStr = $dateArr['year'].'-'.$dateArr['month'].'-'.$dateArr['day'];
// skip if older than 'now'
if($d < new DateTime()){
	//echo " # ".$arr[$i]['shift'];
	//continue;
}
else {
	// period time
	$addStr = "PT";
	
	// get shift of the day
	switch ($shift) {
	  case "EARLYEARLY":
		$dOn = new DateTime($dStr);
		$dOn->add(new DateInterval($addStr.$on_EARLYEARLY));
		$dOff = new DateTime($dStr);
		$dOff->add(new DateInterval($addStr.$off_EARLYEARLY));
		break;
	  case "EARLYMID":
		$dOn = new DateTime($dStr);
		$dOn->add(new DateInterval($addStr.$on_EARLYMID));
		$dOff = new DateTime($dStr);
		$dOff->add(new DateInterval($addStr.$off_EARLYMID));
		break;
	  case "WEEKENDEARLY":
		$dOn = new DateTime($dStr);
		$dOn->add(new DateInterval($addStr.$on_WEEKENDEARLY));
		$dOff = new DateTime($dStr);
		$dOff->add(new DateInterval($addStr.$off_WEEKENDEARLY));
		break;
	  default:
		// delete from FHEM
		//echo " # ".$arr[$i]['shift'];
		return;
	}
	/***/
	$atnameBadAn = "at_auto_".$dOn->format('Y_m_d')."_bad_an";
	$atnameBadAus = "at_auto_".$dOff->format('Y_m_d')."_bad_aus";
	$atnameKuecheAn = "at_auto_".$dOn->format('Y_m_d')."_kueche_an";
	$atnameKuecheAus = "at_auto_".$dOff->format('Y_m_d')."_kueche_aus";
	
	// time stamp formatted for FHEM
	$datetimeStrOn = $dOn->format('Y-m-d') . "T" . $dOn->format('H:i:s');
	$datetimeStrOff = $dOff->format('Y-m-d') . "T" . $dOff->format('H:i:s');
	
	$cmdBadAn = str_replace("##atname##", $atnameBadAn, $cmdPart);
	$cmdBadAn = str_replace("##datetimeStr##", $datetimeStrOn, $cmdBadAn);
	// echo $cmdBadAn.$tplBadAn;
	$ch1 = curl_init($cmdBadAn.$tplBadAn);
	
	// echo "<br/> ";
	$cmdBadAus = str_replace("##atname##", $atnameBadAus, $cmdPart);
	$cmdBadAus = str_replace("##datetimeStr##", $datetimeStrOff, $cmdBadAus);
	// echo $cmdBadAus.$tplBadAus;
	$ch2 = curl_init($cmdBadAus.$tplBadAus);
	
	// echo "<br/> ";
	$cmdKuecheAn = str_replace("##atname##", $atnameKuecheAn, $cmdPart);
	$cmdKuecheAn = str_replace("##datetimeStr##", $datetimeStrOn, $cmdKuecheAn);
	// echo $cmdKuecheAn.$tplKuecheAn;
	$ch3 = curl_init($cmdKuecheAn.$tplKuecheAn);
	
	// echo "<br/> ";
	$cmdKuecheAus = str_replace("##atname##", $atnameKuecheAus, $cmdPart);
	$cmdKuecheAus = str_replace("##datetimeStr##", $datetimeStrOn, $cmdKuecheAus);
	// echo $cmdKuecheAus.$tplKuecheAus;
	$ch4 = curl_init($cmdKuecheAus.$tplKuecheAus);
	
	try{
		$mh = curl_multi_init();

		curl_multi_add_handle($mh,$ch1);
		curl_multi_add_handle($mh,$ch2);
		curl_multi_add_handle($mh,$ch3);
		curl_multi_add_handle($mh,$ch4);
		
		do {
			$status = curl_multi_exec($mh, $active);
			if ($active) {
				curl_multi_select($mh);
			}
		} while ($active && $status == CURLM_OK);
		
		// Alle Handles schließen
		curl_multi_remove_handle($mh, $ch1);
		curl_close($ch1);
		curl_multi_remove_handle($mh, $ch2);
		curl_close($ch2);
		curl_multi_remove_handle($mh, $ch3);
		curl_close($ch3);
		curl_multi_remove_handle($mh, $ch4);
		curl_close($ch4);
		curl_multi_close($mh);
		http_response_code(200);
	}
	catch (Exception $e) {
		http_response_code(500);
	}

	/***/
	
}
?>