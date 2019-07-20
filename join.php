<?php
	include "sql.php";
	$mysql=new join();
?>
<html>
<head>
	<meta charset="utf-8">
	<title>join</title>
	<script src="script.js"></script>
</head>
<body>
	<div><a href="index.php">Kanpai!</a></div>
	<?php if(isset($_POST["name"])):?>
		<?php $mysql->send_mail();?>
	<?php elseif(isset($_GET["email"]) && isset($_GET["pass"]) && isset($_GET["uniq"])):?>
		<?php $mysql->check_mail();?>
	<?php else:?>
		<form method="post" name="join_form" onsubmit="return join_match.check_form();">
			<p>お名前：</p>
			<input type="text" name="name">
			<p>メールアドレス：</p>
			<input type="text" name="email">
			<p>パスワード：</p>
			<input type="password" name="pass">
			<p>パスワードをもう一度入力してください：</p>
			<input type="password" name="pass2"><br><br>
			<input type="submit" value="完了">
		</form>
	<?php endif?>
</body>
</html>