<?php
    header("Content-Type:text/html;charset=utf-8");
    require 'Library/common.php';//引入数据库实例
    $resultList=$M->table('rules')->select();
?>
<!DOCTYPE html>
<html>
<head>
	<title>ONS导航树</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8">
	<style type="text/css">@import url('Public/main/css/dtree/dtree.css');</style>
	<script type="text/javascript">var dtreeIconBasePath = "Public/main/css/dtree";</script>
	<script language="javascript" src="Public/main/css/dtree/dtree.js"></script>
    <link rel="stylesheet" type="text/css" href="Public/main/css/tree.css" />
</head>
<body>
<script type="text/javascript"> 
var treeMenu = [
	{ level:1, name:"系统检测", ico:"Public/main/images/icon_default.gif",link:"main.php"},
    { level:1, name:"采集管理", ico:"Public/main/images/icon_default.gif",link:"manage.php"},
	<?php if($resultList) { ?>
	{ level:1, name:"采集列表"},
	<?php for($i=0;$i<count($resultList);$i++){?>
	{ level:2, name:"<?php echo $resultList[$i]['title']; ?>", ico:"Public/main/images/icon_default.gif",link:"<?php echo "read.php?id=".$resultList[$i]['id'] ?>"},
	<?php } }?>
];
</script>
<div id="menuControll">
    菜单控制:【<a href="#" onclick="tree.openAll();this.blur();return false;" style="color:#333333;text-decoration:none">展开</a>】
    【<a href="#" onclick="tree.closeAll();this.blur();return false;" style="color:#333333;text-decoration:none">折叠</a>】
</div>
<div class="dtree" style="margin:10px;">
<script type="text/javascript"> 
//建立新树
tree = new dTree('tree');
tree.config.target = "MainFrame";
tree.config.useCookies = false;
var selNum = -1;
var link = "";
//根目录
tree.add(0,-1,'管理中心', null, null, null, '', '');
var count = 0;
var pLevelIdArray = new Array();
pLevelIdArray[1] = 0;
var currLevel = 1;
for (var i=0; i<treeMenu.length; i++) {
	var item = treeMenu[i];
	var itemLevel = item.level;
	pLevelIdArray[itemLevel+1] = ++count;
	if (item.link!=null && item.link!="") {
		if (item.ico!=null) {
			tree.add(count, pLevelIdArray[itemLevel], item.name, item.link, null, null, item.ico, item.ico);
		} else {
			tree.add(count, pLevelIdArray[itemLevel], item.name, item.link);
		}
	} else {
		if (item.ico!=null) {
			tree.add(count, pLevelIdArray[itemLevel], item.name, null, null, null, item.ico, item.ico);
		} else {
			tree.add(count, pLevelIdArray[itemLevel], item.name);
		}
	}
	if (item.select) {
		selNum = count;
		link = item.link;
	}
}
document.write(tree);
tree.openAll();
if (selNum != -1) {
	tree.openTo(selNum,true);
	top.document.frames["MainFrame"].location.href=link;
}
</script>
</div>
</body>
</html>