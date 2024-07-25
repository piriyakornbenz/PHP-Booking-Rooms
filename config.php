<?php 
    $servername = "localhost";
    $username = "root";
    $password = "";
    try {
        $conn = new PDO("mysql:host=$servername; dbname=bookingcalendar",$username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        date_default_timezone_set('Asia/Bangkok');
        // echo "connect successfully.";
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
?>