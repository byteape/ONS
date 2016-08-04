-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 08 月 04 日 02:11
-- 服务器版本: 5.5.20
-- PHP 版本: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `github`
--

-- --------------------------------------------------------

--
-- 表的结构 `oc_content`
--

CREATE TABLE IF NOT EXISTS `oc_content` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `category_id` int(8) NOT NULL COMMENT '类别cid',
  `title` varchar(300) NOT NULL COMMENT '标题',
  `create_time` int(8) NOT NULL COMMENT '发表时间',
  `detail` text NOT NULL COMMENT '文章内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章内容表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `oc_rules`
--

CREATE TABLE IF NOT EXISTS `oc_rules` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL COMMENT '目标网名称标识',
  `url` varchar(200) NOT NULL COMMENT '链接地址',
  `root_url` varchar(300) DEFAULT NULL COMMENT '根链接',
  `charset` varchar(100) NOT NULL DEFAULT 'UTF-8' COMMENT '列表页编码类型',
  `detail_charset` varchar(100) NOT NULL DEFAULT 'UTF-8' COMMENT '内容页编码',
  `r_list` varchar(300) NOT NULL COMMENT '列表匹配规则',
  `title_first` tinyint(1) NOT NULL DEFAULT '1' COMMENT '标题排第一个子元素',
  `create_time_format` varchar(100) DEFAULT NULL COMMENT '时间格式',
  `r_detail` varchar(300) DEFAULT NULL COMMENT '内容匹配规则',
  `detail_right_add` varchar(300) DEFAULT NULL COMMENT '内容右补位字符',
  `is_del_ahref` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否过滤掉ahref标签',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='采集规则表' AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `oc_rules`
--

INSERT INTO `oc_rules` (`id`, `title`, `url`, `root_url`, `charset`, `detail_charset`, `r_list`, `title_first`, `create_time_format`, `r_detail`, `detail_right_add`, `is_del_ahref`) VALUES
(1, '腾讯新闻网', 'http://gd.qq.com/l/edu/wyxx/more.htm', '', 'GBK', 'GBK', '/<li>·<atarget=\\"_blank\\"href=\\"(.*?)\\">(.*?)<\\/a>　<spanclass=\\"pub_time\\">(.*?)<\\/span><\\/li>/i', 1, 'M月d日&#160;H:i', '/<div id=\\"Cnt-Main-Article-QQ\\" bossZone=\\"content\\">([\\s\\S]*?)<\\/div>/', '', 1),
(2, '新浪新闻网', 'http://roll.news.sina.com.cn/news/gnxw/gdxw1/index.shtml', '', 'GBK', 'UTF-8', '/<li><ahref=\\"(.*?)\\"target=\\"_blank\\">(.*?)<\\/a><span>\\((.*?)\\)<\\/span><\\/li>/i', 1, 'm月d日H:i', '/<div class=\\"article article_16\\" id=\\"artibody\\">([\\s\\S]*?)<p class=\\"article-editor\\">/i', '', 1),
(3, '中国健康网', 'http://health.china.com.cn/node_549752.htm', 'http://health.china.com.cn/', 'UTF-8', 'UTF-8', '/<li><spanclass=\\"date\\">(.*?)<\\/span>&#183;<ahref=\\"(.*?)\\">(.*?)<\\/a><\\/li>/i', 0, 'Y-m-d', '/<!--enpcontent-->([\\s\\S]*?)<!--\\/enpcontent-->/i', '', 1),
(4, '腾讯新闻科技快报', 'http://gd.qq.com/digi/cities/index.htm', 'http://gd.qq.com', 'GBK', 'GBK', '/<h3class=\\"mxzxItem\\"><atarget=\\"_blank\\"class=\\"newsTit\\"href=\\"(.*?)\\">(.*?)<\\/a><\\/h3><pclass=\\"newsInfo\\">(?:.*?)<spanclass=\\"date\\">(.*?)<\\/span>/i', 1, 'Y-m-d', '/<div id=\\"Cnt-Main-Article-QQ\\" bossZone=\\"content\\">([\\s\\S]*?)<\\/div>/', '', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
