<?php
abstract class UrlHelper
{
	public static function parse(&$data)
	{
		$func = str_replace('/', '_', $data['rule']);
		if(method_exists('UrlHelper', $func))
		{
			self::$func($data);
		}
	}
}