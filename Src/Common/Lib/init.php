<?php
defined('YURUN_START') or exit;
/**
 * YurunBlog版本信息
 */
define('YB_VERSION','0.0.3');
/**
 * YurunBlog字母版本号
 * @Dev 开发版
 * @Beta 测试版
 * @Release 正式版
 */
define('YB_VERSION_STAGE','Dev');
// header头中的版本信息
header('X-Powered-By:YurunBlog ' . YB_VERSION . ' ' . YB_VERSION_STAGE);
// 加载项目函数库
require_once APP_LIB . 'function.php';
Event::register('YURUN_APP_LOAD_COMPLETE',function(){
    // 处理字典数据
    $m = new DictModel;
    if(IS_DEBUG)
    {
        $m->generateConst();
    }
    $m->loadDict();
    // 启用插件
    Plugin::load();
    // 启用任务
    Task::init();
});
/**
 * 生成URL
 */
Event::register('YP_URL_CREATE', function($data){
	UrlHelper::parse($data);
});
/**
 * 前台页面加载事件
 */
Event::register('YB_HOME_ONLOAD',function($data){
	$categoryModel = new CategoryModel();
	$data['control']->view->categorys = $categoryModel->onlyGetShow()->getAssocList();
});
/**
 * 加载主题的自定义处理文件
 */
$file = APP_TEMPLATE . Config::get('@.THEME') . '/theme.php';
if(is_file($file))
{
    include_once $file;
}
unset($file);