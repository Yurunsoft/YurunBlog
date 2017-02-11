<?php
/**
 * 一个封装的非标准API控制器
 * @author Yurun <yurun@yurunsoft.com>
 * @copyright 宇润软件(Yurunsoft.Com) All rights reserved.
 */
class APIBaseControl extends Control
{
	public $dataFormat,$dataFormatOption;
	public $status = 0;
	public $apiData = array();
	public function __construct()
	{
		Event::register('YURUN_CONTROL_EXEC_COMPLETE', array($this,'parseResult'));
		Event::register('YURUN_MCA_NOT_FOUND', array($this,'parseNotFound'));
		set_exception_handler(array($this,'parseException'));
		$this->dataFormat = Config::get('@.APIBase.dataFormat','json');
		$this->dataFormatOption = Config::get('@.APIBase.dataFormatOption',null);
	}
	public function parseResult($params)
	{
		if(null !== $params['returnResult'])
		{
			$this->status = $params['returnResult'];
		}
		$this->__parseResult();
		$this->returnData($this->apiData, $this->dataFormat, $this->dataFormatOption);
	}
	protected function __parseResult()
	{
		
	}
	public function parseException($exception)
	{
		if(Config::get('@.LOG_ERROR'))
		{
			Log::add(Dispatch::module().'/'.Dispatch::control().'/'.Dispatch::action().'错误:'.$exception->getMessage().' 文件:'.$exception->getFile().' 行数:'.$exception->getLine());
		}
		$this->__parseException($exception);
		$this->parseResult();
	}
	protected function __parseException($exception)
	{
		
	}
	public function parseNotFound()
	{
		$this->__parseNotFound();
		$this->parseResult();
	}
	protected function __parseNotFound()
	{
		
	}
}