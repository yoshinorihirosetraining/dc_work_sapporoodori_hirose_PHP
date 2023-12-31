<?php

require_once('../../include/config/const.php');
require_once('../../include/utility/common_func.php');

/**
* データベースからユニークなファイル名を取得
* 
* @return string ユニークなファイル名
*/
function get_unique_filename_via_db() {
    $db = get_database_connection();

    // SELECT FOR UPDATE文の実行
    $db->beginTransaction();
    $sql = "SELECT id FROM ec_site_unique_filename FOR UPDATE;";
    $stmt = execute_query($db, $sql);
    $row0 = $stmt->fetch();
    if (!$row0) {
        user_error("データベースの取得に失敗しました");
    }
    $result = (int)($row0['id']);
    if ($stmt->fetch()) {
        user_error("ファイルネームデータベースで複数行の一致がありました。");
    }

    // UPDATE文の実行
    $sql = "UPDATE ec_site_unique_filename SET id=:id";
    $stmt = execute_query($db, $sql, [':id' => $result + 1]);
    if ($stmt->rowCount() == 1) {
        $db->commit();
    } else {
        $db->rollBack();
        user_error("ユーザーの追加に失敗しました。");
    }

    return $result;
}

/**
* 商品を追加
* 
* @param string $product_name 商品名
* @param string $price 単価
* @param string $stock_qty 在庫数量
* @param string $product_image 商品の画像ファイル名
* @param string $public_flg 公開フラグ(1ならば公開、0ならば非公開)
*/
function add_product_via_db($product_name, $price, $stock_qty, $product_image, $public_flg) {
    $db = get_database_connection();
    // INSERT文の実行
    $db->beginTransaction();
    $sql = "INSERT INTO ec_site_product (product_name, price, stock_qty, product_image, public_flg) VALUES (:product_name, :price, :stock_qty, :product_image, :public_flg)";
    $stmt = execute_query($db, $sql, [':product_name' => $product_name, ':price' => $price, ':stock_qty' => $stock_qty, ':product_image' => $product_image, ':public_flg' => $public_flg]);
    if ($stmt->rowCount() == 1) {
        $db->commit();
    } else {
        $db->rollBack();
        user_error("商品の追加に失敗しました。");
    }
}

/**
* 在庫数量を更新
* 
* @param string $product_id 商品ID
* @param string $stock_qty 在庫数量
*/
function update_stock_qty_via_db($product_id, $stock_qty) {
    $db = get_database_connection();
    // UPDATE文の実行
    $db->beginTransaction();
    $sql = "UPDATE ec_site_product SET stock_qty=:stock_qty WHERE product_id=:product_id";
    $stmt = execute_query($db, $sql, [':product_id' => $product_id, ':stock_qty' => $stock_qty]);
    if ($stmt->rowCount() == 1) {
        $db->commit();
    } else {
        $db->rollBack();
        user_error("商品の更新に失敗しました。");
    }
}

/**
* 公開フラグを更新
* 
* @param string $product_id 商品ID
* @param string $public_flg 公開フラグ
*/
function update_public_flg_via_db($product_id, $public_flg) {
    $db = get_database_connection();
    // UPDATE文の実行
    $db->beginTransaction();
    $sql = "UPDATE ec_site_product SET public_flg=:public_flg WHERE product_id=:product_id";
    $stmt = execute_query($db, $sql, [':product_id' => $product_id, ':public_flg' => $public_flg]);
    if ($stmt->rowCount() == 1) {
        $db->commit();
    } else {
        $db->rollBack();
        user_error("商品の更新に失敗しました。");
    }
}
/**
* 商品を削除
* 
* @param string $product_id 商品ID
*/
function delete_product_via_db($product_id) {
    $db = get_database_connection();
    // DELETE文の実行
    $db->beginTransaction();
    $sql = "DELETE FROM ec_site_product WHERE product_id=:product_id";
    $stmt = execute_query($db, $sql, [':product_id' => $product_id]);
    if ($stmt->rowCount() == 1) {
        $db->commit();
    } else {
        $db->rollBack();
        user_error("商品の削除に失敗しました。");
    }
}

/**
* 商品一覧を取得
* 
* @return array 商品データベースの全行
*/
function get_product_list_via_db() {
    $db = get_database_connection();
    // SELECT文の実行
    $sql = "SELECT * FROM ec_site_product";
    $stmt = execute_query($db, $sql);
    return $stmt->fetchAll();
}
