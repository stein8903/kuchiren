<?php
	include "sql.php";
	$paging = new paging3("index",12,6);
?>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>index</title>
	<!--Bootstrap-->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

	<script src="script.js"></script>
	<script>
		// apple.type="window";
		// apple.color = "black";
		// apple.getInfo();

		// もっと読み込む機能
		// window.onload = function(){
		// 	showMore.start_count();
		// 	showMore.scroll_request();
		// }
	</script>
</head>
<body>
	<!--ヘッダー-->
	<div class="container">
		<div class="jumbotron">
		<div><a href="index.php">Kanpai!</a></div>
		<form method="get" action="search.php">
			<input type="search" name="search" placeholder="飲み会を探す！">
			<a href="plan.php">企画する</a>
		</form>
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
			<a href="login.php?return_p=index.php">ログイン・新規登録</a>
		<?php endif?>
		</div>
	</div>

	<!--コンテンツ-->
	<div class="container">
		<div class="row">
			<?php foreach ($paging->sql_show() as $value) :?>
				<div class="col-xs-12 col-sm-4" style="margin-bottom:40px;">
					<div class="thumbnail">
						<img src="<?php echo $paging->path.$value['image'];?>" style="width:100; height;100px;">
						<div class="caption">
							<div><a href="details.php?id=<?php echo $value['id'];?>"><?php echo $value["title"];?></a></div>
							<div><?php echo $value["body"];?></div>
							<div><a href="profile.php?id=<?php echo $value['planner_id'];?>"><?php echo $value["planner"];?></a></div>
							<div><?php echo $value["date_time"];?></div>
						</div>
					</div>
				</div>
			<?php endforeach?>
		</div>
	</div>

	<!--ページングリスト-->
	<div class="container">
		<div id="position"></div>
		<?php foreach ($paging->page_list() as $key => $value) :?>
			<?php if ($value!=NULL) :?>
				<a <?php paging::current_page($value);?> href="index.php?page=<?php echo $value;?>"><?php echo $key;?></a>
			<?php endif?>
		<?php endforeach?>


		<form id="frm">
		<p><input type="text" id="page" name="page"></p>
		</form>
		<p><button id="btn" onclick="showMore.request();">Load</button></p>
		<div id="position"></div>


		<p><button onclick="speak1()">Load</button></p>
	</div>

  <!--フッダー-->
  <div class="footer">
  	<div class="container">
    	<p class="text-muted">Place sticky footer content here.</p>
  </div>
  </div>
</body>
</html>