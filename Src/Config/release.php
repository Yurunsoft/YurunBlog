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
	'TEMPLATE_CACHE_ON' => true,
	'TEMPLATE_CACHE_EXPIRE' => 3600
);