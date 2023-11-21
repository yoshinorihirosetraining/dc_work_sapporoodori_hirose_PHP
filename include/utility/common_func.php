<?php

require_once('../../include/config/const.php');

/**
* 配列の各キーにpreg_matchを実施し、最初にマッチした検索結果を返す
* 
* @param string $pattern 検索するパターン
* @param array $array 入力する配列
* @return array 検索結果もしくはnull
*/
function match_keyword_from_array($pattern, $array) {
    foreach ($array as $key => $val) {
        $result = preg_match($pattern, $key, $match, PREG_OFFSET_CAPTURE);
        if ($result) {
            return $match;
        }
    }
    return null;
}

/**
* データベースへ接続
* 
* @return PDO PDOオブジェクト
*/
function get_database_connection() {
    try {
        $db = new PDO(DB_DSN, DB_LOGIN_USER, DB_PASSWORD);
        // エラーモードを例外モードに設定
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
}

/**
* クエリを実行
* 
* @param PDO PDOオブジェクト
* @param string SQL文
* @param array プリペアド・ステートメントのバインドパラメータを値とする配列
* @return PDOStatement PDOStatementオブジェクト
*/
function execute_query($db, $sql, $params = []) {
    $stmt = $db->prepare($sql);
    if (!$stmt->execute($params)) {
        user_error("SQL文の実行に失敗しました。SQL: " . $sql);
    }
    return $stmt;
}