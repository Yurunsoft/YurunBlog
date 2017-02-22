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
	public function parseSelect($data)
	{
		$tableName = $this->tableName();
		return $this->field("{$tableName}.*,
							dictStatus.Text as StatusText,
							user.Name as AuthorName,
							category.Name as CategoryName")
					->join('left',$this->tableName('dict') . ' as dictStatus',"dictStatus.Type = 'CONTENT_STATUS' and dictStatus.Value = {$tableName}.Status")
					->join('left',$this->tableName('user') . ' as user',"{$tableName}.Author = user.ID")
					->join('left',$this->tableName('category') . ' as category',"{$tableName}.CategoryID = category.ID")
					;
	}
	/**
	 * 处理查询条件
	 */
	public function parseCondition($data)
	{
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
		$params = array(&$data);
		$result = Event::trigger('YB_ADD_ARTICLE_BEFORE',$params);
		if(null !== $result && true !== $result)
		{
			return $result;
		}
		if(isEmpty($data['PostTime']))
		{
			unset($data['PostTime']);
		}
		if(!isset($data['Author']))
		{
			$data['Author'] = Globals::$user['ID'];
		}
		return parent::__addBefore($data);
	}
	public function __addAfter(&$data,$result)
	{
		$params = array(&$data,$result);
		$result = Event::trigger('YB_ADD_ARTICLE_AFTER',$params);
		if(null !== $result && true !== $result)
		{
			return $result;
		}
		$categoryModel = new CategoryModel;
		if(!$categoryModel->addItems($data['CategoryID']))
		{
			$this->error = '增加分类文章数失败';
			return false;
		}
		return parent::__addAfter($data,$result);
	}
	public function __editBefore(&$data)
	{
		$params = array(&$data);
		$result = Event::trigger('YB_EDIT_ARTICLE_BEFORE',$params);
		if(null !== $result && true !== $result)
		{
			return $result;
		}
		$info = $this->getByPk($data['ID']);
		if(!isset($info['CategoryID']))
		{
			$this->error = '该内容不存在';
			return false;
		}
		$this->lastCategoryID = $info['CategoryID'];
		return parent::__editBefore($data);
	}
	public function __editAfter(&$data,$result)
	{
		$params = array(&$data,$result);
		$result = Event::trigger('YB_EDIT_ARTICLE_AFTER',$params);
		if(null !== $result && true !== $result)
		{
			return $result;
		}
		if($this->lastCategoryID != $data['CategoryID'])
		{
			$categoryModel = new CategoryModel;
			if(!$categoryModel->deleteItems($this->lastCategoryID) || !$categoryModel->addItems($data['CategoryID']))
			{
				$this->error = '处理分类文章数失败';
				return false;
			}
		}
		return parent::__editAfter($data,$result);
	}
	public function __saveBefore(&$data)
	{
		$params = array(&$data);
		$result = Event::trigger('YB_SAVE_ARTICLE_BEFORE',$params);
		if(null !== $result && true !== $result)
		{
			return $result;
		}
		if(isEmpty($data['Title']))
		{
			$this->error = '标题不能为空';
			return false;
		}
		$data['Title'] = htmlspecialchars($data['Title']);
		if(isEmpty($data['UpdateTime']))
		{
			$data['UpdateTime'] = date('Y-m-d H:i:s');
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
		$params = array(&$data,$result);
		$result = Event::trigger('YB_SAVE_ARTICLE_AFTER',$params);
		if(null !== $result && true !== $result)
		{
			return $result;
		}
		return parent::__saveAfter($data,$result);
	}
	public function __deleteBefore(&$pkData)
	{
		$params = array(&$pkData);
		$result = Event::trigger('YB_DELETE_ARTICLE_BEFORE',$params);
		if(null !== $result && true !== $result)
		{
			return $result;
		}
		Log::add($pkData);
		$info = $this->getByPk($pkData);
		if(!isset($info['CategoryID']))
		{
			$this->error = '该内容不存在';
			return false;
		}
		$this->lastCategoryID = $info['CategoryID'];
		return parent::__deleteBefore($pkData);
	}
	public function __deleteAfter($result)
	{
		$params = array($result);
		$result = Event::trigger('YB_DELETE_ARTICLE_AFTER',$params);
		if(null !== $result && true !== $result)
		{
			return $result;
		}
		$categoryModel = new CategoryModel;
		if(!$categoryModel->deleteItems($this->lastCategoryID))
		{
			$this->error = '处理分类文章数失败';
			return false;
		}
		return parent::__deleteAfter($result);
	}
}