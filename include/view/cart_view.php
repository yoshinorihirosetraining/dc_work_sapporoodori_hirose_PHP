<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ライラックマート - ショッピングカート</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>ライラックマート</h1>
        <div>
            <p><a href="login.php">お買い物を続ける</a></p>
            <p><a href="logout.php">ログアウト</a></p>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </header>
    <h2>ショッピングカート</h2>
    <?php if ($errmsg != ""): ?>
    <div class="cart-update-error-message">
        <?php echo $errmsg ?>
    </div>
    <?php endif; ?>
    <?php if ($msg != ""): ?>
    <div class="cart-update-success-message">
        <?php echo $msg ?>
    </div>
    <?php endif; ?>

    <?php foreach ($cart_info as $i): ?>
    <div class="cart-item">
        <img src="<?php echo $i['product_image'] ?>" width="270" height="180">
        <div class="cart-item-name"><?php echo $i['product_name'] ?></div>
        <form method="post" class="cart-item-delete-form">
            <input type="submit" class="cart-item-delete-btn" name="cart_delete_btn<?php echo $i['cart_id'] ?>" value="削除する">
        </form>
        <p class="cart-item-price-text">価格：¥ <?php echo $i['price'] ?></p>
        <form method="post">
            <div class="cart-item-qty">
                数量：
                <input type="text" name="qty<?php echo $i['cart_id'] ?>" value="<?php echo $i['product_qty'] ?>">
            </div>
            <input type="submit" class="cart-item-change-btn" name="cart_change_btn<?php echo $i['cart_id'] ?>" value="変更する">
        </form>
    </div>
    <?php endforeach; ?>

    <div id="cart-total-and-checkout-btn">
        <p id="cart-total">合計：<?php echo $cart_total ?>円</p>
        <input type="submit" id="cart-checkout-btn" name="checkout-btn" value="購入する">
    </div>
</body>
</html>