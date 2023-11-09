<?php

require_once '../../include/model/ec_site_model.php';

session_start();

if ($_SESSION['state'] == 'admin') {
    header('Location: admin.php');
} else if ($_SESSION['state'] != 'login') {
    header('Location: index.php');
}

$msg = "";
$errmsg = "";

// 「削除する」ボタンを押下した時の処理
$match = match_keyword_from_array('/^cart_delete_btn([0-9]+)$/', $_POST);
if ($match) {
    $cart_id = $match[1][0];
    delete_cart_via_db($cart_id);
    $msg = "カートの項目を削除しました。";
}

// 「変更する」ボタンを押下した時の処理
$match = match_keyword_from_array('/^cart_change_btn([0-9]+)$/', $_POST);
if ($match) {
    $cart_id = $match[1][0];
    $new = $_POST['qty' . $cart_id];
    if (!preg_match('/^[0-9]{1,10}$/', $new)) {
        $errmsg = "カートの数量には半角数字を入力してください。";
    } else if ($new == 0) {
        $errmsg = "カートの数量に０を指定する代わりに「削除する」ボタンを押してください。";
    } else {
        $i = get_cart_information_from_cart_id_via_db($cart_id);
        if ($new > $i['stock_qty']) {
            $errmsg = $i['product_name'].'の数量を'.$new.'個に変更できませんでした(在庫: '.$i['stock_qty'].'個)。';
        } else {
            update_cart_via_db($cart_id, $new);
            $msg = "カートの数量を変更しました。";
        }
    }

}

$user_id = $_SESSION['user_id'];

// 「購入する」ボタンを押下した時の処理
if (isset($_POST['cart_checkout_btn'])) {
    if (checkout_via_db($user_id, $errmsg)) {
        header('Location: checkout.php');
    }
}


$cart_info = get_cart_information_via_db($user_id);
$cart_total = get_cart_total_via_db($user_id);

include_once '../../include/view/cart_view.php';
