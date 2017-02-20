<?php
// 是否开启调试模式。部署请设为false，可以提升性能。
define('IS_DEBUG', true);
// 网站根目录
define('WEB_ROOT_PATH',__DIR__.DIRECTORY_SEPARATOR);
// 系统根目录
define('APP_PATH',WEB_ROOT_PATH.'Common'.DIRECTORY_SEPARATOR);
// 定义配置目录
define('APP_CONFIG',WEB_ROOT_PATH.'Config'.DIRECTORY_SEPARATOR);
// 定义缓存目录
define('APP_CACHE',WEB_ROOT_PATH.'Cache'.DIRECTORY_SEPARATOR);
// 定义模版目录
define('APP_TEMPLATE',WEB_ROOT_PATH.'Template'.DIRECTORY_SEPARATOR);
// 定义模块目录
define('APP_MODULE',WEB_ROOT_PATH.'Modules'.DIRECTORY_SEPARATOR);
// 定义插件目录
define('APP_PLUGIN',WEB_ROOT_PATH.'Plugin'.DIRECTORY_SEPARATOR);
if(IS_DEBUG)
{
	require_once APP_PATH.'YurunPHP/Yurun.php';
}
else
{
	require_once APP_PATH.'YurunPHP/Yurun-min.php';
}