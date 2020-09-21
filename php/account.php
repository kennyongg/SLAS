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
 * Create Account Through Form (with Duplication Check)
 */
if (isset($_SESSION) && isset($_POST['registerEmail']) && $_SESSION['acc_type'] == "admin") {
    try {
        $checkEmail = $link->prepare(
            'SELECT * FROM user_credential WHERE id = :email');

        $checkEmail->execute(array(
            ':email' => $_POST['registerEmail']
        ));

        $result = $checkEmail->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            $insertCredentialDb = $link->prepare(
                "INSERT INTO user_credential VALUES (:id, :password, :acc_type, 1)"
            );
            $insertCredentialDb->execute(array(
                ":id" => $_POST['registerEmail'],
                ":password" => password_hash($_POST['password'], PASSWORD_BCRYPT),
                ":acc_type" => $_POST['acc_type']
            ));

            if ($_POST['acc_type'] === 'student') {
                $insertUserDb = $link->prepare(
                    "INSERT INTO student VALUES (:email, :nameUser, :course, :intake)"
                );
                $insertUserDb->execute(array(
                    ":email" => $_POST['registerEmail'],
                    ":nameUser" => $_POST['name'],
                    ":course" => $_POST['course'],
                    ":intake" => $_POST['intake']
                ));

                echo json_encode(array("success" => "Account updated."));
            }
            else if ($_POST['acc_type'] == 'lecturer') {
                $insertUserDb = $link->prepare(
                    "INSERT INTO staff VALUES (:email, :name, :department, :office, :phone)"
                );
                $insertUserDb->execute(array(
                    ":email" => $_POST['registerEmail'],
                    ":name" => $_POST['name'],
                    ":department" => $_POST['department'],
                    ":office" => $_POST['office'],
                    ":phone" => $_POST['phone']
                ));

                echo json_encode(array("success" => "Account updated."));
            }
        }
        else {
            echo json_encode(array("error" => "Email is in use."));
        }
    } catch (PDOException $PDOException) {
        echo json_encode(array('PDO-error' => $PDOException->getMessage()));
    }
}

//if (isset($_SESSION) && isset($_POST['registerEmail']) && $_SESSION['acc_type'] == "admin") {
//    try {
//        $checkEmail = $link->prepare(
//            'SELECT * FROM user_credential WHERE id = :email');
//
//        $checkEmail->execute(array(
//            ':email' => $_POST['registerEmail']
//        ));
//
//        $result = $checkEmail->fetch(PDO::FETCH_ASSOC);
//
//        if ($result) {
//            echo json_encode(array('error' => "Email is in use."));
//        } else {
//            echo json_encode(array('success' => "This email is available."));
//        }
//    } catch (PDOException $PDOException) {
//        echo json_encode(array('PDO-error' => $PDOException->getMessage()));
//    }
//}


/**
 * Edit Account Through Form (Details)
 */
if (isset($_SESSION) && isset($_POST['active']) && $_SESSION['acc_type'] == "admin") {
    try {
        $editCredentialDb = $link->prepare(
            "UPDATE user_credential 
                        SET id = :id, acc_type = :acc_type, active = :active 
                        WHERE id = :id"
        );
        $editCredentialDb->execute(array(
            ":id" => $_POST['email'],
            //            ":password" => $password,
            ":acc_type" => $_POST['acc_type'],
            ":active" => $_POST['active']
        ));

        if ($_POST['acc_type'] === 'student') {
            $editUserDb = $link->prepare(
                "UPDATE student SET email=:email, name=:nameUser, course=:course, 
                            intake=:intake WHERE email=:email"
            );
            $editUserDb->execute(array(
                ":email" => $_POST['email'],
                ":nameUser" => $_POST['name'],
                ":course" => $_POST['course'],
                ":intake" => $_POST['intake']
            ));

            echo json_encode(array("success" => "Account updated."));
        }
        else if ($_POST['acc_type'] === 'lecturer') {
            $editUserDb = $link->prepare(
                "UPDATE staff SET email=:email, name=:nameUser, department=:department, 
                office_location=:office, phone=:phone WHERE email=:email"
            );
            $editUserDb->execute(array(
                ":email" => $_POST['email'],
                ":nameUser" => $_POST['name'],
                ":department" => $_POST['department'],
                ":office" => $_POST['office'],
                ":phone" => $_POST['phone']
            ));

            echo json_encode(array("success" => "Account updated."));
        }
        else {
            echo json_encode(array("error" => "Profile not updated."));
        }
    } catch (PDOException $PDOException) {
        echo json_encode(array( 'PDO-error' => $PDOException->getMessage() ));
    }
}

?>
