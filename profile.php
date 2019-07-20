<?php
	include "sql.php";
	$mysql=new mysql("profile");
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
	<a href="login.php?return_p=profile.php?id=<?php echo $_GET['id'];?>">ログイン・新規登録</a>
<?php endif?>
<html>
<head>
	<meta charset="utf-8">
	<title>profile</title>
</head>
<body>
	<div><a href="index.php">Kanpai!</a></div>
	<!---->
	<?php $user_info=$mysql->profile_user_info();?>
	<div>
		<div><img src="<?php echo $mysql->profile.$user_info['image'];?>"></div>
		<div style="font-weight:bold"><?php echo $user_info["name"];?></div>
		<div>性別：<?php echo $user_info["gender"];?></div>
		<div>誕生日：<?php echo $user_info["birth"];?></div>
		<div>自己紹介：<?php echo $user_info["intro"];?></div>
		<div><a href="setting.php?id=<?php echo $_GET['id'];?>">プロフィールを編集する</a></div>
	</div><br>

	<!---->
	<div>
		<div>作成した企画：<?php echo $mysql->profile_count_plan("plan");?></div>
		<div>参加した企画：<?php echo $mysql->profile_count_plan("join");?></div>
		<div>お気に入りの企画：<?php echo $mysql->profile_count_plan("like");?></div>
	</div><br>

	<!---->
	<div>
		<div style="color:red;">作成した企画：</div>
		<?php if (is_array($mysql->profile_show_plan("plan"))):?>
			<?php foreach ($mysql->profile_show_plan("plan") as $key => $value):?>
				<div><a href="details.php?id=<?php echo $value['id'];?>"><?php echo $value["title"];?></a></div>
				<div><img src="<?php echo $mysql->path.$value['image'];?>" style="width:30px; height:30px;"></div>
				<div><?php echo $value["body"];?></div>
				<div><a href="profile.php?id=<?php echo $value['planner_id'];?>"><?php echo $value["planner"];?></a></div>
				<div><?php echo $value["date_time"];?></div><br>
			<?php endforeach?>
		<?php else:?>
			<div>作成した企画がありません…</div>
		<?php endif?>
		<div style="color:red;">参加した企画：</div>
		<?php if (is_array($mysql->profile_show_plan("join"))):?>
			<?php foreach ($mysql->profile_show_plan("join") as $key => $value):?>
				<div><a href="details.php?id=<?php echo $value['id'];?>"><?php echo $value["title"];?></a></div>
				<div><img src="<?php echo $mysql->path.$value['image'];?>" style="width:30px; height:30px;"></div>
				<div><?php echo $value["body"];?></div>
				<div><a href="profile.php?id=<?php echo $value['planner_id'];?>"><?php echo $value["planner"];?></a></div>
				<div><?php echo $value["date_time"];?></div><br>
			<?php endforeach?>
		<?php else:?>
			<div>作成した企画がありません…</div>
		<?php endif?>
		<div style="color:red;">お気に入りの企画：</div>
		<?php if (is_array($mysql->profile_show_plan("like"))):?>
			<?php foreach ($mysql->profile_show_plan("like") as $key => $value):?>
				<div><a href="details.php?id=<?php echo $value['id'];?>"><?php echo $value["title"];?></a></div>
				<div><img src="<?php echo $mysql->path.$value['image'];?>" style="width:30px; height:30px;"></div>
				<div><?php echo $value["body"];?></div>
				<div><a href="profile.php?id=<?php echo $value['planner_id'];?>"><?php echo $value["planner"];?></a></div>
				<div><?php echo $value["date_time"];?></div><br>
			<?php endforeach?>
		<?php else:?>
			<div>作成した企画がありません…</div>
		<?php endif?>
	</div>
</body>
</html>