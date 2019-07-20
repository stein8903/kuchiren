<?php
include "config.php";

class paging2 extends user_info{

	public $page_name;
	public $rows_num;
	public $p_list_num;
	public $table;

	public $page;
	public $page_count;
	public $all_rows_count;

	function __construct($a,$b,$c=5,$d){
		parent::__construct();
		$this->page_name = $a;
		$this->rows_num = $b;
		$this->p_list_num = $c;
		if (empty($d)) {
			$this->table = $this->plan;
		}else{
			$this->table = $d;
		}

		//$page;
		if (isset($_POST["page"])) {
			$this->page = $_POST["page"];
		}else{
			$this->page = isset($_GET["page"]) ? $_GET["page"] : "1";
		}

		//$all_rows_count, $page_count;
		$search = $_GET["search"];
		if ($this->page_name=="index") {
			$sql="SELECT * FROM $this->table";
		}else if ($this->page_name=="search") {
			$sql="SELECT * FROM $this->table WHERE title LIKE '%$search%'";
		}
		$result=mysql_db_query($this->db, $sql);
		$this->page_count = ceil(mysql_num_rows($result)/$this->rows_num);
		$this->all_rows_count = mysql_num_rows($result);
	}

	function sql_show(){
		$search=$_GET["search"];
		if ($this->page==0 || $this->page > $this->page_count || !ctype_digit($this->page)) {
			if ($this->page_count!=0) {
				echo "当ページは利用できません…";
				exit();
			}
		}

		$limit = $this->page * $this->rows_num - $this->rows_num;
		if ($this->page_name=="index") {
			$sql="SELECT * FROM $this->table ORDER BY date_time DESC LIMIT $limit,$this->rows_num";
		}else if ($this->page_name=="search") {
			$sql="SELECT * FROM $this->table WHERE title LIKE '%$search%' ORDER BY date_time DESC LIMIT $limit,$this->rows_num";
		}
		$result=mysql_db_query($this->db, $sql);
		while ($rows=mysql_fetch_assoc($result)) {
			$id=$rows["id"];
			$title=$rows["title"];
			$image=$rows["image"];
			$body=$rows["body"];
			$planner=$rows["planner"];
			$planner_id=$rows["planner_id"];
			$date_time=substr($rows["date_time"], 0,16);
			$data_array[]=array("id"=>$id,"title"=>$title,"image"=>$image,"body"=>$body,"planner"=>$planner,"planner_id"=>$planner_id,"date_time"=>$date_time);
		}
		return $data_array;
	}

	function page_list(){
		$index_count=ceil($this->page_count / $this->p_list_num);
		$start = 1;
		$for = $this->p_list_num + 1;
		$back = 0;
		for ($i=0; $i < $index_count; $i++) { 
			if ($this->page>=$start && $this->page<=$start+$this->p_list_num-1) {
				if ($back>0) {
					$back_p = $back;
				}
				for ($i=0; $i < $this->p_list_num; $i++) { 
					$start_p[] = $start;
					if ($start >= $this->page_count) {
						break;
					}
					$start++;
				}
				if ($for<=$this->page_count) {
					$for_p = $for;
				}else{
					break;
				}
			}
			$start+=$this->p_list_num;
			$for+=$this->p_list_num;
			$back+=$this->p_list_num;
		}
		$data_array=array("<<"=>$back_p);
		for ($i=0; $i <$this->p_list_num ; $i++) { 
			$data_array+=array($start_p[$i]=>$start_p[$i]);
		}
		$data_array+=array(">>"=>$for_p);
		return $data_array;
	}

	static function current_page($value){
		$page = isset($_GET["page"]) ? $_GET["page"] : 1;
		if ($value==$page) {
			echo "style=background-color:red; color:white;";
		}
	}

	function last(){
		$last_article = $this->all_rows_count % $this->rows_num;
		return $data_array=array("page_count"=>$this->page_count,"last_article"=>$last_article);
	}
}

class paging3 extends user_info{

	public $paging;

	function __construct($a,$b,$c){
		parent::__construct();
		$table = $this->plan;
		$this->paging = new paging2($a,$b,$c,$table);
	}

	function sql_show(){
		return $this->paging->sql_show();
	}

	function page_list(){
		return $this->paging->page_list();
	}
}

class paging extends user_info{

	public $page_name;
	public $col_num;
	public $p_list_num;
	public $table;

	function __construct($a, $b, $c=6){
		parent::__construct();
		$this->page_name = $a;
		$this->col_num = $b;
		$this->p_list_num = $c;
		$this->table = $this->plan;
	}

	function sql_show(){
		$search=$_GET["search"];
		if (isset($_POST["page"])) {
			$page=$_POST["page"];
		}else{
			$page=isset($_GET["page"]) ? $_GET["page"] : "1";
		}
		if($this->page_name=="index"){
			$sql="SELECT * FROM $this->table";
		}else if ($this->page_name=="search") {
			$sql="SELECT * FROM $this->table WHERE title LIKE '%$search%'";
		}
		$result=mysql_db_query($this->db, $sql);
		$page_count=ceil(mysql_num_rows($result)/$this->col_num);
		if ($page==0 || $page>$page_count || !ctype_digit($page)) {
			if ($page_count!=0) {
				echo "当ページは利用できません…";
				exit();
			}
		}
		$limit=$page * $this->col_num - $this->col_num;
		if ($this->page_name=="index") {
			$sql="SELECT * FROM $this->table ORDER BY date_time DESC LIMIT $limit,$this->col_num";
		}else if ($this->page_name=="search") {
			$sql="SELECT * FROM $this->table WHERE title LIKE '%$search%' ORDER BY date_time DESC LIMIT $limit,$this->col_num";
		}
		$result=mysql_db_query($this->db, $sql);
		while ($rows=mysql_fetch_array($result)) {
			$id=$rows["id"];
			$title=$rows["title"];
			$image=$rows["image"];
			$body=$rows["body"];
			$bgm=$rows["bgm"];
			$planner_id=$rows["planner_id"];
			$planner=$rows["planner"];
			$date_time=substr($rows["date_time"], 0,16);
			$data_array[]=array("id"=>$id,"title"=>$title,"image"=>$image,"body"=>$body,"bgm"=>$bgm,"planner_id"=>$planner_id,"planner"=>$planner,"date_time"=>$date_time);
		}
		return $data_array;
	}

	function page_list(){
		$search=$_GET["search"];
		$page=isset($_GET["page"]) ? $_GET["page"] : 1;
		if ($this->page_name=="index") {
			$sql="SELECT * FROM $this->table";
		}else if ($this->page_name=="search") {
			$sql="SELECT * FROM $this->table WHERE title LIKE '%$search%'";
		}
		$result=mysql_db_query($this->db, $sql);
		$page_count=ceil(mysql_num_rows($result) / $this->col_num);
		$index_count=ceil($page_count / $this->p_list_num);
		$start=1;
		$for=$this->p_list_num+1;
		$back=0;
		for ($i=0; $i <$index_count ; $i++) { 
			if ($page>=$start && $page<=$start+$this->p_list_num-1) {
				if ($back>0) {
					$back_p=$back;
				}
				for ($i=0; $i <$this->p_list_num ; $i++) { 
					$start_p[]=$start;
					if ($start>=$page_count) {
						break;
					}
					$start++;
				}
				if ($for<=$page_count) {
					$for_p=$for;
				}else{
					break;
				}
			}
			$start+=$this->p_list_num;
			$for+=$this->p_list_num;
			$back+=$this->p_list_num;
		}
		$data_array=array("<<"=>$back_p);
		for ($i=0; $i <$this->p_list_num ; $i++) { 
			$data_array+=array($start_p[$i]=>$start_p[$i]);
		}
		$data_array+=array(">>"=>$for_p);
		return $data_array;
	}

