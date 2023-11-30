<?php

require_once('../../include/config/const.php');
require_once('../../include/utility/common_func.php');

/**
* 公開フラグが1である商品データベースの行を取得
* 
* @return array 商品データベースの行
*/
function get_public_product_list_via_db() {
    $db = get_database_connection();
    // SELECT文の実行
    $sql = "SELECT * FROM ec_site_product WHERE public_flg=1";
    $stmt = execute_query($db, $sql);
    return $stmt->fetchAll();
}

/**
* 在庫数量を取得
* 
* @param string $product_id 商品ID
* @return string 在庫数量
*/
function get_stock_qty_via_db($product_id) {
    $db = get_database_connection();
    // SELECT文の実行
    $sql = "SELECT stock_qty FROM ec_site_product WHERE product_id = :product_id";
    $stmt = execute_query($db, $sql, [':product_id' => $product_id]);
    
    $row0 = $stmt->fetch();
    if (!$row0) {
        user_error("商品データベースで０行の一致がありました。");
    }
    if ($stmt->fetch()) {
        user_error("商品データベースで複数行の一致がありました。");
    }
    return $row0['stock_qty'];
}

/**
* カート数量を取得
* 
* @param string $user_id ユーザーID
* @param string $product_id 商品ID
* @return string カート数量
*/
function get_product_qty_via_db($user_id, $product_id) {
    $db = get_database_connection();
    // SELECT文の実行
    $sql = "SELECT product_qty FROM ec_site_cart WHERE user_id = :user_id AND product_id = :product_id";
    $stmt = execute_query($db, $sql, [':user_id' => $user_id, ':product_id' => $product_id]);
    
    $row0 = $stmt->fetch();
    if (!$row0) {
        return 0;
    }
    if ($stmt->fetch()) {
        user_error("カートデータベースで複数行の一致がありました。");
    }
    return $row0['product_qty'];
}

/**
* カート数量が在庫数量よりも少ないかどうかを判別
* 
* @param string $user_id ユーザーID
* @param string $product_id 商品ID
* @return bool trueならばカート数量が在庫数量よりも少ない、falseならばそうでない
*/
function is_product_qty_less_than_stock_qty($user_id, $product_id) {
    return (get_product_qty_via_db($user_id, $product_id) < get_stock_qty_via_db($product_id));
}

/**
* カート数量を更新
* 
* @param string $user_id ユーザー名
* @param string $product_id 商品ID
* @param string $product_qty カート数量
*/
function update_product_qty_via_db($user_id, $product_id, $product_qty) {
    $db = get_database_connection();
    // UPDATE文の実行
    $db->beginTransaction();
    $sql = "UPDATE ec_site_cart SET product_qty=:product_qty WHERE user_id=:user_id AND product_id=:product_id";
    $stmt = execute_query($db, $sql, [':product_qty' => $product_qty, ':user_id' => $user_id, ':product_id' => $product_id]);
    if ($stmt->rowCount() == 1) {
        $db->commit();
    } else {
        $db->rollBack();
        user_error("カートの更新に失敗しました。");
    }
}

/**
* カートの行を追加
* 
* @param string $user_id ユーザーID
* @param string $product_id 商品ID
* @param string $product_qty カート数量
*/
function insert_product_qty_via_db($user_id, $product_id, $product_qty) {
    $db = get_database_connection();
    // UPDATE文の実行
    $db->beginTransaction();
    $sql = "INSERT INTO ec_site_cart (user_id, product_id, product_qty) VALUES (:user_id, :product_id, :product_qty)";
    $stmt = execute_query($db, $sql, [':product_qty' => $product_qty, ':user_id' => $user_id, ':product_id' => $product_id]);
    if ($stmt->rowCount() == 1) {
        $db->commit();
    } else {
        $db->rollBack();
        user_error("カートデータベースへの挿入に失敗しました。");
    }
}

/**
* ウェルカムメッセージを表示
*
* @return string ウェルカムメッセージ
*/
function display_welcome_message() {
    if ($_SESSION['welcome'] == 'true') {
        return "こんにちは、" . $_SESSION['user_name'] . "さん！";
    }
    return "";
}

/**
* カートに商品を追加
* 
* @param string $user_id ユーザーID
* @param string $product_id 商品ID
* @return string メッセージ
*/
function add_product_to_cart($user_id, $product_id) {
    $product_qty = get_product_qty_via_db($user_id, $product_id);
    if ($product_qty == 0) {
        insert_product_qty_via_db($user_id, $product_id, 1);
    } else {
        update_product_qty_via_db($user_id, $product_id, $product_qty + 1);
    }
    return "カートに商品を１個追加しました。";
}