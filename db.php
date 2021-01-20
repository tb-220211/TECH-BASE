<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>テーブル作成</title>
</head>
<body>
    <?php

    /*DB接続設定*/
    $dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
	/*仮登録テーブル作成*/
    $sql = "CREATE TABLE IF NOT EXISTS pre_member"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "urltoken varchar(128) NOT NULL,"
    . "mail varchar(50) NOT NULL,"
    . "date datetime NOT NULL,"
    . "flag tinyint(1) DEFAULT 0"
    .");";
    $stmt = $pdo->query($sql);

    //本登録テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS member"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "account varchar(50) NOT NULL,"
    . "mail varchar(50) NOT NULL,"
    . "password varchar(128) NOT NULL,"
    . "flag tinyint(1) DEFAULT 1"
    .");";
    $stmt = $pdo->query($sql);
    
    ?>
	
</body>
</html>