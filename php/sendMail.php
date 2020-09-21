<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use SendGrid\Mail\Mail;
use SendGrid\Mail\TypeException;

$dotenv = Dotenv::create(__DIR__ . '/../');
$dotenv->load();

/**
 * Send User Credentials Email for New User
 *
 * @param string $name      User Full Name
 * @param string $userEmail User Email
 * @param string $password  Account Password
 *
 * @throws TypeException
 */
function sendCredentialEmail($name, $userEmail, $password)
{
    $email = new Mail();
    $email->setFrom("system@slas.com", "Appointment System");
    $email->setSubject("SLAS Credentials");
    $email->addTo(
        (isset($_ENV['TRAP_EMAIL']) ? $_ENV['TRAP_EMAIL'] : $userEmail), $name,
        [
            "name" => $name,
            "email" => $userEmail,
            "password" => $password
        ],
        0
    );

    $email->setTemplateId("d-be43d48e91ba4e2f9d596052cfda0454");
    $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));

    try {
        $sendgrid->send($email);
    } catch (Exception $exception) {
        echo json_encode(array("mail-error" => $exception->getMessage()));
    }
}

/**
 * Send Password Reset Email
 *
 * @param string $userEmail User Email
 * @param string $name      User Full Name
 * @param string $id        Recovery ID
 * 
 * @throws TypeException
 */
function sendRecoveryMail($userEmail, $name, $id){
    $email = new Mail();
    $email->setFrom("system@slas.com", "Appointment System");
    $email->setSubject("Reset your Password");
    $email->addTo(
        (isset($_ENV['TRAP_EMAIL']) ? $_ENV['TRAP_EMAIL'] : $userEmail), $name,
        [
            "name" => $name,
            "email" => $userEmail,
            "recoveryId" => $id
        ],
        0
    );

    $email->setTemplateId("d-e7d985cf66a346cf8c1c66895389f500");
    $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));

    try {
        $sendgrid->send($email);
    } catch (Exception $exception) {
        echo json_encode(array("mail-error" => $exception->getMessage()));
    }
}

/**
 * Send Request Email to Lecturer
 *
 * @param string $title         Appointment Title
 * @param string $lecturerEmail Lecturer Email
 * @param string $lecturerName  Lecturer Full Name
 * @param string $studentName   Student Full Name
 * @param string $subject       Course Subject
 * @param string $date          Date of Appointment
 * @param string $startTime     Start Time of Appointment
 * @param string $endTime       End Time of Appointment
 * @param string $venue         Location of Appointment
 *
 * @throws TypeException
 */
function sendRequestMail($title, $lecturerEmail, $lecturerName, $studentName,
                         $subject, $date, $startTime, $endTime, $venue)
{
    $email = new Mail();
    $email->setFrom("system@slas.com", "Appointment System");
    $email->setSubject("Request for Appointment");
    $email->addTo(
        (isset($_ENV['TRAP_EMAIL']) ? $_ENV['TRAP_EMAIL'] : $lecturerEmail),
        $lecturerName,
        [
            "title" => $title,
            "lecturer_name" => $lecturerName,
            "student_name" => $studentName,
            "subject" => $subject,
            "date" => $date,
            "time" => "$startTime - $endTime",
            "venue" => $venue
        ],
        0
    );
    $email->setTemplateId("d-e22c7042446a45cb8f181df13017f005");
    $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));

    try {
        $sendgrid->send($email);
    } catch (Exception $exception) {
        echo json_encode(array("mail-error" => $exception->getMessage()));
    }
}

/**
 * Send Appointment Approved Email to Student with .ics attached
 *
 * @param string $lecturerName Lecturer Full Name
 * @param string $studentEmail Student Email
 * @param string $title        Appointment Title
 * @param string $studentName  Student Full Name
 * @param string $subject      Subject Name
 * @param string $date         Date of Appointment
 * @param string $startTime    Start Time of Appointment
 * @param string $endTime      End Time of Appointment
 * @param string $venue        Venue of Appointment
 * @param string $id           Appointment ID
 *
 * @throws TypeException
 */
function sendAppointmentApprovedEmail($lecturerName, $studentEmail, $title, $studentName,
                                      $subject, $date, $startTime, $endTime, $venue, $id) {
    $email = new Mail();
    $email->setFrom("system@slas.com", "Appointment System");
    $email->setSubject("Approved - Appointment Request");
    $email->addTo(
        (isset($_ENV['TRAP_EMAIL']) ? $_ENV['TRAP_EMAIL'] : $studentEmail), $studentName,
        [
            "title" => $title,
            "lecturer_name" => $lecturerName,
            "student_name" => $studentName,
            "subject" => $subject,
            "date" => $date,
            "time" => "$startTime - $endTime",
            "venue" => $venue
        ],
        0
    );

    $attachment = base64_encode(file_get_contents("./uploads/ics/consultation-"
        . "$id" . ".ics"));
    $email->addAttachment($attachment, "text/calendar", "Appointment.ics");

    $email->setTemplateId("d-3ac0dd66ba7d48318cf1151c3378a9c2");
    $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));

    try {
        $sendgrid->send($email);
    } catch (Exception $exception) {
        echo json_encode(array("mail-error" => $exception->getMessage()));
    }
}