	static function current_page($value){
		$page=isset($_GET["page"]) ? $_GET["page"] : 1;
		if ($value==$page) {
			echo "style='background-color:red; color:white;'";
		}
	}
}

// class paging2 extends paging{

// 	function sql_show(){
// 		parent::sql_show();
// 	}
// 	function page_list(){
// 		parent::page_list();
// 	}
// }

class login extends user_info{

	function __construct($ex_day=1){
		parent::__construct();
		if (isset($_POST["email"]) && isset($_POST["pass"]) && isset($_POST["save"])) {
			$email=$_POST["email"];
			$pass=$_POST["pass"];
			$sql="SELECT * FROM $this->login WHERE email='$email' AND pass='$pass'";
			$result=mysql_db_query($this->db, $sql);
			if (mysql_num_rows($result)) {
				$expire = time() + 60 * 60 * 24 * $ex_day;
				setcookie("email",$email,$expire,"/kuchiren5/login.php");
				setcookie("pass",$pass,$expire,"/kuchiren5/login.php");
			}
		}
		if (isset($_POST["email"]) && isset($_POST["pass"]) && !isset($_POST["save"])) {
			setcookie("email","",time()-1,"/kuchiren5/login.php");
			setcookie("pass","",time()-1,"/kuchiren5/login.php");
		}
		if (isset($_SESSION["login"])) {
			echo "<script>alert('既にログインされています')</script>";
			echo "<meta http-equiv='refresh' content='0.1; url=index.php'>";
			exit();
		}
	}

	function check_login(){
		$email=$_POST["email"];
		$pass=$_POST["pass"];
		$sql="SELECT * FROM $this->login WHERE email='$email' AND pass='$pass'";
		$result=mysql_db_query($this->db, $sql);
		if (mysql_num_rows($result)) {
			echo "ログインに成功しました！";
			$rows=mysql_fetch_assoc($result);
			$_SESSION["login"] = $rows["name"];
			$_SESSION["id"] = $rows["id"];
			$_SESSION["image"] = $rows["image"];
			if (isset($_GET["return_p"])) {
				echo "<meta http-equiv='refresh' content='0.1; url={$_GET["return_p"]}'>";
			}else{
				echo "<meta http-equiv='refresh' content='0.1; url=index.php'>";
			}
		}else{
			echo "存在しないIDか、パスワードが違います<br>";
			echo "<input type='button' onclick='history.back();' value='戻る'>";
		}
	}
}

class join extends user_info{

	function __construct(){
		parent::__construct();
		if (isset($_SESSION["login"]) && !isset($_GET["uniq"])) {
			echo "<script>alert('既にログインされています！');</script>";
			echo "<meta http-equiv='refresh' content='0.1; url=index.php'>";
			exit();
		}
	}

	function send_mail(){
		$name=$_POST["name"];
		$email=$_POST["email"];
		$pass=$_POST["pass"];
		$sql="SELECT * FROM $this->login WHERE email='$email'";
		$result=mysql_db_query($this->db, $sql);
		if (mysql_num_rows($result)) {
			echo "既に同名のアドレスで登録されております<br>";
			echo "<input type='button' onclick='history.back();' value='戻る'>";
		}else{
			echo "確認用のメールを送信しました！";
			$uniq=uniqid();
			$url_check=$this->domain."join.php?name=".$name."&email=".$email."&pass=".$pass."&uniq=".$uniq;
			$sql="INSERT INTO $this->preLogin(name,email,pass,uniq) VALUES('$name','$email','$pass','$uniq')";
			mysql_db_query($this->db, $sql);
			//メール送信
			$subject = "[steins-t]".$name."さんに登録確認のお願い";
			$message = <<<EOM
				<html>
				<head>
					<style>
						div{
							margin-bottom:20px;
						}
					</style>
				</head>
				<body>
					<div>{$name}さん</div>
					<div>steins-t事務局です。</div>
					<div>{$name}さんのユーザー登録を受付ました。\n以下のアドレスをクリックすることで、登録確認が完了します。</div>
					<div>
						<p>【確認用URL:】</p>
						<p><a href="{$url_check}">{$url_check}</a></p>
					</div>
				</body>
				</html>
EOM;
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=ISO-2022-JP' . "\r\n";
			$headers .= 'From: steins-t事務局 <info@steins-t.com>' . "\r\n";
			mail($email, $subject, $message, $headers);
		}
	}

	function check_mail(){
		$name=$_GET["name"];
		$email=$_GET["email"];
		$pass=$_GET["pass"];
		$uniq=$_GET["uniq"];
		$sql="SELECT * FROM $this->preLogin WHERE name='$name' AND email='$email' AND pass='$pass' AND uniq='$uniq'";
		$result=mysql_db_query($this->db, $sql);
		if (mysql_num_rows($result)) {
			echo "認証に成功しました！";
			$sql="INSERT INTO $this->login(id,email,pass,name,image,date_time) VALUES(NULL,'$email','$pass','$name','profile_image.png',sysdate())";
			mysql_db_query($this->db, $sql);
			$sql="DELETE FROM $this->preLogin WHERE email='$email'";
			mysql_db_query($this->db, $sql);
			$sql="SELECT * FROM $this->login WHERE email='$email'";
			$result=mysql_db_query($this->db, $sql);
			$rows=mysql_fetch_assoc($result);
			$_SESSION["id"]=$rows["id"];
			$_SESSION["login"]=$rows["name"];
			$_SESSION["image"]="profile_image.png";
			mb_language("Japanese");
			mb_internal_encoding("UTF-8");
			mb_send_mail($email, "本登録完了しました", "おめでとうございます。本登録完了しました！","From:info@steins-t.com");
			echo "<meta http-equiv='refresh' content='0.1; url=index.php'>";
		}else{
			echo "認証に失敗しました！";
		}
	}
}

class mysql extends user_info{

