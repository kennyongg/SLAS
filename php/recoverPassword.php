<?php

require __DIR__ . "/../vendor/autoload.php";

require_once 'dbconn.php';
require_once 'nanoGenerator.php';
require_once 'sendMail.php';

use SendGrid\Mail\TypeException;

$errMsg = array();

if (isset($_POST['recoverPassword'])){
    try {
        $checkCredentials = $link->prepare("SELECT * FROM user_credential where id=:email");
        $checkCredentials->execute(array(
            ':email' => $_POST['recoverPassword']
        ));

        $userCredentials = $checkCredentials->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $PDOException) {
        echo json_encode(array("PDO-error" => $PDOException->getMessage()));
    }

    if ($userCredentials == ''){
        echo json_encode(array("error" => "Email not found!"));

    }
    else {
        try {
            $email = $userCredentials['id'];
            $accType = $userCredentials['acc_type'];

            if ($accType == "lecturer"){
                $getName = $link->prepare("SELECT name FROM staff WHERE email=:email");
                $getName->execute(array(":email" => $email));
            }
            elseif ($accType == "student") {
                $getName = $link->prepare("SELECT name FROM student WHERE email=:email");
                $getName->execute(array(":email" => $email));
            }

            $name = $getName->fetchColumn();

            $hadRequested = false;

            $checkExistingRequest = $link->prepare(
                "SELECT * FROM password_recovery WHERE email=:email AND status=1"
            );
            $checkExistingRequest->execute(array(":email" => $email));

            $existingRequest = $checkExistingRequest->fetch(PDO::FETCH_ASSOC);

            if ($existingRequest){
                $hadRequested = true;
            }

            if ($hadRequested) {
                try {
                    sendRecoveryMail($email, $name, $existingRequest['id']);

                    echo json_encode(array(
                        "success" => "Password recovery requested successfully!"
                    ));
                } catch (TypeException $typeException) {
                    echo json_encode(array("mail-error" => $typeException->getMessage()));
                }
            }
            else {
                $isDuplicate = false;

                do {
                    $recoveryId = generateRecoveryId();

                    $checkRecoveryId = $link->prepare(
                        "SELECT id FROM password_recovery WHERE id=:id");
                    $checkRecoveryId->execute(array(":id" => $recoveryId));

                    $recoveryDb = $checkRecoveryId->fetch(PDO::FETCH_ASSOC);

                    if ($recoveryId == $recoveryDb['id']) {
                        $isDuplicate = true;
                    }

                } while ($isDuplicate == true);

                $insertRecoveryDb = $link->prepare(
                    "INSERT INTO password_recovery values (:id, :email, '1')");

                $insertRecoveryDb->execute(array(
                    ":id" => $recoveryId,
                    ":email" => $email
                ));

                try {
                    sendRecoveryMail($email, $name, $recoveryId);
                } catch (TypeException $typeException) {
                    echo json_encode(array("mail-error" => $typeException->getMessage()));
                }

                echo json_encode(array(
                    "success" => "Password recovery requested successfully!"
                ));
            }
        } catch (PDOException $PDOException) {
            echo json_encode(array("PDO-error" => $PDOException->getMessage()));
        }
    }
}

if (isset($_GET['recoveryId'])) {
    $currentDate = date("Y-m-d");

    $recoveryId = $_GET['recoveryId'];

    try {
        $checkRecoveryId = $link->prepare("SELECT * FROM password_recovery WHERE id=:id");
        $checkRecoveryId->execute(array(":id" => $recoveryId));

        $recoveryData = $checkRecoveryId->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $PDOException){
        echo json_encode(array("PDO-error" => $PDOException->getMessage()));
    }

    if ($recoveryData['status'] == false) {
        echo json_encode(array("error" => "Expired token"));
    }
    else {
        echo json_encode(array("success" => $recoveryData['email'],
            "recoveryId" => $recoveryId));
    }
}

if (isset($_POST['newPassword']) && isset($_POST['email'])){
    try {
        $recoveryId = $_POST['recoveryId'];
        $account = $_POST['email'];
        $newPass = $_POST['newPassword'];

        $updatePassword = $link->prepare(
            "UPDATE user_credential SET password=:pass WHERE id=:id");
        $updatePassword->execute(array(
            ":pass" => password_hash($newPass, PASSWORD_BCRYPT),
            ":id" => $account
        ));

        $invalidateToken = $link->prepare(
            "UPDATE password_recovery SET status=0 WHERE id=:id");
        $invalidateToken->execute(array(":id" => $recoveryId));

        echo json_encode(array("success" => "Password changed successfully"));
    } catch (PDOException $PDOException) {
        echo json_encode(array("PDO-error" => $PDOException->getMessage()));
    }
}
?>
