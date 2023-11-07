<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ライラックマート - ログイン</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>ライラックマート</h1>
    </header>
    <h2>ログイン</h2>
    <?php if ($errmsg != ""): ?>
    <div class="error-message">
        <?php echo $errmsg ?>
    </div>
    <?php endif; ?>
    <form method="post">
        <label for="user_name">ユーザー名</label>
        <input type="text" name="user_name" id="user_name"
            value="<?php echo $user_name ?>">
        <br>
        <label for="password">パスワード</label>
        <input type="password" name="password" id="password">
        <br>
        <input type="submit" name="index_submit_btn" value="ログイン">
        <br>
        <br>
        <a href="registration.php">新規登録ページへ</a>
    </form>
</body>
</html>
