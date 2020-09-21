<?php

session_start();

require __DIR__ . "/../vendor/autoload.php";

require_once 'dbconn.php';
require_once 'sendMail.php';

if(!isset($_SESSION['id'])){
    header("Location: ../index.html");
    exit;
}

/**
 * Profile fetching for Current User
 */
if (isset($_SESSION) && isset($_POST['getUserProfile'])) {
    $userEmail = $_SESSION['id'];

    if ($_SESSION['acc_type'] == "student") {
        try {
            $getStudentInfo = $link->prepare('SELECT * FROM student WHERE email=:email');
            $getStudentInfo->execute(array(
                'email' => $userEmail
            ));

            $studentInfo = $getStudentInfo->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $PDOException) {
            echo json_encode(array("PDO-error" => $PDOException));
        }
        if ($studentInfo == '') {
            echo json_encode(array("error" => "Student data not found!"));
        } else {
            $student = array(
                "email" => $studentInfo['email'],
                "name" => $studentInfo['name'],
                "course" => $studentInfo['course'],
                "intake" => $studentInfo['intake'],
            );

            echo json_encode($student);
        }
    } elseif ($_SESSION['acc_type'] == "lecturer" || $_SESSION['acc_type'] == "admin") {
        try {
            $getStaffInfo = $link->prepare('SELECT * FROM staff WHERE email=:email');
            $getStaffInfo->execute(array(
                'email' => $userEmail
            ));

            $staffInfo = $getStaffInfo->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $PDOException) {
            echo json_encode(array("PDO-error" => $PDOException));
        }
        if ($staffInfo == '') {
            echo json_encode(array("error" => "Lecturer data not found!"));
        } else {
            $staff = array(
                "email" => $staffInfo['email'],
                "name" => $staffInfo['name'],
                "department" => $staffInfo['department'],
                "office_location" => $staffInfo['office_location'],
                "phone" => $staffInfo['phone']
            );

            echo json_encode($staff);
        }
    }
}

/**
 * Get Lecturer Profile for Appointment Creation
 */
if (isset($_SESSION) && isset($_POST['lecturerEmail'])) {
    try {
        $getStaffInfo = $link->prepare('SELECT name, department, office_location, phone, email 
        FROM staff WHERE email=:email');
        $getStaffInfo->execute(array(
            'email' => $_POST['lecturerEmail']
        ));

        $staffInfo = $getStaffInfo->fetch(PDO::FETCH_ASSOC);

        echo json_encode(array($staffInfo));
    } catch (PDOException $PDOException) {
        echo json_encode(array("PDO-error" => $staffInfo));
    }
}


/**
 * TODO: Refactor and Checking (ixgoh)
 */
//if (isset($_SESSION) && isset($_POST['updateProfile'])) {
//    try {
//        if ($_SESSION['acc_type'] == 'student') {
//            $updateStudentDb = $link->prepare(
//                "UPDATE student SET name=:name, course=:course, intake=:intake
//                WHERE email=:email"
//            );
//            $updateStudentDb->execute(array(
//                ":email" => $_POST['email'],
//                ":nameUser" => $_POST['name'],
//                ":course" => $_POST['course'],
//                ":intake" => $_POST['intake']
//            ));
//
//            $stmt = $link->prepare(
//                'SELECT email FROM student WHERE email = :email');
//            $stmt->execute(array(
//                ":email" => $email,
//            ));
//        }
//        else if ($_POST['acc_type'] === 'lecturer') {
//            $email = $_POST['email'];
//            $name = $_POST['name'];
//            $department = $_POST['department'];
//            $office = $_POST['office'];
//            $phone = $_POST['phone'];
//
//            $insertUserDb = $link->prepare(
//                "UPDATE staff SET email=:email, name=:nameUser, department=:department,
//                office_location=:office, phone=:phone WHERE email=:email"
//            );
//            $insertUserDb->execute(array(
//                ":email" => $email,
//                ":nameUser" => $name,
//                ":department" => $department,
//                ":office" => $office,
//                ":phone" => $phone
//            ));
//        }
//    } catch (PDOException $e) {
//        $errMsg = array('PDO-error' => $e->getMessage());
//    }
//}

?>
