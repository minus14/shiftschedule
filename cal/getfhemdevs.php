<?php
//header('Content-Type: application/json');

//$cmd = "http://192.168.14.10:8080/webhook?cmd=jsonlist2%20TYPE=at%20NAME&XHR=1";
$cmd = "http://192.168.14.10:8080/webhook?cmd=list%20TYPE=at%20NAME&XHR=1";
$data = file_get_contents($cmd);
echo $data;
?>