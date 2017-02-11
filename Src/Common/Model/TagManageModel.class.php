<?php
abstract class TagManageModel extends BaseModel
{
	public $table = 'tag_relation';
	public function parseData(&$data)
	{
		unset($data['Type']);
	}
	public static function getInstance($type)
	{
		$typeCode = BaseDict::getName(TAG_TYPE, (int)$type);
		$type = strtolower(substr($typeCode,9));
		$class = TagManage . ucfirst($type) . 'Model';
		return new $class;
	}
}