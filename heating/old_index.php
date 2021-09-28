<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Titel</title>
  </head>
  <body>
	
	<?php
	// defmod atTest at 2021-09-24T16:00:00 set HUEDevice5 off
	// http://fhempi:8080/webhook?cmd=defmod atTest at 2021-09-23T21:20:00 set ht_office desiredTemperature manual off
	// http://fhempi:8080/webhook?cmd=defmod atTest at 2021-09-23T21:20:00 set ht_bad desiredTemperature auto 23
	
	$tplBadAn = "";
	$tplBadAus = "";
	$tplKuecheAn = "";
	$tplKuecheAus = "";
	
	$cmdPart1 = "http://fhempi:8080/webhook?cmd=";
	// at_name
	$cmdPart2 = "";
	
	//
	
	$data = file_get_contents('http://tunnelpi/cal/getdata.php');
	
	$arr = json_decode($data, true);
	
	$count =sizeof($arr);
	echo "<h3>Total elements: ".$count."</h3>";
	
	//$dict = {};
	
	for($i=0;$i<$count;$i++){

		$dateArr = date_parse_from_format("m-d-Y", $arr[$i]['dateStr']);
		$d = new DateTime($dateArr['year'].'-'.$dateArr['month'].'-'.$dateArr['day']);
		echo "<br/>";
		echo $d->format('d.m.Y');
		
		echo "<br/>";
		//
	}
	// $Date = new DateTime('2013-07-09T04:58:23.075Z'); //create dateTime object
	// $Date->setTimezone(new DateTimeZone('Europe/Berlin')); //set the timezone
	// echo $Date->format('d/m/Y H:i'); 

	/*
	$date = "09-23-2021";
	
	$d = new DateTime($dateArr['year'].'-'.$dateArr['month'].'-'.$dateArr['day']);
	echo "<br/>";
	echo $d->format('d.m.Y H:i');
	
	// period time
	$addStr = "PT";
	// nbr hours
	$addStr .= "5";
	// hour
	$addStr .= "H";
	// nbr minutes
	$addStr .= "45";
	// minute
	$addStr .= "M";
	
	//$d->add(new DateInterval('PT5H30M'));
	$d->add(new DateInterval($addStr));
	
	echo "<br/>";
	echo $d->format('d.m.Y H:i');
	*/
	
	?>
	


  </body>
</html>