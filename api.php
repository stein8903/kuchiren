<?php
	include("sql.php");
	$mysql=new mysql();
	if(isset($_POST["id"]) && isset($_POST["user_id"])){
		$mysql->details_save("like");
		// print($_POST["user_id"]."  ".$_POST["id"]);
	}
	if (isset($_POST["join"])) {
		$mysql->details_save("join");
	}

	//Swift用
	if (isset($_GET["json"])){
		// $jrow1 = array("pref"=>"KIM","address"=>"stein8903@yahoo.co.jp");
		// $jrow = array("data"=>$jrow1);
		$show_sql = new paging("index",25);
		echo json_encode($show_sql->sql_show());
	}
	if(isset($_GET["id"]) && isset($_GET["detail"])){
		echo json_encode($mysql->details_show("plan"));
	}
	if (isset($_GET["slide"]) && isset($_GET["id"])){
		// echo json_encode($mysql->details_show("subPlans"));
		$array = array("main_info"=>$mysql->details_show("plan"),"sub_info"=>$mysql->details_show("subPlans"));
		echo json_encode($array);
	}

	//jsの「もっと読み込む」テスト
	if (isset($_POST["page"])) {
		$show_sql = new paging("index",5);
		echo json_encode($show_sql->sql_show());
	}
	// if (isset($_POST["page"])) {
	// 	echo "<script>alert('サーバまできたよ！')</script>";
	// 	echo json_encode($mysql->sql_show("index",6));
	// }
?>