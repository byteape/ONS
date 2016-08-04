<?php
require 'Library/common.php';
$resultList=$M->table('rules')->select();
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
    <link rel="stylesheet" href="Public/main/css/manage.css" type="text/css" media="all" />
</header>
<body>
<div class="content">
 <div class="nav">
     <a class="sel" href="manage.php">采集列表</a>
   <a href="edit.php">添加采集</a>
</div>
<div>
<table class="bordered">
  <thead><tr>
    <td>ID</td>
    <td>采集名称</td>
    <td>链接地址</td>
    <td>列表编码类型</td>
	<td>内容编码类型</td>
    <td>操作</td>
  </tr>
  </thead>
  <tbody>
  <?php for($i=0;$i<count($resultList);$i++){ ?>
  <tr>
	<td><?php echo $resultList[$i]['id'] ;?></td>
	<td><?php echo $resultList[$i]['title'] ;?></td>
	<td><?php echo $resultList[$i]['url'] ;?></td>
	<td><?php echo $resultList[$i]['charset'] ;?></td>
	<td><?php echo $resultList[$i]['detail_charset'] ;?></td>
	<td><a class="btn_a" href="edit.php?id=<?php echo $resultList[$i]['id']; ?>">修改</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn_a2" href="del.php?id=<?php echo $resultList[$i]['id']; ?>"" onclick="return confirm('确定删除吗？')">删除</a></td>
  </tr>  
  <?php } ?>
  </tbody>
  <tfoot>
    <tr><td colspan="9"><div class="page"><div>    </div></div></td>
  </tr>
  </tfoot>
</table>
</div>
</body>
</html>