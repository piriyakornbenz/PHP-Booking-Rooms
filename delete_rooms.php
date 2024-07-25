<?php 
    session_start();
    require('./config.php');
    if (isset($_GET['delete_id'])) {
        $id = $_GET['delete_id'];
        $stmt = $conn->prepare("DELETE FROM rooms WHERE id=:id");
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "delete successful.";
            header('location: rooms.php');
        }else {
            $_SESSION['error'] = "delete failed.";
            header('location: rooms.php');
        }
    }
?>