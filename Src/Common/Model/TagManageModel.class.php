<?php
abstract class TagManageModel extends BaseModel
{
	public $table = 'tag_relation';
	public function __saveBefore(&$data)
	{
		unset($data['Type']);
		return parent::__saveBefore($data);
	}
	public static function getInstance($type)
	{
		$typeCode = BaseDict::getName(TAG_TYPE, (int)$type);
		$type = strtolower(substr($typeCode,9));
		$class = TagManage . ucfirst($type) . 'Model';
		return new $class;
	}
}