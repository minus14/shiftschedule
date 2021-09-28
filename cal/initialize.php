<?php

$db = new SQLite3('shiftschedule.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);

// Create a table.
$db->query('CREATE TABLE IF NOT EXISTS "shifts" (
    "dateStr" VARCHAR PRIMARY KEY NOT NULL,
    "shift" VARCHAR
)');


$db->query('DELETE FROM "shifts"');



?>