<!DOCTYPEhtml>  
<html>  
 <head>  
  <meta charset="utf-8">  
  <title>mission5-1</title>  
 </head>  
 <body> 




<?php 
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード名';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//テーブル作成
	$sql = "CREATE TABLE IF NOT EXISTS newtb"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"//投稿番号
	. "name char(32),"//名前
	. "comment TEXT,"//コメント
	. "date char(64),"//日付
	. "pass char(32)"//パスワード
	.");";
	//SQL文をデータベースへ送信するには"query"メソッドを使用
	$stmt = $pdo->query($sql);

//valueの初期設定
  $get1="";
  $get2="";
  $get3="";
  //$form=$_POST["button"];
if(!empty($_POST["comment"]) && !empty($_POST["name"]) && !empty($_POST["p_submit"])){
  //新規投稿
  if(empty($_POST["hide"])){ 
    $comment=$_POST["comment"]; 
    $name=$_POST["name"];
    //投稿パスワード
    $p_submit=$_POST["p_submit"];
    //日付 
    $postedAt = date('Y年m月d日 H:i:s'); 
	
	
	  //作成したテーブルに、insertを行ってデータを入力する。
	  $sql = $pdo -> prepare("INSERT INTO newtb (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
	  //name等のパラメータに値を入れる
	  $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	  $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	  $sql -> bindParam(':date', $postedAt, PDO::PARAM_STR);
	  $sql -> bindParam(':pass', $p_submit, PDO::PARAM_STR);
	  //実行 insert必須
	  $sql -> execute();
	  }else{
	    $secret=$_POST["hide"];
	    $comment=$_POST["comment"]; 
  	    $name=$_POST["name"];
  	     //投稿パスワード
   	    $p_submit=$_POST["p_submit"];
  	     //日付 
  	    $postedAt = date('Y年m月d日 H:i:s'); 

	    $id = $secret;
	    $sql = 'SELECT pass FROM newtb where id=:id';
	    $stmt = $pdo->prepare($sql);
	    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	    $stmt->execute();
	    //SQLを実行した「結果データ」を取得する($stmt2に代入)処理
	    //データベースから取得した実際のデータが取り出せる。例：$stmt2[‘id’]など
	    $stmt2 = $stmt->fetch();

		if($stmt2["pass"] == $p_submit){
		  //UPDATEによる投稿内容の編集
		  $id = $secret; //変更する投稿番号
		  $sql = 'update newtb set name=:name,comment=:comment,date=:date,pass=:pass where id=:id';
	  	  $stmt = $pdo->prepare($sql);
	  	  $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	  	  $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	  	  $stmt->bindParam(':date', $postedAt, PDO::PARAM_STR);
	  	  $stmt->bindParam(':pass', $p_submit, PDO::PARAM_STR);
	  	  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	  	  $stmt->execute();
	  	  }
	}//else

//削除	
	}elseif(!empty($_POST["num"]) && !empty($_POST["p_delete"])){ 
	  //削除番号 
	  $delete=$_POST["num"];
	  //削除パスワード
	  $p_delete=$_POST["p_delete"];
	  $id = $delete;
	  //SELECT <問い合わせ内容>
 	  // FROM [<データベース>.]<テーブル>
	  // [WHERE <条件>];
	  $sql = 'SELECT pass FROM newtb where id=:id';
	  $stmt = $pdo->prepare($sql);
	  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	  $stmt->execute();
	  //SQLを実行した「結果データ」を取得する処理
	  $stmt2 = $stmt->fetch();
		if($stmt2['pass'] == $p_delete){
		  $id = $delete;
		  $sql = 'DELETE FROM newtb WHERE id=:id';
		  $stmt = $pdo->prepare($sql);
		  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
		  $stmt->execute();
		}


		//編集
		}elseif(!empty($_POST["edit"]) && !empty($_POST["p_edit"])){ 
		  //編集番号　変数代入 
		  $edinum=$_POST["edit"]; 
		  $p_edit=$_POST["p_edit"];
		  $id = $edinum;
		  $sql = 'SELECT pass FROM newtb where id=:id';
		  $stmt = $pdo->prepare($sql);
		  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
		  $stmt->execute();
		  $stmt2 = $stmt->fetch();

			if($stmt2['pass'] == $p_edit){
			  //SELECTによる名前の取得
			  $id = $edinum;
			  $sql = 'SELECT name FROM newtb where id=:id';
			  $stmt = $pdo->prepare($sql);
			  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
			  $stmt->execute();
			  $ediname = $stmt->fetch();
			  //SELECTによるコメントの取得
			  $id = $edinum;
			  $sql = 'SELECT comment FROM newtb where id=:id';
			  $stmt = $pdo->prepare($sql);
			  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
			  $stmt->execute();
			  $edicom = $stmt->fetch();
			  //編集対象の投稿をフォームに表示
			  $get1=$ediname['name'];
			  $get2=$edicom['comment'];
			  $get3=$edinum;
			}//if
		}//elseif

?>


<p>掲示板のお題</p><h1>好きな教科</h1>
<form method="post" action="mission_5-1.php">  
  <input type="text" name="name" placeholder="名前" value="<?php if(isset($get1)){echo $get1;} ?>"></br>  
  <input type="text" name="comment" placeholder="コメント" value="<?php if(isset($get2)){echo $get2;} ?>"></br>
  <input type="text" name="p_submit" placeholder="パスワード" value=""/>
  <input type="submit" name="button" value= "送信"></br>
  <input type="hidden" name="hide" placeholder="隠れる" value="<?php if(isset($get3)){echo $get3;} ?>"></br> 

</form> 
 <form method="post" action="mission_5-1.php"> 
 <input type="text" name="num" placeholder="削除番号" value=""/></br> 
 <input type="text" name="p_delete" placeholder="パスワード" value=""/>
 <input type="submit" name="button" value= "削除"></br> 

</form> 
 <form method="post" action="mission_5-1.php"> 
 <input type="text" name="edit" placeholder="編集対象番号" value=""/></br> 
 <input type="text" name="p_edit" placeholder="パスワード" value=""/>
 <input type="submit" name="button" value= "編集"></br> 

<?php
  if(isset($_POST['edit']) || isset($_POST['name']) || isset($_POST['num'])){
//入力したデータをselectにより表示する
  $sql = 'SELECT * FROM newtb';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['date'].'<br>';
	echo "<hr>";
	}
  }
?>

</body> 
</html>