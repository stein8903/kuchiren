<?php
	include "sql.php";
	$mysql=new mysql("setting");
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
<?php endif?>
<html>
<head>
	<meta charset="utf-8">
	<title>setting</title>
</head>
<body>
	<div><a href="index.php">Kanpai!</a></div>
	<?php if (isset($_POST["gender"])) {
		$mysql->setting_update();
	}?>
	<?php $user_info=$mysql->setting_user_info();?>
	<form method="post">
		<div>名前：<input type="text" readonly="readonly" value="<?php echo $user_info['name'];?>"></div>
		
		<div>ID：<input type="text" readonly="readonly" value="<?php echo $user_info['id'];?>"></div>
		
		<div>プロフィール画像：<img src="<?php echo $mysql->profile.$user_info['image']?>"></div><br>
		
		<div>性別：
			<select name="gender">
				<option value="男" <?php if($user_info["gender"]=="男"){echo "selected";}?>>男性</option>
				<option value="女" <?php if($user_info["gender"]=="女"){echo "selected";}?>>女性</option>
			</select>
		</div><br>

		<div>生年月日：
			<select name="year">
				<?php $now=date("Y",time());?>
				<?php for ($i=$now-100; $i <=$now ; $i++) :?>
					<option value="<?php echo $i;?>" <?php if($user_info["year"]==$i){echo "selected";}?>><?php echo $i;?></option>
				<?php endfor?>
			</select>年
			<select name="month">
				<?php for ($i=1; $i <=12 ; $i++) :?>
					<option value="<?php echo $i;?>" <?php if($user_info["month"]==$i){echo "selected";}?>><?php echo $i;?></option>
				<?php endfor?>
			</select>月
			<select name="day">
				<?php for ($i=1; $i <=31 ; $i++) :?>
					<option value="<?php echo $i;?>" <?php if($user_info["day"]==$i){echo "selected";}?>><?php echo $i;?></option>
				<?php endfor?>
			</select>日
		</div><br>

		<div>自己紹介：<textarea maxlength="10" name="intro"><?php echo $user_info["intro"];?></textarea></div><br>
		<div><input type="submit" value="完了"></div>
	</form>
</body>
</html>