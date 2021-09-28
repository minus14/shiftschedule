<?php
header('Content-Type: application/json');
$db = new SQLite3('shiftschedule.sqlite', SQLITE3_OPEN_READWRITE);


$statement = $db->prepare('SELECT * FROM "shifts"');
// $statement->bindValue(':dateStr', '09-10-2021');
$result = $statement->execute();


// key exists?
// $statement = $db->prepare('SELECT EXISTS(SELECT 1 FROM "shifts" WHERE "dateStr" = :dateStr )');
// $statement->bindValue(':dateStr', '09-10-2021');
// $result = $statement->execute();
// print_r($result->fetchArray()[0]);

//echo("Get the 1st row as an associative array:\n");
//print_r($result->fetchArray(SQLITE3_ASSOC));
//echo("\n");


//echo("Get the next row as a numeric array:\n");
//print_r($result->fetchArray(SQLITE3_BOTH));
//echo("\n");

//return $result->fetchArray(SQLITE3_ASSOC);

$array = array();
while($data = $result->fetchArray())
{
	 $array[] = $data;
}

// return $array;
	
//return $arr;
echo json_encode($array);

// while($row = ) ) {
       // print_r($row);
		// print_r("<br/>");
    // }

// If there are no more rows, fetchArray() returns FALSE.

// free the memory, this in NOT done automatically, while your script is running
$result->finalize();
 
?>