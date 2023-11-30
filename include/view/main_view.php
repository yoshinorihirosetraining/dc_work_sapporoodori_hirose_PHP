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
            <p><a href="cart.php">カート</a></p>
            <p><a href="logout.php">ログアウト</a></p>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </header>
    <div class="container">
        <?php if ($msg != ""): ?>
        <div class="cart-addition-success-message">
            <?php echo $msg ?>
        </div>
        <?php endif; ?>
        <div class="item-list">
            <?php foreach (get_public_product_list_via_db() as $p): ?>

            <div class="item">
                <img src="<?php echo $p['product_image'] ?>" width="270" height="180">
                <div class="item-text">
                    <p class="item-name"><?php echo $p['product_name'] ?></p>
                    <p class="item-price"><?php echo $p['price'] ?>円</p>
                    <div class="clear"></div>
                </div>
                <?php if (is_product_qty_less_than_stock_qty($_SESSION['user_id'], $p['product_id'])): ?>
                <form method="post">
                    <input type="submit" name="login_add_to_cart_btn<?php echo $p['product_id'] ?>" value="カートに入れる">
                </form>
                <?php else: ?>
                <p>売り切れ</p>
                <?php endif; ?>
            </div>

            <?php endforeach; ?>

        </div>
    </div>
</body>
</html>
