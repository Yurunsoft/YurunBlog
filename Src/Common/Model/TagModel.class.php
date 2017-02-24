<?php
class TagModel extends BaseModel
{
	public function __saveBefore(&$data)
	{
		if(isEmpty($data['Code']))
		{
			$data['Code'] = 'tag_' . uniqid();
		}
		return parent::__saveBefore($data);
	}
	/**
	 * 处理查询条件
	 */
	public function parseCondition($data)
	{
		if(!empty($data['type']))
		{
			$this->where(array('Type'=>$data['type']));
		}
		return $this;
	}
	/*
	 * 处理查询内容
	 */
	public function __selectBefore()
	{
		$this->field($this->tableName() . '.*,dict.text as TypeName')
			 ->join('left',$this->tableName('dict') . ' as dict','dict.type=\'' . TAG_TYPE . '\' and dict.value = ' . $this->tableName() . '.Type');
	}
	public static function getManageInstance($code)
	{
		static $tagModel;
		if(null === $tagModel)
		{
			$tagModel = new TagModel;
		}
		$tag = $tagModel->getByCode($code);
		$typeCode = BaseDict::getName(TAG_TYPE, (int)$tag['Type']);
		$type = strtolower(substr($typeCode,9));
		$class = TagManage . ucfirst($type) . 'Model';
		return new $class($tag['ID']);
	}
}