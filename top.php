<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>トップページ</title>
</head>
<body>
<center>
<h1>大学生学習お役立ちサービス【トップページ】</h1>
<h3>
<?php

session_start();

echo "ようこそ".$_SESSION['account']."さん！";

?>
</h3>
<h3>
<span style="background-color: yellow">
◯件の課題があります。<br>
直近の締切は◯月◯日です。
</span>
</h3>

<a href="https://tb-220211.tech-base.net/kadai_top.php" >
<img src="kadai_top.JPG" alt="課題" style="width: 300px; height: 300px;"></a>

<a href="https://tb-220211.tech-base.net/tanni_top.php">
<img src="tanni_top.JPG" alt="単位" style="width: 300px; height: 300px;"></a>

<a href="https://tb-220211.tech-base.net/keiji_top.php">
<img src="keiji_top.JPG" alt="掲示板" style="width: 300px; height: 300px;"></a>

<a href="https://tb-220211.tech-base.net/situmonn_top.php">
<img src="situmonn_top.JPG" alt="質問" style="width: 300px; height: 300px;"></a>
</center>
</body>
</html>