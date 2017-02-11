<?php
class APISaveOptionItem
{
	/**
	 * 单条记录
	 */
	const TYPE_SINGLE = 1;
	/**
	 * 多条记录
	 */
	const TYPE_MUILT = 2;
	/**
	 * 保存记录类型
	 * @var int 
	 */
	public $type = self::TYPE_SINGLE;
	/**
	 * 数据组名
	 * @var string 
	 */
	public $dataGroupName = '';
	/**
	 * 数据来源：all、post、get
	 * @var type 
	 */
	public $dataFromMethod = 'all';
	/**
	 * 关联数据
	 * @var array 
	 */
	public $dataRelation = array();
	/**
	 * 关联父级数据的字段名
	 * @var type 
	 */
	public $relationParentFieldName = 'parent_id';
	/**
	 * 使用父级数据哪个字段的值关联
	 * @var type 
	 */
	public $parentRelationKeyFieldName = 'main.id';
	/**
	 * 数据
	 * @var array 
	 */
	public $data = array();
	/**
	 * 模型
	 * @var BaseModel
	 */
	public $model = 0;
	/**
	 * 数据处理回调
	 * @var type 
	 */
	public $dataCallback = array();
	public function __construct($option = array())
	{
		foreach($option as $key => $value)
		{
			if(isset($this->$key))
			{
				$this->$key = $value;
			}
		}
	}
}