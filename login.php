<?php
	include "sql.php";
	// $mysql=new mysql("login");
	$mysql=new login(30);
?>
<html>
<head>
	<meta charset="utf-8">
	<title>login</title>
</head>
<body>
	<div><a href="index.php">Kanpai!</a></div>
	<?php if(isset($_POST["email"]) && isset($_POST["pass"])):?>
		<?php $mysql->check_login();?>
	<?php else:?>
		<form method="post">
			<p>ログインID：<input type="text" name="email" value='<?php if(isset($_COOKIE["email"])){echo $_COOKIE["email"];}?>'></p>
			<p>パスワード：<input type="password" name="pass" value='<?php if(isset($_COOKIE["pass"])){echo $_COOKIE["pass"];}?>'></p>
			<p><input type="checkbox" name="save" <?php if(isset($_COOKIE["email"])){echo "checked";}?>>IDとパスワードを保存する</p>
			<input type="submit" value="ログイン">
			<a href="join.php">メールで新規登録する</a>
		</form>
	<?php endif?>
</body>
</html>