<?php

require_once '../../include/model/registration_model.php';

session_start();

if ($_SESSION['state'] == 'login') {
    header('Location: login.php');
    exit();
} else if ($_SESSION['state'] == 'admin') {
    header('Location: admin.php');
    exit();
}

$msg = "";
$errmsg = "";

// 「登録」ボタンが押された時の処理
if ($_SERVER["REQUEST_METHOD"] != "POST" 
|| !isset($_POST['registration_submit_btn'])) {
    // 押されてない→何もしない
} else if ($_POST['user_name'] == '') {
    $errmsg = "ユーザー名を入力してください。";
} else if ($_POST['password'] == '') {
    $errmsg = "パスワードを入力してください。";
} else if (!preg_match('/^[0-9a-zA-Z]{5,32}$/', $_POST['user_name'])) {
    $errmsg = "ユーザー名は5文字以上32文字以下の半角英数字で入力してください。";
} else if (!preg_match('/^[0-9a-zA-Z]{8,32}$/', $_POST['password'])) {
    $errmsg = "パスワードは8文字以上32文字以下の半角英数字で入力してください。";
} else if (is_normal_user_name_via_db($_POST['user_name'])) {
    $errmsg = "このユーザー名はすでに使われております。";
} else {
    add_normal_user_via_db($_POST['user_name'], $_POST['password']);
    $msg = "ユーザーを登録しました。";
}

include_once '../../include/view/registration_view.php';
