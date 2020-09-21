<?php

session_start();

require __DIR__ . "/../vendor/autoload.php";

require_once 'dbconn.php';
require_once 'nanoGenerator.php';
require_once 'sendMail.php';

use League\Csv\Reader;

$csv_mimetypes = array(
    'text/csv',
    'text/plain',
    'application/csv',
    'text/comma-separated-values',
    'application/excel',
    'application/vnd.ms-excel',
    'application/vnd.msexcel',
    'text/anytext',
    'application/octet-stream',
    'application/txt',
);

/**
 * Import Staff
 */
if ($_SESSION['acc_type'] == "admin" && isset($_FILES['staffCSV'])) {
    if (in_array($_FILES['staffCSV']['type'], $csv_mimetypes)) {
        $fileInfo = pathinfo($_FILES['staffCSV']['name']);
        $tmpFileInfo = pathinfo($_FILES['staffCSV']['tmp_name']);

        $fileName = $fileInfo['filename'];
        $tmpFileName = $tmpFileInfo['filename'];

        $ext = $fileInfo['extension'];

        $target_dir = __DIR__ . "/uploads/csv/staff/";

        $newFile = $target_dir . $fileName . "-" . $tmpFileName . "." . $ext;

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        move_uploaded_file($_FILES['staffCSV']['tmp_name'], $newFile);

        try {
            $insertCredentialDb = $link->prepare(
                "INSERT INTO user_credential VALUES (:id, :pwd, 'lecturer', :active)");

            $insertStaffDb = $link->prepare(
                    "INSERT INTO staff VALUES (:email, :name, :dept, :loc, :phone)");

            $updateCredentialDb = $link->prepare(
                "UPDATE user_credential SET active=:active WHERE id=:id");

            $updateStaffDb = $link->prepare("UPDATE staff SET name=:name, department=:dept, 
                    office_location=:loc, phone=:phone WHERE email=:id"
            );

            $csv = Reader::createFromPath($newFile)
                ->setHeaderOffset(0);

            foreach ($csv as $record) {
                $checkExisting = $link->prepare("SELECT * FROM user_credential WHERE id=:id");
                $checkExisting->execute(array(
                    ":id" => $record['email']
                ));
                $exists = $checkExisting->fetch(PDO::FETCH_ASSOC);

                if (!$exists) {
                    $pass = generatePassword();
                    $hashedPassword = password_hash($pass, PASSWORD_BCRYPT);

                    $insertCredentialDb->execute(array(
                        ":id" => $record['email'],
                        ":pwd" => $hashedPassword,
                        ":active" => $record['active']
                    ));

                    $insertStaffDb->execute(array(
                        ":email" => $record['email'],
                        ":name" => $record['name'],
                        ":dept" => $record['department'],
                        ":loc" => $record['office_location'],
                        ":phone" => $record['phone']
                    ));

                    try {
                        sendCredentialEmail($record['name'], $record['email'], $pass);
                    } catch (Exception $exception) {
                        echo json_encode(array("mail-error" => $exception->getMessage()));
                    }
                } else {
                    $updateCredentialDb->execute(array(
                        ":active" => $record['active'],
                        ":id" => $record['email']
                    ));

                    $updateStaffDb->execute(array(
                        ":name" => $record['name'],
                        ":dept" => $record['department'],
                        ":loc" => $record['office_location'],
                        ":phone" => $record['phone'],
                        ":id" => $record['email']
                    ));
                }
            }

            echo json_encode(array("success" => "Uploaded!"));
            header("Location: ../app/adminPage.html");
            exit;
        } catch (PDOException $PDOException) {
            echo json_encode(array("PDO-error" => $PDOException->getMessage()));
        }
    }
    else {
        echo json_encode(array("error" => "Uploaded file type is not in .csv format"));
    }
}

/**
 * Import Student
 */
