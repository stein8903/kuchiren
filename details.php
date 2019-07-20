<?php
	include "sql.php";
	$mysql=new mysql("details");
?>
<?php if(isset($_SESSION["login"])):?>
	<div>
		<img src="<?php echo $mysql->profile.$_SESSION['image'];?>" style="width:20px; height:20px;">
		<a href="profile.php?id=<?php echo $_SESSION['id'];?>"><?php echo $_SESSION["login"];?></a>さん、こんにちは！
	</div>
	<form method="post">
		<input type="submit" value="ログアウト">
		<input type="hidden" name="logout">
	</form>
<?php else:?>
	<a href="login.php?return_p=details.php?id=<?php echo $_GET['id'];?>">ログイン・新規登録</a>
<?php endif?>
<html>
<head>
	<meta charset="utf-8">
	<title>details</title>
	<script src="script.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
</head>
<body>
	<div style="background-color:#ffcd00; color:white; height:40px; display:none; position:fixed; top:0px; left:0px; width:100%;" id="top"></div>
	<div><a href="index.php">Kanpai!</a></div>
	<div id="show"></div>
	<?php
	//入力
	if (isset($_POST["save_join"])) {
		$mysql->details_save("join");
	}
	if (isset($_POST["save_like"])) {
		$mysql->details_save("like");
	}
	if (isset($_POST["comment"])) {
		$mysql->details_save("comment");
	}
	//削除
	if (isset($_POST["delete_join"])) {
		$mysql->details_delete("join");
	}
	if(isset($_POST["delete_like"])){
		$mysql->details_delete("like");
	}
	if (isset($_POST["delete_comment"])) {
		$mysql->details_delete("comment");
	}
	if (isset($_POST["delete_plan"])) {
		$mysql->details_delete("plan");
	}
	?>

	<?php if($mysql->details_check("planner")):?>
		<form method="post" onsubmit="return details.confirm_delete();" name="delete_form">
			<input type="hidden" name="delete_plan">
			<input type="submit" value="この企画を削除する">
		</form>
	<?php endif?>
	<?php foreach ($mysql->details_show("plan") as $value):?>
		<div><audio src="<?php echo $mysql->path.$value['bgm'];?>" autoplay></div>
		<div><?php echo $value["title"];?></div>
		<div><img src="<?php echo $mysql->path.$value['image'];?>" style="width:60px; height:60px;"></div>
		<div><?php echo $value["body"];?></div>
		<div><a href="profile.php?id=<?php echo $value['planner_id'];?>"><?php echo $value["planner"];?></a></div>
		<div><?php echo $value["date_time"];?></div>
	<?php endforeach?><br>

	<?php if(is_array($mysql->details_show("subPlans"))):?>
		<?php foreach ($mysql->details_show("subPlans") as $key => $value) :?>
			<div><img src="<?php echo $mysql->path.$value["image"];?>" style="width:60px; height:60px;"></div>
			<div><?php echo $value["body"];?></div>
			<div></div>
		<?php endforeach?><br>
	<?php endif?>

	<div>
		参加人数：<?php echo $mysql->details_show("count","join");?>
		<?php if(isset($_SESSION["login"])):?>
			<?php if($mysql->details_check("join")):?>
				<div onclick="delete_join.submit()"><a href="##">参加をキャンセルする</a></div>
				<form name="delete_join" method="post">
					<input type="hidden" name="delete_join">
				</form>
			<?php else:?>
				<div onclick="save_join.submit()"><a href="##">この企画に参加する</a></div>
				<form name="save_join" method="post">
					<input type="hidden" name="save_join">
				</form>
			<?php endif?>
		<?php else:?>
			<a href="login.php?return_p=details.php?id=<?php echo $_GET['id'];?>">この企画に参加する</a>
		<?php endif?>
	</div>
	<div>
		お気に入り人数：<?php echo $mysql->details_show("count","like");?>
		<?php if(isset($_SESSION["login"])):?>
			<?php if($mysql->details_check("like")):?>
				<div id="cancle_btn" onclick="delete_like.submit()" style="cursor:pointer;color:red;">お気に入りから削除する</div>
				<form name="delete_like" method="post">
					<input type="hidden" name="delete_like">
				</form>
			<?php else:?>
				<div id="like_btn" onclick="details.likeSend(<?php echo $_SESSION['id'];?>)" style="cursor:pointer;color:blue">お気に入りに入れる</div>
				<!--
				onclick="save_like.submit()"
				<form name="save_like" method="post">
					<input type="hidden" name="save_like">
				</form>
				-->
			<?php endif?>
			<img id="buffering-like" src="images/buffering.gif" style="display:none">
		<?php else:?>
			<a href="login.php?return_p=details.php?id=<?php echo $_GET['id'];?>">お気に入りに入れる</a>
		<?php endif?>
	</div><br>

	<div style="font-weight:bold;">＊コメント一覧＊</div>
	<form method="post">
		<input type="text" name="comment" placeholder="コメントを入力">
	</form>
	<?php if(is_array($mysql->details_show("comment"))):?>
		<?php foreach ($mysql->details_show("comment") as $key => $value) :?>
			<div>
				<a href="profile.php?id=<?php echo $value['commenter_id'];?>"><img src="<?php echo $mysql->profile.$value['image'];?>" style="width:20px; height:20px;"><?php echo $value["commenter"];?></a>：<?php echo $value["comment"];?>
				<?php if($_SESSION["id"]==$value["commenter_id"]):?>
					<form method="post" style="display:inline;">
						<input type="hidden" name="delete_comment" value="<?php echo $value['comment'];?>">
						<input type="submit" value="削除">
					</form>
				<?php endif?>
			</div>
		<?php endforeach?>
	<?php else:?>
		<div>コメントがありません…</div>
	<?php endif?><br>

	<div style="font-weight:bold;">＊この企画に参加予定の人＊</div>
	<?php if (is_array($mysql->details_show("join"))) :?>
		<?php foreach ($mysql->details_show("join") as $key => $value) :?>
			<div><a href="profile.php?id=<?php echo $value['id'];?>"><img src="<?php echo $mysql->profile.$value['image'];?>" style="width:20px; height:20px;"><?php echo $value["name"];?></a></div>
		<?php endforeach?>
	<?php else:?>
		<div>参加予定の人がまだありません…</div>
	<?php endif?><br>
	<div style="font-weight:bold;">＊参加したい人＊</div>
	<?php if (is_array($mysql->details_show("like"))) :?>
		<?php foreach ($mysql->details_show("like") as $key => $value) :?>
			<div><a href="profile.php?id=<?php echo $vaule['id'];?>"><img src="<?php echo $mysql->profile.$value['image'];?>" style="width:20px; height:20px;"><?php echo $value["name"];?></a></div>
		<?php endforeach?>
	<?php else:?>
		<div>お気に入りの人がまだありません…</div>
	<?php endif?>
</body>
</html>