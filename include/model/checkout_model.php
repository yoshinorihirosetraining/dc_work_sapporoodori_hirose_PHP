<?php

require_once('../../include/config/const.php');
require_once('../../include/utility/common_func.php');

/**
* ユーザーIDを指定して、カートの行を取得
* 
* @param string $user_id ユーザーID
* @return array カートの行
*/
function get_cart_information_via_db($user_id) {
    $db = get_database_connection();
    // SELECT文の実行
    $sql = "SELECT * FROM ec_site_cart INNER JOIN ec_site_product ON ec_site_cart.product_id = ec_site_product.product_id WHERE user_id=:user_id";
    $stmt = execute_query($db, $sql, [':user_id' => $user_id]);
    return $stmt->fetchAll();
}

/**
* ユーザーIDを指定して、カートの合計金額を取得
* 
* @param string $user_id ユーザーID
* @return string カートの合計金額
*/
function get_cart_total_via_db($user_id) {
    $db = get_database_connection();
    // SELECT文の実行
    $sql = "SELECT SUM(product_qty * price) FROM ec_site_cart INNER JOIN ec_site_product ON ec_site_cart.product_id = ec_site_product.product_id WHERE user_id=:user_id";
    $stmt = execute_query($db, $sql, [':user_id' => $user_id]);
    $sum = $stmt->fetch()['SUM(product_qty * price)'];
    if ($sum == "") {
        $sum = 0;
    }
    return $sum;
}

/**
* ユーザーIDを指定して、カートの行を削除
* 
* @param string $user_id ユーザーID
*/
function delete_cart_all_via_db($user_id) {
    $db = get_database_connection();
    // DELETE文の実行
    $db->beginTransaction();
    $sql = "DELETE FROM ec_site_cart WHERE user_id=:user_id";
    $stmt = execute_query($db, $sql, [':user_id' => $user_id]);
    $db->commit();
}
