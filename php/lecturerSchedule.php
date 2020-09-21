<?php

session_start();

require_once 'dbconn.php';

if(!isset($_SESSION['id'])){
    header("Location: ../index.html");
    exit;
}

/**
 * Lecturer Schedule Creation
 */
if (isset($_SESSION) && isset($_POST['addSchedule'])) {
    $insertScheduleDb = $link->prepare(
        "INSERT INTO lecturer_timetable VALUES (:email, :sTime, :eTime ,:day)");
    $insertScheduleDb->execute(array(
        ":email" => $_SESSION['id'],
        ":sTime" => $_POST['start_time'],
        ":eTime" => $_POST['end_time'],
        ":day" => $_POST['day']
    ));
}

/**
 * Lecture Schedule Deletion
 */
if (isset($_SESSION) && isset($_POST['deleteSchedule'])) {
    $deleteScheduleDb = $link->prepare("DELETE FROM lecturer_timetable 
        WHERE lecturer_id=:email AND start_time=:sTime AND end_time=:eTime AND day=:day");
    $deleteScheduleDb->execute(array(
        ":email" => $_SESSION['id'],
        ":sTime" => $_POST['start_time'],
        ":eTime" => $_POST['end_time'],
        ":day" => $_POST['day']
    ));
}

/**
 * Get Lecturer's Schedule
 */
if (isset($_SESSION) && isset($_POST['getSchedule'])) {
    try {
        $fetchLecturerSchedule = $link->prepare(
            "SELECT * FROM lecturer_timetable WHERE lecturer_id=:id 
            ORDER BY FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 
            'Friday', 'Saturday', 'Sunday'), start_time");
        $fetchLecturerSchedule->execute(array(":id" => $_POST['getSchedule']));

        $lecturerSchedule = $fetchLecturerSchedule->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($lecturerSchedule);
    } catch (PDOException $PDOException) {
        echo json_encode(array("PDO-error" => $PDOException->getMessage()));
    }
}
?>
