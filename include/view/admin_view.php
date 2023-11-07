<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ライラックマート - 商品登録</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>ライラックマート</h1>
    </header>
    <h2>商品登録</h2>
    <form method="post" enctype="multipart/form-data" id="product-registration-form">
        <?php if ($add_msg != ""): ?>
        <span class="product-registration-success-message"><?php echo $add_msg ?></span>
        <br>
        <?php endif; ?>
        <label for="product_name">商品名　　：</label>
        <input type="text" name="product_name" id="product_name" value="<?php echo $add_product_name ?>">
        <br>
        <label for="price">価格　　　：</label>
        <input type="text" name="price" id="price" value="<?php echo $add_price ?>">
        <br>
        <label for="quantity">個数　　　：</label>
        <input type="text" name="quantity" id="quantity" value="<?php echo $add_quantity ?>">
        <br>
        <label for="image">商品画像　：</label>
        <input type="file" name="image" id="image">
        <br>
        <label for="status">ステータス：</label>
        <select name="status" id="status">
            <option value="public">公開</option>
            <option value="private">非公開</option>
        </select>
        <br>
        <?php if ($add_errmsg != ""): ?>
        <span class="product-registration-error-message"><?php echo $add_errmsg ?></span>
        <br>
        <?php endif; ?>
        <input type="submit" name="admin_add_product_btn" value="商品を登録">
    </form>
    <br>
    <a href="logout.php">ログアウト</a>
    <br>
    <?php if ($update_errmsg != ""): ?>
    <p class="product-list-error-message"><?php echo $update_errmsg ?></p>
    <?php endif; ?>
    <?php if ($update_msg != ""): ?>
    <p class="product-list-success-message"><?php echo $update_msg ?></p>
    <?php endif; ?>
    <form method="post">
        <table>
            <tr>
                <th>商品画像</th>
                <th>商品名</th>
                <th>価格</th>
                <th>在庫数</th>
                <th>公開フラグ</th>
                <th>削除</th>
            </tr>
            <?php foreach (get_product_list_via_db() as $i => $p): $id = $p['product_id']; ?>
            <tr <?php if ($p['public_flg'] == 0): ?>class="private-table-row"<?php endif; ?> >
                <td>
                    <img src="<?php echo $p['product_image'] ?>" width="300" height="200">
                </td>
                <td><?php echo $p['product_name'] ?></td>
                <td>¥ <?php echo $p['price'] ?></td>
                <td>
                    <form method="post">
                        <input type="text" name="stock<?php echo $id ?>" value="<?php echo $p['stock_qty'] ?>" class="stock-text">
                        <input type="submit" name="admin_stock_update_btn<?php echo $id ?>" value="変更する">
                    </form>
                </td>
                <td>
                    <form method="post">
                        <input type="submit" name="admin_status_btn<?php echo $id ?>" value="<?php echo ($p['public_flg'] == 1 ? "非表示にする" : "表示する") ?>">
                    </form>
                </td>
                <td>
                    <form method="post">
                        <input type="submit" name="admin_delete_btn<?php echo $id ?>" value="削除する">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </form>
</body>
</html>
