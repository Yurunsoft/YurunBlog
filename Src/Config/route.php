<?php
return array(
    'route' => array(
    	// 默认不指定文件时使用的文件名。可不设置。
    	'default_file'          => 'index.php',
    	// 是否隐藏默认文件名，不设置时默认为false
    	'hide_default_file'     => true,
    	// 路由规则
    	'rules'                 => array(
    		'all/[page:int]'					=>	'Home/Index/index',
    		''									=>	'Home/Index/index',
    		'admin'								=>	'Admin/Index/index',
    		'Api/[control]/[action]'			=>	'Public/Api/call',
			'a/[Alias:word].html'				=>	'Home/Article/view',
			'[Alias:word].html'					=>	'Home/Page/view',
    		'[Alias:word]/[page:int]'			=>	'Home/Article/list',
    		'[Alias:word]'						=>	'Home/Article/list',
    	)
    )
);