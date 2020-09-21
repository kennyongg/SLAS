<?php

require __DIR__ . "/../vendor/autoload.php";

use Hidehalo\Nanoid\Client;
use Hidehalo\Nanoid\GeneratorInterface;

/**
 * Generate Password using NanoID
 */
function generatePassword()
{
    $nano = new Client();

    $size = 10;

    $nano_pwd = $nano->generateId($size, Client::MODE_DYNAMIC);

    return $nano_pwd;
}

/**
 * Generate Appointment ID using NanoID
 */
function generateAppointmentId() {
    $nano = new Client();

    $size = 20;

    $appointmentId = $nano->generateId($size, Client::MODE_NORMAL);

    return $appointmentId;
}

/**
 * Generate Recovery ID using NanoID
 */
function generateRecoveryId() {
    $nano = new Client();

    $size = 30;

    $recoveryId  = $nano->generateId($size, Client::MODE_DYNAMIC);

    return $recoveryId;
}

?>

