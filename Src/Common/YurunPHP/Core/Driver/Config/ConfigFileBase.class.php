<?php
/**
 * 文件配置驱动基类
 * @author Yurun <yurun@yurunsoft.com>
 * @copyright 宇润软件(Yurunsoft.Com) All rights reserved.
 */
abstract class ConfigFileBase extends ConfigBase
{
	// 配置文件名
	protected $fileName = '';
	
	/**
	 * 构造方法
	 * @param type $option        	
	 */
	public function __construct($option = null)
	{
		parent::__construct($option);
		if(isset($option['filename']))
		{
			$this->fileName = $option['filename'];
			if(!is_file($this->fileName))
			{
				$this->fileName = APP_CONFIG . $this->fileName;
			}
			$this->fromFile($this->fileName);
		}
	}
	/**
	 * 从文件载入数据，将清空原数据
	 * @param string $fileName        	
	 */
	public function fromFile($fileName)
	{
		// 清空数据
		$this->clear();
		$this->fileName = $fileName;
		$this->parseFileToData($fileName);
	}
	/**
	 * 从文件载入数据，将合并覆盖原数据
	 * @param string $fileName        	
	 */
	public function fromFileMerge($fileName)
	{
		$this->parseFileToData($fileName);
	}
	/**
	 * 获取配置文件名
	 * @return string
	 */
	public function fileName()
	{
		return $this->fileName;
	}
	/**
	 * 将文件转换为数据
	 * @param string $fileName  
	 * @return bool     	
	 */
	protected abstract function parseFileToData($fileName);
}