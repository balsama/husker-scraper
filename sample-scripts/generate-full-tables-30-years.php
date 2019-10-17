#!/usr/bin/env php
<?php

include_once 'vendor/autoload.php';

use Balsama\HuskerScraper\Roster;

$current_year = date("Y");
$first_year = ($current_year - 57);
$year_to_get = $current_year;

while ($year_to_get >= $first_year) {
    $roster = new Roster($year_to_get);
    echo "\n\n----\n" . $year_to_get . "\n----\n";
    echo $roster->getRosterTable();

    $year_to_get--;
}
