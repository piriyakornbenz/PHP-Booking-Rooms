<?php

    function slotCount() {
        require('./config.php');
        $stmt = $conn->prepare("SELECT * FROM slottime");
        $stmt->execute();
        $row = $stmt->fetch();

        // data from database
        $start = $row['start'];
        $end = $row['end'];
        $duration = $row['duration'];

        $startTime = strtotime($start);
        $endTime = strtotime($end);
        $durationInSeconds = $duration * 60;

        if ($endTime <= $startTime) {
            return 0;
        }

        $totalDuration = $endTime - $startTime;
        $slotCount = floor($totalDuration / $durationInSeconds);

        return $slotCount;
    }

?>