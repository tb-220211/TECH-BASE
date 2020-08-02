<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Mission_5-1</title>
</head>
<body>
    <h1>[NBA掲示板]</h1>
    
    <h3>NBAについて語り合う掲示板</h3>
    <?php
    /*DB接続設定*/
    $dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    /*以下新規投稿機能*/
    /*投稿フォームが全て空でないときに送信ボタンが押されたとき*/
    if($_POST["editing"]==NULL && $_POST["name"]!=NULL && $_POST["comment"]!=NULL
       && $_POST["password"]!=NULL&& $_POST["postsubmit"]!=NULL){
        /*データレコードを挿入(Mission_4-5参照)*/
        $sql = $pdo -> prepare("INSERT INTO Mission_5_1 (name, comment, password, date) 
                                VALUES (:name, :comment, :password, :date)");
	    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':password', $password, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
	    $name = $_POST["name"];
        $comment = $_POST["comment"]; 
        $password = $_POST["password"];
        $date = date("Y/m/d/ H:i:s");
        $sql -> execute();
    }elseif($_POST["editing"]==NULL && $_POST["name"]!=NULL && $_POST["comment"]!=NULL
            && $_POST["password"]==NULL && $_POST["postsubmit"]!=NULL){
           echo "パスワードを入力してください。";
    }elseif($_POST["editing"]==NULL && $_POST["name"]!=NULL && $_POST["comment"]!=NULL
            && $_POST["postsubmit"]!=NULL){
           echo "コメントを入力してください。";
    }elseif($_POST["editing"]==NULL && $_POST["name"]==NULL && $_POST["postsubmit"]!=NULL){
           echo "名前を入力してください。";
    }
    /*以上新規投稿機能*/

    /*以下削除機能*/
    /*削除フォームが全て空でないときに削除ボタンが押されたとき*/
    if($_POST["deletenum"]!=NULL && $_POST["password4delete"]!=NULL
       && $_POST["deletesubmit"]!=NULL){
        /*削除対象のデータレコードを抽出(Mission_4-6参照)*/
        $id = $_POST["deletenum"];
        $sql = 'SELECT * FROM Mission_5_1 WHERE id=:id ';
        $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
        $stmt->execute();                             // ←SQLを実行する。
        $results = $stmt->fetchAll(); 
        foreach ($results as $row){
            if($row['password']==$_POST["password4delete"]){  //パスワードが一致したとき
        	    $id = $_POST["deletenum"];                    //削除対象番号の投稿を指定
        	    $sql ='delete from Mission_5_1 where id=:id'; //削除を実行
        	    $stmt=$pdo->prepare($sql);
	            $stmt->bindParam(':id',$id,PDO::PARAM_INT);
	            $stmt->execute();
	            echo "投稿番号" . $_POST["deletenum"] . "を削除しました。";
            }else{                                            //パスワードが一致しないとき
                echo "パスワードが一致しません。";
            }
        }
    }elseif($_POST["deletenum"]!=NULL && $_POST["password4delete"]==NULL 
            && $_POST["deletesubmit"]!=NULL){
           echo "パスワードを入力してください。";
    }
    //以上削除機能

    /*以下編集対象表示機能*/
    /*編集対象番号とパスワードが空でないとき*/
    if($_POST["editnum"]!=NULL&&$_POST["password4edit"]!=NULL&& $_POST["editsubmit"]!=NULL){
        //編集対象のデータレコードを抽出
        $id = $_POST["editnum"];
        $sql = 'SELECT * FROM Mission_5_1 WHERE id=:id ';
        $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
        $stmt->execute();                             // ←SQLを実行する。
        $results = $stmt->fetchAll(); 
        foreach ($results as $row){
            if($row['password']==$_POST["password4edit"]){   
                $password4edit = $row['password'];    /*パスワードを取得*/   
                $editname = $row['name'];             /*名前を取得*/
                $editcomment = $row['comment'];       /*コメントを取得*/
                echo "投稿番号". $_POST["editnum"] ."を編集中。<br>";
            }else{                                    /*パスワードが一致しないとき*/
                echo "パスワードが一致しません。";
            }
        }     
    }elseif($_POST["editnum"]!=NULL && $_POST["password4edit"]==NULL 
            && $_POST["editsubmit"]!=NULL){
           echo "パスワードを入力してください。";
    }                 
    /*以上編集対象表示機能*/

    /*以下編集投稿機能*/
    /*テキストボックスと他の要素が空でないとき*/
    if($_POST["editing"]!=NULL && $_POST["name"]!=NULL && $_POST["comment"]!=NULL
       && $_POST["password"]!=NULL && $_POST["postsubmit"]!=NULL){    
        /*入力されているデータレコードの内容を編集*/
	    $id = $_POST["editing"];//変更する投稿番号
	    $name = $_POST["name"];
        $comment = $_POST["comment"];//
        $password = $_POST["password"];
        $date = date("Y/m/d/ H:i:s");
	    $sql='UPDATE Mission_5_1 SET name=:name,comment=:comment, 
              password=:password, date=:date WHERE id=:id';
	    $stmt=$pdo->prepare($sql);
	    $stmt->bindParam(':name',$name,PDO::PARAM_STR);
        $stmt->bindParam(':comment',$comment,PDO::PARAM_STR);
        $stmt->bindParam(':password',$password,PDO::PARAM_STR);
        $stmt->bindParam(':date',$date,PDO::PARAM_STR);
        $stmt->bindParam(':id',$id,PDO::PARAM_STR);
	    $stmt->execute();          
        echo "投稿番号". $_POST["editing"] ."を編集しました。";           
    }
        /*以上編集投稿機能*/  
    ?>

<form action=""method="post">
        <!--送信フォーム-->
        [投稿フォーム]<br>
        <p>
        <input type="text" name="name" value="<?php echo $editname ?>" 
         placeholder="名前">
        <input type=text name="comment" value="<?php echo $editcomment ?>" 
         placeholder="コメント">
        <input type="hidden" name="editing" value="<?php echo $_POST["editnum"] ?>" >
        <input type="number" name="password" value="<?php echo $password4edit ?>"
         placeholder="パスワード">
        <input type="submit" name="postsubmit">
        </p>
        <!--削除フォーム-->
        [削除フォーム]<br>
        <p>
        <input type="number" name="deletenum" placeholder="削除対象番号">
        <input type="number" name="password4delete" placeholder="パスワード">
        <input type="submit" name="deletesubmit" value="削除">
        </p>
        <!--編集番号指定用フォーム-->
        [編集フォーム]<br>
        <p>
        <input type="number" name="editnum" placeholder="編集対象番号">
        <input type="number" name="password4edit" placeholder="パスワード">
        <input type="submit" name="editsubmit" value="編集">
        </p>
    </form>

    <!--以下表示機能_常に表示-->
    <?php
    $sql = 'SELECT * FROM Mission_5_1';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo "投稿番号[" . $row['id'].'],';
        echo "投稿者：" . $row['name'].'さん,';
        echo "コメント「" . $row['comment'].'」';
		echo $row['date'];
	echo "<hr>";
    }
    ?>
    <!--以上表示機能_常に表示-->
</body>
</html>