	function __construct($a="1"){
		parent::__construct();//親クラスのコンストラクタを呼び出す
		if ($a=="plan") {
			if (!isset($_SESSION["login"])) {
				echo "<script>alert('先にログインしてください')</script>";
				echo "<meta http-equiv='refresh' content='0.1; url=login.php?return_p=plan.php'>";
				exit();
			}
		}
		if ($a=="login") {
			if(isset($_POST["email"]) && isset($_POST["pass"]) && isset($_POST["save"])){
				$email=$_POST["email"];
				$pass=$_POST["pass"];
				$sql="SELECT * FROM $this->login WHERE email='$email' AND pass='$pass'";
				$result=mysql_db_query($this->db, $sql);
				if (mysql_num_rows($result)) {
					setcookie("email",$email,time()+3600,"/kuchiren5/login.php");
					setcookie("pass",$pass,time()+3600,"/kuchiren5/login.php");
				}
			}
			if(isset($_POST["email"]) && isset($_POST["pass"]) && !isset($_POST["save"])){
				setcookie("email",$email,time()-1,"/kuchiren5/login.php");
				setcookie("pass",$pass,time()-1,"/kuchiren5/login.php");
			}
			if (isset($_SESSION["login"])) {
				echo "<script>alert('既にログインされています！');</script>";
				echo "<meta http-equiv='refresh' content='0.1; url=index.php'>";
				exit();
			}
		}
		if ($a=="join") {
			if (isset($_SESSION["login"]) && !isset($_GET["uniq"])) {
				echo "<script>alert('既にログインされています！');</script>";
				echo "<meta http-equiv='refresh' content='0.1; url=index.php'>";
				exit();
			}
		}
		if ($a=="setting") {
			if ($_SESSION["id"]!=$_GET["id"]) {
				echo "<script>alert('不正なアクセスです！')</script>";
				echo "<meta http-equiv='refresh' content='0.1; url=index.php'>";
				exit();
			}
		}
		if ($a=="profile") {
			$id=$_GET["id"];
			$sql="SELECT * FROM $this->login WHERE id='$id'";
			$result=mysql_db_query($this->db, $sql);
			if (!mysql_num_rows($result)) {
				echo "<script>alert('退会されたユーザーです')</script>";
				echo "<script>history.back();</script>";
				exit();
			}
		}
		if ($a=="details") {
			$id=$_GET["id"];
			$sql="SELECT * FROM $this->plan WHERE id='$id'";
			$result=mysql_db_query($this->db, $sql);
			if (!mysql_num_rows($result)) {
				echo "<script>alert('存在しない企画です');</script>";
				echo "<meta http-equiv='refresh' content='0.1; url=index.php'>";
				exit();
			}
		}
	}
	/*------------------------Index,Search---------------------------*/
	function sql_show($a,$b){
		$search=$_GET["search"];
		if (isset($_POST["page"])) {
			$page=$_POST["page"];
		}else{
			$page=isset($_GET["page"]) ? $_GET["page"] : "1";
		}
		if($a=="index"){
			$sql="SELECT * FROM $this->plan";
		}else if ($a=="search") {
			$sql="SELECT * FROM $this->plan WHERE title LIKE '%$search%'";
		}
		$result=mysql_db_query($this->db, $sql);
		$page_count=ceil(mysql_num_rows($result)/$b);
		if ($page==0 || $page>$page_count || !ctype_digit($page)) {
			if ($page_count!=0) {
				echo "当ページは利用できません…";
				exit();
			}
		}
		$limit=$page*$b-$b;
		if ($a=="index") {
			$sql="SELECT * FROM $this->plan ORDER BY date_time DESC LIMIT $limit,$b";
		}else if ($a=="search") {
			$sql="SELECT * FROM $this->plan WHERE title LIKE '%$search%' ORDER BY date_time DESC LIMIT $limit,$b";
		}
		$result=mysql_db_query($this->db, $sql);
		while ($rows=mysql_fetch_array($result)) {
			$id=$rows["id"];
			$title=$rows["title"];
			$image=$rows["image"];
			$body=$rows["body"];
			$planner_id=$rows["planner_id"];
			$planner=$rows["planner"];
			$date_time=substr($rows["date_time"], 0,16);
			$data_array[]=array("id"=>$id,"title"=>$title,"image"=>$image,"body"=>$body,"planner_id"=>$planner_id,"planner"=>$planner,"date_time"=>$date_time);
		}
		return $data_array;
	}




















	// function sql_show($a){
	// 	$search=$_GET["search"];
	// 	$page=isset($_GET["page"]) ? $_GET["page"] : "1";
	// 	if ($a=="index") {
	// 		$sql="SELECT * FROM $this->plan";
	// 	}else if($a=="search"){
	// 		$sql="SELECT * FROM $this->plan WHERE title LIKE '%$search%'";
	// 	}
	// 	$result=mysql_db_query($this->db, $sql);
	// 	$page_count=ceil(mysql_num_rows($result)/3);
	// 	if ($page==0 || $page>$page_count || !ctype_digit($page)) {
	// 		if ($page_count!=0) {
	// 			echo "当ページは利用できません";
	// 			exit();	
	// 		}
	// 	}
	// 	$limit=$page*3-3;
	// 	if ($a=="index") {
	// 		$sql="SELECT * FROM $this->plan ORDER BY date_time DESC LIMIT $limit,3";
	// 	}else if($a=="search"){
	// 		$sql="SELECT * FROM $this->plan WHERE title LIKE '%$search%' ORDER BY date_time DESC LIMIT $limit,3";
	// 	}
	// 	$result=mysql_db_query($this->db, $sql);
	// 	while ($rows=mysql_fetch_assoc($result)) {
	// 		$id=$rows["id"];
	// 		$title=$rows["title"];
	// 		$image=$rows["image"];
	// 		$body=$rows["body"];
	// 		$planner=$rows["planner"];
	// 		$planner_id=$rows["planner_id"];
	// 		$date_time=$rows["date_time"];
	// 		$data_array[]=array("id"=>$id,"title"=>$title,"image"=>$image,"body"=>$body,"planner"=>$planner,"planner_id"=>$planner_id,"date_time"=>$date_time);
	// 	}
	// 	return $data_array;
	// }


	//第２引数を=col_numに更新
	//第３引数を＝p_list_numに更新！
	function page_list($a,$column_num=3,$index_num=3){
		$search=$_GET["search"];
		$page=isset($_GET["page"]) ? $_GET["page"] : 1;
		if ($a=="index") {
			$sql="SELECT * FROM $this->plan";
		}else if ($a=="search") {
			$sql="SELECT * FROM $this->plan WHERE title LIKE '%$search%'";
		}
		$result=mysql_db_query($this->db, $sql);
		$page_count=ceil(mysql_num_rows($result)/$column_num);
		$index_count=ceil($page_count/$index_num);
		$start=1;
		$for=$index_num+1;
		$back=0;
		for ($i=0; $i <$index_count ; $i++) { 
			if ($page>=$start && $page<=$start+$index_num-1) {
				if ($back>0) {
					$back_p=$back;
				}
				for ($i=0; $i <$index_num ; $i++) { 
					$start_p[]=$start;
					if ($start>=$page_count) {
						break;
					}
					$start++;
				}
				if ($for<=$page_count) {
					$for_p=$for;
				}else{
					break;
				}
			}
			$start+=$index_num;
			$for+=$index_num;
			$back+=$index_num;
		}
		$data_array=array("<<"=>$back_p);
		for ($i=0; $i <$index_num ; $i++) { 
			$data_array+=array($start_p[$i]=>$start_p[$i]);
		}
		$data_array+=array(">>"=>$for_p);
		return $data_array;
	}



















