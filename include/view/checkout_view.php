<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ライラックマート - 購入完了ページ</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>ライラックマート</h1>
        <div>
            <p><a href="login.php">新規にお買い物をする</a></p>
            <p><a href="logout.php">ログアウト</a></p>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </header>
    <div id="checkout-container">
        <h2>購入完了ページ</h2>
        <div class="checkout-success-message">
            購入が完了しました、ありがとうございました！
        </div>

        <?php foreach ($cart_info as $i): ?>
        <div class="checkout-item">
            <img src="<?php echo $i['product_image'] ?>" width="270px" height="180px">
            <div class="checkout-item-name"><?php echo $i['product_name'] ?></div>
            <p class="checkout-item-price">価格：¥ <?php echo $i['price'] ?></p>
            <p class="checkout-item-qty">数量：<?php echo $i['product_qty'] ?></p>
        </div>
        <?php endforeach; ?>
        <p id="checkout-total">合計：<?php echo $cart_total ?>円</p>
    </div>
</body>
</html>