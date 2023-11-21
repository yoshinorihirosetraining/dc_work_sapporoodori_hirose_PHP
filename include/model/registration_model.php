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
    $stmt = execute_query($db, $sql, [':user_name' => $user_name, ':password' => $password]);
    if ($stmt->rowCount() == 1) {
        $db->commit();
    } else {
        $db->rollBack();
        user_error("ユーザーの追加に失敗しました。");
    }
}

