<?php
/*采集入库处理页*/
header("Content-Type:text/html;charset=utf-8");
date_default_timezone_set('Asia/Shanghai');
error_reporting(E_ALL ^E_NOTICE);
/*接收参数开始*/
$id=$_POST['id'];//采集规则的id号
$idStr= substr($_POST['s'],0,strlen($_POST['s'])-1);
$idArr=explode(',',$idStr);//需要入库的下载文章号数组
/*接收参数结束*/
/*引入与定义变量开始*/
require 'Library/common.php';//数据库操作实例
require 'Library/CaijiClass.class.php';//采集处理类
require 'Config/function.php';//公共函数库
$dir="Public/template/";//下载存储目录
/*引入与定义变量结束*/
$rule=$M->table('rules')->where(array('id'=>$id))->find();

/*读取并插入数据库开始*/
$n=0;//成功插入数据库记数
for($i=0;$i<count($idArr);$i++){
    $parent_url=$dir.$idArr[$i].'.txt';
    $patters_post[0]='/<mytitle>([\s\S]*?)<\/mytitle>/i';//标题匹配规则
    $patters_post[1]=$rule['r_detail'];//内容匹配规则
    $patters_post[2]='/<mydate>([\s\S]*?)<\/mydate>/i';//发表时间匹配规则
    $post_result=preg_match_content($patters_post,$parent_url);
    $title=$post_result[0][1][0];$detail=$post_result[1][1][0];$create_time=strtotime(trim($post_result[2][1][0]));
    if($title && $detail && $create_time){
        $dataAll[]=array(
            'category_id'=>$category_id,//所属类别id
            'title'=>removetagall($title),//去除标题里的html标签
            'detail'=>addslashes($detail),//要对内容字段进行转义
            'create_time'=>$create_time,
        );
        $n++;
    }else{
        $redata['result']=0;
        $redata['msg']="出现错误，没有匹配完全的内容。\n id:".$i."-标题:".$title;
        echo(json_encode($redata));die;
    }
}
//插入数据库
for($m=0;$m<count($dataAll);$m++){
    $re=$M->table('content')->data($dataAll[$m])->insert();
    if($m==0){$startInsertNum=$re;}//获取插入数据库的开始的id号保存
}
/*读取并插入数据库结束*/
/*json数据返回*/
if($re){
    $redata['result']=1;
    $redata['msg']='数据采集成功!总共插入'.$n.'条数据，从id号：'.$startInsertNum.'开始！';
}else{
    $redata['result']=0;
    $redata['msg']='出现错误，数据没有采集成功！请联系QQ:1132083961';
}
echo(json_encode($redata));