<?php
/**
 * API配置类
 */
class APIOption
{
	/**
	 * 是否允许不分页查询全部数据
	 * @var boolean 
	 */
	public $allowNoPage = true;
	/**
	 * 是否允许分页查询
	 * @var boolean 
	 */
	public $allowPage = true;
	/**
	 * 是否允许参数传入每页显示数量
	 * @var type 
	 */
	public $allowFieldPageShow = false;
	/**
	 * 页码参数名
	 * @var string 
	 */
	public $pageFieldName = 'page';
	/**
	 * 每页显示数量参数名
	 * @var string 
	 */
	public $pageShowFieldName = 'num';
	/**
	 * 每页显示数量参数名
	 * @var string 
	 */
	public $maxPageShow = 100;
	/**
	 * 数据组名
	 * @var type 
	 */
	public $dataGroupName = 'group';
	/**
	 * 数据来源：all、post、get
	 * @var type 
	 */
	public $dataFromMethod = 'all';
	/**
	 * 主键参数名
	 * @var string 
	 */
	public $pkFieldName = 'ID';
	/**
	 * 模型
	 * @var BaseModel
	 */
	public $model = null;
	/**
	 * 保存的配置
	 * @var array $saveOptions
	 */
	public $saveOptions = array();
	/**
	 * 成功事件
	 * @var type 
	 */
	public $onSuccess = null;
	/**
	 * 失败事件
	 * @param type $option
	 */
	public $onFail = null;
	public function __construct($option = array())
	{
		foreach($option as $key => $value)
		{
			$this->$key = $value;
		}
	}
}