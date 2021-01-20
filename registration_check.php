<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>本登録確認ページ</title>
</head>
<body>
<h1>大学生学習お役立ちサービス【本登録確認ページ】</h1>

<h3>
以下の内容で登録する場合は「登録する」ボタンを押してください。<br>
修正する場合は「戻る」ボタンを押して修正してください。
</h3>

<?php
session_start();

header("Content-type: text/html; charset=utf-8");

//クロスサイトリクエストフォージェリ対策のトークン判定
if($_POST['token'] != $_SESSION['token']){
    echo "不正アクセスの可能性あり";
    exit();
}

//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

//前後にある半角全角スペースを削除する関数
function spaceTrim($str){
    //行頭
    $str = preg_replace('/^[  ]+/u', '', $str);
    //末尾
    $str = preg_replace('/[  ]+$/u', '', $str);
    return $str;
}

//エラーメッセージの初期化
$errors = array();

if(empty($_POST)){
    header("Location: registration_mail_form.php");
    exit();
}else{
    //POSTされたデータを各変数に代入
    $account = isset($_POST['account']) ? $_POST['account'] : NULL;
    $password = isset($_POST['password']) ? $_POST['password'] : NULL;
    $mail = isset($_POST['mail']) ? $_POST['mail'] : NULL;

    //前後にある半角全角スペースを削除
    $account = spaceTrim($account);
    $password = spaceTrim($password);
    $mail = spaceTrim($mail);

    //アカウント入力判定
    if($account == ''):
        $errors['account'] = "アカウントが入力されていません。";
    elseif(mb_strlen($account)>10):
        $errors['account_length'] = "アカウントは10文字以内で入力してください。";
    endif;

  //パスワード入力判定
  if($password == ''):
    $errors['password'] = "パスワードが入力されていません。";
elseif(!preg_match('/^[0-9a-zA-Z]{5,30}$/', $_POST["password"])):
    $errors['password_length'] = "パスワードは半角英数字の5文字以上30文字以内で入力してください。";
endif;

}

//エラーがなければセッションに登録
if(count($errors) === 0){
    $_SESSION['account'] = $account;
    $_SESSION['password'] = $password;
    $_SESSION['mail'] = $mail;
}

?>


<?php if (count($errors) === 0): ?>


<form action="registration_insert.php" method="post">

<p>メールアドレス：<?=htmlspecialchars($mail, ENT_QUOTES)?></p>
<p>アカウント名：<?=htmlspecialchars($account, ENT_QUOTES)?></p>
<p>パスワード：<?=htmlspecialchars($password, ENT_QUOTES)?></p>

<input type="button" value="戻る" onClick="history.back()">
<input type="hidden" name="token" value="<?=$_POST['token']?>">
<input type="submit" value="登録する">

</form>

<?php elseif(count($errors) > 0): ?>

<?php
foreach($errors as $value){
    echo "<p>".$value."</p>";
}    
?>

<input type="button" value="戻る" onClick="history.back()">

<?php endif; ?>
</body>
</html>