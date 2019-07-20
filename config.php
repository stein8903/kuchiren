<?php
class user_info{
	//データベース
	protected $server="mysql009.phy.lolipop.lan";
	protected $user="LAA0230611";
	protected $password="zmfl8903";
	protected $db="LAA0230611-stein8903";
	//テーブル
	public $plan="plan";
	public $subPlans="subPlans_tbl";
	public $login="login_tbl";
	public $preLogin="preLogin_tbl";
	public $likeJoin="like_join";
	public $comment="kuchikomi";
	//URL
	public $domain="http://www.steins-t.com/kuchiren5/";
	public $path="../kuchiren3/files/";
	public $profile="../kuchiren3/profile_images/";
	
	function __construct(){
		// header("Content-Type: text/html; charset=UTF-8");
		session_start();
		if (isset($_POST["logout"])) {
			session_unset();
		}
		$connect=mysql_connect($this->server,$this->user,$this->password);
		mysql_query("SET NAMES utf8",$connect);
	}
}
?>