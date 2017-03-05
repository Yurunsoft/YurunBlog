<?php
class TagManageContentModel extends TagManageModel
{
	private $contentModel;
	public $tagID = -1;
	public $type = TAG_TYPE_CONTENT;
	public function __construct($tagID = 0)
	{
		parent::__construct();
		$this->contentModel = new ContentModel;
		$this->tagID = (int)$tagID;
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
		return $this->contentModel->parseCondition($data)->selectRelatedContentByTagIDs(array($data['TagID']),0,$page,$show,$totalPages);
	}
	public function saveRelations($relationID,$relations)
	{
		$tagModel = new TagModel;
		$tableName = $this->tableName();
		$tagTableName = $tagModel->tableName();
		if(isEmpty($relations))
		{
			$tags = $relations = array();
		}
		else 
		{
			if(is_string($relations))
			{
				$relations = explode(',',$relations);
			}
			$tags = $tagModel->where(array($tagTableName.'.Type'=>$this->type,$tagTableName.'.Name'=>array('in',$relations)))->select();
		}
		$oldRelations = $this->selectByContentID($relationID);
		// 删除旧的不存在的关联
		$tagsIDs = array_column($tags,'ID');
		$oldIDs = array_column($oldRelations,'TagID');
		$diffIDs = array_diff($oldIDs,$tagsIDs);
		if(!$this->removeRelations($relationID,$diffIDs))
		{
			$this->error = '移除旧标签关联失败';
			return false;
		}
		// 新增关联
		$tagsNames = array_column($tags,'Name');
		$oldNames = array_column($oldRelations,'Name');
		$relationTagNames = array_diff($relations,$oldNames);
		$relationTagIDs = array();
		foreach($relationTagNames as $index => $tagName)
		{
			$index2 = array_search($tagName,$tagsNames);
			if(false !== $index2)
			{
				$relationTagIDs[] = $tags[$index2]['ID'];
				unset($relationTagNames[$index]);
			}
		}
		if(!$this->addRelations($relationID,$relationTagNames,$relationTagIDs))
		{
			$this->error = '新增标签关联失败';
			return false;
		}
		return true;
	}
	public function removeRelations($relationID,$tagIDs)
	{
		if(empty($tagIDs))
		{
			return true;
		}
		return $this->where(array('TagID'=>array('in',$tagIDs),'RelationID'=>$relationID))
					->delete();
	}
	public function addRelations($relationID,$relationTagNames,$relationTagIDs = array())
	{
		$tagModel = new TagModel;
		foreach($relationTagNames as $tagName)
		{
			if('' === $tagName)
			{
				continue;
			}
			$tagID = $tagModel->add(array(
				'Type'	=>	$this->type,
				'Name'	=>	$tagName,
			),Db::RETURN_INSERT_ID);
			if($tagID <= 0)
			{
				return false;
			}
			$relationTagIDs[] = $tagID;
		}
		foreach($relationTagIDs as $tagID)
		{
			if(!$this->add(array(
				'TagID'			=>	$tagID,
				'RelationID'	=>	$relationID
			)))
			{
				return false;
			}
		}
		return true;
	}
	public function selectByContentID($contentID)
	{
		$tableName = $this->tableName();
		$tagTableName = $this->tableName('tag');
		return $this->field($tableName . '.*,' . $tagTableName . '.*')
					->join('left',$tagTableName,$tagTableName . '.ID=' . $tableName . '.TagID')
					->where(array('Type'=>$this->type,'RelationID'=>$contentID))
					->select();
	}
}