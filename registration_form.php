<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>本登録ページ</title>
</head>
<body>
<h1>大学生学習お役立ちサービス【本登録ページ】</h1>

<h3>アカウント名・パスワードを入力し、「確認する」ボタンを押してください</h3>
<?php
session_start();

header("Content-type: text/html; charset=utf-8");

//クロスサイトリクエストフォージェリ(CSFR)対策
$_SESSION['token']= base64_encode(openssl_random_pseudo_bytes(32));
$token = $_SESSION['token'];

//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

/*DB接続設定*/
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//エラーメッセージの初期化
$errors = array();

if(empty($_GET)){
    header("Location: registration_mail_form.php");
    exit();
}else{
    //GETデータを変数に代入
    $urltoken = isset($_GET['urltoken']) ? $_GET['urltoken'] : NULL;

    //メール入力判定
    if($urltoken == ''){
        $errors['urltoken'] = "もう一度登録をやり直してください";
    }else{
    $sql = 'SELECT * FROM pre_member WHERE urltoken=:urltoken ';
    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
    $stmt->bindParam(':urltoken', $urltoken, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
    $stmt->execute();                             // ←SQLを実行する。
    $results = $stmt->fetchAll();
        foreach ($results as $row){
            $mail = $row['mail'];
        }
    }
}

?>

<?php if(count($errors) === 0): ?>

<form action="registration_check.php" method="post">

<p>メールアドレス： <?php echo $mail;?> <input type="hidden" name="mail" value="<?php echo $mail;?>"></p>
<p>アカウント名：<input type="text" name="account"></p>
<p>パスワード：<input type ="text" name="password"></p>

<input type="hidden" name="token" value="<?=$token?>">
<input type="submit" value="確認する">

</form>

<?php elseif(count($errors) > 0):?>

<?php
foreach($errors as $value){
    echo "<p>".$value."</p>";
}
?>

<?php endif; ?>

</body>
</html>