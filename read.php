<?php
error_reporting(E_ALL ^E_NOTICE);
date_default_timezone_set('Asia/Shanghai');
require 'Library/common.php';
$categoryList = require("Config/categoryList.php");
$id=$_GET['id'];
$resultRule=$M->table('rules')->where(array('id'=>$id))->find();
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
</header>
<body>
<div id="navi">
	<div id='naviDiv'>
		<span><img src="Public/main/images/arror.gif" width="7" height="11" border="0" alt=""></span>&nbsp;<?php echo $resultRule['title']; ?>采集<span>&nbsp;
	</div>
</div>
<div id="mainContainer" style="height:700px;width:100%;padding:0px;margin:0px;">
<div style="width:100%;height:100%;float:left;">
	<p class="name">
	    <label style="font-size:18px;"><?php echo $resultRule['title']; ?>（<?php echo $resultRule['url']; ?>）</label>
	</p>
	<form name="form1" method="post" >
		<p class="name">
			<input name="geturl" type="text" id="geturl"   placeholder="链接地址" class="demo" value="<?php echo $SaveData['page_start'];?>"/>
		</p>
		<p class="name">
			<input style="width:30%;" name="btime" type="text"  id="btime"  onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" placeholder="开始时间" class="demo" readOnly  />
			<input style="width:30%;" name="etime" type="text"  id="etime"  onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" placeholder="结束时间" class="demo" readOnly  />
		</p>
        <p class="name">
			<select id="category_id" class="demo" style="width:30%;">
			    <option >请选择入库类别</option>
			<?php for($i=0;$i<count($categoryList);$i++){ ?>
			    <option value="<?php echo $categoryList[$i]['category_id'];?>"><?php echo $categoryList[$i]['category_name'];?></option>
		    <?php  } ?>
			</select>
		</p>
		<p>
			<div id="setf" ><input type="button" onclick="sendAction()" id="settxt" value="开始采集" style="width:220px;cursor:pointer;" class="demo"/></div>
		</p>
    </form>
</div>
</div>
<script>
function sendAction(){
	var id="<?php echo $id?$id:0;  ?>";
	var geturl=$('#geturl').val();
	var btime=$('#btime').val();
	var etime=$('#etime').val();
	var category_id=$('#category_id').val();
	//alert(geturl);alert(btime);alert(etime);alert(category_id);
	if(!id){alert('必须输入采集规则id');}
	else if(!geturl){alert('必须输入列表地址');}
	else if(!btime){alert('必须输入开始时间范围');}
	else if(!etime){alert('必须输入结束时间范围');}
	else if(!category_id){alert('必须选择类别id');}
	else{
		window.parent.coverShow(1);
		$.ajax({
			type: "post",
			url : "action.php",
			dataType:'json',
			data:  {id:id,geturl:geturl,btime:btime,etime:etime,category_id:category_id},
			success: function(data){
				if(data['result']){
					alert(data['msg']);
				}else{
					alert(data['msg']);
				}
				window.parent.coverShow(0);
			}
	    });
	}
}
</script>
</body>
</html>