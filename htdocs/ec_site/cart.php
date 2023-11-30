<?php

require_once '../../include/model/cart_model.php';

session_start();

check_session_and_redirect_index();

$msg = "";
$errmsg = "";

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cart_checkout_btn'])) {
        $errmsg = process_checkout($_SESSION['user_id']);
    } else {
        $match = match_keyword_from_array('/^cart_(delete|change)_btn([0-9]+)$/', $_POST);
        if ($match) {
            $action = $match[1][0];
            $cart_id = $match[2][0];
            if ($action == 'delete') {
                $msg = delete_cart_item($cart_id);
            } elseif ($action == 'change') {
                $new_quantity = $_POST['qty' . $cart_id];
                $errmsg = change_cart_item_quantity($cart_id, $new_quantity);
            }
        }
    }
}

$cart_info = get_cart_information_via_db($_SESSION['user_id']);
$cart_total = get_cart_total_via_db($_SESSION['user_id']);

include_once '../../include/view/cart_view.php';
