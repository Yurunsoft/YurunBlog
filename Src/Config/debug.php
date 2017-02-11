<?php
return array(
	'DB' => array (
		'default'	=>	array(
			'type' 		=> 'Mysqli',
			'option'	=>	array(
				'host' => '127.0.0.1',
				'port' => '3306',
				'username' => 'root',
				'password' => 'root',
				'dbname' => 'db_yurunblog',
				'prefix' => 'yb_',
				'charset' => 'utf8',
			)
		)
	),
	'DEFAULT_DB'	=>	'default',
	'MODEL_FIELDS_CACHE'	=>	false,		// 是否对模型字段缓存
);