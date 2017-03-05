<?php
class ContentModel extends BaseModel
{
	public $table = 'content';
	public $exDataType = 0;
	public $type = 0;
	protected $lastCategoryID;
	/*
	 * 处理查询内容
	 */
	public function __selectBefore()
	{
		$tableName = $this->tableName();
		$this->field("{$tableName}.*,
					dictStatus.Text as StatusText,
					user.Name as AuthorName,
					category.Name as CategoryName
					")
			 ->join('left',$this->tableName('dict') . ' as dictStatus',"dictStatus.Type = 'CONTENT_STATUS' and dictStatus.Value = {$tableName}.Status")
			 ->join('left',$this->tableName('user') . ' as user',"{$tableName}.Author = user.ID")
			 ->join('left',$this->tableName('category') . ' as category',"{$tableName}.CategoryID = category.ID")
			 ;
	}
	public function __selectOneAfter(&$data)
	{
		static $tagContentModel;
		if(null === $tagContentModel)
		{
			$tagManageContentModel = new TagManageContentModel;
		}
		$data['Tags'] = $tagManageContentModel->selectByContentID($data['ID']);
		switch((int)$data['Type'])
		{
			case CONTENT_TYPE_ARTICLE:
				$data['Url'] = Dispatch::url('Article/view',$data);
				break;
			case CONTENT_TYPE_PAGE:
				$data['Url'] = Dispatch::url('Page/view',$data);
				break;
		}
		return parent::__selectOneAfter($data);
	}
	/**
	 * 处理查询条件
	 */
	public function parseCondition($data)
	{
		if($this->type > 0)
		{
			$this->where(array($this->tableName() . '.Type'=>$this->type));
		}
		if(!empty($data['CategoryID']))
		{
			$categoryModel = new CategoryModel;
			$this->where(array($this->tableName() . '.CategoryID'=>array('in',$categoryModel->getChildsIds($data['CategoryID']))));
		}
		if(!empty($data['Status']))
		{
			$this->where(array($this->tableName() . '.Status'=>$data['Status']));
		}
		if(!isEmpty($data['Search']))
		{
			$this->where(array($this->tableName() . '.Title'=>array('like','%' . htmlspecialchars($data['Search']) . '%')));
		}
		return $this;
	}

	public function __addBefore(&$data)
	{
		$params = array('data'=>&$data,'result'=>&$eventResult);
		Event::trigger('YB_ADD_ARTICLE_BEFORE',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			$this->error = $eventResult;
			return false;
		}
		if(isEmpty($data['Alias']))
		{
			$data['Alias'] = uniqid('',true);
			$data['AliasAuto'] = true;
		}
		else if(Validator::regex($data['Alias'],'/^\d+$/'))
		{
			$this->error = '别名不能为纯数字';
			return false;
		}
		else
		{
			$data['AliasAuto'] = false;
			if($this->aliasExists($data['Alias']))
			{
				$this->error = '别名已被使用';
				return false;
			}
		}
		if(isEmpty($data['PostTime']))
		{
			unset($data['PostTime']);
		}
		if(!isset($data['Author']))
		{
			$data['Author'] = Globals::$user['ID'];
		}
		$data['Type'] = $this->type;
		return parent::__addBefore($data);
	}
	public function __addAfter(&$data,$result)
	{
		$params = array('data'=>&$data,'addResult'=>$result,'result'=>&$eventResult);
		Event::trigger('YB_ADD_ARTICLE_AFTER',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			$this->error = $eventResult;
			return false;
		}
		if($data['AliasAuto'] && !$this->wherePk($result)->edit(array('ID'=>$result,'Alias'=>$result)))
		{
			return false;
		}
		if(!empty($data['CategoryID']))
		{
			$categoryModel = new CategoryModel;
			if(!$categoryModel->addItems($data['CategoryID']))
			{
				$this->error = '增加分类文章数失败';
				return false;
			}
		}
		return parent::__addAfter($data,$result);
	}
	public function __editBefore(&$data)
	{
		$params = array('data'=>&$data,'result'=>&$eventResult);
		Event::trigger('YB_EDIT_ARTICLE_BEFORE',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			$this->error = $eventResult;
			return false;
		}
		if(isset($data['Alias']))
		{
			if('' === $data['Alias'])
			{
				$data['Alias'] = $data['ID'];
			}
			else
			{
				if($data['Alias'] != $data['ID'] && Validator::regex($data['Alias'],'/^\d+$/'))
				{
					$this->error = '别名不能为纯数字';
					return false;
				}
				if($this->aliasExists($data['Alias'],$data['ID']))
				{
					$this->error = '别名已被使用';
					return false;
				}
			}
		}
		if(!empty($data['CategoryID']))
		{
			$info = $this->getByPk($data['ID']);
			if(!isset($info['CategoryID']))
			{
				$this->error = '该内容不存在';
				return false;
			}
			$this->lastCategoryID = $info['CategoryID'];
		}
		return parent::__editBefore($data);
	}
	public function __editAfter(&$data,$result)
	{
		$params = array('data'=>&$data,'editResult'=>$result,'result'=>&$eventResult);
		Event::trigger('YB_EDIT_ARTICLE_AFTER',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			$this->error = $eventResult;
			return false;
		}
		if(!empty($data['CategoryID']))
		{
			if($this->lastCategoryID != $data['CategoryID'])
			{
				$categoryModel = new CategoryModel;
				if(!$categoryModel->deleteItems($this->lastCategoryID) || !$categoryModel->addItems($data['CategoryID']))
				{
					$this->error = '处理分类文章数失败';
					return false;
				}
			}
		}
		return parent::__editAfter($data,$result);
	}
	public function __saveBefore(&$data)
	{
		$params = array('data'=>&$data,'result'=>&$eventResult);
		Event::trigger('YB_SAVE_ARTICLE_BEFORE',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			$this->error = $eventResult;
			return false;
		}
		if(isset($data['Title']))
		{
			if('' === $data['Title'])
			{
				$this->error = '标题不能为空';
				return false;
			}
			$data['Title'] = htmlspecialchars($data['Title']);
		}
		if(!isset($data['ID']) || (isEmpty($data['UpdateTime']) && (!isset($data['LockUpdateTime']) || !$data['LockUpdateTime'])))
		{
			$data['UpdateTime'] = date('Y-m-d H:i:s');
		}
		else
		{
			unset($data['UpdateTime']);
		}
		if(isset($data['Top']))
		{
			$data['Top'] = (int)$data['Top'];
		}
		if(isset($data['CanComment']))
		{
			$data['CanComment'] = (int)$data['CanComment'];
		}
		if(isset($data['Keywords']))
		{
			$data['Keywords'] = htmlspecialchars($data['Keywords']);
		}
		if(isset($data['Description']))
		{
			$data['Description'] = htmlspecialchars($data['Description']);
		}
		return parent::__saveBefore($data);
	}
	public function __saveAfter(&$data,$result)
	{
		$params = array('data'=>&$data,'saveResult'=>$result,'result'=>&$eventResult);
		Event::trigger('YB_SAVE_ARTICLE_AFTER',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			$this->error = $eventResult;
			return false;
		}
		// 处理标签
		if(isset($data['Tags']))
		{
			$tagManageModel = new TagManageContentModel;
			if(!$tagManageModel->saveRelations(isset($data['ID']) ? $data['ID'] : $result,$data['Tags']))
			{
				$this->error = $tagManageModel->error;
				return false;
			}
		}
		return parent::__saveAfter($data,$result);
	}
	public function __deleteBefore(&$pkData)
	{
		$params = array('pkData'=>&$pkData,'result'=>&$eventResult);
		Event::trigger('YB_DELETE_ARTICLE_BEFORE',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			$this->error = $eventResult;
			return false;
		}
		$info = $this->getByPk($pkData);
		if(!isset($info['CategoryID']))
		{
			$this->error = '该内容不存在';
			return false;
		}
		if($info['CategoryID'] > 0)
		{
			$this->lastCategoryID = $info['CategoryID'];
		}
		return parent::__deleteBefore($pkData);
	}
	public function __deleteAfter($result)
	{
		$params = array('deleteResult'=>$result,'result'=>&$eventResult);
		Event::trigger('YB_DELETE_ARTICLE_AFTER',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			$this->error = $eventResult;
			return false;
		}
		if($this->lastCategoryID > 0)
		{
			$categoryModel = new CategoryModel;
			if(!$categoryModel->deleteItems($this->lastCategoryID))
			{
				$this->error = '处理分类文章数失败';
				return false;
			}
		}
		return parent::__deleteAfter($result);
	}
	public function homeSelect()
	{
		return $this->where(array('Status'=>CONTENT_STATUS_NORMAL));
	}
	public function orderByNew()
	{
		return $this->order(array(
			'Top'			=>	'desc',
			'Index',
			'UpdateTime'	=>	'desc'
		));
	}
	/**
	 * 给内容加浏览量
	 * @param int $id 
	 * @param int $view 
	 * @return mixed 
	 */
	public function incView($id,$view = 1)
	{
		return $this->wherePk($id)->inc(array('View'=>$view));
	}
	/**
	 * 给内容加评论数
	 * @param int $id 
	 * @param int $comments 
	 * @return mixed 
	 */
	public function incComments($id,$comments = 1)
	{
		return $this->wherePk($id)->inc(array('Comments'=>$comments));
	}
	public function selectRelatedContentByTagIDs($tagIDs,$contentID = 0,$page = 1,$show = 10,&$recordCount = null)
	{
		if(!isset($tagIDs[0]))
		{
			return array();
		}
		$tagRelationTableName = $this->tableName('tag_relation');
		$tableName = $this->tableName();
		return $this->from($tagRelationTableName)
					->join('',$tableName,$tagRelationTableName . '.RelationID=' . $tableName . '.ID')
					->order(array(
						'Index',
						'UpdateTime'	=>	'desc'
					))
					->where(array($tagRelationTableName . '.TagID'=>array('in',$tagIDs),$tableName . '.ID'=>array('<>',$contentID)))
					->distinct(true)
					->selectPage();
	}
}