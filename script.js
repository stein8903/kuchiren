var join_match={
	check_form: function(){
		var form=document.forms.join_form;
		var mail_check=form.email.value.match(/^[a-z0-9A-Z_/-]{1,20}@[a-zA-Z0-9_/-]{1,10}[.][a-zA-Z0-9]{1,5}(.|[a-z])[a-z]{1,3}$/);
		if (!mail_check) {
			alert("メールアドレスが正しくありません！");
			return false;
		}else if(form.pass.value.match(/[^a-zA-Z0-9]/) || form.pass.value.length>20 || form.pass.value.length<6 || form.pass.value==""){
			alert("パスワードは6~20の半角英数字を入力してください");
			return false;
		}else if(form.pass.value!=form.pass2.value){
			alert("パスワードが一致しません！");
			return false;
		}else{
			return true;
		}
	}
};

var details={
	confirm_delete: function(){
		var really=confirm("この企画を本当に削除しますか？");
		if (really) {
			return true;
		}else{
			alert("削除をキャンセルしました!");
			return false;
		}
	},

	apiUrl: "api.php",
	likeSend: function(session_id){
		var request=new XMLHttpRequest();
		request.onreadystatechange=function(){
			var show=document.getElementById("show");
			if (request.readyState==4) {
				if (request.status==200) {
					$("#buffering-like").hide();
					document.getElementById("top").innerHTML="お気に入りに登録しました！";
					$("#top").slideDown();
					setTimeout(function(){$("#top").slideUp();},1000);

					document.getElementById("like_btn").innerHTML="お気に入りから削除する";
					document.getElementById("like_btn").setAttribute("onclick","details.cancleSend()");
					document.getElementById("like_btn").setAttribute("id","cancle_btn");
					document.getElementById("cancle_btn").setAttribute("style","color:red;");
					show.innerHTML=request.responseText;
				}else{
					document.getElementById("top").innerHTML="サーバーエラーが発生しました";
					$("#top").slideDown();
					setTimeout(function(){$("#top").slideUp();},1000);
				}
			}else{
				$("#buffering-like").show();
			}
		}
		var planId = location.search.slice(1);//id=222
		var userId="&user_id="+session_id;//&user_id=2
		request.open("POST","api.php",true);
		request.setRequestHeader("content-type","application/x-www-form-urlencoded;charset=UTF-8");
		request.send(planId+userId);
	}
};





var showMore = {
	request: function(){
		var request = new XMLHttpRequest();
		request.onload = function() {
			if (request.status == 200) {
				var obj = JSON.parse(request.response);
				var articles=document.getElementById("articles");
				var br=document.createElement('br');
				var div=document.createElement('div');
				for (var i = 0; i < obj.length; i++) {
					var oya=document.createElement("article");
					articles.appendChild(oya);

					var title_e=document.createElement('div');
					oya.appendChild(title_e);

					var element=document.createElement('a');
					element.href="details.php?id="+obj[i]["id"];
					element.innerHTML=obj[i]["title"];
					title_e.appendChild(element);

					var element=document.createElement("img");
					element.src="../kuchiren3/files/"+obj[i]["image"];
					element.style.width="30px";
					element.style.height="30px";
					oya.appendChild(element);

					var element=document.createElement('div');
					element.innerHTML=obj[i]["body"];
					oya.appendChild(element);

					var element=document.createElement("div");
					element.innerHTML=obj[i]["date_time"];
					oya.appendChild(element);

					articles.appendChild(document.createElement('br'));
				};
				showMore.start_count();
			}else{
				alert("エラー");
			}
		}
		request.open('POST', 'api.php');
		request.responseType = 'text';
		var formData = new FormData(document.getElementById('frm'));
		request.send(formData);
	},
	start_count: function(){
		var articles = document.getElementById("articles");
		var article_count = articles.getElementsByTagName('article').length;
		var page_count = article_count/5+1;
		var text = document.getElementById("page");
		text.value = page_count;
	},
	scroll_request: function(){
		window.onscroll = function(){
			// 現在位置の取得
			var dElm = document.documentElement , dBody = document.body ;
			var nY = dElm.scrollTop || dBody.scrollTop ;		// 現在位置のY座標
			var hoge = window.innerHeight;
			var max = dElm.scrollHeight || dBody.scrollHeight ;
			var position = document.getElementById("position");
			position.innerHTML = nY+hoge+"/"+max;
			if (nY+hoge==max) {
				showMore.request();
			};
		}
	}
};

// function Apple(){
// 	this.type = "type";
// 	this.color = "yellow";
// 	this.getInfo = getAppleInfo;
// 	this.getInfo2 = hoge.arato;
// }
// function getAppleInfo() {
//     return this.color + ' ' + this.type + ' apple';
// }
// var hoge = {
// 	arato:function(){
// 		return this.color + ' ' + this.type + ' apple';
// 	}
// }
var apple = {
	color: "white",
	type: "ios",
	getInfo: function(){
		apple.preface();
	},
	preface: function(){
		alert("call a method in a method!!!");
		alert("色は"+this.color+"で、タイプは"+this.type+"です");
	}
};



var plan={
	addArticle: function(){
		var plan_form=document.getElementById("plan_form");
		var br=document.createElement("br");
		var count=document.getElementsByTagName("textarea").length;
		//その他にもタグネームでいろんなことができる↓
		//http://wp-p.info/tpl_rep.php?cat=js-intermediate&fl=r3
		

		//わかりやすくするための区切り
		var element=document.createElement('div');
		element.innerHTML="スライド"+count;
		element.style.backgroundColor="#ffcd00";
		element.style.color="white";
		plan_form.appendChild(element);
		plan_form.appendChild(br);

		//フォーム追加
		//textarea追加
		var count2 = count+1;					//pikicastのhl_imageのupfile[1]を追加したため加えた
		var element=document.createElement('textarea');
		element.rows="10";
		element.setAttribute("maxlength","100");
		element.name="body["+count2+"]";
		plan_form.appendChild(element);
		plan_form.appendChild(br);

		//fileタイプ追加
		var element=document.createElement("input");
		element.type="file";
		element.name="upfile["+count2+"]";
		plan_form.appendChild(element);
		plan_form.appendChild(br);
	},
	lastTest: function(){
		// var plan_form=document.getElementById("plan_form");
		// alert(plan_form.lastChild.nodeName);
		var x=document.getElementsByTagName("textarea");
		alert(x.length);
	}
};