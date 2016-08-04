<?php
if(isset($_POST['install'])){
    $files=dirname(__FILE__)."/Config/config.php";
				if(!is_writable($files)){ 
				    echo $files."<font color=red>不可写！！！</font>";
				}else{ 
						$db_type=$_POST['type'];
						$db_host=$_POST['host'];
						$db_name=$_POST['name'];
						$db_user=$_POST['user'];
						$db_pwd=$_POST['pwd'];
						$db_prefix=$_POST['prefix'];

						
						$config_str = "<?php"; 
						$config_str .= "\n"; 
						$config_str .= 'return array(';
						$config_str .= "\n"; 
						$config_str .= '"DB_HOST"=> "' . $db_host . '",';
						$config_str .= "\n"; 
						$config_str .= '"DB_NAME" => "' . $db_name . '",'; 
						$config_str .= "\n"; 
						$config_str .= '"DB_USER" => "' . $db_user . '",'; 
						$config_str .= "\n"; 
						$config_str .= '"DB_PWD" => "' . $db_pwd . '",'; 
						$config_str .= "\n"; 
						$config_str .= '"DB_PREFIX"=>"' . $db_prefix . '",'; 
						$config_str .= "\n"; 
						$config_str .= '"DB_CHARSET"=>"utf8",';
						$config_str .= "\n"; 
						$config_str .= '"DB_TYPE"=>"mysql",';
						$config_str .= "\n";
                        $config_str .= '"DB_PCONNECT"=>false,';
                        $config_str .= "\n";
                        $config_str .= '"DB_PORT"=>"3306",';
                        $config_str .= "\n";
                    $config_str .= ');?>';
						$ff = fopen($files, "w+"); 
						fwrite($ff, $config_str); 
						//===================== 
						include_once (dirname(__FILE__)."/Config/config.php");
						if (!@$link = mysql_connect($db_host, $db_user, $db_pwd)) { 
						     echo "数据库连接失败! 请返回上一页检查连接参数 <a href=install.php>返回修改</a>"; 
						} else { 							
							mysql_query("CREATE DATABASE `$db_name`"); 
							mysql_select_db($db_name); 
							$sql_query[] = "CREATE TABLE IF NOT EXISTS `" . $db_prefix . "content` (
                              `id` int(8) NOT NULL AUTO_INCREMENT,
                              `category_id` int(8) NOT NULL COMMENT '类别cid',
                              `title` varchar(300) NOT NULL COMMENT '标题',
                              `create_time` int(8) NOT NULL COMMENT '发表时间',
                              `detail` text NOT NULL COMMENT '文章内容',
                              PRIMARY KEY (`id`)
                            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章内容表' AUTO_INCREMENT=1 ;";
							
							$sql_query[] = "CREATE TABLE IF NOT EXISTS `" . $db_prefix . "rules` (
                              `id` int(8) NOT NULL AUTO_INCREMENT,
                              `title` varchar(100) NOT NULL COMMENT '目标网名称标识',
                              `url` varchar(200) NOT NULL COMMENT '链接地址',
                              `root_url` varchar(300) DEFAULT NULL COMMENT '根链接',
                              `charset` varchar(100) NOT NULL DEFAULT 'UTF-8' COMMENT '列表页编码类型',
                              `detail_charset` varchar(100) NOT NULL DEFAULT 'UTF-8' COMMENT '内容页编码',
                              `r_list` varchar(300) NOT NULL COMMENT '列表匹配规则',
                              `title_first` tinyint(1) NOT NULL DEFAULT '1' COMMENT '标题排第一个子元素',
                              `create_time_format` varchar(100) NOT NULL COMMENT '时间格式',
                              `r_detail` varchar(300) NOT NULL COMMENT '内容匹配规则',
                              `detail_right_add` varchar(300) NOT NULL COMMENT '内容右补位字符',
                              `is_del_ahref` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否过滤掉ahref标签',
                              PRIMARY KEY (`id`)
                            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='采集规则表' AUTO_INCREMENT=4 ;";
							
							
							$sql_query[] = "INSERT INTO `" . $db_prefix . "rules` (`id`, `title`, `url`, `root_url`, `charset`, `detail_charset`, `r_list`, `title_first`, `create_time_format`, `r_detail`, `detail_right_add`, `is_del_ahref`) VALUES
(1, '腾讯新闻网', 'http://gd.qq.com/l/edu/wyxx/more.htm', '', 'GBK', 'GBK', '".addslashes('/<li>·<atarget=\"_blank\"href=\"(.*?)\">(.*?)<\/a>　<spanclass=\"pub_time\">(.*?)<\/span><\/li>/i')."', 1, 'M月d日&#160;H:i', '".addslashes('/<div id=\"Cnt-Main-Article-QQ\" bossZone=\"content\">([\s\S]*?)<\/div>/')."', '', 1),
(2, '新浪新闻网', 'http://roll.news.sina.com.cn/news/gnxw/gdxw1/index.shtml', '', 'GBK', 'UTF-8', '".addslashes('/<li><ahref=\"(.*?)\"target=\"_blank\">(.*?)<\/a><span>\((.*?)\)<\/span><\/li>/i')."', 1, 'm月d日H:i', '".addslashes('/<div class=\"article article_16\" id=\"artibody\">([\s\S]*?)<p class=\"article-editor\">/i')."', '', 1),
(3, '中国健康网', 'http://health.china.com.cn/node_549752.htm', 'http://health.china.com.cn/', 'UTF-8', 'UTF-8', '".addslashes('/<li><spanclass=\"date\">(.*?)<\/span>&#183;<ahref=\"(.*?)\">(.*?)<\/a><\/li>/i')."', 0, 'Y-m-d', '".addslashes('/<!--enpcontent-->([\s\S]*?)<!--\/enpcontent-->/i')."', '', 1),
(4, '腾讯新闻科技快报', 'http://gd.qq.com/digi/cities/index.htm', 'http://gd.qq.com', 'GBK', 'GBK',  '".addslashes('/<h3class=\"mxzxItem\"><atarget=\"_blank\"class=\"newsTit\"href=\"(.*?)\">(.*?)<\/a><\/h3><pclass=\"newsInfo\">(?:.*?)<spanclass=\"date\">(.*?)<\/span>/i')."', 1, 'Y-m-d',  '".addslashes('/<div id=\"Cnt-Main-Article-QQ\" bossZone=\"content\">([\s\S]*?)<\/div>/')."', '', 1);;";
							//================================
							foreach($sql_query as $val){  
							    mysql_query("set names utf8");
							    mysql_query($val);
							}
                            //写入锁文件
                            $lockfile=dirname(__FILE__)."/Data/lock.html";
                            $lo = fopen($lockfile, "w+");
                            fwrite($lo, "");
                            echo '<script>alert("恭喜您，ONS采集系统安装成功! 版本号:ONSV1.0发布版");location.href="index.php"</script>';
                            @unlink("install.php");
						} 
				} 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ONSV1.0发布版</title>
<style type="text/css">
<!--
.STYLE1 {
	font-size: 12px;
	font-weight: bold;
}
.STYLE2 {
	font-size: 11px;
	color:red;
}
a:link {color: #0044BB}
a:visited {color: #0044BB}
a:hover {color: #0044BB}
a:active {color: #0044BB}
-->
</style>
</head>
<body style="margin:0 auto; width:500px;">
<div style="width:400px; height:200px; margin-top:130px;margin-right:100px">
<div><img src="Public/main/images/logo.png"/>ONS采集系统</div>
<div>--Powered by wgl</div>
<div>作者：王国梁  </div>
  <form id="form1" name="form1" method="post" action="">
    <table width="1000" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
		<td>&nbsp;</td>
      </tr>
      <tr>
        <td><div align="right" class="STYLE1">数据库类型：</div></td>
        <td><input type="text" name="type" value="mysql"/></td>
      </tr>
      <tr>
        <td><div align="right" class="STYLE1">服务器地址：</div></td>
        <td><input type="text" name="host" value="localhost"/></td>
      </tr>
       <tr>
        <td><div align="right" class="STYLE1">数据库名：</div></td>
        <td><input type="text" name="name"/></td>
      </tr>
	  <tr>
        <td><div align="right" class="STYLE1">数据表前缀：</div></td>
        <td><input type="text" name="prefix" value="os_"/></td>
      </tr>
	    <tr>
        <td><div align="right" class="STYLE1">数据库用户名：</div></td>
        <td><input type="text" name="user" value="root"/></td>
      </tr>
      <tr>
        <td><div align="right" class="STYLE1">数据库密码：</div></td>
        <td><input type="text" name="pwd"/><span class="STYLE2">*如果您使用wamp建站，默认密码留空</span></td>
		<td></td>
      </tr>
      <tr style="line-height: 60px;">
        <td height="20">&nbsp;</td>
        <td height="20" colspan="3"><input type="submit" name="install" value="一键安装"/></td>
      </tr>
    </table>
  </form>
</div>
</body>
</html>