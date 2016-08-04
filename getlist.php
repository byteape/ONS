<?php
header("Content-Type:text/html;charset=utf-8");
//采集规则测试页
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
$content=$p1->getAllContent();//获取全部html内容
echo($content);
