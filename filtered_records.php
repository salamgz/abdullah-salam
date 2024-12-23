<?php
require_once 'config/database.php';
session_start();

// Filter berdasarkan durasi dan mood
$filter = [
    '$and' => [
        ['duration' => ['$gte' => 6]], // Comparison operator
        ['mood' => ['$in' => ['sangat-segar', 'segar']]] // Logical operator
    ]
];

$goodSleepRecords = $collection->find($filter);
?> 