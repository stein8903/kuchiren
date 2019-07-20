<?php
	include "sql.php";
	$mysql=new mysql("plan");
?>
<?php if(isset($_SESSION["login"])):?>
	<div>
		<img src="<?php echo $mysql->profile.$_SESSION['image'];?>" style="width:20px; height:20px;">
		<a hreF="profile.php?id=<?php echo $_SESSION['id'];?>"><?php echo $_SESSION["login"];?></a>さん、こんにちは！
	</div>
	<form method="post">
		<input type="submit" value="ログアウト">
		<input type="hidden" name="logout">
	</form>
<?php endif?>
<html>
<head>
	<meta charset="utf-8">
	<title>plan</title>
	<script src="script.js"></script>
</head>
<body>
	<div><a href="index.php">Kanpai!</a></div>
	<?php if(isset($_POST["title"]) && isset($_POST["body"]) && isset($_FILES["upfile"])):?>
		<?php $mysql->insert_plans2();?>
	<?php else:?>
		<form method="post" enctype="multipart/form-data">
			<div id="plan_form">
				<p>タイトル</p>
				<!-- <input type="text" name="title[0]"> -->
				<textarea rows="5" maxlength="50" name="title"></textarea>
				<p>飲み会概要</p>
				<textarea rows="10" maxlength="100" name="body[0]"></textarea><br>
				横長：<input type="file" name="upfile[0]"><br>
				縦長：<input type="file" name="upfile[1]"><br>
				<!--音楽ファイル追加-->
				BGM：<input type="file" name="upfile[2]"><br></br>

				<!--<p>スライド１</p>
				<textarea rows="10" maxlength="100" name="body[1]"></textarea><br>
				<input type="file" name="upfile[1]"><br><br>-->
			</div>

			<br><br><input type="button" onclick="plan.addArticle();" value="スライドを追加する"><br><br>
			<input type="submit" value="投稿"><input type="reset" name="リセット">
		</form>
	<?php endif?>
</body>
</html>