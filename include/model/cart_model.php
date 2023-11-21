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
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':user_id', $user_id);

    if (!$stmt->execute()) {
        user_error("データベースの取得に失敗しました");
    }
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
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':user_id', $user_id);

    if (!$stmt->execute()) {
        user_error("データベースの取得に失敗しました");
    }
    $sum = $stmt->fetch()['SUM(product_qty * price)'];
    if ($sum == "") {
        $sum = 0;
    }
    return $sum;
}

/**
* カートの一行を削除
* 
* @param string $cart_id カートID
*/
function delete_cart_via_db($cart_id) {
    $db = get_database_connection();
    // DELETE文の実行
    $db->beginTransaction();
    $sql = "DELETE FROM ec_site_cart WHERE cart_id=:cart_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':cart_id', $cart_id);
    $stmt->execute();
    if ($stmt->rowCount() == 1) {
        $db->commit();
    } else {
        $db->rollBack();
        user_error("カートの削除に失敗しました。");
    }
}

/**
* カートIDから、カートの一行を取得
* 
* @param string $cart_id カートID
* @return array カートの一行
*/
function get_cart_information_from_cart_id_via_db($cart_id) {
    $db = get_database_connection();
    // SELECT文の実行
    $sql = "SELECT * FROM ec_site_cart INNER JOIN ec_site_product ON ec_site_cart.product_id = ec_site_product.product_id WHERE cart_id=:cart_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':cart_id', $cart_id);

    if (!$stmt->execute()) {
        user_error("データベースの取得に失敗しました");
    }
    return $stmt->fetch();
}

/**
* カート数量を更新
* 
* @param string $cart_id カートID
* @param string $product_qty カート数量
*/
function update_cart_via_db($cart_id, $product_qty) {
    $db = get_database_connection();
    // UPDATE文の実行
    $db->beginTransaction();
    $sql = "UPDATE ec_site_cart SET product_qty=:product_qty WHERE cart_id=:cart_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':product_qty', $product_qty);
    $stmt->bindValue(':cart_id', $cart_id);
    $stmt->execute();
    if ($stmt->rowCount() == 1) {
        $db->commit();
    } else {
        $db->rollBack();
        user_error("カートの更新に失敗しました。");
    }
}

/**
* 決済処理を行う(カートの内容に従って在庫数量を更新)
* 
* @param string $user_id ユーザーID
* @param string &$errmsg 決済に失敗した時にエラーメッセージを代入します
* @return bool trueならば成功、falseならば失敗
*/
function checkout_via_db($user_id, &$errmsg) {
    $db = get_database_connection();
    $db->beginTransaction();

    // SELECT FOR UPDATE文の実行
    $sql = "SELECT * FROM ec_site_cart INNER JOIN ec_site_product ON ec_site_cart.product_id = ec_site_product.product_id WHERE user_id=:user_id FOR UPDATE";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':user_id', $user_id);
    if (!$stmt->execute()) {
        user_error("データベースの取得に失敗しました");
    }
    $info = $stmt->fetchAll();

    // 在庫がカートより多いことを確認
    foreach ($info as $i) {
        if ($i['stock_qty'] < $i['product_qty']) {
            $errmsg = $i['product_name'].'のカート数量('.$i['product_qty'].'個)が在庫数量('.$i['stock_qty'].'個)を越えています。';
            $db->rollBack();
            return false;
        }
    }

    // UPDATE文の実行
    foreach ($info as $i) {
        $sql = "UPDATE ec_site_product SET stock_qty = :stock_qty WHERE product_id = :product_id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':stock_qty', $i['stock_qty'] - $i['product_qty']);
        $stmt->bindValue(':product_id', $i['product_id']);
        $stmt->execute();
        if ($stmt->rowCount() != 1) {
            $db->rollBack();
            user_error("カートの更新に失敗しました。");
            return false;
        }
    }

    $db->commit();
    return true;
}
