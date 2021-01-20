<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>本登録完了ページ</title>
</head>
<body>
<?php
session_start();
 
header("Content-type: text/html; charset=utf-8");
 
//クロスサイトリクエストフォージェリ（CSRF）対策のトークン判定
if ($_POST['token'] != $_SESSION['token']){
	echo "不正アクセスの可能性あり";
	exit();
}
 
//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');
 
//データベース接続
/*DB接続設定*/
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
 
//エラーメッセージの初期化
$errors = array();
 
if(empty($_POST)) {
	header("Location: registration_mail_form.php");
	exit();
}
 
$mail = $_SESSION['mail'];
$account = $_SESSION['account'];
 
$password = $_SESSION['password'];
 
//ここでデータベースに登録する

	
	
	
	//memberテーブルに本登録する
	$sql = $pdo -> prepare("INSERT INTO member (account, mail, password) 
	VALUES (:account, :mail, :password)");
	//プレースホルダへ実際の値を設定する
    $sql -> bindParam(':account', $account, PDO::PARAM_STR);
    $sql -> bindParam(':mail', $mail, PDO::PARAM_STR);
    $sql -> bindParam(':password', $password, PDO::PARAM_STR);
    $sql -> execute();



	//pre_memberのflagを1にする
	
    $sql=$pdo->prepare("UPDATE pre_member SET flag=1 WHERE mail=(:mail)");
    $sql->bindParam(':mail',$mail,PDO::PARAM_STR);
    $sql -> execute();


	
	
	//セッション変数を全て解除
	$_SESSION = array();
	
	//セッションクッキーの削除・sessionidとの関係を探れ。つまりはじめのsesssionidを名前でやる
	if (isset($_COOKIE["PHPSESSID"])) {
    		setcookie("PHPSESSID", '', time() - 1800, '/');
	}
	
 	//セッションを破棄する
 	session_destroy();
 	
 	
	

 
?>
 

 
<?php if (count($errors) === 0): ?>
<h1>大学生学習お役立ちサービス【会員登録完了ページ】</h1>
 
<p>登録完了いたしました。ログイン画面からどうぞ。</p>
<p><a href="https://tb-220211.tech-base.net/login.php" target="_blank">ログインはこちら</a></p>
 
<?php elseif(count($errors) > 0): ?>
 
<?php
foreach($errors as $value){
	echo "<p>".$value."</p>";
}
?>
 
<?php endif; ?>
 
</body>
</html>