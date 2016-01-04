<?php

require "ParseCSV.php";
$csv = new ParseCSV;

// This array is a map of the keys we want to return and the column names provided in the CSV
$map =
[
    'date' => 'Date',
    'name' => 'user name', // Column names are matched case-insensitively
    'ice_cream' => 'Number of Ice Creams Eaten',
];

// If the CSV is parsed properly, you should see an array corresponding to the columns you matched
$array = $csv->parse('example.csv', $map);
print_r($array);

?>
