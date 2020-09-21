<?php

session_start();

require_once 'dbconn.php';

if(!isset($_SESSION['id'])){
    header("Location: ../index.html");
    exit;
}

/**
 * Search Lecturers
 */
if (isset($_SESSION) && isset($_GET['searchLecturer'])) {
    $query = $_GET['searchLecturer'];

    try {
        $searchLecturer = $link->prepare("SELECT email, name, acc_type FROM staff
        INNER JOIN user_credential ON staff.email = user_credential.id
        WHERE (name LIKE :query OR email LIKE :query) AND acc_type = 'lecturer' LIMIT 5");
        $searchLecturer->execute(array(":query" => "%$query%"));

        $lecturerData = $searchLecturer->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $PDOException) {
        echo json_encode(array("PDO-error" => $PDOException->getMessage()));
    }

    echo json_encode($lecturerData);
}


/**
 * Search User and Get Profile
 */
if (isset($_SESSION) && isset($_POST['getProfile'])) {
    $user = $_POST['getProfile'];

    try {
        $searchUser = $link->prepare(
            "SELECT acc_type FROM user_credential WHERE id=:id");
        $searchUser->execute(array(":id" => $user));

        $accountType = $searchUser->fetchColumn();

        if ($accountType == "lecturer") {
            $searchUser = $link->prepare("SELECT email, name, department, 
                office_location, phone, active FROM staff, user_credential 
                WHERE staff.email = user_credential.id AND email=:email");
        }
        elseif ($accountType == "student") {
            $searchUser = $link->prepare("SELECT email, name, course, 
                intake, active FROM student, user_credential 
                WHERE student.email = user_credential.id AND email=:email");
        }

        $searchUser->execute(array(":email" => $user));
        $userData = $searchUser->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($userData);
    } catch (PDOException $PDOException) {
        echo json_encode(array('PDO-error' => $PDOException->getMessage()));
    }


    echo json_encode($userData);
}

?>
