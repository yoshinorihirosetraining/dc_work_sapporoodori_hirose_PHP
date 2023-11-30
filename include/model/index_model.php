<?php

require_once('../../include/config/const.php');
require_once('../../include/utility/common_func.php');

/**
* 一般ユーザーであるかどうかを判別
* 
* @param string $user_name ユーザー名
* @param string $password パスワード
* @return bool trueならば一般ユーザー、falseならばそうでない
*/
function is_normal_user_via_db($user_name, $password) {
    $db = get_database_connection();
    // SELECT文の実行
    $sql = "SELECT password FROM ec_site_user WHERE user_name = :user_name";
    $stmt = execute_query($db, $sql, [':user_name' => $user_name]);
    
    $row0 = $stmt->fetch();
    if (!$row0) {
        return false;
    }
    if ($stmt->fetch()) {
        user_error("アカウントデータベースで複数行の一致がありました。");
    }
    return password_verify($password, $row0['password']);
}

/**
* ユーザー名からユーザーIDを取得
* 
* @param string $user_name ユーザー名
* @return string ユーザーID
*/
function get_user_id_via_db($user_name) {
    $db = get_database_connection();
    // SELECT文の実行
    $sql = "SELECT user_id FROM ec_site_user WHERE user_name = :user_name";
    $stmt = execute_query($db, $sql, [':user_name' => $user_name]);
    
    $row0 = $stmt->fetch();
    if (!$row0) {
        user_error("アカウントデータベースで０行の一致がありました。");
    }
    if ($stmt->fetch()) {
        user_error("アカウントデータベースで複数行の一致がありました。");
    }
    return $row0['user_id'];
}

/**
* ログイン処理の関数
* 
*/
function process_login() {
    global $errmsg; // エラーメッセージをグローバルで使用

    if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST['index_submit_btn'])) {
        // POSTでない場合は何もしない
        return;
    }

    if ($_POST['user_name'] == '') {
        $errmsg = "ユーザー名を入力してください。";
    } else if ($_POST['password'] == '') {
        $errmsg = "パスワードを入力してください。";
    } else if (!preg_match('/^[0-9a-zA-Z_]{5,32}$/', $_POST['user_name'])) {
        $errmsg = "ユーザー名は5文字以上32文字以下の半角英数字で入力してください。";
    } else if (!preg_match('/^[0-9a-zA-Z_]{8,32}$/', $_POST['password'])) {
        $errmsg = "パスワードは8文字以上32文字以内の半角英数字で入力してください。";
    } else {
        // データベースを用いた認証処理
        authenticate_user($_POST['user_name'], $_POST['password']);
    }
}

/**
* ユーザーを認証する
* 
* @param string ユーザー名
* @param string パスワード
*/
function authenticate_user($user_name, $password) {
    // データベース認証とセッションセットアップのロジック
    if (is_normal_user_via_db($user_name, $password)) {
        // セッション設定とリダイレクト
        $_SESSION['state'] = 'login';
        $_SESSION['user_name'] = $user_name;
        $_SESSION['user_id'] = get_user_id_via_db($user_name);
        $_SESSION['welcome'] = 'true';
        header('Location: login.php');
        exit();
    } else if ($user_name == 'ec_admin' && $password == 'ec_admin') {
        $_SESSION['state'] = 'admin';
        header('Location: admin.php');
        exit();
    } else {
        global $errmsg;
        $errmsg = "ユーザー名とパスワードが一致しません。";
    }
}
