<?php 
require 'Library/common.php';
error_reporting(E_ALL ^E_NOTICE);
date_default_timezone_set('Asia/Shanghai');
$pid=$_POST['id'];
$id=$_GET['id'];
if($pid){
	//提交更新相关信息
	$postData=array(
	    'title'=>$_POST['title'],
		'url'=>$_POST['url'],
		'root_url'=>$_POST['root_url'],
		'charset'=>$_POST['charset'],
		'detail_charset'=>$_POST['detail_charset'],
		'r_list'=>addslashes($_POST['r_list']),
		'title_first'=>$_POST['title_first'],
		'create_time_format'=>$_POST['create_time_format'],
		'r_detail'=>addslashes($_POST['r_detail']),
		'detail_right_add'=>$_POST['detail_right_add'],
		'is_del_ahref'=>$_POST['is_del_ahref']
	);
	$upResult=$M->table('rules')->where(array('id'=>$pid))->data($postData)->update();
	if($upResult){echo "<script>alert('更新成功');</script>";}
	$id=$pid;
}
if(!$pid && !$id){
	//如果是新增记录
	if($_POST['title']){
		$postData=array(
			'title'=>$_POST['title'],
			'url'=>$_POST['url'],
			'root_url'=>$_POST['root_url'],
			'charset'=>$_POST['charset']?$_POST['charset']:'UTF-8',
			'detail_charset'=>$_POST['detail_charset']?$_POST['detail_charset']:'UTF-8',
			'r_list'=>addslashes($_POST['r_list']),
			'title_first'=>$_POST['title_first']?$_POST['title_first']:1,
			'create_time_format'=>$_POST['create_time_format'],
			'r_detail'=>addslashes($_POST['r_detail']),
			'detail_right_add'=>$_POST['detail_right_add'],
			'is_del_ahref'=>$_POST['is_del_ahref']?$_POST['is_del_ahref']:1,
		);
		$inResult=$M->table('rules')->data($postData)->insert();
		if($inResult){echo "<script>alert('新增成功');</script>";}
		$id=$inResult;
		$actionTitle="添加采集";
	}
}else{
	$actionTitle="修改采集";
}
//编辑查询输出相关信息
$resultRule=$M->table('rules')->where(array('id'=>$id))->find();
$info=$resultRule;
?>
<!DOCTYPE html>
<html>
<header>
	<meta http-equiv="content-type" content="text/html;charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="Public/main/css/default.css" />
	<script type="text/javascript" src="Public/main/js/jquery.min.js"></script>
	<script type="text/javascript" src="Public/layer/layer.js"></script>
	<script type="text/javascript" src="Public/My97DatePicker/WdatePicker.js"></script>
    <link rel="stylesheet" type="text/css" href="Public/style.css" />
	<link rel="stylesheet" href="Public/main/css/demo.css" type="text/css" media="all" />
    <link rel="stylesheet" type="text/css" href="Public/main/css/edit.css" />
</header>
<body>
<div class="content">
 <div class="nav">
   <a class="sel" href="manage.php">采集列表</a>
   <a href="edit.php">添加采集</a>
 </div>
<div>
<form action="" method="post">
<table class="bordered">
 <tr>
    <td width="90px">采集名称</td>
    <td width="300px"><input name="title" type="text" id="title"   placeholder="采集名称" class="demo" value="<?php echo $info['title'];?>"/></td>
    <td width="60px">采集地址</td>
    <td width="220px"><input name="url" type="text" id="url"   placeholder="采集地址" class="demo" value="<?php echo $info['url'];?>"/></td>
    <td width="100px">列表编码</td>
	<td ><input type="radio" name="charset" <?php if($info['charset']=='UTF-8'){echo 'checked="checked"'; } ?> value="UTF-8"/>UTF-8 <input type="radio" name="charset" style="margin-left:10px;" value="GBK" <?php if($info['charset']=='GBK'){echo 'checked="checked"'; } ?> />GBK/gb2312</td>
  </tr>
  <tr>
    <td>列表匹配规则</td>
    <td colspan="5" style="vertical-align:middle">
	 <textarea rows="1" name="r_list" id="r_list" style="width:90%"><?php echo htmlspecialchars($info['r_list']);?></textarea><?php if($info['id']){?><a href="getlist.php?id=<?php echo $info['id']; ?>" target="_blank"><img src="Public/main/images/html.png"/></a><?php } ?>
	</td>
  </tr>
  <tr>
     <td>列表根链接</td>
    <td colspan="3"><input style="width:80%" name="root_url" type="text" id="root_url"   placeholder="列表根链接，若为绝对地址则留空即可" class="demo" value="<?php echo $info['root_url'];?>"/></td>
     <td>内容编码</td>
	 <td ><input type="radio" name="detail_charset" <?php if($info['detail_charset']=='UTF-8'){echo 'checked="checked"'; } ?> value="UTF-8"/>UTF-8 <input type="radio" name="detail_charset" style="margin-left:10px;" value="GBK" <?php if($info['detail_charset']=='GBK'){echo 'checked="checked"'; } ?> />GBK/gb2312</td>
  </tr>
   <tr>
   <td>列表时间规则</td>
	<td width="100px;"><input  name="create_time_format" type="text" id="create_time_format"   placeholder="列表时间规则" class="demo" value="<?php echo htmlspecialchars($info['create_time_format']);?>"/></td>
   <td>匹配顺序</td>
	<td ><input type="radio" name="title_first" <?php if($info['title_first']==1){echo 'checked="checked"'; } ?> value="1" />标题在前<input type="radio" name="title_first"  style="margin-left:20px;" value="0" <?php if($info['title_first']==0){echo 'checked="checked"'; } ?>/>时间在前</td>
	<td>内容过滤a链接</td>
	<td><input type="radio" name="is_del_ahref" <?php if($info['is_del_ahref']==1){echo 'checked="checked"'; } ?> value="1"/>是<input type="radio" name="charset"  style="margin-left:20px;" value="0" <?php if($info['is_del_ahref']==0){echo 'checked="checked"'; } ?>/>否</td>
   </tr>
  <tr>
    <td>内容匹配规则</td>
    <td colspan="3" style="vertical-align:middle">
	<textarea rows="1" name="r_detail" id="r_detail" style="width:98%"><?php echo htmlspecialchars($info['r_detail']);?></textarea>
	</td>
    <td>内容右补字符</td>
    <td><input name="detail_right_add" type="text" id="detail_right_add"   placeholder="内容右补位字符，无则留空" class="demo" value="<?php echo $info['detail_right_add'];?>"/></td>
  </tr>
  <tr>
  <?php if($info['id']){ ?>
  <td colspan="3" style="text-align:center;"><input type="submit" name="Submit" value="提交采集规则" class="demo"></td>
  <td colspan="3" style="text-align:center;"><input onclick="gototest()" type="button" name="button" value="测试采集规则" class="demo"></td>
  <?php }else{ ?>
  <td colspan="6" style="text-align:center;"><input type="submit" name="Submit" value="提交采集规则" class="demo"></td>
  <?php } ?>
  </tr>
  <input type="hidden" value="<?php echo $info['id'];?>" name="id"/>
</table>
</form>
</div>
<script>
function gototest(){
	window.open("test.php?id=<?php echo $info['id'];?>");
}
</script>
</body>
</html>