if ($_SESSION['acc_type'] == "admin" && isset($_FILES['studentCSV'])) {
    if (in_array($_FILES['studentCSV']['type'], $csv_mimetypes)) {
        $fileInfo = pathinfo($_FILES['studentCSV']['name']);
        $tmpFileInfo = pathinfo($_FILES['studentCSV']['tmp_name']);

        $fileName = $fileInfo['filename'];
        $tmpFileName = $tmpFileInfo['filename'];

        $ext = $fileInfo['extension'];

        $target_dir = __DIR__ . "/uploads/csv/student/";

        $newFile = $target_dir . $fileName . "-" . $tmpFileName . "." . $ext;

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        move_uploaded_file($_FILES['studentCSV']['tmp_name'], $newFile);

        try {
            $insertCredentialDb = $link->prepare(
                "INSERT INTO user_credential VALUES (:id, :pwd, 'student', :active)");

            $insertStudentDb = $link->prepare(
                "INSERT INTO student VALUES (:email, :name, :course, :intake)");

            $updateCredentialDb = $link->prepare(
                "UPDATE user_credential SET active=:active WHERE id=:id");

            $updateStudentDb = $link->prepare("UPDATE student SET name=:name, 
                   course=:course, intake=:intake WHERE email=:id");

            $csv = Reader::createFromPath($newFile)
                ->setHeaderOffset(0);

            foreach ($csv as $record) {
                $checkExisting = $link->prepare("SELECT * FROM user_credential WHERE id=:id");
                $checkExisting->bindValue(':id', $record['email'], PDO::PARAM_STR);
                $checkExisting->execute();
                $exists = $checkExisting->fetch(PDO::FETCH_ASSOC);

                if (!$exists) {
                    $pass = generatePassword();
                    $hashedPassword = password_hash($pass, PASSWORD_BCRYPT);

                    $insertCredentialDb->execute(array(
                        ":id" => $record['email'],
                        ":pwd" => $hashedPassword,
                        ":active" => $record['active']
                    ));

                    $insertStudentDb->execute(array(
                        ":email" => $record['email'],
                        ":name" => $record['name'],
                        ":course" => $record['course'],
                        ":intake" => $record['intake']
                    ));

                    try {
                        sendCredentialEmail($record['name'], $record['email'], $pass);
                    } catch (Exception $exception) {
                        echo json_encode(array("mail-error" => $exception->getMessage()));
                    }
                } else {
                    $updateCredentialDb->execute(array(
                        ":active" => $record['active'],
                        ":id" => $record['email']
                    ));

                    $updateStudentDb->execute(array(
                        ":name" => $record['name'],
                        ":course" => $record['course'],
                        ":intake" => $record['intake'],
                        ":id" => $record['email']
                    ));
                }
            }

            echo json_encode(array("success" => "Uploaded!"));
            header("Location: ../app/adminPage.html");
            exit;
        } catch (PDOException $PDOException) {
            echo json_encode(array("PDO-error" => $PDOException->getMessage()));
        }
    }
    else {
        echo json_encode(array("error" => "Uploaded file type is not in .csv format"));
    }
}

/**
 * Import Subject
 */
if ($_SESSION['acc_type'] == "admin" && isset($_FILES['subjectCSV'])) {
    if (in_array($_FILES['subjectCSV']['type'], $csv_mimetypes)) {
        $fileInfo = pathinfo($_FILES['subjectCSV']['name']);
        $tmpFileInfo = pathinfo($_FILES['subjectCSV']['tmp_name']);

        $fileName = $fileInfo['filename'];
        $tmpFileName = $tmpFileInfo['filename'];

        $ext = $fileInfo['extension'];

        $target_dir = __DIR__ . "/uploads/csv/subject/";

        $newFile = $target_dir . $fileName . "-" . $tmpFileName . "." . $ext;

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        move_uploaded_file($_FILES['subjectCSV']['tmp_name'], $newFile);

        try {
            $insertSubjectDb = $link
                ->prepare("INSERT INTO subject VALUES (:id, :name, :lecturer)");

            $updateSubjectDb = $link->prepare("UPDATE subject SET name=:name, 
                   lecturer=:lecturer WHERE id=:id");

            $csv = Reader::createFromPath($newFile)
                ->setHeaderOffset(0);

            foreach ($csv as $record) {
                $checkExisting = $link->prepare("SELECT * FROM subject WHERE id=:id");
                $checkExisting->execute(array(":id" => $record['id']));
                $exists = $checkExisting->fetch(PDO::FETCH_ASSOC);

                if (!$exists) {
                    $insertSubjectDb->execute(array(
                        ":id" => $record['id'],
                        ":name" => $record['name'],
                        ":lecturer" => $record['lecturer'],
                    ));
                } else {
                    $updateSubjectDb->execute(array(
                        ":id" => $record['id'],
                        ":name" => $record['name'],
                        ":lecturer" => $record['lecturer'],
                    ));
                }
            }

            echo json_encode(array("success" => "Uploaded!"));
            header("Location: ../app/adminPage.html");
            exit;
        } catch (PDOException $PDOException) {
            echo json_encode(array("PDO-error" => $PDOException->getMessage()));
        }
    }
    else {
        echo json_encode(array("error" => "Uploaded file type is not in .csv format"));
    }
}

/**
 * Import Student Enrollment
 */
if ($_SESSION['acc_type'] == "admin" && isset($_FILES['enrollmentCSV'])) {
    if (in_array($_FILES['enrollmentCSV']['type'], $csv_mimetypes)) {
        $fileInfo = pathinfo($_FILES['enrollmentCSV']['name']);
        $tmpFileInfo = pathinfo($_FILES['enrollmentCSV']['tmp_name']);

        $fileName = $fileInfo['filename'];
        $tmpFileName = $tmpFileInfo['filename'];

        $ext = $fileInfo['extension'];

        $target_dir = __DIR__ . "/uploads/csv/enrollment/";

        $newFile = $target_dir . $fileName . "-" . $tmpFileName . "." . $ext;

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        move_uploaded_file($_FILES['enrollmentCSV']['tmp_name'], $newFile);

        try {
            $insertEnrollmentDb = $link
                ->prepare("INSERT INTO enrollment VALUES (:student, :subject, :active)");

            $updateEnrollmentDb = $link->prepare("UPDATE enrollment SET student=:student, 
                      subject=:subject, active=:active 
                      WHERE student=:student AND subject=:subject");

            $csv = Reader::createFromPath($newFile)
                ->setHeaderOffset(0);

            foreach ($csv as $record) {
                $checkExisting = $link->prepare("SELECT * FROM enrollment 
                    WHERE student=:student AND subject=:subject");
                $checkExisting->execute(array(
                    ":student" => $record['studentEmail'],
                    ":subject" => $record['subjectCode']
                ));
                $exists = $checkExisting->fetch(PDO::FETCH_ASSOC);

                if (!$exists) {
                    $insertEnrollmentDb->execute(array(
                        ":student" => $record['studentEmail'],
                        ":subject" => $record['subjectCode'],
                        ":active" => $record['active'],
                    ));
                } else {
                    $updateEnrollmentDb->execute(array(
                        ":student" => $record['studentEmail'],
                        ":subject" => $record['subjectCode'],
                        ":active" => $record['active'],
                    ));
                }
            }

            echo json_encode(array("success" => "Uploaded!"));
            header("Location: ../app/adminPage.html");
            exit;
        } catch (PDOException $PDOException) {
            echo json_encode(array("PDO-error" => $PDOException->getMessage()));
        }
    }
    else {
        echo json_encode(array("error" => "Uploaded file type is not in .csv format"));
    }
}
?>
