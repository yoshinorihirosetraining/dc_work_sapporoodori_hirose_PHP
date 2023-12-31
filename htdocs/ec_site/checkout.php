<?php

require_once '../../include/model/checkout_model.php';

session_start();

check_session_and_redirect_index();

$user_id = $_SESSION['user_id'];
$cart_info = get_cart_information_via_db($user_id);
$cart_total = get_cart_total_via_db($user_id);

include_once '../../include/view/checkout_view.php';

delete_cart_all_via_db($user_id);
