<?php
require_once 'config/database.php';

try {
    // Test koneksi
    $databases = $client->listDatabases();
    
    echo "Koneksi MongoDB berhasil!<br>";
    echo "Database yang tersedia:<br>";
    foreach ($databases as $db) {
        echo " - " . $db->getName() . "<br>";
    }
    
    // Cek collection
    echo "<br>Collection dalam sleep_tracker:<br>";
    $collections = $database->listCollectionNames();
    foreach ($collections as $collection) {
        echo " - " . $collection . "<br>";
    }
    
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?> 