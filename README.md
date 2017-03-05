#YurunBlog

##简介

YurunBlog 基于 YurunPHP 框架开发，目前这还属于Demo阶段，暂时只推荐用于学习了解 YurunPHP 框架。

时间紧迫，很多地方不够完善，许多功能没有来得及开发，系统结构后续可能会发生变化。

这个开源项目我会持续开发更新，也欢迎有兴趣的朋友一起来完善 YurunBlog 。

YurunBlog不追求什么功能强大，也不追求什么轻便简洁。反正功能强大的东西早晚要被嫌弃臃肿，轻便简洁的会嫌弃他功能少，久而久之轻便简洁也慢慢变臃肿。没有任何意义，实用就行。

演示地址（宇润的博客）：http://blog.yurunsoft.com/

##配置说明

* 首先确认你的Apache/Nginx支持pathinfo

* 将 `Db/db_yurunblog.sql` 导入数据库

* 打开 `Src/Config/debug.php` 修改数据库配置

##目录说明

Cache    	缓存目录

Common		公用文件目录

Config		配置文件目录

Logs		日志目录

Modules		各模块目录

---- Admin		后台

---- Home		前台

---- Plugin		插件

---- Public		公用模块，现在放的是公共API
    
Plugin		插件目录

Static		静态文件目录，防止css/js/图片等

Template	前台模版目录

index.php	入口文件

###更多说明请阅读`Document\开发说明.txt`