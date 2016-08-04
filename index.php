<?php
$lockfile=dirname(__FILE__)."/Data/lock.html";
$dir="Public/template/";
//如果目录不存在，则创建目录
if(!file_exists($dir)){mkdir ($dir);}
if(!file_exists($lockfile)){
    header("Location:install.php");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>章鱼新闻采集系统(Octopus News System)</title>
	<meta http-equiv="content-type" content="text/html;charset=UTF-8">
	<meta name="Keywords" content="章鱼新闻采集系统" />
	<meta name="Description" content="章鱼新闻采集系统" />
	<link rel="stylesheet" type="text/css" href="Public/main/css/style.css" />
    <script type="text/javascript" src="Public/main/js/jquery-1.6.4.min.js"></script>
    <script type="text/javascript" src="Public/main/js/jquery-ui-1.8.16.custom.min.js"></script>
</head>
<body>
<div id="wrapper">
	<div id="header">
		<div id="logo"><img src="Public/main/images/logo.png" width="160" height="49"/></div>
		<div id="menu">
			<div id="menu_container"><marquee>ONS(Octopus News System)采集系统，操作简单，速度更快，保密安全，修改需要懂PHP正则表达式和基本的PHP知识。</marquee>
			</div>
		</div>
	</div>
	<div id="navigator" style="width:15%; ">
		<iframe src="tree.php"></iframe>
        </div>
	<div id="main" style="float:right;width:82% !important;margin-right:10px; ">
		<iframe name="MainFrame" src="main.php"></iframe>
	</div>
	<div id="footer">Copyright © 2010-2016 All Rights Reserved Powered By ONS</div>
</div>
</body>
<script type="text/javascript">
function screenAdapter(){
	document.getElementById('footer').style.top=document.documentElement.scrollTop+document.documentElement.clientHeight- document.getElementById('footer').offsetHeight+"px";
	document.getElementById('navigator').style.height=document.documentElement.clientHeight-100+"px";
	document.getElementById('main').style.height=document.documentElement.clientHeight-100+"px";
}
window.onscroll=function(){screenAdapter()};
window.onresize=function(){screenAdapter()};
window.onload=function(){screenAdapter()};
</script>
<script>
 //遮盖层显示与隐藏
function coverShow(type){
	if(type){
		$('#create_cache').css('display','block');
	}else{
		$('#create_cache').css('display','none');
	}
}
</script>
<div style="position: fixed;width: 100%;height: 100%;legt: 0;top: 0;display: none;" id="create_cache">
    <i class="mask" style="display: block;position: fixed;width: 100%;height: 100%;left: 0;top: 0;background: rgba(0,0,0,.3);"></i>
    <div style="width: 100%;position: fixed;top: 50%;left: 0;text-align: center;"><img src="Public/layer/skin/default/loading-0.gif" alt=""  /></div>
</div>
</html>