	// function page_list($a){
	// 	$search=$_GET["search"];
	// 	$page=isset($_GET["page"]) ? $_GET["page"] : 1;
	// 	if ($a=="index") {
	// 		$sql="SELECT * FROM $this->plan";
	// 	}else if($a=="search"){
	// 		$sql="SELECT * FROM $this->plan WHERE title LIKE '%$search%'";
	// 	}
	// 	$result=mysql_db_query($this->db, $sql);
	// 	$page_count=ceil(mysql_num_rows($result)/3);
	// 	$index_count=ceil($page_count/3);
	// 	$start=1;
	// 	$for=4;
	// 	$back=0;
	// 	for ($i=0; $i <$index_count ; $i++) { 
	// 		if ($page==$start || $page==$start+1 || $page==$start+2) {
	// 			if ($back>0) {
	// 				$back_p=$back;
	// 			}
	// 			for ($i=0; $i < 3; $i++) { 
	// 				$start_p[]=$start;
	// 				if ($start>=$page_count) {
	// 					break;
	// 				}
	// 				$start++;
	// 			}
	// 			if ($for<=$page_count) {
	// 				$for_p=$for;
	// 			}else{
	// 				break;
	// 			}
	// 		}
	// 		$start+=3;
	// 		$for+=3;
	// 		$back+=3;
	// 	}
	// 	return $data_array=array("<<"=>$back_p,$start_p[0]=>$start_p[0],$start_p[1]=>$start_p[1],$start_p[2]=>$start_p[2],">>"=>$for_p);
	// }
	function current_page($value){
		$page=isset($_GET["page"]) ? $_GET["page"] : 1;
		if ($value==$page) {
			echo "style='background-color:red; color:white;'";
		}
	}















	// function current_page($value){
	// 	$page=isset($_GET["page"]) ? $_GET["page"] : 1;
	// 	if($value==$page){
	// 		echo "style='background-color:red; color:white;'";
	// 	}
	// }

	/*-----------------------Plan----------------------------*/
	//複数の画像投稿（まだ）
	function insert_images(){
		foreach ($_FILES["file"]["error"] as $key => $value) {
			if ($value == UPLOAD_ERR_OK) {
				$tmp=$_FILES["file"]["tmp_name"][$key];
				$name=$_FILES["file"]["name"][$key];
				$path=$this->path.$name;
				while (file_exists($path)) {
					$pathinfo=pathinfo($name);
					$name=$pathinfo["filename"].substr(str_shuffle("123"), 0,1).".png";
					$path=$this->path.$name;
				}
				if (move_uploaded_file($tmp, $path)) {
					echo "投稿に成功しました！";
				}else{
					echo "ファイルアップロードに失敗しました！<br>";
				}
			}else{
				echo "ファイルアップロードに失敗しました！<br>";
				echo "<input type='button' value='戻る' onclick='history.back()'>";
			}
		}
		$sql="INSERT INTO $this->plan(id,title,image,body,planner,planner_id,date_time) 
		VALUES(NULL,'$title','$name','$body','$planner','$planner_id',sysdate())";
		mysql_db_query($this->db, $sql);
		$sql="SELECT id FROM $this->plan WHERE planner_id='$planner_id' ORDER BY date_time DESC LIMIT 0,1";
		$result=mysql_db_query($this->db, $sql);
		$rows=mysql_fetch_assoc($result);
		echo $id=$rows["id"];
		echo "<meta http-equiv='refresh' content='2; url=index.php'>";
	}

