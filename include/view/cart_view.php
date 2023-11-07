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
            <p>お買い物を続ける</p>
            <p>ログアウト</p>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </header>
    <h2>ショッピングカート</h2>
    <div class="cart-update-error-message">
        エラーメッセージを表示します。
    </div>
    <div class="cart-update-success-message">
        カートに正常に追加されたメッセージを表示します。
    </div>
    <form method="post">
        <div class="cart-item">
            <img src="image/キャベツ.jpg" width="270" height="180">
            <div class="cart-item-name">キャベツ</div>
            <input type="submit" class="cart-item-delete-btn" name="delete1" value="削除する">
            <p class="cart-item-price-text">価格：¥ 100</p>
            <div class="cart-item-qty">
                数量：
                <input type="text" name="qty1" value="2">
            </div>
            <input type="submit" class="cart-item-change-btn" name="change1" value="変更する">
        </div>
        <div class="cart-item">
            <img src="image/きゅうり.jpg" width="270" height="180">
            <div class="cart-item-name">きゅうり</div>
            <input type="submit" class="cart-item-delete-btn" name="delete2" value="削除する">
            <p class="cart-item-price-text">価格：¥ 120</p>
            <div class="cart-item-qty">
                数量：
                <input type="text" name="qty2" value="1">
            </div>
            <input type="submit" class="cart-item-change-btn" name="change2" value="変更する">
        </div>
        <div class="cart-item">
            <img src="image/トマト.jpg" width="270" height="180">
            <div class="cart-item-name">トマト</div>
            <input type="submit" class="cart-item-delete-btn" name="delete3" value="削除する">
            <p class="cart-item-price-text">価格：¥ 130</p>
            <div class="cart-item-qty">
                数量：
                <input type="text" name="qty3" value="1">
            </div>
            <input type="submit" class="cart-item-change-btn" name="change3" value="変更する">
        </div>
        <div id="cart-total-and-checkout-btn">
            <p id="cart-total">合計：450円</p>
            <input type="submit" id="cart-checkout-btn" name="checkout-btn" value="購入する">
        </div>
    </form>
</body>
</html>