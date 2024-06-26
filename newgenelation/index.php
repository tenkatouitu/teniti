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

//データベース接続
try {
    $pdo = new PDO('mysql:charset=UTF8;dbname=kari;host=127.0.0.1', 'root', '');
} catch (PDOException $e) {
    //接続エラーのときエラー内容を取得する
    $error_message[] = $e->getMessage();
}

//送信して受け取ったデータは$_POSTの中に自動的に入る。
//投稿データがあるときだけログを表示する。
if (!empty($_POST["submitButton"])) {

    //表示名の入力チェック
    if (empty($_POST["username"])) {
        $error_message[] = "お名前を入力してください。";
    } else {
        $escaped['username'] = htmlspecialchars($_POST["username"], ENT_QUOTES, "UTF-8");
    }

    //コメントの入力チェック
    if (empty($_POST["comment"])) {
        $error_message[] = "コメントを入力してください。";
    } else {
        $escaped['comment'] = htmlspecialchars($_POST["comment"], ENT_QUOTES, "UTF-8");
    }

    //エラーメッセージが何もないときだけデータ保存できる
    if (empty($error_message)) {
        // var_dump($_POST);

        //ここからDB追加のときに追加
        $current_date = date("Y-m-d H:i:s");

        //トランザクション開始
        $pdo->beginTransaction();

        try {

            //SQL作成
            $statment = $pdo->prepare("INSERT INTO karit (username, comment, postDate) VALUES (:username, :comment, :current_date)");

            //値をセット
            $statment->bindParam(':username', $escaped["username"], PDO::PARAM_STR);
            $statment->bindParam(':comment', $escaped["comment"], PDO::PARAM_STR);
            $statment->bindParam(':current_date', $current_date, PDO::PARAM_STR);

            //SQLクエリの実行
            $res = $statment->execute();

            //ここまでエラーなくできたらコミット
            $res = $pdo->commit();
        } catch (Exception $e) {
            //エラーが発生したときはロールバック(処理取り消し)
            $pdo->rollBack();
        }

        if ($res) {
            $success_message = "コメントを書き込みました。";
        } else {
            $error_message[] = "書き込みに失敗しました。";
        }

        $statment = null;
    }
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hina+Mincho&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="change">
    <div class="leaves-container">
 <!--<div class="cherry-blossom-container">-->
    <h1 class="gamingbackground" id="title">天一知恵袋</h1>
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
                                <p class="username gamingbackground"><?php echo $value['username'] ?></p>
                                <time>:<?php echo date('Y/m/d H:i', strtotime($value['postDate'])); ?></time>
                                
                            </div>
                            <p class="comment gamingbackground"><?php echo $value['comment']; ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
        <form action="deletepage.php" class="formWrapper">
            <input type="submit" value="削除ページへ">
        </form>
        <form method="POST" action="" class="formWrapper">
            <div>
                <label class="gamingbackground">Name:</label>
                <input type="text" name="username">
            </div>
            <div>
                <textarea name="comment" class="comment"></textarea>
            </div>
            <div>
                <input type="submit" value="投稿"name="submitButton">
            </div>
        </form>
    </div>
    </div>
<!--</div>-->
<script src="main.js"></script>
</body>

</html>
