<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ライラックマート - ユーザー登録</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>ライラックマート</h1>
    </header>
    <h2>ユーザー登録</h2>
    <?php if ($msg != ""): ?>
    <div class="success-message">
        <?php echo $msg ?>
    </div>
    <?php endif; ?>
    <?php if ($errmsg != ""): ?>
    <div class="error-message">
        <?php echo $errmsg ?>
    </div>
    <?php endif; ?>
    <form method="post">
        <label for="user_name">ユーザー名</label>
        <input type="text" name="user_name" id="user_name">
        <br>
        <label for="password">パスワード</label>
        <input type="password" name="password" id="password">
        <br>
        <input type="submit" name="registration_submit_btn" value="登録">
        <br>
        <br>
        <a href="index.php">ログインページへ</a>
    </form>
</body>
</html>
