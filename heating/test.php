<?php 
$beginn = microtime(true); 

// Skript und Aktionen die gemessen werden sollen 

$dauer = microtime(true) - $beginn; 
echo "Verarbeitung des Skripts: $dauer Sek.";
?>