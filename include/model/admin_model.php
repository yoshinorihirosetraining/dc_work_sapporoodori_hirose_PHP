<?php

require_once('../../include/config/const.php');
require_once('../../include/utility/common_func.php');

/**
* データベースからユニークなファイル名を取得
* 
* @return string ユニークなファイル名
*/
function get_unique_filename_via_db() {
    try {
        // データベースへ接続
        $db = new PDO(DB_DSN, DB_LOGIN_USER, DB_PASSWORD);

        // SELECT FOR UPDATE文の実行
        $db->beginTransaction();
        $sql = "SELECT id FROM ec_site_unique_filename FOR UPDATE;";
        $stmt = $db->prepare($sql);

        if (!$stmt->execute()) {
            user_error("データベースの取得に失敗しました");
        }
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
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $result + 1);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $db->commit();
        } else {
            $db->rollBack();
            user_error("ユーザーの追加に失敗しました。");
        }

    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
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
    try {
        // データベースへ接続
        $db = new PDO(DB_DSN, DB_LOGIN_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
    // INSERT文の実行
    $db->beginTransaction();
    $sql = "INSERT INTO ec_site_product (product_name, price, stock_qty, product_image, public_flg) VALUES (:product_name, :price, :stock_qty, :product_image, :public_flg)";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':product_name', $product_name);
    $stmt->bindValue(':price', $price);
    $stmt->bindValue(':stock_qty', $stock_qty);
    $stmt->bindValue(':product_image', $product_image);
    $stmt->bindValue(':public_flg', $public_flg);
    $stmt->execute();
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
    try {
        // データベースへ接続
        $db = new PDO(DB_DSN, DB_LOGIN_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
    // UPDATE文の実行
    $db->beginTransaction();
    $sql = "UPDATE ec_site_product SET stock_qty=:stock_qty WHERE product_id=:product_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':product_id', $product_id);
    $stmt->bindValue(':stock_qty', $stock_qty);
    $stmt->execute();
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
    try {
        // データベースへ接続
        $db = new PDO(DB_DSN, DB_LOGIN_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
    // UPDATE文の実行
    $db->beginTransaction();
    $sql = "UPDATE ec_site_product SET public_flg=:public_flg WHERE product_id=:product_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':product_id', $product_id);
    $stmt->bindValue(':public_flg', $public_flg);
    $stmt->execute();
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
    try {
        // データベースへ接続
        $db = new PDO(DB_DSN, DB_LOGIN_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
    // DELETE文の実行
    $db->beginTransaction();
    $sql = "DELETE FROM ec_site_product WHERE product_id=:product_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':product_id', $product_id);
    $stmt->execute();
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
    try {
        // データベースへ接続
        $db = new PDO(DB_DSN, DB_LOGIN_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
    // SELECT文の実行
    $sql = "SELECT * FROM ec_site_product";
    $stmt = $db->prepare($sql);
    
    if (!$stmt->execute()) {
        user_error("データベースの取得に失敗しました");
    }
    return $stmt->fetchAll();
}
