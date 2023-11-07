<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ライラックマート - 商品一覧</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>ライラックマート</h1>
        <div>
            <p>カート</p>
            <p><a href="logout.php">ログアウト</a></p>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </header>
    <div class="container">
        <div class="cart-addition-success-message">
            カートに正常に追加されたメッセージを表示します。
        </div>
        <div class="item-list">
            <?php foreach (get_public_product_list_via_db() as $p): ?>

            <div class="item">
                <img src="<?php echo $p['product_image'] ?>" width="270" height="180">
                <div class="item-text">
                    <p class="item-name"><?php echo $p['product_name'] ?></p>
                    <p class="item-price"><?php echo $p['price'] ?>円</p>
                    <div class="clear"></div>
                </div>
                <input type="submit" name="add-to-cart1" value="カートに入れる">
            </div>

            <?php endforeach; ?>

        </div>
    </div>
</body>
</html>
