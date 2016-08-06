<?php
/*采集下载与结果分析反馈页面*/
header("Content-Type:text/html;charset=utf-8");
date_default_timezone_set('Asia/Shanghai');
error_reporting(E_ALL ^E_NOTICE);
/*接收参数开始*/
$id=$_POST['id'];//采集规则id号
$geturl=$_POST['geturl'];//需要采集的url地址
$btime=strtotime($_POST['btime']);//筛选开始时间
$etime=strtotime($_POST['etime']);//筛选结束时间
$category_id=$_POST['category_id'];//所属类别id号
/*接收参数结束*/
/*引入与定义变量开始*/
require 'Library/common.php';
require 'Library/CaijiClass.class.php';
require 'Config/function.php';
$dir="Public/template/";
/*引入与定义变量结束*/
/*采集规则的查询与变量分配开始*/
$rule=$M->table('rules')->where(array('id'=>$id))->find();
$charset=$rule['charset'];
$r_list=$rule['r_list'];
/*采集规则的查询与变量分配结束*/
/*第一步删除路径所有帖子*/
$fnum=scandir($dir);
for($i=2;$i<count($fnum);$i++){
	$fname=$dir.$fnum[$i];
	unlink($fname);
}
/*第二步获取列表并下载内容文本*/
$p1=new CaijiClass($geturl,$charset);
$content=$p1->getAllContent();//获取全部html内容
$post_pattern[0]=$r_list;
$result=$p1->pregMatch($post_pattern);
$listResult=$result[0];unset($listResult[0]);
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
preg_match_all('/([A-Z]+)/i',$rule['create_time_format'],$match_rebs);
$match_rebs_array=$match_rebs[1];
foreach($match_rebs_array as $k=>$v){
	$match_rebs_array[$k]=strtolower($v);//为用户输入的匹配字符全部转为小写后的数组
}
//开始查找拼接出标准格式的时间数据
$y_k=array_search('y',$match_rebs_array);
$m_k=array_search('m',$match_rebs_array);
$d_k=array_search('d',$match_rebs_array);
$h_k=array_search('h',$match_rebs_array);
$i_k=array_search('i',$match_rebs_array);
$s_k=array_search('s',$match_rebs_array);
$count=count($urlArray);
$n=0;//开始标号
for($i=0;$i<$count;$i++){
	preg_match($date_pattern,$dateArray[$i],$match_re);
    $match_re=array_splice($match_re,1);//删除数组第一个元素，剩于为全数据元素。
	$date_y=$y_k!==false?$match_re[$y_k]:date('Y',time());
	$date_m=$m_k!==false?$match_re[$m_k]:date('m',time());
	$date_d=$d_k!==false?$match_re[$d_k]:date('d',time());
	$date_h=$h_k!==false?$match_re[$h_k]:date('h',time());
	$date_i=$i_k!==false?$match_re[$i_k]:date('i',time());
	$date_s=$s_k!==false?$match_re[$s_k]:date('s',time());
	$dataTime=strtotime($date_y.'-'.$date_m.'-'.$date_d.' '.$date_h.':'.$date_i.':'.$date_s);
	if($dataTime>=$btime && $dataTime<=$etime){
		$getDetailUrl=$rule['root_url']?$rule['root_url'].$urlArray[$i]:$urlArray[$i];//如果带根链接要加上
		$p2=new CaijiClass($getDetailUrl,$rule['detail_charset']);
		$post_content=$p2->getAllContent(false);
		//加入标题和时间开始
		$post_content.="<mytitle>".$titleArray[$i]."</mytitle>";
		$post_content.="<mydate>".date('Y-m-d H:i:s',strtotime($date_y.'-'.$date_m.'-'.$date_d.' '.$date_h.':'.$date_i.':'.$date_s))."</mydate>";
		//加入标题和时间结束
		$filename=$dir.$n.'.txt';
		$file=fopen($filename,'w');
		fwrite($file,$post_content);
		fclose($file);
		$n++;
	}
}
/*第三步读取并返回列表数据*/
if(!$n){
    $allData['allnum']=0;
    $allData['data']=null;
}else{
	for($i=0;$i<$n;$i++){
		$parent_url=$dir.$i.'.txt';
		$patters_post[0]='/<mytitle>([\s\S]*?)<\/mytitle>/i';//标题匹配规则
		$patters_post[1]=$rule['r_detail'];//内容匹配规则
		$patters_post[2]='/<mydate>([\s\S]*?)<\/mydate>/i';//发表时间匹配规则
		$post_result=preg_match_content($patters_post,$parent_url);
		$title=$post_result[0][1][0];$detail=$post_result[1][1][0];$create_time=strtotime(trim($post_result[2][1][0]));
		if($title && $detail && $create_time){
			$dataAll[]=array(
                'id'=>$i,
				'title'=>removetagall($title),//去除标题里的html标签
				'create_time'=>date('Y-m-d H:i:s',$create_time),
			);
		}
	}
    $allData['allnum']=$n;
    $allData['yesnum']=count($dataAll);
    $allData['data']=$dataAll;
	echo(json_encode($allData));
}