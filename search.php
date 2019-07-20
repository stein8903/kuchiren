<?php
	include "sql.php";
	$mysql=new paging("search",5,6);
?>
<?php if(isset($_SESSION["login"])):?>
	<div>
		<img src="../kuchiren3/profile_images/<?php echo $_SESSION['image'];?>" style="width:20px; height:20px;">
		<a href="profile.php?id=<?php echo $_SESSION['id'];?>"><?php echo $_SESSION["login"];?></a>さん、こんにちは！
	</div>
	<form method="post">
		<input type="submit" value="ログアウト">
		<input type="hidden" name="logout">
	</form>
<?php else:?>
	<a href="hisashi_login.php?return_p=index.php">ログイン・新規登録</a>
<?php endif?>
<html>
<head>
	<meta charset="utf-8">
	<title>search</title>
</head>
<body>
	<div><a href="index.php">Kanpai!</a></div>
	<form method="get" action="search.php">
		<input type="search" name="search" placeholder="飲み会を探す！">
		<a href="hisashi_plan.php">企画する</a>
	</form>
	<div>『<?php echo $_GET["search"];?>』に関する検索結果：</div>
	<?php if (is_array($mysql->sql_show())) :?>
		<?php foreach ($mysql->sql_show() as $value):?>
			<div><a href="details.php?id=<?php echo $value['id'];?>"><?php echo $value["title"];?></a></div>
			<div><img src="../kuchiren3/files/<?php echo $value['image'];?>" style="width:30px; height:30px;"></div>
			<div><?php echo $value["body"];?></div>
			<div><a href="profile.php?id=<?php echo $value['planner_id'];?>"><?php echo $value["planner"];?></a></div>
			<div><?php echo $value["date_time"];?></div><br>
		<?php endforeach?>
	<?php else:?>
		<div>該当する飲み会が見つかりません…</div>
	<?php endif?>

	<?php foreach ($mysql->page_list("search",5,6) as $key => $value) :?>
		<?php if($value!=NULL):?>
			<a <?php $mysql->current_page($value);?> href="search.php?search=<?php echo $_GET['search'];?>&page=<?php echo $value;?>"><?php echo $key;?></a>
		<?php endif?>
	<?php endforeach?>
</body>
</html>