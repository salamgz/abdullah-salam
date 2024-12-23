<?php
require_once '../config/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['record_id'];
        $sleep_time = new DateTime($_POST['sleep_time']);
        $wake_time = new DateTime($_POST['wake_time']);
        
        // Hitung durasi tidur
        $interval = $sleep_time->diff($wake_time);
        $duration = $interval->h + ($interval->days * 24);
        
        // Tentukan status tidur
        $status = ($duration >= 7 && $duration <= 9) ? 'normal' : 'warning';
        
        // Update data
        $result = $collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($id)],
            ['$set' => [
                'sleep_time' => $_POST['sleep_time'],
                'wake_time' => $_POST['wake_time'],
                'duration' => $duration,
                'status' => $status,
                'mood' => $_POST['mood'],
                'notes' => $_POST['notes'],
                'updated_at' => new MongoDB\BSON\UTCDateTime()
            ]]
        );

        if ($result->getModifiedCount() > 0) {
            $_SESSION['success_message'] = "Data berhasil diperbarui!";
        } else {
            $_SESSION['error_message'] = "Tidak ada perubahan data.";
        }
        
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
    }
    
    header('Location: ../index.php');
    exit;
}
?> 