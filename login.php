<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログインページ</title>
</head>
<body>
<h1>大学生学習お役立ちサービス【ログインページ】</h1>

<form action=""method="post">
<p>メールアドレス：<input type ="text" name="mail"> </p>
<p>パスワード：<input type ="text" name="pass"></p>
<input type="submit" name="submit" value="送信">
</form>

<?php

session_start();

/*DB接続設定*/
$dsn = 'mysql:dbname=tb220211db;host=localhost';
$user = 'tb-220211';
$password = 'nu2mzpvm7D';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

if(isset($_POST['submit'])){
    $flag = "";
    if($_POST['mail'] != NULL && $_POST['pass'] != NULL){
        $mail = $_POST['mail'];
        $pass = $_POST['pass'];
        $sql = 'SELECT * FROM member';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchALL();
        foreach($results as $row){
            if($row['mail'] == $mail && $row['password'] == $pass){
                $_SESSION['id'] = $row['id'];
                $_SESSION['account'] = $row['account'];
                $flag = 1;
            }
        }
        if($flag == 1){
            header("location: top.php");
        }else{
            $error = 2;
        }
        $flag = 0;
    }else{
        $error = 1;
    }
}


?>

</body>
</html>