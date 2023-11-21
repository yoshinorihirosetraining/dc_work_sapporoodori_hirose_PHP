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
    $sql = "SELECT user_name, password FROM ec_site_user WHERE user_name = :user_name AND password = :password";
    $stmt = execute_query($db, $sql, [':user_name' => $user_name, ':password' => $password]);
    
    if (!$stmt->fetch()) {
        return false;
    }
    if ($stmt->fetch()) {
        user_error("アカウントデータベースで複数行の一致がありました。");
    }
    return true;
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
