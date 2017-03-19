<?php
abstract class TagManageModel extends BaseModel
{
	public $table = 'tag_relation';
	public $type = 0;
	public $tagRelationModel;
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
	public static function getInstanceByTagID($tagID)
	{
		$tagModel = new TagModel;
		$tag = $tagModel->getByPk($tagID);
		if(!isset($tag['ID']))
		{
			return null;
		}
		return self::getInstance($tag['Type']);
	}
	/**
	 * 查询多条记录，支持分页
	 * @param type $data
	 * @param type $page
	 * @param type $show
	 * @param type $totalPages
	 * @return type
	 */
	public function selectList($data = array(),$page = null,$show = null,&$totalPages = null)
	{
		if(isset($data['TagID']))
		{
			$tagID = (int)$data['TagID'];
		}
		else
		{
			$tagID = $this->tagID;
		}
		$this->tagRelationModel->setOption($this->getOption());
		$relationTableName = $this->tagRelationModel->tableName();
		$pk = $relationTableName . '.' . $this->tagRelationModel->pk;
		$ids = $this->tagRelationModel->field($pk . ' as id')
									  ->from($relationTableName)
									  ->join('',$this->tableName(),$this->tableName() . '.RelationID=' . $pk . ' and ' . $this->tableName() . '.TagID=' . (int)$tagID)
								      ->selectList($data,$page,$show,$totalPages);
		if(!isset($ids[0]))
		{
			return array();
		}
		$ids = array_column($ids,'id');
		return $this->tagRelationModel->where(array($pk=>array('in',$ids)))
									  ->orderField($pk,$ids)
									  ->select();
	}
}