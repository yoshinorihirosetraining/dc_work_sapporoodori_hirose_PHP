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
    try {
        // データベースへ接続
        $db = new PDO(DB_DSN, DB_LOGIN_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
    // SELECT文の実行
    $sql = "SELECT user_name, password FROM ec_site_user WHERE user_name = :user_name AND password = :password";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':user_name', $user_name);
    $stmt->bindValue(':password', $password);
    
    if (!$stmt->execute()) {
        user_error("データベースの取得に失敗しました");
    }
    
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
    try {
        // データベースへ接続
        $db = new PDO(DB_DSN, DB_LOGIN_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
    // SELECT文の実行
    $sql = "SELECT user_id FROM ec_site_user WHERE user_name = :user_name";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':user_name', $user_name);
    
    if (!$stmt->execute()) {
        user_error("データベースの取得に失敗しました");
    }
    
    $row0 = $stmt->fetch();
    if (!$row0) {
        user_error("アカウントデータベースで０行の一致がありました。");
    }
    if ($stmt->fetch()) {
        user_error("アカウントデータベースで複数行の一致がありました。");
    }
    return $row0['user_id'];
}
