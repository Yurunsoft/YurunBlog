<?php
defined('YURUN_START') or exit;
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
Event::register('YP_URL_CREATE', function(&$data){
	UrlHelper::parse($data);
});