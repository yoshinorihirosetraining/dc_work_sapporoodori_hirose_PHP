<?php

require_once '../../include/model/index_model.php';

session_start();

if ($_SESSION['state'] == 'login') {
    header('Location: login.php');
    exit();
} else if ($_SESSION['state'] == 'admin') {
    header('Location: admin.php');
    exit();
}


$errmsg = "";

// 「ログイン」ボタンが押された時の処理
if ($_SERVER["REQUEST_METHOD"] != "POST" 
|| !isset($_POST['index_submit_btn'])) {
    // 押されてない→何もしない
} else if ($_POST['user_name'] == '') {
    $errmsg = "ユーザー名を入力してください。";
} else if ($_POST['password'] == '') {
    $errmsg = "パスワードを入力してください。";
} else if (!preg_match('/^[0-9a-zA-Z_]{5,32}$/', $_POST['user_name'])) {
    $errmsg = "ユーザー名は5文字以上32文字以下の半角英数字で入力してください。";
} else if (!preg_match('/^[0-9a-zA-Z_]{8,32}$/', $_POST['password'])) {
    $errmsg = "パスワードは8文字以上32文字以内の半角英数字で入力してください。";
} else {
    // データベースにアクセスして照合
    if (is_normal_user_via_db($_POST['user_name'], $_POST['password'])) {
        $_SESSION['state'] = 'login';
        $_SESSION['user_name'] = $_POST['user_name'];
        $_SESSION['user_id'] = get_user_id_via_db($_POST['user_name']);
        $_SESSION['welcome'] = 'true';
        header('Location: login.php');
        exit();
    } else if ($_POST['user_name'] == 'ec_admin' && $_POST['password'] == 'ec_admin') {
        $_SESSION['state'] = 'admin';
        header('Location: admin.php');
        exit();
    } else {
        $errmsg = "ユーザー名とパスワードが一致しません。";
    }
}

// ログイン画面内で「ユーザー名」入力フィールドの値を保持する処理
if ($_SERVER["REQUEST_METHOD"] == "POST"
&& isset($_POST['index_submit_btn'])) {
    $user_name = htmlspecialchars($_POST['user_name']);
} else {
    $user_name = "";
}

include_once '../../include/view/index_view.php';
