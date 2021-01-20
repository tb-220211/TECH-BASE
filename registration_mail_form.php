<?php
session_start();

header("Content-type: text/html; charset=utf-8");

//クロスサイトリクエストフォージェリ(CSRF)対策
$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
$token = $_SESSION['token'];

//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

?>

<!DOCTYPE html>
<html>
<head>
<title>仮登録ページ</title>
<meta charset="utf-8">
</head>
<body>
<h1>大学生学習お役立ちサービス【仮登録ページ】</h1>

<h3>メールアドレスを入力し、「登録」ボタンを押してください</h3>

<form action="registration_mail_check.php" method="post">

<p>メールアドレス:<input type="text" name="mail" size="50" placeholder="メールアドレスを入力してください"></p>

<input type="hidden" name="token" value="<?=$token?>">
<input type="submit" value="登録する">

</form>

</body>
</html>