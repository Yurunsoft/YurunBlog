/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50547
Source Host           : 127.0.0.1:3306
Source Database       : db_yurunblog

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2017-03-03 17:31:12
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for yb_category
-- ----------------------------
DROP TABLE IF EXISTS `yb_category`;
CREATE TABLE `yb_category` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Parent` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父级分类ID；0-顶级分类',
  `Articles` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类文章数量',
  `Name` varchar(128) NOT NULL DEFAULT '' COMMENT '分类名称',
  `Title` varchar(128) NOT NULL DEFAULT '' COMMENT '在分类页面中显示的标题',
  `Keywords` varchar(1024) NOT NULL DEFAULT '' COMMENT 'SEO关键词',
  `Description` varchar(1024) NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `Index` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '顺序；可选：0-255 越小越靠前',
  `IsShow` bit(1) NOT NULL DEFAULT b'1' COMMENT '是否显示',
  `Alias` varchar(128) NOT NULL DEFAULT '' COMMENT '别名',
  `CategoryTemplate` varchar(255) NOT NULL DEFAULT '' COMMENT '使用的分类模版名称',
  `ArticleTemplate` varchar(255) NOT NULL DEFAULT '' COMMENT '分类下默认文章模版名称',
  `NavigationShow` bit(1) NOT NULL DEFAULT b'0' COMMENT '是否在导航栏显示；0-隐藏 1-显示',
  `Level` int(11) NOT NULL DEFAULT '0' COMMENT '层级',
  PRIMARY KEY (`ID`),
  KEY `alias` (`Alias`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yb_category
-- ----------------------------
INSERT INTO `yb_category` VALUES ('1', '0', '6', '未分类', '未分类', '我是关键词', '我是描述', '0', '', '1', 'default', 'default', '', '0');
INSERT INTO `yb_category` VALUES ('2', '1', '1', '一二三四五', 'test', '', '', '0', '', '2', 'default', 'default', '', '1');

-- ----------------------------
-- Table structure for yb_comment
-- ----------------------------
DROP TABLE IF EXISTS `yb_comment`;
CREATE TABLE `yb_comment` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ContentID` int(11) NOT NULL COMMENT '内容ID',
  `CommentID` int(11) NOT NULL DEFAULT '0' COMMENT '父评论ID；为0则为顶级评论',
  `Status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '评论状态',
  `Content` text NOT NULL COMMENT '评论内容',
  `Name` varchar(32) NOT NULL DEFAULT '' COMMENT '评论人名称',
  `UserID` int(11) NOT NULL DEFAULT '0' COMMENT '评论用户ID',
  `Email` varchar(255) NOT NULL DEFAULT '' COMMENT '评论人邮箱地址',
  `QQ` varchar(11) NOT NULL DEFAULT '' COMMENT '评论人QQ号码',
  `UA` varchar(255) NOT NULL COMMENT '评论者的User Agent',
  `IP` varbinary(16) NOT NULL COMMENT '发布评论的客户端IP（可能是代理地址）',
  `UserIP` varbinary(16) NOT NULL COMMENT '发布评论的用户IP（可能被伪造）',
  `Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '评论时间',
  PRIMARY KEY (`ID`),
  KEY `ContentID` (`ContentID`,`CommentID`,`Status`,`Time`),
  KEY `CommentID` (`CommentID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yb_comment
-- ----------------------------
INSERT INTO `yb_comment` VALUES ('1', '6', '0', '1', '1', 'test', '0', '', '', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.104 Safari/537.36 Core/1.53.2329.400 QQBrowser/9.5.10460.400', 0x00000000000000000000000000000001, 0x00000000000000000000000000000001, '2017-03-02 17:54:41');
INSERT INTO `yb_comment` VALUES ('2', '6', '0', '1', '2', 'test', '0', '', '', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.104 Safari/537.36 Core/1.53.2329.400 QQBrowser/9.5.10460.400', 0x00000000000000000000000000000001, 0x00000000000000000000000000000001, '2017-03-02 17:54:42');
INSERT INTO `yb_comment` VALUES ('3', '6', '0', '1', '3', 'test', '0', '', '', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.104 Safari/537.36 Core/1.53.2329.400 QQBrowser/9.5.10460.400', 0x00000000000000000000000000000001, 0x00000000000000000000000000000001, '2017-03-02 17:54:43');
INSERT INTO `yb_comment` VALUES ('4', '6', '0', '1', '4', 'test', '0', '', '', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.104 Safari/537.36 Core/1.53.2329.400 QQBrowser/9.5.10460.400', 0x00000000000000000000000000000001, 0x00000000000000000000000000000001, '2017-03-02 21:04:50');

-- ----------------------------
-- Table structure for yb_content
-- ----------------------------
DROP TABLE IF EXISTS `yb_content`;
CREATE TABLE `yb_content` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Type` tinyint(3) unsigned NOT NULL COMMENT '数据表内部内容类型；1-文章 2-单页',
  `Status` tinyint(4) NOT NULL COMMENT '状态',
  `Author` int(11) NOT NULL COMMENT '发布人ID',
  `CategoryID` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `Title` varchar(255) NOT NULL DEFAULT '' COMMENT '内容标题',
  `Content` mediumtext NOT NULL COMMENT '内容',
  `Summary` text NOT NULL COMMENT '摘要',
  `Keywords` varchar(1024) NOT NULL DEFAULT '' COMMENT 'SEO关键词',
  `Description` varchar(1024) NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `View` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `Comments` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论数量',
  `CanComment` bit(1) NOT NULL DEFAULT b'1' COMMENT '是否可被评论',
  `Top` bit(1) NOT NULL DEFAULT b'0' COMMENT '是否指定；0-不置顶 1-置顶',
  `Alias` varchar(100) NOT NULL DEFAULT '' COMMENT '别名',
  `Template` varchar(255) NOT NULL DEFAULT '' COMMENT '使用的模版名称',
  `Index` tinyint(4) NOT NULL DEFAULT '0' COMMENT '顺序；可选：0-255 越小越靠前',
  `PostTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发布时间',
  `UpdateTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最后更新时间',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Alias` (`Alias`) USING HASH,
  KEY `CategoryID` (`CategoryID`,`Status`,`UpdateTime`,`PostTime`),
  KEY `Author` (`Author`),
  KEY `Top` (`Top`,`Index`,`UpdateTime`),
  KEY `Type` (`Type`),
  KEY `Index` (`Index`,`UpdateTime`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yb_content
-- ----------------------------
INSERT INTO `yb_content` VALUES ('1', '1', '1', '1', '1', '欢迎使用YurunBlog！', '<p style=\"text-indent: 2em;\">欢迎使用YurunBlog！</p><p style=\"text-indent: 2em;\"><span style=\"text-indent: 32px;\">YurunBlog是一款基于YurunPHP框架开发的博客系统！</span></p>', '<p style=\"white-space: normal; text-indent: 2em;\">欢迎使用YurunBlog！</p><p style=\"white-space: normal; text-indent: 2em;\">YurunBlog是一款基于YurunPHP框架开发的博客系统！</p>', '我是关键词', '我是描述', '2', '0', '', '\0', '1', 'default', '0', '2017-02-24 16:56:06', '2017-02-24 19:59:56');
INSERT INTO `yb_content` VALUES ('2', '1', '1', '1', '1', 'test', '<p>123</p><pre class=\"brush:php;toolbar:false\">&lt;?php\r\nreturn&nbsp;array(\r\n&nbsp;&nbsp;&nbsp;&nbsp;&#39;route&#39;&nbsp;=&gt;&nbsp;array(\r\n&nbsp;&nbsp;&nbsp;&nbsp;	//&nbsp;默认不指定文件时使用的文件名。可不设置。\r\n&nbsp;&nbsp;&nbsp;&nbsp;	&#39;default_file&#39;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;=&gt;&nbsp;&#39;index.php&#39;,\r\n&nbsp;&nbsp;&nbsp;&nbsp;	//&nbsp;是否隐藏默认文件名，不设置时默认为false\r\n&nbsp;&nbsp;&nbsp;&nbsp;	&#39;hide_default_file&#39;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;=&gt;&nbsp;true,\r\n&nbsp;&nbsp;&nbsp;&nbsp;	//&nbsp;路由规则\r\n&nbsp;&nbsp;&nbsp;&nbsp;	&#39;rules&#39;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;=&gt;&nbsp;array(\r\n&nbsp;&nbsp;&nbsp;&nbsp;		&#39;all/[page:int]&#39;					=&gt;	&#39;Home/Index/index&#39;,\r\n&nbsp;&nbsp;&nbsp;&nbsp;		&#39;&#39;									=&gt;	&#39;Home/Index/index&#39;,\r\n&nbsp;&nbsp;&nbsp;&nbsp;		&#39;admin&#39;								=&gt;	&#39;Admin/Index/index&#39;,\r\n&nbsp;&nbsp;&nbsp;&nbsp;		&#39;Api/[control]/[action]&#39;			=&gt;	&#39;Public/Api/call&#39;,\r\n			&#39;a/[Alias:word].html&#39;				=&gt;	&#39;Home/Article/view&#39;,\r\n			&#39;[Alias:word].html&#39;					=&gt;	&#39;Home/Page/view&#39;,\r\n&nbsp;&nbsp;&nbsp;&nbsp;		&#39;[Alias:word]/[page:int]&#39;			=&gt;	&#39;Home/Article/list&#39;,\r\n&nbsp;&nbsp;&nbsp;&nbsp;		&#39;[Alias:word]&#39;						=&gt;	&#39;Home/Article/list&#39;,\r\n&nbsp;&nbsp;&nbsp;&nbsp;	)\r\n&nbsp;&nbsp;&nbsp;&nbsp;)\r\n);</pre><p><br/></p>', '<p>12321321321321321</p>', '', '', '1', '0', '', '\0', '2', 'default', '0', '2017-02-24 17:05:20', '2017-02-25 13:56:09');
INSERT INTO `yb_content` VALUES ('3', '1', '1', '1', '1', '特特gas多个', '<h3>12312321<br/></h3><ol class=\" list-paddingleft-2\" style=\"list-style-type: decimal;\"><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li><li><p>a</p></li><li><p>b</p></li><li><p>c</p></li></ol><h3>aaa<br/></h3><p>1231<br/></p><h3>bbb<br/></h3><p>123213</p>', '', '', '', '5', '1', '', '\0', '3', 'default', '0', '2017-02-25 15:42:52', '2017-03-03 15:59:26');
INSERT INTO `yb_content` VALUES ('4', '1', '1', '1', '1', '噶第三个多萨666', '<p>噶圣诞过得</p>', '', '', '', '44', '0', '', '\0', '4', 'default', '0', '2017-02-27 16:40:33', '2017-03-03 16:03:32');
INSERT INTO `yb_content` VALUES ('5', '1', '1', '1', '1', '搜狗的', '<p>广东萨嘎速度</p><p><a href=\"//localhost:2222/Static/Upload/2017/03/02/1488440096636413.png\" target=\"_blank\"><img src=\"//localhost:2222/Static/Upload/2017/03/02/11488440096636413.png\" title=\"1488440096636413.png\" alt=\"宇润软件LOGO.png\"/></a></p>', '', '', '', '114', '0', '\0', '\0', '5', 'default', '0', '2017-02-27 16:41:19', '2017-03-03 17:28:14');
INSERT INTO `yb_content` VALUES ('6', '1', '1', '1', '2', '特色公司的', '<p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, &quot;PingFang SC&quot;, 微软雅黑, Tahoma, Arial, sans-serif; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\">今天用Navicat遇到个问题，在新建查询时候提示“Cannot create file …… 拒绝访问”。下面给出两种解决方法。<br style=\"box-sizing: border-box;\"/></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, &quot;PingFang SC&quot;, 微软雅黑, Tahoma, Arial, sans-serif; white-space: normal; background-color: rgb(255, 255, 255); text-align: center;\"><img src=\"http://blog.yurunsoft.com/Static/Upload/2017/02/28/1488275597666316.jpg\" title=\"Navicat提示“Cannot create file …… 拒绝访问”\" alt=\"Navicat提示“Cannot create file …… 拒绝访问”\" width=\"931\" height=\"127\" border=\"0\" vspace=\"0\" layer-index=\"0\" style=\"box-sizing: border-box; border: none; vertical-align: middle; display: inline-block; max-width: 100%; height: 127px; width: 931px;\"/></p><h3 style=\"box-sizing: border-box; font-family: &quot;Helvetica Neue&quot;, Helvetica, &quot;PingFang SC&quot;, 微软雅黑, Tahoma, Arial, sans-serif; font-weight: 400; line-height: 1.1; color: rgb(0, 150, 136); margin: 10px 0px; font-size: 2.25rem; padding: 4px 8px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); border-left: 5px solid rgb(0, 150, 136); border-radius: 0px 2px 2px 0px; white-space: normal; background-color: rgb(255, 255, 255);\">方法一</h3><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, &quot;PingFang SC&quot;, 微软雅黑, Tahoma, Arial, sans-serif; white-space: normal; background-color: rgb(255, 255, 255);\">以管理员身份运行<span style=\"box-sizing: border-box; text-indent: 32px;\">Navicat。</span></p><h3 style=\"box-sizing: border-box; font-family: &quot;Helvetica Neue&quot;, Helvetica, &quot;PingFang SC&quot;, 微软雅黑, Tahoma, Arial, sans-serif; font-weight: 400; line-height: 1.1; color: rgb(0, 150, 136); margin: 10px 0px; font-size: 2.25rem; padding: 4px 8px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); border-left: 5px solid rgb(0, 150, 136); border-radius: 0px 2px 2px 0px; white-space: normal; background-color: rgb(255, 255, 255);\">方法二</h3><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, &quot;PingFang SC&quot;, 微软雅黑, Tahoma, Arial, sans-serif; white-space: normal; background-color: rgb(255, 255, 255);\">1、在数据库上<span style=\"box-sizing: border-box; font-weight: 700;\">右键</span>——<span style=\"box-sizing: border-box; font-weight: 700;\">连接属性</span><br style=\"box-sizing: border-box;\"/></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, &quot;PingFang SC&quot;, 微软雅黑, Tahoma, Arial, sans-serif; white-space: normal; background-color: rgb(255, 255, 255);\"><img src=\"http://blog.yurunsoft.com/Static/Upload/2017/02/28/1488275620294126.jpg\" title=\"Navicat新建查询报错的问题两种解决方法\" width=\"274\" height=\"155\" border=\"0\" vspace=\"0\" alt=\"Navicat新建查询报错的问题两种解决方法\" layer-index=\"1\" style=\"box-sizing: border-box; border: none; vertical-align: middle; display: inline-block; max-width: 100%; height: 155px; width: 274px;\"/></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, &quot;PingFang SC&quot;, 微软雅黑, Tahoma, Arial, sans-serif; white-space: normal; background-color: rgb(255, 255, 255);\">2、<span style=\"box-sizing: border-box; font-weight: 700;\">高级</span>——<span style=\"box-sizing: border-box; font-weight: 700;\">设置位置</span>，修改路径即可。</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, &quot;PingFang SC&quot;, 微软雅黑, Tahoma, Arial, sans-serif; white-space: normal; background-color: rgb(255, 255, 255);\"><img src=\"http://blog.yurunsoft.com/Static/Upload/2017/02/28/1488275620393385.jpg\" title=\"Navicat新建查询报错的问题两种解决方法\" width=\"486\" height=\"549\" border=\"0\" vspace=\"0\" alt=\"Navicat新建查询报错的问题两种解决方法\" layer-index=\"2\" style=\"box-sizing: border-box; border: none; vertical-align: middle; display: inline-block; max-width: 100%; height: 549px; width: 486px;\"/></p><h3 style=\"box-sizing: border-box; font-family: &quot;Helvetica Neue&quot;, Helvetica, &quot;PingFang SC&quot;, 微软雅黑, Tahoma, Arial, sans-serif; font-weight: 400; line-height: 1.1; color: rgb(0, 150, 136); margin: 10px 0px; font-size: 2.25rem; padding: 4px 8px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); border-left: 5px solid rgb(0, 150, 136); border-radius: 0px 2px 2px 0px; white-space: normal; background-color: rgb(255, 255, 255);\">原因分析</h3><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, &quot;PingFang SC&quot;, 微软雅黑, Tahoma, Arial, sans-serif; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\">navicat新建查询时，会获取上次临时执行的sql，以及保存的查询。这些查询会以.sql文件保存在上面设置的路径中。</p><p><br/></p>', '', '', '', '870', '50', '', '\0', '6', 'default', '0', '2017-02-27 18:59:56', '2017-03-03 16:02:54');

-- ----------------------------
-- Table structure for yb_dict
-- ----------------------------
DROP TABLE IF EXISTS `yb_dict`;
CREATE TABLE `yb_dict` (
  `Name` varchar(64) NOT NULL,
  `Type` varchar(64) NOT NULL DEFAULT '',
  `Text` varchar(64) NOT NULL,
  `Value` varchar(255) NOT NULL,
  PRIMARY KEY (`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yb_dict
-- ----------------------------
INSERT INTO `yb_dict` VALUES ('COMMENT_STATUS', '', '评论状态', '');
INSERT INTO `yb_dict` VALUES ('COMMENT_STATUS_NORMAL', 'COMMENT_STATUS', '正常', '1');
INSERT INTO `yb_dict` VALUES ('COMMENT_STATUS_VERIFY_NOT_PASS', 'COMMENT_STATUS', '审核不通过', '3');
INSERT INTO `yb_dict` VALUES ('COMMENT_STATUS_WAIT_VERIFY', 'COMMENT_STATUS', '等待审核', '2');
INSERT INTO `yb_dict` VALUES ('CONTENT_STATUS', '', '内容状态', '');
INSERT INTO `yb_dict` VALUES ('CONTENT_STATUS_DRAFT', 'CONTENT_STATUS', '草稿', '4');
INSERT INTO `yb_dict` VALUES ('CONTENT_STATUS_HIDE', 'CONTENT_STATUS', '隐藏', '5');
INSERT INTO `yb_dict` VALUES ('CONTENT_STATUS_NORMAL', 'CONTENT_STATUS', '正常', '1');
INSERT INTO `yb_dict` VALUES ('CONTENT_STATUS_VERIFY_NOT_PASS', 'CONTENT_STATUS', '审核不通过', '3');
INSERT INTO `yb_dict` VALUES ('CONTENT_STATUS_WAIT_VERIFY', 'CONTENT_STATUS', '等待审核', '2');
INSERT INTO `yb_dict` VALUES ('CONTENT_TYPE', '', '内容类型', '');
INSERT INTO `yb_dict` VALUES ('CONTENT_TYPE_ARTICLE', 'CONTENT_TYPE', '文章', '1');
INSERT INTO `yb_dict` VALUES ('CONTENT_TYPE_PAGE', 'CONTENT_TYPE', '页面', '2');
INSERT INTO `yb_dict` VALUES ('EX_DATA_TYPE', '', '扩展数据类型', '');
INSERT INTO `yb_dict` VALUES ('EX_DATA_TYPE_ARTICLE', 'EX_DATA_TYPE', '文章', '1');
INSERT INTO `yb_dict` VALUES ('EX_DATA_TYPE_CATEGORY', 'EX_DATA_TYPE', '分类', '2');
INSERT INTO `yb_dict` VALUES ('EX_DATA_TYPE_COMMENT', 'EX_DATA_TYPE', '评论', '4');
INSERT INTO `yb_dict` VALUES ('EX_DATA_TYPE_PAGE', 'EX_DATA_TYPE', '页面', '3');
INSERT INTO `yb_dict` VALUES ('TAG_TYPE', '', '标签类型', '');
INSERT INTO `yb_dict` VALUES ('TAG_TYPE_CONTENT', 'TAG_TYPE', '内容标签', '1');

-- ----------------------------
-- Table structure for yb_exdata
-- ----------------------------
DROP TABLE IF EXISTS `yb_exdata`;
CREATE TABLE `yb_exdata` (
  `Type` tinyint(3) unsigned NOT NULL COMMENT '扩展数据类型；1-文章 2-单页 3-分类',
  `AssocPk` int(10) unsigned NOT NULL COMMENT '如果是文章则是文章ID；如果是分类则是分类ID；同理……',
  `Key` varchar(255) NOT NULL COMMENT '键',
  `Value` mediumtext NOT NULL COMMENT '值',
  PRIMARY KEY (`Type`,`AssocPk`,`Key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yb_exdata
-- ----------------------------

-- ----------------------------
-- Table structure for yb_tag
-- ----------------------------
DROP TABLE IF EXISTS `yb_tag`;
CREATE TABLE `yb_tag` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '标签位ID',
  `Type` tinyint(4) unsigned NOT NULL COMMENT '标签位类型',
  `Name` varchar(32) NOT NULL COMMENT '标签位名称',
  `Code` varchar(32) NOT NULL COMMENT '标签位代码',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Type` (`Type`,`Code`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yb_tag
-- ----------------------------
INSERT INTO `yb_tag` VALUES ('1', '1', 'a', 'a');
INSERT INTO `yb_tag` VALUES ('2', '1', 'b', 'b');
INSERT INTO `yb_tag` VALUES ('3', '1', 'c', 'c');
INSERT INTO `yb_tag` VALUES ('4', '1', 'd', 'd');
INSERT INTO `yb_tag` VALUES ('5', '1', 'test', 'test');
INSERT INTO `yb_tag` VALUES ('6', '1', '测试', '测试');
INSERT INTO `yb_tag` VALUES ('7', '1', '测试1', '测试1');
INSERT INTO `yb_tag` VALUES ('8', '1', '测试2', '测试2');
INSERT INTO `yb_tag` VALUES ('9', '1', '测试3', '测试3');
INSERT INTO `yb_tag` VALUES ('10', '1', '1', '1');
INSERT INTO `yb_tag` VALUES ('11', '1', '2', '2');
INSERT INTO `yb_tag` VALUES ('12', '1', '3', '3');
INSERT INTO `yb_tag` VALUES ('13', '1', '啦啦啦', '啦啦啦');

-- ----------------------------
-- Table structure for yb_tag_relation
-- ----------------------------
DROP TABLE IF EXISTS `yb_tag_relation`;
CREATE TABLE `yb_tag_relation` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TagID` int(11) NOT NULL COMMENT '标签ID',
  `RelationID` int(11) NOT NULL COMMENT '关联数据的ID',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `TagID` (`TagID`,`RelationID`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yb_tag_relation
-- ----------------------------
INSERT INTO `yb_tag_relation` VALUES ('15', '1', '4');
INSERT INTO `yb_tag_relation` VALUES ('5', '1', '5');
INSERT INTO `yb_tag_relation` VALUES ('16', '2', '4');
INSERT INTO `yb_tag_relation` VALUES ('6', '5', '5');
INSERT INTO `yb_tag_relation` VALUES ('10', '6', '3');
INSERT INTO `yb_tag_relation` VALUES ('11', '9', '3');
INSERT INTO `yb_tag_relation` VALUES ('17', '13', '4');

-- ----------------------------
-- Table structure for yb_user
-- ----------------------------
DROP TABLE IF EXISTS `yb_user`;
CREATE TABLE `yb_user` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `LevelID` tinyint(4) NOT NULL COMMENT '级别ID',
  `Username` varchar(32) NOT NULL COMMENT '用户名',
  `Name` varchar(32) NOT NULL,
  `Password` varchar(32) NOT NULL COMMENT '密码',
  `Email` varchar(255) NOT NULL DEFAULT '' COMMENT '邮箱',
  `QQ` varchar(16) NOT NULL DEFAULT '' COMMENT 'QQ',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yb_user
-- ----------------------------
INSERT INTO `yb_user` VALUES ('1', '1', 'admin', '管理员', '85be93f7f51fce6dd0c0d702e98c86c8', '', '');

-- ----------------------------
-- Table structure for yb_user_level
-- ----------------------------
DROP TABLE IF EXISTS `yb_user_level`;
CREATE TABLE `yb_user_level` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(32) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yb_user_level
-- ----------------------------
INSERT INTO `yb_user_level` VALUES ('1', '管理员');
INSERT INTO `yb_user_level` VALUES ('2', '普通用户');
