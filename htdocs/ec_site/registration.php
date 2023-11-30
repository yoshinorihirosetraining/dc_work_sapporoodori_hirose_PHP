<?php

require_once '../../include/model/registration_model.php';
session_start();

check_session_and_redirect_main();

$msg = "";
$errmsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registration_submit_btn'])) {
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    $errmsg = validate_user_input($user_name, $password);
    if (empty($errmsg)) {
        $msg = register_user($user_name, $password);
    }
}

include_once '../../include/view/registration_view.php';
