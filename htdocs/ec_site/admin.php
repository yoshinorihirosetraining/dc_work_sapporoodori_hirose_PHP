<?php

require_once '../../include/model/ec_site_model.php';

session_start();

if ($_SESSION['state'] == 'login') {
    header('Location: login.php');
    exit();
} else if ($_SESSION['state'] != 'admin') {
    header('Location: index.php');
    exit();
}

$add_msg = ""; // 商品登録成功のメッセージ
$add_errmsg = ""; // 商品登録のエラー内容

$update_msg = ""; // 商品一覧更新成功のメッセージ
$update_err_msg = ""; // 商品一覧更新失敗のメッセージ

$add_product_name = "";
$add_price = "";
$add_quantity = "";

// 「商品を登録」ボタンが押された時の処理
if ($_SERVER["REQUEST_METHOD"] != "POST" 
|| !isset($_POST['admin_add_product_btn'])) {
    // 押されてない→何もしない
} else if ($_POST['product_name'] == "") {
    $add_errmsg = "商品名が入力されていません。";
} else if ($_POST['price'] == "") {
    $add_errmsg = "価格が入力されていません。";
} else if ($_POST['quantity'] == "") {
    $add_errmsg = "個数が入力されていません。";
} else if ($_FILES['image']['name'] == "") {
    $add_errmsg = "画像ファイルが指定されていません。";
} else if (!preg_match('/^[0-9]{1,10}$/', $_POST['price'])) {
    $add_errmsg = "価格には半角数字を入力してください。";
} else if (!preg_match('/^[0-9]{1,10}$/', $_POST['quantity'])) {
    $add_errmsg = "個数には半角数字を入力してください。";
} else if (!preg_match('/\.(png|PNG|jpeg|JPEG|jpg|JPG)$/', $_FILES['image']['name'])) {
    $add_errmsg = "画像ファイルにはJPEGまたはPNGフォーマットを指定してください。";
} else {
    preg_match('/\.([a-zA-Z]+)$/', $_FILES['image']['name'], $ext_match, PREG_OFFSET_CAPTURE);
    $ext = $ext_match[1][0];
    $unique_filename = get_unique_filename_via_db();
    $save = 'image/' . $unique_filename . "." . $ext;
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $save)) {
        $add_errmsg = "画像ファイルのアップロードに失敗しました。";
    }
    add_product_via_db(
        $_POST['product_name'],
        $_POST['price'],
        $_POST['quantity'],
        $save,
        ($_POST['status'] == 'public' ? 1 : 0));
    $add_msg = "商品の登録に成功しました。";
}

// 商品登録でエラーが発生した時にテキストフィールドを補完する処理
if ($_SERVER["REQUEST_METHOD"] == "POST" 
&& isset($_POST['admin_add_product_btn'])
&& $add_errmsg != "") {
    $add_product_name = $_POST['product_name'];
    $add_price = $_POST['price'];
    $add_quantity = $_POST['quantity'];
}

// 「変更する」ボタン押下時の処理
$match = match_keyword_from_array('/^admin_stock_update_btn([0-9]+)$/', $_POST);
if ($match) {
    $product_id = $match[1][0];
    $stock_qty = $_POST['stock' . $product_id];
    if (!preg_match('/^[0-9]+$/', $stock_qty)) {
        $update_errmsg = "在庫数に半角数字を入力してください。";
    } else {
        update_stock_qty_via_db($product_id, $stock_qty);
        $update_msg = "在庫数を変更しました。";
    }
}

// 「非表示にする」／「表示する」ボタン押下時の処理
$match = match_keyword_from_array('/^admin_status_btn([0-9]+)$/', $_POST);
if ($match) {
    $product_id = $match[1][0];
    $public_flg =
        ($_POST['admin_status_btn' . $product_id] == '表示する')
        ? 1
        : 0;
    update_public_flg_via_db($product_id, $public_flg);
    $update_msg = "ステータスを変更しました。";
}

// 「削除する」ボタン押下時の処理
$match = match_keyword_from_array('/^admin_delete_btn([0-9]+)$/', $_POST);
if ($match) {
    $product_id = $match[1][0];
    delete_product_via_db($product_id);
    $update_msg = "商品を削除しました。";
}


include_once '../../include/view/admin_view.php';
