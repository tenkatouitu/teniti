<?php
date_default_timezone_set("Asia/Tokyo");

//変数の初期化
$current_date = null;
$message = array();
$message_array = array();
$success_message = null;
$error_message = array();
$escaped = array();
$pdo = null;
$statment = null;
$res = null;
$flg = false;

//データベース接続
try {
    $pdo = new PDO('mysql:charset=UTF8;dbname=kari;host=127.0.0.1', 'root', '');
} catch (PDOException $e) {
    //接続エラーのときエラー内容を取得する
    $error_message[] = $e->getMessage();
}

//デリート部分　DELETE FROM `karit` WHERE `karit`.`id` = 19
if(!empty($_POST["deletebutton"])){
    echo "デリートボタン押したぜ？？？";
    echo $_POST["idtext"];
      //表示名の入力チェック
      if (empty($_POST["idtext"])) {
        $error_message[] = "IDを入力してください。";
    } else {
        $escaped['idtext'] = htmlspecialchars($_POST["idtext"], ENT_QUOTES, "UTF-8");
    }

     //エラーメッセージが何もないときだけデータ保存できる
     if (empty($error_message)) {
        // var_dump($_POST);

        //ここからDB追加のときに追加
        //$current_date = date("Y-m-d H:i:s");

        //トランザクション開始
        $pdo->beginTransaction();

        try {

            //SQL作成
            $statment = $pdo->prepare("DELETE FROM karit WHERE karit.id = :id");

            //値をセット
            /*$statment->bindParam(':username', $escaped["username"], PDO::PARAM_STR);
            $statment->bindParam(':comment', $escaped["comment"], PDO::PARAM_STR);
            $statment->bindParam(':current_date', $current_date, PDO::PARAM_STR);*/
            $statment->bindParam(':id', $_POST["idtext"], PDO::PARAM_STR);
            //$stmt->bindValue(':id', '花子');

            //SQLクエリの実行
            $res = $statment->execute();

            //IDがなかった場合if文を通る
            $count = $statment->rowCount();
            if($count == 0){
                $flg = false;
                //$error_message[0] ->getMessage();
            }

            //ここまでエラーなくできたらコミット
            $res = $pdo->commit();
        } catch (Exception $e) {
            //エラーが発生したときはロールバック(処理取り消し)
            $pdo->rollBack();
        }

        if($flg){
            header("Location: index.php");
        } else {
            header('Location: deletepage.php');
        }
        $statment = null;
    }
    header("Location: index.php");
}

//DBからコメントデータを取得する
$sql = "SELECT id, username, comment, postDate FROM karit ORDER BY postDate ASC";
$message_array = $pdo->query($sql);


//DB接続を閉じる
$pdo = null;
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>天一知恵袋</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="change">
    <h1 class="gaming" id="title">天一知恵袋</h1>
    <hr>
    <div class="boardWrapper">
        <!-- メッセージ送信成功時 -->
        <?php if (!empty($success_message)) : ?>
            <p class="success_message"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <!-- バリデーションチェック時 -->
        <?php if (!empty($error_message)) : ?>
            <?php foreach ($error_message as $value) : ?>
                <div class="error_message">※<?php echo $value; ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
        <section>
            <?php if (!empty($message_array)) : ?>
                <?php foreach ($message_array as $value) : ?>
                    <article>
                        <div class="wrapper">
                            <div class="nameArea">
                                <span>　ID：</span>
                                <p><?php echo $value['id'] ?></p>
                                <span>名前：</span>
                                <p class="username gaming"><?php echo $value['username'] ?></p>
                                <time>:<?php echo date('Y/m/d H:i', strtotime($value['postDate'])); ?></time>
                                
                            </div>
                            <p class="comment"><?php echo $value['comment']; ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
        <form method="POST" action="" class="formWrapper">
            <div>
                <p>ここに消したい投稿のIDを入力して削除ボタンを押してください。</p>
                <input type="submit" value="削除"name="deletebutton">
                <label class="labelb">ID：</label>
                <input type="text" name="idtext">
            </div>
        </form>
        <form action="index.php" class="formWrapper">
            <input type="submit" value="戻る" name="indexback">
        </form>
        <p>ここは別のページです。</p>
    </div>
</body>

</html>
