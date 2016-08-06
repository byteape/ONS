<?php
/*采集管理规则之获取经过压缩后的列表页html代码，获取列表规则时，请查看源代码提取！*/
header("Content-Type:text/html;charset=utf-8");
$id=$_GET['id'];
require 'Library/common.php';
require 'Library/CaijiClass.class.php';
require 'Config/function.php';
$dir="Public/template/";
$rule=$M->table('rules')->where(array('id'=>$id))->find();
$geturl=$rule['url'];
$charset=$rule['charset'];
$r_list=$rule['r_list'];
$p1=new CaijiClass($geturl,$charset);
$content=$p1->getAllContent();//获取经过压缩后的列表全部html内容，若getlAllContent(false)则为获取不经压缩的内容
echo($content);
