<?php
require_once __DIR__ . '/../vendor/autoload.php';

try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $database = $client->sleep_tracker;
    $collection = $database->sleep_records;
} catch (Exception $e) {
    die("Error koneksi ke MongoDB: " . $e->getMessage());
}
?> 