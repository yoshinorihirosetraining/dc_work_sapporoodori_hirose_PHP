<?php

require_once('../../include/config/const.php');

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
    if (!row0) {
        user_error("アカウントデータベースで０行の一致がありました。");
    }
    if ($stmt->fetch()) {
        user_error("アカウントデータベースで複数行の一致がありました。");
    }
    return $row0['user_id'];
}

function is_normal_user_name_via_db($user_name) {
    try {
        // データベースへ接続
        $db = new PDO(DB_DSN, DB_LOGIN_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
    // SELECT文の実行
    $sql = "SELECT user_name FROM ec_site_user WHERE user_name = :user_name";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':user_name', $user_name);
    
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

function add_normal_user_via_db($user_name, $password) {
    try {
        // データベースへ接続
        $db = new PDO(DB_DSN, DB_LOGIN_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
    // INSERT文の実行
    $db->beginTransaction();
    $sql = "INSERT INTO ec_site_user (user_name, password) VALUES (:user_name, :password)";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':user_name', $user_name);
    $stmt->bindValue(':password', $password);
    $stmt->execute();
    if ($stmt->rowCount() == 1) {
        $db->commit();
    } else {
        $db->rollBack();
        user_error("ユーザーの追加に失敗しました。");
    }
}

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

function match_keyword_from_array($pattern, $array) {
    foreach ($array as $key => $val) {
        $result = preg_match($pattern, $key, $match, PREG_OFFSET_CAPTURE);
        if ($result) {
            return $match;
        }
    }
    return null;
}

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

function get_public_product_list_via_db() {
    try {
        // データベースへ接続
        $db = new PDO(DB_DSN, DB_LOGIN_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
    // SELECT文の実行
    $sql = "SELECT * FROM ec_site_product WHERE public_flg=1";
    $stmt = $db->prepare($sql);
    
    if (!$stmt->execute()) {
        user_error("データベースの取得に失敗しました");
    }
    return $stmt->fetchAll();
}

function get_stock_qty_via_db($product_id) {
    try {
        // データベースへ接続
        $db = new PDO(DB_DSN, DB_LOGIN_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
    // SELECT文の実行
    $sql = "SELECT stock_qty FROM ec_site_product WHERE product_id = :product_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':product_id', $product_id);
    
    if (!$stmt->execute()) {
        user_error("データベースの取得に失敗しました");
    }
    
    $row0 = $stmt->fetch();
    if (!row0) {
        user_error("商品データベースで０行の一致がありました。");
    }
    if ($stmt->fetch()) {
        user_error("商品データベースで複数行の一致がありました。");
    }
    return $row0['stock_qty'];
}

function get_product_qty_via_db($user_id, $product_id) {
    try {
        // データベースへ接続
        $db = new PDO(DB_DSN, DB_LOGIN_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
    // SELECT文の実行
    $sql = "SELECT product_qty FROM ec_site_cart WHERE user_id = :user_id AND product_id = :product_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':user_id', $user_id);
    $stmt->bindValue(':product_id', $product_id);
    
    if (!$stmt->execute()) {
        user_error("データベースの取得に失敗しました");
    }
    
    $row0 = $stmt->fetch();
    if (!$row0) {
        return 0;
    }
    if ($stmt->fetch()) {
        user_error("カートデータベースで複数行の一致がありました。");
    }
    return $row0['product_qty'];
}

function is_product_qty_less_than_stock_qty($user_id, $product_id) {
    return (get_product_qty_via_db($user_id, $product_id) < get_stock_qty_via_db($product_id));
}

function update_product_qty_via_db($user_id, $product_id, $product_qty) {
    try {
        // データベースへ接続
        $db = new PDO(DB_DSN, DB_LOGIN_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
    // UPDATE文の実行
    $db->beginTransaction();
    $sql = "UPDATE ec_site_cart SET product_qty=:product_qty WHERE user_id=:user_id AND product_id=:product_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':product_qty', $product_qty);
    $stmt->bindValue(':user_id', $user_id);
    $stmt->bindValue(':product_id', $product_id);
    $stmt->execute();
    if ($stmt->rowCount() == 1) {
        $db->commit();
    } else {
        $db->rollBack();
        user_error("カートの更新に失敗しました。");
    }
}

function insert_product_qty_via_db($user_id, $product_id, $product_qty) {
    try {
        // データベースへ接続
        $db = new PDO(DB_DSN, DB_LOGIN_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
    // UPDATE文の実行
    $db->beginTransaction();
    $sql = "INSERT INTO ec_site_cart (user_id, product_id, product_qty) VALUES (:user_id, :product_id, :product_qty)";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':product_qty', $product_qty);
    $stmt->bindValue(':user_id', $user_id);
    $stmt->bindValue(':product_id', $product_id);
    $stmt->execute();
    if ($stmt->rowCount() == 1) {
        $db->commit();
    } else {
        $db->rollBack();
        user_error("カートデータベースへの挿入に失敗しました。");
    }
}
