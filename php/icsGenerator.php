<?php

require __DIR__ . '/../vendor/autoload.php';

use Eluceo\iCal\Component\Calendar;
use Eluceo\iCal\Component\Event;
use Eluceo\iCal\Component\Timezone;

/**
 * Generate .ics Calendar File
 *
 * @param string $id          Appointment ID
 * @param string $startDate   Start Date of Appointment
 * @param string $startTime   Start Time of Appointment
 * @param string $endDate     End Date of Appointment
 * @param string $endTime     End Time of Appointment
 * @param string $venue       Location of Appointment
 * @param string $studentName Student Full Name
 * @param string $subjectName Lecturer Full Name
 *
 * @throws Exception
 */
function generateCalendar($id, $startDate, $startTime, $endDate, $endTime,
                          $venue, $studentName, $subjectName)
{
    $tz = 'Asia/Kuala_Lumpur';
    $dtz = new DateTimeZone($tz);
    date_default_timezone_set($tz);

    $vTimezone = new Timezone($tz);

    $vCal = new Calendar('id');
    $vCal->setTimezone($vTimezone);

    $vEvent = new Event();

    $startDateTime = date('Ymd\THis', strtotime("$startDate $startTime"));
    $endDateTime = date('Ymd\THis', strtotime("$endDate $endTime"));

    $vEvent
        ->setDtStart(new DateTime($startDateTime, $dtz))
        ->setDtEnd(new DateTime($endDateTime, $dtz))
        ->setUseTimezone(true)
        ->setNoTime(false)
        ->setIsPrivate(true)
        ->setLocation($venue)
        ->setSummary('Student Appointment')
        ->setDescription("Student: ".$studentName."\nSubject: ".$subjectName);

    $vCal->addComponent($vEvent);

    if (!is_dir("./uploads/ics")) {
        mkdir("./uploads/ics", 0755, true);
    }

    file_put_contents("./uploads/ics/consultation-" . $id . ".ics", $vCal->render());
}
?>

