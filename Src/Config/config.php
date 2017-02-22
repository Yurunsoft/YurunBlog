<?php
return array(
	'CONFIGS'	=>	array(
		'Custom'	=>	array(
			'type'		=>	'PHP',
			'option'	=>	array(
				'filename'	=>	'custom.php',
			),
			'autoload'	=>	true,
		),
	),
	'LOCAL_PATH_STATIC'			=>	'../Static/',
	'TEMPLATE_EXT'				=> '.html',
	'pagebar_default_page_show' => 15,
	'QUERY_DATA_GROUP_NAME'		=>	'query',
	'pagebar_default_field'		=>	'page',
	'UPLOAD'					=>	array(
		'SUBPATH'	=>	array('date','Y/m/d'),
		'FILERULE'	=>	array(array('$this','unique')),
		'SAVEPATH'	=>	'Static/Upload/',
	),
	'TEMPLATE_CONSTS'			=>	array(
		'__PLUGIN__'	=>	'__WEBROOT__/Plugin'
	),
	'AUTOLOAD_RULES'	=>	array(
		array('type'=>'FirstWord','word'=>'YC','path'=>'Lib/Component/%class'),
	),
	'IMPORT'	=>	array(
		'excel'	=>	APP_LIB . 'PHPExcel.php'
	),
	'LOG_PATH'			=>	'../Logs',
	'USER_SESSION'	=>	array(
		'table'					=>	'user',
		'passwordSalt'			=>	't438h3fdz23sxskn',
		'excludeCheckMCA'		=>	array('Admin/Index/login','Admin/Index/login_do'),
		'pk'					=>	'ID',
		'sessionName'			=>	'@.USER_VCODE',
		'usernameFieldName'		=>	'Username',
		'passwordFieldName'		=>	'Password',
		'loginUrlMCA'			=>	'Index/login'
	),
	'THEME_ON' => false,
);