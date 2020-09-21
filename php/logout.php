<?php
if (isset($_GET['logout']) && $_GET['logout'] = "true") {
    try {
        session_start();
        session_unset();
        session_destroy();
        echo json_encode(array("success" => "Logout successful"));
    } catch (Exception $exception){
        echo json_encode(array("error" => "Unable to Logout properly"));
    }
}
?>
