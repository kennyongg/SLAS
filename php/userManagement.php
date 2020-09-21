<?php

session_start();

require_once 'dbconn.php';
require_once 'nanoGenerator.php';
require_once 'sendMail.php';

if(!isset($_SESSION['id'])){
    header("Location: ../index.html");
    exit;
}

/**
 * Add user through form
 */
if ($_SESSION['acc_type'] == "admin" && isset($_POST['register'])) {
    if (isset($_POST['registerStudentEmail'])) {
        $accountType = "student";
    }
    else {
        $accountType = "lecturer";
    }

    $pass = generatePassword();
    $hashedPassword = password_hash($pass, PASSWORD_BCRYPT);

    try {
        if ($accountType == "student") {
            $insertCredentialDb = $link->prepare(
                "INSERT INTO user_credential VALUES (:id, :pwd, 'student', :active)");
            $insertCredentialDb->execute(array(
                ":id" => $_POST['registerStudentEmail'],
                ":pwd" => $hashedPassword,
                ":active" => "1"
            ));

            $insertStudentDb = $link->prepare(
                "INSERT INTO student VALUES (:email, :name, :course, :intake)");
            $insertStudentDb->execute(array(
                ":email" => $_POST['registerStudentEmail'],
                ":name" => $_POST['registerStudentName'],
                ":course" => $_POST['registerStudentCourse'],
                ":intake" => $_POST['registerStudentIntake']
            ));

            try {
                sendCredentialEmail($_POST['registerStudentName'],
                    $_POST['registerStudentEmail'], $pass);
            } catch (Exception $exception) {
                echo json_encode(array("mail-error" => $exception->getMessage()));
            }

            echo json_encode(array("success" => "User Created!"));
        }
        elseif ($accountType == "lecturer") {
            $insertCredentialDb = $link->prepare(
                "INSERT INTO user_credential VALUES (:id, :pwd, 'lecturer', :active)");
            $insertCredentialDb->execute(array(
                ":id" => $_POST['registerLecturerEmail'],
                ":pwd" => $hashedPassword,
                ":active" => "1"
            ));

            $insertStaffDb = $link->prepare(
                "INSERT INTO staff VALUES (:email, :name, :dept, :loc, :phone)");
            $insertStaffDb->execute(array(
                ":email" => $_POST['registerLecturerEmail'],
                ":name" => $_POST['registerLecturerName'],
                ":dept" => $_POST['registerLecturerDept'],
                ":loc" => $_POST['registerLecturerOffice'],
                ":phone" => $_POST['registerLecturerPhone']
            ));

            try {
                sendCredentialEmail($_POST['registerLecturerName'],
                    $_POST['registerLecturerEmail'], $pass);
            } catch (Exception $exception) {
                echo json_encode(array("mail-error" => $exception->getMessage()));
            }

            echo json_encode(array("success" => "User Created!"));
        }
    } catch (PDOException $PDOException) {
        echo json_encode(array("PDO-error" => $PDOException->getMessage()));
    }
}

/**
 * Edit User through Form
 */
if ($_SESSION['acc_type'] == "admin" && isset($_POST['editUserEmail'])) {
    $checkAccountType = $link->prepare("SELECT acc_type FROM user_credential WHERE id=:id");
    $checkAccountType->execute(array(":id" => $_POST['editUserEmail']));
    $accountType = $checkAccountType->fetchColumn();

    $updateCredentialDb = $link->prepare(
        "UPDATE user_credential SET active=:active WHERE id=:id");
    $updateCredentialDb->execute(array(
        ":id" => $_POST['editUserEmail'],
        ":active" => $_POST['editUserActive']
    ));

    try {
        if ($accountType == "student") {
            $updateUserDb = $link->prepare(
                "UPDATE student SET name=:name, intake=:intake, course=:course 
                WHERE email=:id");
            $updateUserDb->execute(array(
                ":name" => $_POST['editUserName'],
                ":course" => $_POST['editUserCourse'],
                ":intake" => $_POST['editUserIntake'],
                ":id" => $_POST['editUserEmail']
            ));

            echo json_encode(array("success" => "User Updated!"));
        }
        elseif ($accountType == "lecturer") {
            $updateUserDb = $link->prepare(
                "UPDATE staff SET name=:name, phone=:phone, office_location=:loc, 
                 department=:dept WHERE email=:id");
            $updateUserDb->execute(array(
                ":id" => $_POST['editUserEmail'],
                ":name" => $_POST['editUserName'],
                ":dept" => $_POST['editUserDept'],
                ":loc" => $_POST['editUserOffice'],
                ":phone" => $_POST['editUserPhone']
            ));

            echo json_encode(array("success" => "User Updated!"));
        }
    } catch (PDOException $PDOException) {
        echo json_encode(array("PDO-error" => $PDOException->getMessage()));
    }
}

/**
 * Password Change
 */
if (isset($_SESSION) && isset($_POST['changePassword'])){
    try {
        $updateCredentialDb = $link->prepare("UPDATE user_credential SET password=:pass 
        WHERE id=:id");
        $updateCredentialDb->execute(array(
            ":pass" => password_hash($_POST['changePassword'], PASSWORD_BCRYPT),
            ":id" => $_SESSION['id']
        ));

        echo json_encode(array("success" => "Password changed successfully!"));
    } catch (PDOException $PDOException) {
        echo json_encode(array("PDO-error" => $PDOException->getMessage()));
    }

}

?>
