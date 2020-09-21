<?php

session_start();

require_once 'dbconn.php';

if(!isset($_SESSION['id'])){
    header("Location: ../index.html");
    exit;
}

/**
 * Search User and Retrieve Profile to Display in Edit Form (Part 1)
 */
if (isset($_POST['userEmail'])) {
    try {
        $selectedUserQuery = $link->prepare(
                "SELECT id, acc_type, active FROM user_credential WHERE id = :id");

        $selectedUserQuery->execute(array(
            ":id" => $_POST['userEmail'],
        ));

        $data = $selectedUserQuery->fetch(PDO::FETCH_ASSOC);

        if (! $data) {
            echo json_encode(array('error' => 'No results found.'));
        } else {
            echo json_encode($data);
        }
    } catch (PDOException $e) {
        echo json_encode(array('PDO-error' => $e->getMessage()));
    }

}

/**
 * Search All Users using Keyword
 */
if (isset($_SESSION) && isset($_POST['searchUser'])) {
    $user = $_POST['searchUser'];

    try {
        $searchUser = $link->prepare(
            "SELECT id FROM user_credential WHERE id LIKE :email LIMIT 5");
        $searchUser->execute(array(":email" => "%$user%"));

        $userList = $searchUser->fetchAll(PDO::FETCH_NUM);

        if (!$userList) {
            echo json_encode(array('error' => 'No results found'));
        } else {
            echo json_encode($userList);
        }
    } catch (PDOException $e) {
        echo json_encode(array('PDO-error' => $e->getMessage()));
    }
}


/**
 * Search User and Retrieve Profile to Display in Edit Form (Part 2)
 */
if (isset($_GET['accType'])) {
    $accType = $_GET['accType'];

    if ($accType == "student") {
        $userEmail = $_GET['studentEmail'];

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
    } elseif ($accType == "lecturer" || $accType == "admin") {

        $userEmail = $_GET['lecturerEmail'];

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

?>

