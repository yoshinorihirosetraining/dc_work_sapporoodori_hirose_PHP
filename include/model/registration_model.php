<?php

require_once('../../include/config/const.php');
require_once('../../include/utility/common_func.php');

/**
* 与えられた文字列が一般ユーザー名であるかどうかを判別
* 
* @param string $user_name 判別したい文字列
* @return bool trueならば一般ユーザー名、falseならばそうでない
*/
function is_normal_user_name_via_db($user_name) {
    $db = get_database_connection();
    // SELECT文の実行
    $sql = "SELECT user_name FROM ec_site_user WHERE user_name = :user_name";
    $stmt = execute_query($db, $sql, [':user_name' => $user_name]);

    if (!$stmt->fetch()) {
        return false;
    }
    if ($stmt->fetch()) {
        user_error("アカウントデータベースで複数行の一致がありました。");
    }
    return true;
}

/**
* 一般ユーザーを追加
* 
* @param string $user_name ユーザー名
* @param string $password パスワード
*/
function add_normal_user_via_db($user_name, $password) {
    $db = get_database_connection();
    // INSERT文の実行
    $db->beginTransaction();
    $sql = "INSERT INTO ec_site_user (user_name, password) VALUES (:user_name, :password)";
    $stmt = execute_query($db, $sql, [':user_name' => $user_name, ':password' => password_hash($password, PASSWORD_DEFAULT)]);
    if ($stmt->rowCount() == 1) {
        $db->commit();
    } else {
        $db->rollBack();
        user_error("ユーザーの追加に失敗しました。");
    }
}

/**
* ユーザー入力の検証を行う
* 
* @param string $user_name ユーザー名
* @param string $password パスワード
*/
function validate_user_input($user_name, $password) {
    if ($user_name == '') {
        return "ユーザー名を入力してください。";
    } else if ($password == '') {
        return "パスワードを入力してください。";
    } else if (!preg_match('/^[0-9a-zA-Z]{5,32}$/', $user_name)) {
        return "ユーザー名は5文字以上32文字以下の半角英数字で入力してください。";
    } else if (!preg_match('/^[0-9a-zA-Z]{8,32}$/', $password)) {
        return "パスワードは8文字以上32文字以下の半角英数字で入力してください。";
    }
    return "";
}

/**
* ユーザー登録処理を行う
* 
* @param string $user_name ユーザー名
* @param string $password パスワード
*/
function register_user($user_name, $password) {
    if (is_normal_user_name_via_db($user_name)) {
        return "このユーザー名はすでに使われております。";
    } else {
        add_normal_user_via_db($user_name, $password);
        return "ユーザーを登録しました。";
    }
}
