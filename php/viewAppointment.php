<?php

session_start();

require_once 'dbconn.php';

if(!isset($_SESSION['id'])){
    header("Location: ../index.html");
    exit;
}

/**
 * Get Appointment List for Landing Page
 */
if (isset($_POST['getLandingAppointmentList'])) {
    $user = $_SESSION['id'];
    $accountType = $_SESSION['acc_type'];

    if ($accountType == "student") {
        $checkAppointmentList = $link->prepare(
            "SELECT name, title, lecturer, subject, start_date, start_time, end_time, venue 
            FROM appointment, staff WHERE student=:email AND staff.email=appointment.lecturer 
            AND status='approved' ORDER BY start_date ASC");
        $checkAppointmentList->execute(array(
            ":email" => $user
        ));
    } elseif ($accountType == "lecturer") {
        $checkAppointmentList = $link->prepare(
            "SELECT name, title, student, subject, start_date, start_time, end_time, venue 
            FROM appointment a, student s WHERE lecturer=:email 
            AND s.email = a.student AND status='approved' ORDER BY start_date ASC");
        $checkAppointmentList->execute(array(
                ":email" => "$user"
        ));
    }

    $appointmentList = $checkAppointmentList->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($appointmentList);
}

/**
 * Get Full Appointment List
 */
if (isset($_POST['getAppointmentList'])) {
    $user = $_SESSION['id'];
    $accountType = $_SESSION['acc_type'];

    if ($accountType == "student") {
        $checkAppointmentList = $link->prepare(
            "SELECT id, title, description, name, lecturer, subject, start_date, 
            start_time, end_time, rejected_reason, venue, status FROM appointment, staff 
            WHERE student=:email AND staff.email = appointment.lecturer 
            ORDER BY start_date DESC");
        $checkAppointmentList->execute(array(
            ":email" => $user
        ));
    } elseif ($accountType == "lecturer") {
        $checkAppointmentList = $link->prepare(
            "SELECT id, title, description, name, lecturer, subject, start_date, 
            start_time, end_time, rejected_reason, venue, status FROM appointment, student 
            WHERE lecturer=:email AND student.email = appointment.student 
            ORDER BY start_date DESC");
        $checkAppointmentList->execute(array(
                ":email" => "$user"
            )
        );
    }

    $appointmentList = $checkAppointmentList->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($appointmentList);
}

/**
 * Get Appointment Details based on ID
 */
if (isset($_GET['appointmentId'])){
    $appointmentId = $_GET['appointmentId'];

    $fetchAppointment = $link->prepare(
        "SELECT * FROM appointment WHERE id=:id");
    $fetchAppointment->execute(array(
        ":id" => $appointmentId
    ));

    $appointmentDetails = $fetchAppointment->fetch(PDO::FETCH_ASSOC);

    echo json_encode($appointmentDetails);
}

?>
