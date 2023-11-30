<?php

require_once '../../include/model/main_model.php';

session_start();

check_session_and_redirect_index();

$msg = display_welcome_message();

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $match = match_keyword_from_array('/^login_add_to_cart_btn([0-9]+)$/', $_POST);
    if ($match) {
        $product_id = $match[1][0];
        $msg = add_product_to_cart($_SESSION['user_id'], $product_id);
    }
}

include_once '../../include/view/main_view.php';
