<?php
class TagModel extends BaseModel
{
	public function __saveBefore(&$data)
	{
		$isAdd = !isset($data['ID']);
		if($isAdd)
		{
			if(!isset($data['Type']))
			{
				$this->error = '请选择标签类型';
				return false;
			}
			if(isEmpty($data['Code']))
			{
				$data['Code'] = $data['Name'];
			}
		}
		if(isset($data['Code']))
		{
			$data['Code'] = parseTagCode($data['Code']);
			if($this->codeExists($data['Code'],$data['Type'],$isAdd ? 0 : $data['ID']))
			{
				$this->error = '标签代码已存在';
				return false;
			}
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
	public static function getManageInstance($type,$code)
	{
		static $tagModel;
		if(null === $tagModel)
		{
			$tagModel = new TagModel;
		}
		$tag = $tagModel->getByCode($code);
		$class = 'TagManage' . $type . 'Model';
		return new $class($tag['ID']);
	}
	/**
	 * 标签代码是否存在
	 * @param string $code
	 * @param int $type
	 * @param int $currID
	 * @return bool
	 */
	public function codeExists($code,$type,$currID = 0)
	{
		$where = array('Type'=>$type,'Code'=>$code);
		if(0 !== $currID)
		{
			$where['ID'] = array('<>',$currID);
		}
		return $this->where($where)->count() > 0;
	}
}