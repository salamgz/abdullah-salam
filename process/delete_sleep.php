<?php
require_once '../config/database.php';
session_start();

if (isset($_GET['id'])) {
    try {
        $id = $_GET['id'];
        
        $result = $collection->deleteOne([
            '_id' => new MongoDB\BSON\ObjectId($id)
        ]);

        if ($result->getDeletedCount() > 0) {
            $_SESSION['success_message'] = "Data berhasil dihapus!";
        } else {
            $_SESSION['error_message'] = "Data tidak ditemukan.";
        }
        
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
    }
}

header('Location: ../index.php');
exit;
?> 