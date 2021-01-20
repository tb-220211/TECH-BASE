<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>仮登録確認ページ</title>
</head>
<body>
<h1>大学生学習お役立ちサービス【仮登録ページ】</h1>

<?php
session_start();

header("Content-type: text/html; charset=utf-8");

//クロスサイトリクエストフォージェリ(CSFR)対策のトークン判定
if($_POST['token'] != $_SESSION['token']){
    echo "不正アクセスの可能性あり";
    exit();
}

//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

/*DB接続設定*/
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
$urltoken = hash('sha256', uniqid(rand(),1));

//エラーメッセージの初期化
$errors = array();

if(empty($_POST)){
    header("Location: registration_mail_form.php");
    exit();
}else{
    //POSTされたデータを変数に代入
    $mail = isset($_POST['mail']) ? $_POST['mail'] : NULL;

    //メール入力判定
    if($mail == ''){
        $errors['mail'] = "メールが入力されていません。";
    }else{
        if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $mail)){
            $errors['mail_check'] = "メールアドレスの形式が正しくありません。";
        }

        /*
        ここで本登録用のmemberテーブルに既に登録されているmailかどうかをチェックする。
        $errors['member_check'] = "このメールアドレスは既に利用されております。";
        */
    }
}

if(count($errors) === 0){
    
    $urltoken = hash('sha256', uniqid(rand(),1));
    $url = "https://tb-220211.tech-base.net/registration_form.php"."?urltoken=".$urltoken;

    /*データレコードを挿入(Mission_4-5参照)*/
    $sql = $pdo -> prepare("INSERT INTO pre_member (urltoken, mail, date) 
    VALUES (:urltoken, :mail,  :date)");
    $sql -> bindParam(':urltoken', $urltoken, PDO::PARAM_STR);
    $sql -> bindParam(':mail', $mail, PDO::PARAM_STR);
    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
    $date = date("Y/m/d/ H:i:s");
    $sql -> execute();


    //以下メール送信機能
    require 'src/Exception.php';
    require 'src/PHPMailer.php';
    require 'src/SMTP.php';
    require 'setting.php';
    // PHPMailerのインスタンス生成
    $mail = new PHPMailer\PHPMailer\PHPMailer();

    $mail->isSMTP(); // SMTPを使うようにメーラーを設定する
    $mail->SMTPAuth = true;
    $mail->Host = 'smtp.gmail.com'; // メインのSMTPサーバー（メールホスト名）を指定
    $mail->Username = 'tbouchikohei@gmail.com'; // SMTPユーザー名（メールユーザー名）
    $mail->Password = 'nu2mzpvm7D'; // SMTPパスワード（メールパスワード）
    $mail->SMTPSecure = 'tls'; // TLS暗号化を有効にし、「SSL」も受け入れます
    $mail->Port = 587; // 接続するTCPポート

    // メール内容設定
    $mail->CharSet = "UTF-8";
    $mail->Encoding = "base64";
    $mail->setFrom('tbouchikohei@gmail.com','大学生学習お役立ちサービス');
    $mail->addAddress( $_POST['mail'], $_POST['mail']); //受信者（送信先）を追加する
    $mail->addReplyTo('tbouchikohei@gmail.com','大学生学習お役立ちサービス');
    $mail->Subject = '【大学生学習お役立ちサービス】会員登録用URLのお知らせ'; // メールタイトル
    $mail->isHTML(true);    // HTMLフォーマットの場合はコチラを設定します
    $body = "下記のURLからご登録下さい。
    $url";

    $mail->Body  = $body; // メール本文
    // メール送信の実行
    if(!$mail->send()) {
    	echo 'メッセージは送られませんでした！';
    	echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
    	echo 'メールをお送りしました。メールに記載されたURLからご登録下さい。';
    }
    //以上メール送信機能
    
        //セッション変数をすべて解除
        $_SESSION = array();

        //クッキーの削除
        if(isset($_COOKIE["PHPSESSID"])){
            setcookie("PHPSESSID", '', time() - 1800, '/');
        }

        //セッションを破棄する
        session_destroy();
}
?>
</body>
</html>