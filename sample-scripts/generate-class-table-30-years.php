#!/usr/bin/env php
<?php

include_once 'vendor/autoload.php';

use Balsama\HuskerScraper\Roster;
use MathieuViossat\Util\ArrayToTextTable;

$current_year = date("Y");
$first_year = ($current_year - 57);
$year_to_get = $current_year;

while ($year_to_get >= $first_year) {
    $roster = new Roster($year_to_get);

    $values[] = $roster->getClassCountByYear();

    $year_to_get--;
}

$renderer = new ArrayToTextTable($values);
$renderer->setDecorator(new \Zend\Text\Table\Decorator\Ascii());
echo $renderer->getTable();