	//記事作成（普通）
	function insert_plan(){
		$title=$_POST["title"];
		$body=$_POST["body"];
		$tmp=$_FILES["upfile"]["tmp_name"];
		$name=$_FILES["upfile"]["name"];
		$path=$this->path.$name;
		$error=$_FILES["upfile"]["error"];
		$planner=$_SESSION["login"];
		$planner_id=$_SESSION["id"];
		if ($error=="2") {
			echo "指定した画像のサイズが大きすぎます！<br>";
			echo "<input type='button' value='戻る' onclick='history.back();'>";
		}else{
			while (file_exists($path)) {
				$pathinfo=pathinfo($name);
				$name=$pathinfo["filename"].substr(str_shuffle("1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0,1).".png";
				$path=$this->path.$name;
			}
			if (move_uploaded_file($tmp, $path)) {
				$sql="INSERT INTO $this->plan(id,title,image,body,planner,planner_id,date_time) 
				VALUES(NULL,'$title','$name','$body','$planner','$planner_id',sysdate())";
				mysql_db_query($this->db, $sql);
				$sql="SELECT * FROM $this->plan WHERE planner_id='$planner_id' ORDER BY date_time DESC";
				$result=mysql_db_query($this->db, $sql);
				$rows=mysql_fetch_assoc($result);
				$id=$rows["id"];
				echo "<meta http-equiv='refresh' content='0.1; url=details.php?id=$id'>";
			}else{
				echo "ファイルアップロードに失敗しました！<br>";
				echo "<input type='button' onclick='history.back();' value='戻る'>";
			}
		}
	}
	
	function insert_plans2(){
		$title=$_POST["title"];
		$body=$_POST["body"];
		$tmp=$_FILES["upfile"]["tmp_name"];
		$name=$_FILES["upfile"]["name"];
		$error=$_FILES["upfile"]["error"];
		$planner=$_SESSION["login"];
		$planner_id=$_SESSION["id"];
		$body_count=count($name);
		for ($i=0; $i <$body_count ; $i++) { 
			if ($error[$i]=="2") {
				echo $i."番目の画像のサイズが大きすぎます";
				echo "<input type='button' onclick='history.back()' value='戻る'>";
			}else{
				$path=$this->path.$name[$i];
				while (file_exists($path)) {
					$pathinfo=pathinfo($name[$i]);
					if ($pathinfo["extension"]=="mp3") {
						$name[$i]=$pathinfo["filename"].substr(str_shuffle("1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0,1).".mp3";
					}else{
						$name[$i]=$pathinfo["filename"].substr(str_shuffle("1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0,1).".png";
					}
					$path=$this->path.$name[$i];
				}
				if (move_uploaded_file($tmp[$i], $path)) {
					if ($i==0) {
						$sql="INSERT INTO $this->plan(id,title,body,image,planner_id,planner,date_time) 
						VALUES(NULL,'$title','$body[$i]','$name[$i]','$planner_id','$planner',sysdate())";
					}else if ($i==1) {
						$sql="SELECT * FROM $this->plan WHERE planner_id='$planner_id' ORDER BY date_time DESC LIMIT 0,1";
						$result=mysql_db_query($this->db, $sql);
						$row=mysql_fetch_assoc($result);
						$id=$row["id"];
						$sql="UPDATE $this->plan SET hl_image='$name[$i]' WHERE id='$id'";
					}else if ($i==2) {
						$sql="SELECT * FROM $this->plan WHERE planner_id='$planner_id' ORDER BY date_time DESC LIMIT 0,1";
						$result=mysql_db_query($this->db, $sql);
						$row=mysql_fetch_assoc($result);
						$id=$row["id"];
						$sql="UPDATE $this->plan SET bgm='$name[$i]' WHERE id='$id'";
					}else{
						$sql="SELECT * FROM $this->plan WHERE planner_id='$planner_id' ORDER BY date_time DESC LIMIT 0,1";
						$result=mysql_db_query($this->db, $sql);
						$row=mysql_fetch_assoc($result);
						$id=$row["id"];
						$sql="INSERT INTO $this->subPlans(plan_id,id,image,body) 
						VALUES('$id',NULL,'$name[$i]','$body[$i]')";
					}
					mysql_db_query($this->db, $sql);
				}else{
					echo $i."番目のファイルのアップロードに失敗しました";
					echo "<input type='button' onclick='history.back();' value='戻る'>";
					if ($i==0) {
						echo "記事が投稿されませんでした";
						break;
					}
				}
			}
		}
	}
	//ブログ型記事の作成
	function insert_plans(){
		$title=$_POST["title"];
		$body=$_POST["body"];
		$tmp=$_FILES["upfile"]["tmp_name"];
		$name=$_FILES["upfile"]["name"];
		$path=$this->path.$name;
		$error=$_FILES["upfile"]["error"];
		$planner=$_SESSION["login"];
		$planner_id=$_SESSION["id"];


		$body_count=count($name);
		for ($i=0; $i < $body_count; $i++) { 
			if ($error[$i]=="2") {
				echo $i."番目の画像のサイズが大きすぎます<br>";
				echo "<input type='button' onclick='history.back()' value='戻る'>";
			}else{
				$path=$this->path.$name[$i];
				while (file_exists($path)) {
					$pathinfo=pathinfo($name[$i]);
					if ($pathinfo["extension"]=="mp3") {
						$name[$i]=$pathinfo["filename"].substr(str_shuffle("1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0,1).".mp3";
						$path=$this->path.$name[$i];
					}else{
						$name[$i]=$pathinfo["filename"].substr(str_shuffle("1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0,1).".png";
						$path=$this->path.$name[$i];
					}
					//①
					//１番目のアップロードファイルと２番目のアップロードファイルの名前のかぶり対策はなっていない
					//なぜならこの段階ではまだアップロードされていないので、２回目ファイルの番にすでにアップロードしているフォルダーを探しても
					//１番目のファイルの名前はでてこない
					//可能性としては少ないが、いつか時間があれば修正
					//②
					//後消したはずのAUTOKEYが再生される問題で削除されなかったsubplansが読まれてくる
					//企画を削除する際にsubplansも消したら問題ないが、どうせ解決すべき問題！
					//削除カレムを追加！？
				}
				if (move_uploaded_file($tmp[$i], $path)) {
					if ($i==0) {
						$sql="INSERT INTO $this->plan(id,title,body,image,planner,planner_id,date_time) 
						VALUES(NULL,'$title[$i]','$body[$i]','$name[$i]','$planner','$planner_id',sysdate())";
					}else if($i==1){
						$sql="SELECT id FROM $this->plan WHERE planner_id='$planner_id' ORDER BY date_time DESC LIMIT 0,1";
						$result=mysql_db_query($this->db, $sql);
						$row=mysql_fetch_assoc($result);
						$id=$row["id"];
						$sql="UPDATE $this->plan SET hl_image='$name[$i]' WHERE id='$id'";
					}else if($i==2){
						$sql="SELECT id FROM $this->plan WHERE planner_id='$planner_id' ORDER BY date_time DESC LIMIT 0,1";
						$result=mysql_db_query($this->db, $sql);
						$row=mysql_fetch_assoc($result);
						$id=$row["id"];
						$sql="UPDATE $this->plan SET bgm='$name[$i]' WHERE id='$id'";
					}else{
						$sql="SELECT id FROM $this->plan WHERE planner_id='$planner_id' ORDER BY date_time DESC LIMIT 0,1";
						$result=mysql_db_query($this->db, $sql);
						$row=mysql_fetch_assoc($result);
						$plan_id=$row["id"];
						$sql="INSERT INTO $this->subPlans(plan_id,id,image,body) 
						VALUES('$plan_id',NULL,'$name[$i]','$body[$i]')";
					}
					mysql_db_query($this->db, $sql);
				}else{
					echo $i."番目の画像アップロードに失敗しました！<br>";
					echo "<input type='button' onclick='history.back()' value='戻る'>";
				}
			}
		}
	}
























	
	// function inser_plan(){
	// 	$title=$_POST["title"];
	// 	$body=$_POST["body"];
	// 	$tmp=$_FILES["upfile"]["tmp_name"];
	// 	$name=$_FILES["upfile"]["name"];
	// 	$path=$this->path.$name;
	// 	$error=$_FILES["upfile"]["error"];
	// 	$planner=$_SESSION["login"];
	// 	$planner_id=$_SESSION["id"];
	// 	if ($error=="2") {
	// 		echo "指定した画像のサイズが大きすぎます！<br>";
	// 		echo "<input type='button' value='戻る' onclick='history.back();'>";
	// 	}else{
	// 		while (file_exists($path)) {
	// 			$pathinfo=pathinfo($name);
	// 			$name=$pathinfo["filename"].substr(str_shuffle("123"), 0,1).".png";
	// 			$path=$this->path.$name;
	// 		}
	// 		if (move_uploaded_file($tmp, $path)) {
	// 			echo "投稿に成功しました！";
	// 			$sql="INSERT INTO $this->plan(id,title,image,body,planner,planner_id,date_time) 
	// 			VALUES(NULL,'$title','$name','$body','$planner','$planner_id',sysdate())";
	// 			mysql_db_query($this->db, $sql);
	// 			$sql="SELECT id FROM $this->plan WHERE planner_id='$planner_id' ORDER BY date_time DESC";
	// 			$result=mysql_db_query($this->db, $sql);
	// 			$rows=mysql_fetch_assoc($result);
	// 			echo "<meta http-equiv='refresh' content='0.1; url=index.php'>";
	// 		}else{
	// 			echo "ファイルアップロードに失敗しました！<br>";
	// 			echo "<input type='button' value='戻る' onclick='history.back();'>";
	// 		}
	// 	}
	// }

	/*-----------------------Login---------------------------*/
	function check_login(){
		$email=$_POST["email"];
		$pass=$_POST["pass"];
		$sql="SELECT * FROM $this->login WHERE email='$email' AND pass='$pass'";
		$result=mysql_db_query($this->db, $sql);
		if (mysql_num_rows($result)) {
			echo "ログインに成功しました！";
			$rows=mysql_fetch_assoc($result);
			$_SESSION["login"]=$rows["name"];
			$_SESSION["id"]=$rows["id"];
			$_SESSION["image"]=$rows["image"];
			if (isset($_GET["return_p"])) {
				echo "<meta http-equiv='refresh' content='0.1; url={$_GET["return_p"]}'>";
			}else{
				echo "<meta http-equiv='refresh' content='0.1; url=index.php'>";
			}
		}else{
			echo "存在しないIDか、パスワードが違います<br>";
			echo "<input type='button' onclick='history.back();' value='戻る'>";
		}
	}
















	
	// function check_login(){
	// 	$email=$_POST["email"];
	// 	$pass=$_POST["pass"];
	// 	$sql="SELECT * FROM $this->login WHERE email='$email' AND pass='$pass'";
	// 	$result=mysql_db_query($this->db, $sql);
	// 	if (mysql_num_rows($result)) {
	// 		echo "ログインに成功しました！";
	// 		$rows=mysql_fetch_assoc($result);
	// 		$_SESSION["id"]=$rows["id"];
	// 		$_SESSION["login"]=$rows["name"];
	// 		$_SESSION["image"]=$rows["image"];
	// 		if (isset($_GET["return_p"])) {
	// 			echo "<meta http-equiv='refresh' content='0.1; url={$_GET["return_p"]}'>";
	// 		}else{
	// 			echo "<meta http-equiv='refresh' content='0.1; url=index.php'>";
	// 		}
	// 	}else{
	// 		echo "存在しないIDか、パスワードが違います<br>";
	// 		echo "<input type='button' value='戻る' onclick='history.back();'>";
	// 	}
	// }
	/*-----------------------Join----------------------------*/
	function send_mail(){
		$name=$_POST["name"];
		$email=$_POST["email"];
		$pass=$_POST["pass"];
		$sql="SELECT * FROM $this->login WHERE email='$email'";
		$result=mysql_db_query($this->db, $sql);
		if (mysql_num_rows($result)) {
			echo "既に同名のアドレスで登録されております<br>";
			echo "<input type='button' onclick='history.back();' value='戻る'>";
		}else{
			echo "確認用のメールを送信しました！";
			$uniq=uniqid();
			$url_check=$this->domain."join.php?name=".$name."&email=".$email."&pass=".$pass."&uniq=".$uniq;
			$sql="INSERT INTO $this->preLogin(name,email,pass,uniq) VALUES('$name','$email','$pass','$uniq')";
			mysql_db_query($this->db, $sql);
			//メール送信
			$subject = "[steins-t]".$name."さんに登録確認のお願い";
			$message = <<<EOM
				<html>
				<head>
					<style>
						div{
							margin-bottom:20px;
						}
					</style>
				</head>
				<body>
					<div>{$name}さん</div>
					<div>steins-t事務局です。</div>
					<div>{$name}さんのユーザー登録を受付ました。\n以下のアドレスをクリックすることで、登録確認が完了します。</div>
					<div>
						<p>【確認用URL:】</p>
						<p><a href="{$url_check}">{$url_check}</a></p>
					</div>
				</body>
				</html>
EOM;
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=ISO-2022-JP' . "\r\n";
			$headers .= 'From: steins-t事務局 <info@steins-t.com>' . "\r\n";
			mail($email, $subject, $message, $headers);
		}
	}

	function check_mail(){
		$name=$_GET["name"];
		$email=$_GET["email"];
		$pass=$_GET["pass"];
		$uniq=$_GET["uniq"];
		$sql="SELECT * FROM $this->preLogin WHERE name='$name' AND email='$email' AND pass='$pass' AND uniq='$uniq'";
		$result=mysql_db_query($this->db, $sql);
		if (mysql_num_rows($result)) {
			echo "認証に成功しました！";
			$sql="INSERT INTO $this->login(id,email,pass,name,image,date_time) VALUES(NULL,'$email','$pass','$name','profile_image.png',sysdate())";
			mysql_db_query($this->db, $sql);
			$sql="DELETE FROM $this->preLogin WHERE email='$email'";
			mysql_db_query($this->db, $sql);
			$sql="SELECT * FROM $this->login WHERE email='$email'";
			$result=mysql_db_query($this->db, $sql);
			$rows=mysql_fetch_assoc($result);
			$_SESSION["id"]=$rows["id"];
			$_SESSION["login"]=$rows["name"];
			$_SESSION["image"]="profile_image.png";
			mb_language("Japanese");
			mb_internal_encoding("UTF-8");
			mb_send_mail($email, "本登録完了しました", "おめでとうございます。本登録完了しました！","From:info@steins-t.com");
		}else{
			echo "認証に失敗しました！";
		}
	}



















	// function send_mail(){
	// 	$name=$_POST["name"];
	// 	$email=$_POST["email"];
	// 	$pass=$_POST["pass"];
	// 	$sql="SELECT * FROM $this->login WHERE email='$email'";
	// 	$result=mysql_db_query($this->db, $sql);
	// 	if (mysql_num_rows($result)) {
	// 		echo "既に同名のアドレスで登録されております<br>";
	// 		echo "<input type='button' onclick='history.back();' value='戻る'>";
	// 	}else{
	// 		echo "仮登録用のメールを送信しました！";
	// 		$uniq=uniqid();
	// 		$url_check=$this->domain."join.php?name=".$name."&email=".$email."&pass=".$pass."&uniq=".$uniq;
	// 		mb_language("Japanese");
	// 		mb_internal_encoding("UTF-8");
	// 		mb_send_mail($email, "仮登録用のメールです", $url_check,"From:meitoku8903@gmail.com");
	// 		$sql="INSERT INTO $this->preLogin(name,email,pass,uniq) VALUES('$name','$email','$pass','$uniq')";
	// 		mysql_db_query($this->db, $sql);
	// 	}
	// }
	// function check_mail(){
	// 	$name=$_GET["name"];
	// 	$email=$_GET["email"];
	// 	$pass=$_GET["pass"];
	// 	$uniq=$_GET["uniq"];
	// 	$sql="SELECT * FROM $this->preLogin WHERE name='$name' AND email='$email' AND pass='$pass' AND uniq='$uniq'";
	// 	$result=mysql_db_query($this->db, $sql);
	// 	if (mysql_num_rows($result)) {
	// 		echo "認証に成功しました！";
	// 		$sql="INSERT INTO $this->login(email,pass,name,image,date_time) 
	// 		VALUES('$email','$pass','$name','profile_image.png',sysdate())";
	// 		mysql_db_query($this->db, $sql);
	// 		$sql="DELETE FROM $this->preLogin WHERE email='$email'";
	// 		mysql_db_query($this->db, $sql);
	// 		$sql="SELECT * FROM $this->login WHERE email='$email'";
	// 		$result=mysql_db_query($this->db, $sql);
	// 		$rows=mysql_fetch_assoc($result);
	// 		$_SESSION["login"]=$rows["name"];
	// 		$_SESSION["id"]=$rows["id"];
	// 		$_SESSION["image"]=$rows["image"];
	// 		echo "<meta http-equiv='refresh' content='0.1; url=index.php'>";
	// 		mb_language("Japanese");
	// 		mb_internal_encoding("UTF-8");
	// 		mb_send_mail($email, "ユーザー登録が完了しました", "ユーザー登録が完了しました","From:stein8903@yahoo.co.jp");
	// 	}else{
	// 		echo "認証に失敗しました！";
	// 	}
	// }
	
	/*------------------------Profile---------------------------*/
	function profile_user_info(){
		$id=$_GET["id"];
		$sql="SELECT * FROM $this->login WHERE id='$id'";
		$result=mysql_db_query($this->db, $sql);
		while ($rows=mysql_fetch_assoc($result)) {
			$name=$rows["name"];
			$image=$rows["image"];
			$gender=empty($rows["gender"]) ? "-" : $rows["gender"];
			$birth=empty($rows["birth"]) ? "-" : $rows["birth"];
			$intro=empty($rows["intro"]) ? "-" : $rows["intro"];
			$data_array=array("name"=>$name,"image"=>$image,"gender"=>$gender,"birth"=>$birth,"intro"=>$intro);
		}
		return $data_array;
	}
	function profile_count_plan($a){
		$id=$_GET["id"];
		if ($a=="plan") {
			$sql="SELECT * FROM $this->plan WHERE planner_id='$id'";
		}else if($a=="like"){
			$sql="SELECT * FROM $this->likeJoin WHERE user_id='$id' AND likee='yes'";
		}else if($a=="join"){
			$sql="SELECT * FROM $this->likeJoin WHERE user_id='$id' AND joinn='yes'";
		}
		$result=mysql_db_query($this->db, $sql);
		return mysql_num_rows($result);
	}
	function profile_show_plan($a){
		$id=$_GET["id"];
		if ($a=="plan") {
			$sql="SELECT * FROM $this->plan WHERE planner_id='$id'";
		}else if($a=="like"){
			$sql="SELECT * FROM $this->likeJoin JOIN $this->plan WHERE $this->likeJoin.user_id='$id' AND $this->likeJoin.likee='yes' AND $this->likeJoin.id=$this->plan.id";
		}else if($a=="join"){
			$sql="SELECT * FROM $this->likeJoin JOIN $this->plan WHERE $this->likeJoin.user_id='$id' AND $this->likeJoin.joinn='yes' AND $this->likeJoin.id=$this->plan.id";
		}
		$result=mysql_db_query($this->db, $sql);
		while ($rows=mysql_fetch_assoc($result)) {
			$id=$rows["id"];
			$title=$rows["title"];
			$image=$rows["image"];
			$body=$rows["body"];
			$planner=$rows["planner"];
			$planner_id=$rows["planner_id"];
			$date_time=$rows["date_time"];
			$data_array[]=array("id"=>$id,"title"=>$title,"image"=>$image,"body"=>$body,"planner"=>$planner,"planner_id"=>$planner_id,"date_time"=>$date_time);
		}
		return $data_array;
	}






















	// function profile_user_info(){
	// 	$id=$_GET["id"];
	// 	$sql="SELECT * FROM $this->login WHERE id='$id'";
	// 	$result=mysql_db_query($this->db, $sql);
	// 	while ($rows=mysql_fetch_assoc($result)) {
	// 		$name=$rows["name"];
	// 		$image=$rows["image"];
	// 		$gender=empty($rows["gender"]) ? "-" : $rows["gender"];
	// 		$birth=empty($rows["birth"]) ? "_" : $rows["birth"];
	// 		$intro=empty($rows["intro"]) ? "-" : $rows["intro"];
	// 		$data_array=array("name"=>$name,"image"=>$image,"gender"=>$gender,"birth"=>$birth,"intro"=>$intro);
	// 	}
	// 	return $data_array;
	// }
	// function profile_count_plan($a){
	// 	$id=$_GET["id"];
	// 	if ($a=="plan") {
	// 		$sql="SELECT * FROM $this->plan WHERE planner_id='$id'";
	// 	}else if($a=="join"){
	// 		$sql="SELECT * FROM $this->likeJoin WHERE user_id='$id' AND joinn='yes'";
	// 	}else if($a=="like"){
	// 		$sql="SELECT * FROM $this->likeJoin WHERE user_id='$id' AND likee='yes'";
	// 	}
	// 	$result=mysql_db_query($this->db, $sql);
	// 	return $count=mysql_num_rows($result);
	// }
	// function profile_show_plan($a){
	// 	$id=$_GET["id"];
	// 	if ($a=="plan") {
	// 		$sql="SELECT * FROM $this->plan WHERE planner_id='$id'";
	// 	}else if($a=="join"){
	// 		$sql="SELECT * FROM $this->likeJoin JOIN $this->plan WHERE $this->likeJoin.user_id='$id' AND $this->likeJoin.joinn='yes' AND $this->likeJoin.id=$this->plan.id";
	// 	}else if($a=="like"){
	// 		$sql="SELECT * FROM $this->likeJoin JOIN $this->plan WHERE $this->likeJoin.user_id='$id' AND $this->likeJoin.likee='yes' AND $this->likeJoin.id=$this->plan.id";
	// 	}
	// 	$result=mysql_db_query($this->db, $sql);
	// 	while ($rows=mysql_fetch_assoc($result)) {
	// 		$id=$rows["id"];
	// 		$title=$rows["title"];
	// 		$body=$rows["body"];
	// 		$image=$rows["image"];
	// 		$planner=$rows["planner"];
	// 		$planner_id=$rows["planner_id"];
	// 		$date_time=$rows["date_time"];
	// 		$data_array[]=array("id"=>$id,"title"=>$title,"body"=>$body,"image"=>$image,"planner"=>$planner,"planner_id"=>$planner_id,"date_time"=>$date_time);
	// 	}
	// 	return $data_array;
	// }

	/*-----------------------Setting----------------------------*/
	function setting_user_info(){
		$id=$_GET["id"];
		$sql="SELECT * FROM $this->login WHERE id='$id'";
		$result=mysql_db_query($this->db, $sql);
		while ($rows=mysql_fetch_assoc($result)) {
			$id=$rows["id"];
			$name=$rows["name"];
			$image=$rows["image"];
			$gender=$rows["gender"];
			$birth=explode("/", $rows["birth"]);
			$intro=$rows["intro"];
			$data_array=array("id"=>$id,"name"=>$name,"image"=>$image,"gender"=>$gender,"year"=>$birth[0],"month"=>$birth[1],"day"=>$birth[2],"intro"=>$intro);
		}
		return $data_array;
	}
	function setting_update(){
		$id=$_GET["id"];
		$gender=$_POST["gender"];
		$birth=$_POST["year"]."/".$_POST["month"]."/".$_POST["day"];
		$intro=$_POST["intro"];
		$sql="UPDATE $this->login SET gender='$gender', birth='$birth', intro='$intro' WHERE id='$id'";
		mysql_db_query($this->db, $sql);
	}


















	// function setting_user_info(){
	// 	$id=$_GET["id"];
	// 	$sql="SELECT * FROM $this->login WHERE id='$id'";
	// 	$result=mysql_db_query($this->db, $sql);
	// 	while ($rows=mysql_fetch_assoc($result)) {
	// 		$name=$rows["name"];
	// 		$id=$rows["id"];
	// 		$image=$rows["image"];
	// 		$gender=$rows["gender"];
	// 		$birth=explode("/", $rows["birth"]);
	// 		$intro=$rows["intro"];
	// 		$data_array=array("name"=>$name,"id"=>$id,"image"=>$image,"gender"=>$gender,"year"=>$birth[0],"month"=>$birth[1],"day"=>$birth[2],"intro"=>$intro);
	// 	}
	// 	return $data_array;
	// }
	// function setting_update(){
	// 	$id=$_GET["id"];
	// 	$gender=$_POST["gender"];
	// 	$birth=$_POST["year"]."/".$_POST["month"]."/".$_POST["day"];
	// 	$intro=$_POST["intro"];
	// 	$sql="UPDATE $this->login SET gender='$gender', birth='$birth', intro='$intro' WHERE id='$id'";
	// 	mysql_db_query($this->db, $sql);
	// }
	/*-----------------------Details----------------------------*/
	function details_show($a,$b=1){
		$id=$_GET["id"];
		if ($a=="plan") {
			$sql="SELECT * FROM $this->plan WHERE id='$id' LIMIT 0,1";
			$result=mysql_db_query($this->db, $sql);
			while ($rows=mysql_fetch_assoc($result)) {
				$id=$rows["id"];
				$title=$rows["title"];
				$body=$rows["body"];
				$image=$rows["image"];
				$hl_image = $rows["hl_image"];
				$bgm=$rows["bgm"];
				$planner=$rows["planner"];
				$planner_id=$rows["planner_id"];
				$date_time=$rows["date_time"];
				$data_array[]=array("id"=>$id,"title"=>$title,"body"=>$body,"image"=>$image,"hl_image"=>$hl_image,"bgm"=>$bgm,"planner"=>$planner,"planner_id"=>$planner_id,"date_time"=>$date_time);
			}	
		}
		if($a=="subPlans"){
			$sql="SELECT * FROM $this->subPlans WHERE plan_id='$id' ORDER BY id ASC";
			$result=mysql_db_query($this->db, $sql);
			while ($rows=mysql_fetch_assoc($result)) {
				$body=$rows["body"];
				$image=$rows["image"];
				$data_array[]=array("body"=>$body,"image"=>$image);
			}
		}
		if ($a=="comment") {
			$sql="SELECT * FROM $this->comment JOIN $this->login WHERE $this->comment.id='$id' AND $this->comment.commenter_id=$this->login.id";
			$result=mysql_db_query($this->db, $sql);
			while ($rows=mysql_fetch_assoc($result)) {
				$commenter=$rows["commenter"];
				$commenter_id=$rows["commenter_id"];
				$comment=$rows["comment"];
				$image=$rows["image"];
				$data_array[]=array("commenter"=>$commenter,"commenter_id"=>$commenter_id,"comment"=>$comment,"image"=>$image);
			}

		}
		if ($a=="join") {
			$sql="SELECT * FROM $this->likeJoin JOIN $this->login WHERE $this->likeJoin.id='$id' AND $this->likeJoin.joinn='yes' AND $this->likeJoin.user_id=$this->login.id";
			$result=mysql_db_query($this->db, $sql);
			while ($rows=mysql_fetch_assoc($result)) {
				$id=$rows["id"];
				$name=$rows["name"];
				$image=$rows["image"];
				$data_array[]=array("id"=>$id,"name"=>$name,"image"=>$image);
			}
		}
		if ($a=="like") {
			$sql="SELECT * FROM $this->likeJoin JOIN $this->login WHERE $this->likeJoin.id='$id' AND $this->likeJoin.likee='yes' AND $this->likeJoin.user_id=$this->login.id";
			$result=mysql_db_query($this->db, $sql);
			while ($rows=mysql_fetch_assoc($result)) {
				$id=$rows["id"];
				$name=$rows["name"];
				$image=$rows["image"];
				$data_array[]=array("id"=>$id,"name"=>$name,"image"=>$image);
			}
		}
		if ($a=="count") {
			if ($b=="join") {
				$sql="SELECT * FROM $this->likeJoin WHERE id='$id' AND joinn='yes'";
			}else if($b=="like"){
				$sql="SELECT * FROM $this->likeJoin WHERE id='$id' AND likee='yes'";
			}
			$result=mysql_db_query($this->db, $sql);
			$data_array=mysql_num_rows($result);
		}
		return $data_array;
	}
	function details_check($a){
		$id=$_GET["id"];
		$login_id=$_SESSION["id"];
		if ($a=="join") {
			$sql="SELECT * FROM $this->likeJoin WHERE id='$id' AND user_id='$login_id' AND joinn='yes'";
		}else if($a=="like"){
			$sql="SELECT * FROM $this->likeJoin WHERE id='$id' AND user_id='$login_id' AND likee='yes'";
		}else if($a=="planner"){
			$sql="SELECT * FROM $this->plan WHERE planner_id='$login_id' AND id='$id'";
		}
		$result=mysql_db_query($this->db, $sql);
		if (mysql_num_rows($result)) {
			return true;
		}else{
			return false;
		}
	}
	function details_save($a){
		$id=isset($_GET["id"]) ? $_GET["id"] : $_POST["id"];
		$login_id=isset($_SESSION["id"]) ? $_SESSION["id"] : $_POST["user_id"];
		$login=$_SESSION["login"];
		$comment=$_POST["comment"];
		if ($a=="join") {
			$sql="SELECT * FROM $this->likeJoin WHERE id='$id' AND user_id='$login_id' AND joinn='yes'";
			$result=mysql_db_query($this->db, $sql);
			if (mysql_num_rows($result)) {
				echo "<script>alert('既に参加されています');</script>";
			}else{
				$sql="INSERT INTO $this->likeJoin(id,user_name,user_id,joinn) 
				VALUES('$id','$login','$login_id','yes')";
			}
		}else if($a=="like"){
			$sql="SELECT * FROM $this->likeJoin WHERE id='$id' AND user_id='$login_id' AND likee='yes'";
			$result=mysql_db_query($this->db, $sql);
			if (mysql_num_rows($result)) {
				echo "<script>alert('既にお気に入りに登録されています！');</script>";
			}else{
				$sql="INSERT INTO $this->likeJoin(id,user_name,user_id,likee) 
				VALUES('$id','$login','$login_id','yes')";
			}
		}else if($a=="comment"){
			if (empty($_SESSION["id"])) {
				echo "<script>alert('先にログインしてください');</script>";
				echo "<meta http-equiv='refresh' content='0.1; url=login.php?return_p=details.php?id=$id'>";
			}else{
				$sql="SELECT * FROM $this->comment WHERE id='$id' AND commenter_id='$login_id' AND comment='$comment'";
				$result=mysql_db_query($this->db, $sql);
				if (mysql_num_rows($result)) {
					echo "<script>alert('同じコメントは１回以上入力できません');</script>";
				}else{
					$sql="INSERT INTO $this->comment(id,commenter,commenter_id,comment,date_time) 
					VALUES('$id','$login','$login_id','$comment',sysdate())";
				}
			}
		}
		mysql_db_query($this->db, $sql);
	}
	function details_delete($a){
		$id=$_GET["id"];
		$login_id=$_SESSION["id"];
		$comment=$_POST["delete_comment"];
		if ($a=="join") {
			$sql="DELETE FROM $this->likeJoin WHERE id='$id' AND user_id='$login_id' AND joinn='yes'";
		}else if($a=="like"){
			$sql="DELETE FROM $this->likeJoin WHERE id='$id' AND user_id='$login_id' AND likee='yes'";
		}else if($a=="comment"){
			$sql="DELETE FROM $this->comment WHERE id='$id' AND commenter_id='$login_id' AND comment='$comment'";
		}
		mysql_db_query($this->db, $sql);
		if($a=="plan"){
			$sql="DELETE FROM $this->plan WHERE id='$id'";
			$sql2="DELETE FROM $this->likeJoin WHERE id='$id'";
			$sql3="DELETE FROM $this->comment WHERE id='$id'";
			mysql_db_query($this->db, $sql);
			mysql_db_query($this->db, $sql2);
			mysql_db_query($this->db, $sql3);
			echo "<script>alert('企画が削除されました！');</script>";
			echo "<meta http-equiv='refresh' content='0.1; url=index.php'>";
		}
	}
}
?>