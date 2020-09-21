<?php

session_start();

require_once 'dbconn.php';

$errMsg = array();
$msg = array();

// Get data from FORM
$username = $_POST['loginEmail'];
$password = $_POST['loginPass'];

if (isset($username) && isset($password)) {
    try {
        $stmt = $link->prepare(
                'SELECT * FROM user_credential WHERE id = :username');

        $stmt->execute(array(
            ':username' => $username
        ));

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data == '') {
            $errMsg = array('error' => 'Invalid credentials, User does not exist');
        }
        else {
            // Login Pass 
            if (password_verify($password, $data['password']) && $data['active'] == true) {
                $_SESSION['id'] = $data['id'];
                $_SESSION['acc_type'] = $data['acc_type'];

                if ($data['acc_type'] == "student") {
                    $getUserName = $link->prepare(
                        "SELECT name FROM student WHERE email=:email");
                }
                elseif ($data['acc_type'] == "lecturer" || $data['acc_type'] == "admin") {
                    $getUserName = $link->prepare(
                        "SELECT name FROM staff WHERE email=:email");

                }

                $getUserName->execute(array(":email" => $data['id']));
                $userName = $getUserName->fetch(PDO::FETCH_ASSOC);

                $msg = array(
                    'success' => 'Login Successful',
                    'id' => $data['id'],
                    'name' => $userName,
                    'acc_type' => $data['acc_type']
                );
            }
            elseif ($password == $data['password'] && $data['active'] == false){
                $errMsg = array('error' => 'Account is deactivated');
            }
            else{
                $errMsg = array('error' => 'Invalid credentials, User does not exist');
            }
        }
    } catch (PDOException $e) {
        $errMsg = array('PDO-error' => $e->getMessage());
    }

}
else{
    if (!isset($username) || !isset($password)) {
        $errMsg = array('error' => 'Username or Password is empty');
    }
}


if (! $errMsg){
    echo $json = json_encode($msg);
} else {
    echo $json = json_encode($errMsg);
}

?>

