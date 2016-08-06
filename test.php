<?php
/*采集规则测试页*/
header("Content-Type:text/html;charset=utf-8");
date_default_timezone_set('Asia/Shanghai');
/*接收参数开始*/
$id=$_GET['id'];
/*引入与定义变量开始*/
require 'Library/common.php';
require 'Library/CaijiClass.class.php';
require 'Config/function.php';
$dir="Public/template/";
$rule=$M->table('rules')->where(array('id'=>$id))->find();

$geturl=$rule['url'];
$charset=$rule['charset'];
$r_list=$rule['r_list'];

$p1=new CaijiClass($geturl,$charset);
$content=$p1->getAllContent();//获取全部经过压缩的html内容

$post_pattern[0]=$r_list;
$result=$p1->pregMatch($post_pattern);
$listResult=$result[0];unset($listResult[0]);
echo "<pre>";
print_r($listResult);//打印列表匹配结果数组
echo "-------------------------------------------------------------------------<br/>";

if($rule['title_first']){
	//如果标题在前
	$urlArray=$listResult[1];//链接
    $titleArray=$listResult[2];//标题
	$dateArray=$listResult[3];//时间
}else{
	//如果标题在后
	$dateArray=$listResult[1];//时间
	$urlArray=$listResult[2];//链接
    $titleArray=$listResult[3];//标题
}
//把时间规则里的所有英文都替换为(\d+?)
$date_pattern='/'.preg_replace('/([A-Z]+)/i','(\d+)',$rule['create_time_format']).'/i';
echo "时间匹配规则为:<br/>";
print_r($date_pattern);
echo "<br/>-------------------------------------------------------------------------<br/>";
preg_match($date_pattern,$dateArray[0],$match_re);
$match_re=array_splice($match_re,1);//删除数组第一个元素，剩于为全数据元素。
echo "时间数据数组为:<br/>";
print_r($match_re);
echo "-------------------------------------------------------------------------<br/>";

preg_match_all('/([A-Z]+)/i',$rule['create_time_format'],$match_rebs);
$match_rebs_array=$match_rebs[1];
foreach($match_rebs_array as $k=>$v){
	$match_rebs_array[$k]=strtolower($v);//为用户输入的匹配字符全部转为小写后的数组
}
echo "时间占位符数组为:<br/>";
print_r($match_rebs_array);
echo "-------------------------------------------------------------------------<br/>";
//开始查找拼接出标准格式的时间数据
$y_k=array_search('y',$match_rebs_array);
$m_k=array_search('m',$match_rebs_array);
$d_k=array_search('d',$match_rebs_array);
$h_k=array_search('h',$match_rebs_array);
$i_k=array_search('i',$match_rebs_array);
$s_k=array_search('s',$match_rebs_array);
$date_y=$y_k!==false?$match_re[$y_k]:date('Y',time());
$date_m=$m_k!==false?$match_re[$m_k]:date('m',time());
$date_d=$d_k!==false?$match_re[$d_k]:date('d',time());
$date_h=$h_k!==false?$match_re[$h_k]:date('h',time());
$date_i=$i_k!==false?$match_re[$i_k]:date('i',time());
$date_s=$s_k!==false?$match_re[$s_k]:date('s',time());

//print_r($date_y.'-'.$date_m.'-'.$date_d.' '.$date_h.':'.$date_i.':'.$date_s);
$count=count($urlArray);
echo "总共记录：".$count."<br/>";
echo "第一条标题：".$titleArray[0]."<br/>";
echo "第一条发布时间(通用格式)：".date('Y-m-d H:i:s',strtotime($date_y.'-'.$date_m.'-'.$date_d.' '.$date_h.':'.$date_i.':'.$date_s))."<br/>";
for($i=0;$i<1;$i++){
	$getDetailUrl=$rule['root_url']?$rule['root_url'].$urlArray[$i]:$urlArray[$i];//如果带根链接要加上
	$p2=new CaijiClass($getDetailUrl,$rule['detail_charset']);
	$post_content=$p2->getAllContent(false);
	$filename=$dir.$i.'.txt';
	$file=fopen($filename,'w');
	fwrite($file,$post_content);
	fclose($file);
}
$patters_post[0]=$rule['r_detail'];//内容
$post_result=preg_match_content($patters_post,$filename);
echo "第一条新闻内容为：<br/>".htmlspecialchars_decode($post_result[0][1][0]);
