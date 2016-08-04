<?php
/*print_r($_SERVER);//系统环境变量
print_r(get_loaded_extensions());//获取php.ini支持的函数模块
print_r(ini_get_all());//获取php.ini的全部配置文件
*/
$ini=ini_get_all();
$fun=get_loaded_extensions();
$fileUrl="Public/template/";
?>
<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ONS主页面</title>
    <link rel="stylesheet" type="text/css" href="Public/main/css/main.css" />
</head>
<body>
    <table class="bordered">
         <tr>
             <td width="100px">当前系统环境</td>
             <td width="300px"><?php echo $_SERVER['SERVER_SOFTWARE'];?></td>
         </tr>
         <tr>
             <td width="100px">CURL函数</td>
             <td width="300px"><?php if(in_array('curl',$fun)){echo "支持";}else{echo "不支持";}?></td>
         </tr>
         <tr>
             <td width="100px">最大执行时间</td>
             <td width="300px"><?php echo $ini['max_execution_time']['global_value'];?>秒</td>
         </tr>
         <tr>
             <td width="100px">Public/template</td>
             <td width="300px"><?php if(!is_dir($fileUrl)){echo "目录不存在";}elseif(!is_writable($fileUrl)){echo "目录没有写入权限";}else{echo "权限正常";} ?></td>
         </tr>
         <tr>
             <td width="100px">软件名称</td>
             <td width="300px">章鱼新闻采集系统(Octopus News System)V1.0</td>
         </tr>
         <tr>
             <td width="100px">版权说明</td>
             <td width="300px">ONS由QQ:1132083961开发，为开源项目，任何第三方使用所造成的结果与本人无关。欢迎合作与二次开发！<br/>后续将会不断升级，敬请关注！<a href="http://wpa.qq.com/msgrd?v=3&amp;uin=1132083961&amp;site=qq&amp;menu=yes" target="_blank"><img border="0" src=" http://wpa.qq.com/pa?p=2:97798819:41" width="74px" height="22px" alt="97798819" style="vertical-align:middle;"></a></td>
         </tr>
    </table>
</body>
</html>
