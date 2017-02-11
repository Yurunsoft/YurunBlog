<?php
abstract class BaseDict
{
	/**
	 * 获取基础数据
	 * @param string $name
	 * @return mixed
	 */
	public static function get($name = null)
	{
		if(null === $name)
		{
			return Config::get('BaseDict');
		}
		else
		{
			return Config::get('BaseDict.' . $name);
		}
	}
	/**
	 * 根据值获取基础数据标题
	 * @param string $name
	 * @param mixed $value
	 * @return string|NULL
	 */
	public static function getText($name,$value)
	{
		$data = Config::get('BaseDict.' . $name);
		foreach($data as $val)
		{
			if($val['value'] == $value)
			{
				return $val['text'];
			}
		}
		return null;
	}
	/**
	 * 根据值获取基础数据名称
	 * @param string $name
	 * @param mixed $value
	 * @return string|NULL
	 */
	public static function getName($name,$value)
	{
		$data = Config::get('BaseDict.' . $name);
		foreach($data as $key => $val)
		{
			if($val['value'] == $value)
			{
				return $key;
			}
		}
		return null;
	}
	public static function __callStatic($name, $arguments)
	{
		if('getText' === substr($name,0,7))
		{
			return self::getText(substr($name,7),$arguments[0]);
		}
		return null;
	}
}