<?php

session_start();

require __DIR__ . "/../vendor/autoload.php";

require_once 'dbconn.php';
require_once 'sendMail.php';
require_once 'icsGenerator.php';
require_once 'nanoGenerator.php';

if(!isset($_SESSION['id'])){
    header("Location: ../index.html");
    exit;
}

/**
 * Appointment creation
 *
 * @throws PDOException
 * @throws Exception
 */
if (isset($_SESSION) && isset($_POST['createAppointment'])) {
    try {
        $studentNameQuery = $link->prepare(
            "SELECT name from student WHERE email=:email");
        $studentNameQuery->execute(array(":email" => $_SESSION['id']));

        $studentName = $studentNameQuery->fetchColumn();

        $isDuplicate = false;

        do {
            $appointmentId = generateAppointmentId();

            $checkAppointmentId = $link->prepare(
                "SELECT id FROM appointment WHERE id=:id");
            $checkAppointmentId->execute(array(":id" => $appointmentId));

            $appointmentDb = $checkAppointmentId->fetch(PDO::FETCH_ASSOC);

            if ($appointmentId == $appointmentDb['id']) {
                $isDuplicate = true;
            }
        } while ($isDuplicate == true);

        $insertAppointmentDb = $link->prepare(
            "INSERT INTO appointment VALUES (:id, :title, :desc, :lEmail, 
                :sEmail, :subject, :sDate, :sTime, :eDate, :eTime, '', :venue, 'pending')"
        );

        $insertAppointmentDb->execute(array(
            ":id" => $appointmentId,
            ":title" => $_POST['title'],
            ":desc" => $_POST['description'],
            ":lEmail" => $_POST['lecturerEmail'],
            ":sEmail" => $_SESSION['id'],
            ":subject" => $_POST['subject'],
            ":sDate" => $_POST['startDate'],
            ":sTime" => $_POST['startTime'],
            ":eDate" => $_POST['endDate'],
            ":eTime" => $_POST['endTime'],
            ":venue" => $_POST['venue']
        ));

        $appointment = getAppointment($appointmentId);

    } catch (PDOException $PDOException) {
        echo json_encode(array("PDO-error" => $PDOException->getMessage()));
    }

    try {
        sendRequestMail($appointment['title'], $appointment['lecturer'],
            $appointment['lecturer_name'], $appointment['student_name'],
            $appointment['subject'], $appointment['start_date'], $appointment['start_time'],
            $appointment['end_time'], $appointment['venue']);
    } catch (Exception $exception) {
        echo json_encode(array("mail-error" => $exception->getMessage()));
    }

    try{
        generateCalendar($appointmentId, $_POST['startDate'], $_POST['startTime'],
            $_POST['endDate'], $_POST['endTime'], $_POST['venue'], $studentName,
            $_POST['subject']);
    } catch (Exception $exception){
        echo json_encode(array("error" => "Unable to generate iCal"));
    }
}

/**
 * Updates appointment whenever it is approved, rejected or cancelled.
 * Send notification emails for appointment attendees.
 *
 * @throws PDOException
 * @throws Exception
 */
if (isset($_SESSION) && isset($_POST['response'])) {
    $appointmentId = $_POST['appointmentId'];
    $response = $_POST['response'];

    if (isset($_POST['rejected_reason'])) {
        $reason = $_POST['rejected_reason'];
    }
    else {
        $reason = '';
    }

    try {
        $updateAppointmentStatus = $link->prepare(
            "UPDATE appointment SET status=:status, rejected_reason=:reason WHERE id=:id"
        );

        $updateAppointmentStatus->execute(array(
            ":id" => $appointmentId,
            ":reason" => $reason,
            ":status" => $response
        ));

        $appointment = getAppointment($appointmentId);

        if ($response == "approved") {
            try {
                sendAppointmentCreatedEmail($appointment['lecturer'],
                    $appointment['lecturer_name'], $appointment['title'],
                    $appointment['student_name'], $appointment['subject'],
                    $appointment['start_date'], $appointment['start_time'],
                    $appointment['end_time'], $appointment['venue'], $appointmentId);
                sendAppointmentApprovedEmail($appointment['lecturer_name'],
                    $appointment['student'], $appointment['title'],
                    $appointment['student_name'], $appointment['subject'],
                    $appointment['start_date'], $appointment['start_time'],
                    $appointment['end_time'], $appointment['venue'], $appointmentId);
            } catch (Exception $exception) {
                echo json_encode(array("mail-error" => $exception->getMessage()));
            }
        }
        elseif ($response == "rejected") {
            try {
                sendAppointmentRejectedEmail($appointment['student'],
                    $appointment['lecturer_name'], $appointment['title'],
                    $appointment['student_name'], $appointment['subject'],
                    $appointment['start_date'], $appointment['start_time'],
                    $appointment['end_time'], $appointment['venue'],
                    $appointment['rejected_reason']);
            } catch (Exception $exception) {
                echo json_encode(array("mail-error" => $exception->getMessage()));
            }
        }
        elseif ($response == "cancelled") {
            try {
                if ($_SESSION['acc_type'] == "student") {
                    sendAppointmentCancelledEmail($appointment['lecturer'],
                        $appointment['lecturer_name'], $appointment['student_name'],
                        $appointment['title'], $appointment['subject'],
                        $appointment['start_date'], $appointment['start_time'],
                        $appointment['end_time'], $appointment['venue'],
                        $appointment['rejected_reason']);
                }
                elseif ($_SESSION['acc_type'] == "lecturer") {
                    sendAppointmentCancelledEmail($appointment['student'],
                        $appointment['student_name'], $appointment['student_name'],
                        $appointment['title'], $appointment['subject'],
                        $appointment['start_date'], $appointment['start_time'],
                        $appointment['end_time'], $appointment['venue'],
                        $appointment['rejected_reason']);
                }


            } catch (Exception $exception) {
                echo json_encode(array("mail-error" => $exception->getMessage()));
            }
        }
    } catch (PDOException $PDOException) {
        echo json_encode(array("PDO-error" => $PDOException->getMessage()));
    }
}

/**
 * Get Appointment Details
 *
 * @param string $appointmentId Appointment ID
 * @return mixed
 */
function getAppointment($appointmentId) {
    global $link;


    $getAppointment = $link->prepare(
        "SELECT a.id, a.title, a.description, a.lecturer, staff.name AS lecturer_name, 
        a.student, student.name AS student_name, subject.name AS subject, a.start_date, 
        a.start_time, a.end_time, a.rejected_reason, a.venue FROM appointment a 
        INNER JOIN student ON student.email = a.student INNER JOIN staff ON 
        staff.email = a.lecturer INNER JOIN subject ON subject.id = a.subject 
        WHERE a.id = :id");
    $getAppointment->execute(array(":id" => $appointmentId));
    $appointment = $getAppointment->fetch(PDO::FETCH_ASSOC);

    return $appointment;

}

?>
