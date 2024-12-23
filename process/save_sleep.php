<?php
require_once '../config/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sleep_time = new DateTime($_POST['sleep_time']);
    $wake_time = new DateTime($_POST['wake_time']);
    
    // Hitung durasi tidur
    $interval = $sleep_time->diff($wake_time);
    $duration = $interval->h + ($interval->days * 24);
    
    // Tentukan status tidur
    $status = ($duration >= 7 && $duration <= 9) ? 'normal' : 'warning';
    
    // Data yang akan disimpan
    $data = [
        'user_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id']),
        'date' => new MongoDB\BSON\UTCDateTime(new DateTime($_POST['sleep_time'])),
        'sleep_time' => $_POST['sleep_time'],
        'wake_time' => $_POST['wake_time'],
        'duration' => $duration,
        'status' => $status,
        'mood' => $_POST['mood'],
        'notes' => $_POST['notes']
    ];
    
    try {
        $collection->insertOne($data);
        $_SESSION['success_message'] = "Data berhasil disimpan!";
        header('Location: ../index.php');
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error menyimpan data: " . $e->getMessage();
        header('Location: ../index.php');
    }
}
?> 