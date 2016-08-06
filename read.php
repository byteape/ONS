<?php
/*采集操作页*/
error_reporting(E_ALL ^E_NOTICE);
date_default_timezone_set('Asia/Shanghai');
/*引入与定义变量开始*/
require 'Library/common.php';
$categoryList = require("Config/categoryList.php");
/*接收参数开始*/
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
                    <option value="0">请选择入库类别</option>
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
	var id="<?php echo $id?$id:0; ?>";
	var geturl=$('#geturl').val();
	var btime=$('#btime').val();
	var etime=$('#etime').val();
	var category_id=$('#category_id').val();
	if(!id){alert('必须输入采集规则id');}
	else if(!geturl){alert('必须输入列表地址');}
	else if(!btime){alert('必须输入开始时间范围');}
	else if(!etime){alert('必须输入结束时间范围');}
	else if(category_id==0){alert('必须选择类别id');}
	else{
		window.parent.coverShow(1);//树型iframe遮盖
        /*再次采集初始化结果窗口开始*/
        $('#allnum').html('<img style="width:15px;height: 15px;vertical-align: middle;margin-left: 10px;" src="Public/layer/skin/default/loading-2.gif"/>');
        $('#yesnum').html('<img style="width:15px;height: 15px;vertical-align: middle;margin-left: 10px;" src="Public/layer/skin/default/loading-2.gif"/>');
        $('#listdataul').html('<img style="width:15px;height: 15px;vertical-align: middle;margin: 0 auto;" src="Public/layer/skin/default/loading-2.gif"/>');//当用户再次采集时，要把document的清空。
        $('#selall').hide();
        $('#getbut').hide();
        /*再次采集初始化结果窗口结束*/
        $('#datalistwindow').show();
		$.ajax({
			type: "post",
			url : "backlist.php",
			dataType:'json',
			data:  {id:id,geturl:geturl,btime:btime,etime:etime,category_id:category_id},
			success: function(data){
				$('#allnum').html(data['allnum']);
                $('#yesnum').html(data['yesnum']);
                var str="";
                for(var i=0;i<data['data'].length;i++){
                    str+='<li><input type="checkbox" name="actionSelected" value="'+data['data'][i]['id']+'"/>'+data['data'][i]['title']+'&nbsp;|&nbsp;'+data['data'][i]['create_time']+'</li>';
                }
                $('#listdataul').html(str);
                $('#selall').show();
                $('#getbut').show();
			}
	    });
	}
}
</script>
<style>
    #listdata ul li{
        list-style:none;
        height: 25px;;
    }
</style>
<!--采集结果数据返回窗口-->
<div style="position: fixed;width: 100%;height: 100%;legt: 0;top: 0;z-index: 20;display: none" id="datalistwindow">
    <i class="mask" style="display: block;position: fixed;width: 100%;height: 100%;left: 0;top: 0;background: rgba(0,0,0,.3);"></i>
    <div style="position: fixed;top: 30%;left: 25%;background-color: #fff;width:50%" id="listdata">
        <div style="padding-left: 20px;border-bottom:1px solid green;line-height: 30px;">总数据:<span id="allnum" style="margin-right: 60px;"><img style="width:15px;height: 15px;vertical-align: middle;margin-left: 10px;" src="Public/layer/skin/default/loading-2.gif"/></span>有效数据:<span id="yesnum" style="margin-right: 60px;"><img style="width:15px;height: 15px;vertical-align: middle;margin-left: 10px;" src="Public/layer/skin/default/loading-2.gif"/></span >以下为有效数据列表：</div>
        <ul id="listdataul" style="height:300px;overflow-y:auto;margin-left:0;padding:20px;">
            <img style="width:15px;height: 15px;vertical-align: middle;margin: 0 auto;" src="Public/layer/skin/default/loading-2.gif"/>
        </ul>
        <div style="margin-left: 20px;margin-bottom: 20px;display: none" id="selall"><input type="checkbox" onclick="CheckAll($(this))"/>全选</div>
        <div style="text-align: center;display: none" id="getbut"><input type="button" onclick="gotoData()" value="采集所选" class="demo"/></div>
    </div>
</div>
<script type="text/javascript">
    //全选与反选
    function CheckAll(e){
        var obj=$("input[name='actionSelected']");
        for(var i=0;i<obj.length;i++){
            if(e.is(':checked')){
                obj[i].checked = true;
            }else{
                obj[i].checked = false;
            }
        }
    }
</script>
<script>
    //采集所选数据
    function gotoData(){
        var id="<?php echo $id?$id:0;  ?>";
       //获取选择的序号
        var obj=document.getElementsByName('actionSelected');
        var s='';
        for(var i=0; i<obj.length; i++){
            if(obj[i].checked) s+=obj[i].value+',';  //如果选中，将value添加到变量s中
        }
        if(s==''){
            alert('您还没有选择任何数据');
        }else{
            $.ajax({
                type: "post",
                url : "action.php",
                dataType:'json',
                data:  {s:s,id:id},
                success: function(data){
                    if(data['result']){
                        alert(data['msg']);
                    }else{
                        alert(data['msg']);
                    }
                    window.parent.coverShow(0);
                    $('#datalistwindow').hide();
                }
            });
        }
    }
</script>
</body>
</html>