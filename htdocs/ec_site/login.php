<?php

require_once '../../include/model/ec_site_model.php';

session_start();

if ($_SESSION['state'] == 'admin') {
    header('Location: admin.php');
} else if ($_SESSION['state'] != 'login') {
    header('Location: index.php');
}

$msg = "";

$user_id = $_SESSION['user_id'];

// 「カートに入れる」ボタンを押下した時の処理
$match = match_keyword_from_array('/^login_add_to_cart_btn([0-9]+)$/', $_POST);
if ($match) {
    $product_id = $match[1][0];
    $product_qty = get_product_qty_via_db($user_id, $product_id);
    if ($product_qty == 0) {
        insert_product_qty_via_db($user_id, $product_id, 1);
    } else {
        update_product_qty_via_db($user_id, $product_id, $product_qty + 1);
    }
    $msg = "カートに商品を１個追加しました。";
}

include_once '../../include/view/login_view.php';