/**
 * Send Appointment Created Email to Lecturer with .ics attached
 *
 * @param string $lecturerEmail Lecturer Email
 * @param string $lecturerName  Lecturer Full Name
 * @param string $title         Appointment Title
 * @param string $studentName   Student Full Name
 * @param string $subject       Subject Name
 * @param string $date          Date of Appointment
 * @param string $startTime     Start Time of Appointment
 * @param string $endTime       End Time of Appointment
 * @param string $venue         Venue of Appointment
 * @param string $id            Appointment ID
 *
 * @throws TypeException
 */
function sendAppointmentCreatedEmail($lecturerEmail, $lecturerName, $title, $studentName,
                                     $subject, $date, $startTime, $endTime, $venue, $id) {
    $email = new Mail();
    $email->setFrom("system@slas.com", "Appointment System");
    $email->setSubject("Your New Appointment");
    $email->addTo(
        (isset($_ENV['TRAP_EMAIL']) ? $_ENV['TRAP_EMAIL'] : $lecturerEmail), $lecturerName,
        [
            "title" => $title,
            "lecturer_name" => $lecturerName,
            "student_name" => $studentName,
            "subject" => $subject,
            "date" => $date,
            "time" => "$startTime - $endTime",
            "venue" => $venue
        ],
        0
    );

    $attachment = base64_encode(file_get_contents("./uploads/ics/consultation-"
        . "$id" . ".ics"));
    $email->addAttachment($attachment, "text/calendar", "Appointment.ics");

    $email->setTemplateId("d-5c68682890da4acab378f09a3880d139");
    $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));

    try {
        $sendgrid->send($email);
    } catch (Exception $exception) {
        echo json_encode(array("mail-error" => $exception->getMessage()));
    }
}

/**
 * Send Appointment Rejected Email to Student
 *
 * @param string $studentEmail Student Email
 * @param string $lecturerName Lecturer Full Name
 * @param string $title        Appointment Title
 * @param string $studentName  Student Full Name
 * @param string $subject      Student Subject
 * @param string $date         Date of Appointment
 * @param string $startTime    Start Time of Appointment
 * @param string $endTime      End Time of Appointment
 * @param string $venue        Venue of Appointment
 * @param string $reason       Reason of Rejection
 *
 * @throws TypeException
 */
function sendAppointmentRejectedEmail($studentEmail, $lecturerName, $title, $studentName,
                                      $subject, $date, $startTime, $endTime, $venue, $reason) {
    $email = new Mail();
    $email->setFrom("system@slas.com", "Appointment System");
    $email->setSubject("Rejected - Appointment Request");
    $email->addTo(
        (isset($_ENV['TRAP_EMAIL']) ? $_ENV['TRAP_EMAIL'] : $studentEmail), $studentName,
        [
            "title" => $title,
            "lecturer_name" => $lecturerName,
            "student_name" => $studentName,
            "subject" => $subject,
            "date" => $date,
            "time" => "$startTime - $endTime",
            "venue" => $venue,
            "reason" => $reason
        ],
        0
    );

    $email->setTemplateId("d-c081d9d8e77645a58708d740d339d9c7");
    $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));

    try {
        $sendgrid->send($email);
    } catch (Exception $exception) {
        echo json_encode(array("mail-error" => $exception->getMessage()));
    }
}

/**
 * Send Appointment Cancellation Email
 *
 * @param string $userEmail User Email
 * @param string $userName  User Full Name
 * @param string $name2     User Full Name
 * @param string $title     Appointment Title
 * @param string $subject   Subject Name
 * @param string $date      Date of Appointment
 * @param string $startTime Start Time of Appointment
 * @param string $endTime   End Time of Appointment
 * @param string $venue     Venue of Appointment
 * @param string $reason    Reason of Cancellation
 *
 * @throws TypeException
 */
function sendAppointmentCancelledEmail($userEmail, $userName, $name2, $title, $subject, $date,
                                       $startTime, $endTime, $venue, $reason){
    $email = new Mail();
    $email->setFrom("system@slas.com", "Appointment System");
    $email->setSubject("Rejected - Appointment Request");
    $email->addTo(
        (isset($_ENV['TRAP_EMAIL']) ? $_ENV['TRAP_EMAIL'] : $userEmail), $userName,
        [
            "title" => $title,
            "name" => $userName,
            "name2" =>$name2,
            "subject" => $subject,
            "date" => $date,
            "time" => "$startTime - $endTime",
            "venue" => $venue,
            "reason" => $reason
        ],
        0
    );

    // Only send to lecturer if the appointment is approved prior to cancellation
    if ($hasApproved == 1) {
        $email->addTo(
            (isset($_ENV['TRAP_EMAIL']) ? $_ENV['TRAP_EMAIL'] : $studentEmail), $studentName,
            [
                "lecturer_name" => $lecturerName,
                "student_name" => $studentName,
                "subject" => $subject,
                "date" => $date,
                "start_time" => $startTime,
                "end_time" => $endTime,
                "venue" => $venue,
                "reason" => $reason
            ],
            0
        );

        $toEmails = [
            (isset($_ENV['TRAP_EMAIL']) ? $_ENV['TRAP_EMAIL'] : $studentEmail) => $studentName,
            (isset($_ENV['TRAP_EMAIL']) ? $_ENV['TRAP_EMAIL'] : $studentEmail) => $studentName
        ];
        $email->addTos($toEmails);
    }

    $email->setTemplateId("");
    $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));

    try {
        $sendgrid->send($email);
    } catch (Exception $exception) {
        echo json_encode(array("mail-error" => $exception->getMessage()));
    }
}
?>
