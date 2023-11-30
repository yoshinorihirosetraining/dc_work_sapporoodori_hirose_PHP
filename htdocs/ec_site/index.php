<?php

require_once '../../include/model/index_model.php';
session_start();

// セッションチェックとリダイレクト
check_session_and_redirect_main();

$errmsg = ""; // エラーメッセージの初期化

// ログイン処理
process_login();

// ログイン画面内で「ユーザー名」入力フィールドの値を保持する処理
$user_name = ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['index_submit_btn'])) 
    ? htmlspecialchars($_POST['user_name']) 
    : "";

include_once '../../include/view/index_view.php';
