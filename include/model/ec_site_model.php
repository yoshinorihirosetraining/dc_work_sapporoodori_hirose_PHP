<?php

require_once('../../include/config/const.php');

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

/**
* 与えられた文字列が一般ユーザー名であるかどうかを判別
* 
* @param string $user_name 判別したい文字列
* @return bool trueならば一般ユーザー名、falseならばそうでない
*/
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

/**
* 一般ユーザーを追加
* 
* @param string $user_name ユーザー名
* @param string $password パスワード
*/
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
* 公開フラグが1である商品データベースの行を取得
* 
* @return array 商品データベースの行
*/
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

/**
* 在庫数量を取得
* 
* @param string $product_id 商品ID
* @return string 在庫数量
*/
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

/**
* カートの行を追加
* 
* @param string $user_id ユーザーID
* @param string $product_id 商品ID
* @param string $product_qty カート数量
*/
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

/**
* ユーザーIDを指定して、カートの行を取得
* 
* @param string $user_id ユーザーID
* @return array カートの行
*/
function get_cart_information_via_db($user_id) {
    try {
        // データベースへ接続
        $db = new PDO(DB_DSN, DB_LOGIN_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
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
    try {
        // データベースへ接続
        $db = new PDO(DB_DSN, DB_LOGIN_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
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
    try {
        // データベースへ接続
        $db = new PDO(DB_DSN, DB_LOGIN_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
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
    try {
        // データベースへ接続
        $db = new PDO(DB_DSN, DB_LOGIN_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
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
    try {
        // データベースへ接続
        $db = new PDO(DB_DSN, DB_LOGIN_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
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
    try {
        // データベースへ接続
        $db = new PDO(DB_DSN, DB_LOGIN_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
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

/**
* ユーザーIDを指定して、カートの行を削除
* 
* @param string $user_id ユーザーID
*/
function delete_cart_all_via_db($user_id) {
    try {
        // データベースへ接続
        $db = new PDO(DB_DSN, DB_LOGIN_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
    // DELETE文の実行
    $db->beginTransaction();
    $sql = "DELETE FROM ec_site_cart WHERE user_id=:user_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':user_id', $user_id);
    $stmt->execute();
    $db->commit();
}