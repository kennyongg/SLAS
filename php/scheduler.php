<?php

require __DIR__ . '/../vendor/autoload.php';

require_once 'dbconn.php';

date_default_timezone_set("Asia/Kuala_Lumpur");
$currentEpoch = time();
$threeDaysAfterEpoch = strtotime('+3 days', $currentEpoch);
$statuses = array("approved", "pending");

foreach ($statuses as $status) {
    try {
        $getAppointmentList = $link->prepare("SELECT * FROM appointment WHERE status=:status");
        $getAppointmentList->execute(array(":status" => $status));
        $appointmentList = $getAppointmentList->fetchAll(PDO::FETCH_ASSOC);

        foreach ($appointmentList as $appointment) {
            $appointmentEndDate = $appointment['end_date'];
            $appointmentEndTime = $appointment['end_time'];
            $appointmentEndDateTime = $appointmentEndDate . " " . $appointmentEndTime;

            $appointmentEpoch = strtotime($appointmentEndDateTime);

            if ($appointmentEpoch <= $currentEpoch && $status == "approved") {
                updateAppointmentStatus($appointment['id'], "completed");
            }

            if ($appointmentEpoch <= $currentEpoch && $status == "pending") {
                //TODO: Email notification (ixgoh)
                updateAppointmentStatus($appointment['id'], "rejected");
            }
        }
    } catch (PDOException $PDOException) {
        echo json_encode(array("PDO-error" => $PDOException->getMessage()));
    }
}

// Remove cancelled, rejected appointments older than 3 days
try {
    $getAppointmentList = $link->prepare("SELECT * FROM appointment 
        WHERE status='rejected' OR status='cancelled'");
    $getAppointmentList->execute();
    $appointmentList = $getAppointmentList->fetchAll(PDO::FETCH_ASSOC);

    foreach ($appointmentList as $appointment) {
        $appointmentEndDate = $appointment['end_date'];
        $appointmentEndTime = $appointment['end_time'];
        $appointmentEndDateTime = $appointmentEndDate . " " . $appointmentEndTime;

        $appointmentEpoch = strtotime($appointmentEndDateTime);

        // Remove appointment older than 3 days
        if ($threeDaysAfterEpoch <= $appointmentEpoch) {
            $removeAppointment = $link->prepare("DELETE FROM appointment WHERE id=:id");
            $removeAppointment->execute(array(":id" => $appointment['id']));
        }
    }
} catch (PDOException $PDOException) {
    echo json_encode(array("PDO-error" => $PDOException->getMessage()));
}

/**
 * Update appointment status in Database
 *
 * @param string $appointmentId Appointment ID
 * @param string $status        Status of Appointment
 */
function updateAppointmentStatus($appointmentId, $status)
{
    global $link;

    if ($status == "rejected") {
        $reason = "Expired. No response from lecturer";
    }
    else {
        $reason = '';
    }

    try {
        $updateAppointmentStatus = $link->prepare(
            "UPDATE appointment SET status=:status, rejected_reason=:reason WHERE id=:id");
        $updateAppointmentStatus->execute(array(
            ":id" => $appointmentId,
            ":reason" => $reason,
            ":status" => $status
        ));
    } catch (PDOException $PDOException) {
        echo json_encode(array("PDO-error" => $PDOException->getMessage()));
    }
}

?>
