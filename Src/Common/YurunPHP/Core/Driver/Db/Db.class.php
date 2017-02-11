<?php
/**
 * 数据库驱动类
 * @author Yurun <yurun@yurunsoft.com>
 * @copyright 宇润软件(Yurunsoft.Com) All rights reserved.
 */
class Db extends Driver
{
	/**
	 * 当前驱动名称
	 * @var type 
	 */
	public static $driverName = '';
	/**
	 * 返回操作是否执行成功
	 * @var int
	 */
	const RETURN_ISOK = 0;
	/**
	 * 返回语句影响行数
	 * @var int
	 */
	const RETURN_ROWS = 1;
	/**
	 * 返回最后插入的自增ID
	 * @var int
	 */
	const RETURN_INSERT_ID = 2;
	
	protected static function __initBefore()
	{
		static::$driverName = 'Db';
	}
	/**
	 * 获得数据库对象实例
	 * @param string $name
	 * @return DbBase
	 */
	public static function get($name = null)
	{
		if(empty($name))
		{
			// 默认数据库配置名
			$name = Config::get('@.DEFAULT_DB');
		}
		$obj = self::getInstance($name);
		if(null !== $obj)
		{
			return $obj;
		}
		$option = Config::get('@.DB.' . $name);
		if(false === $option)
		{
			return false;
		}
		else
		{
			return Db::create($option,$name);
		}
	}
}