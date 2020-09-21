<?php

session_start();

require_once 'dbconn.php';

if(!isset($_SESSION['id'])){
    header("Location: ../index.html");
    exit;
}

/**
 * Get Student Enrollment
 */
if (isset($_SESSION) && isset($_GET['enrollment'])) {
    try {
        $fetchEnrollment = $link->prepare(
            "SELECT e.student, s.name AS student_name, e.subject, su.name AS subject_name, 
        su.lecturer, st.name AS lecturer_name, e.active FROM enrollment e
        INNER JOIN student s ON e.student = s.email INNER JOIN subject su ON e.subject = su.id
        INNER JOIN staff st ON su.lecturer = st.email WHERE e.student=:email AND e.active=1");

        $fetchEnrollment->execute(array(":email" => $_SESSION['id']));
        $enrollmentData = $fetchEnrollment->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($enrollmentData);
    } catch (PDOException $PDOException) {
        echo json_encode(array("PDO-error" => $PDOException->getMessage()));
    }
}

?>
