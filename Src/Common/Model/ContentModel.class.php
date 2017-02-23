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
		$this->where(array($this->tableName() . '.Type'=>$this->type));
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
		$eventResult = Event::trigger('YB_ADD_ARTICLE_BEFORE',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			return $eventResult;
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
		$params = array(&$data,$result);
		$eventResult = Event::trigger('YB_ADD_ARTICLE_AFTER',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			return $eventResult;
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
		$params = array(&$data);
		$eventResult = Event::trigger('YB_EDIT_ARTICLE_BEFORE',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			return $eventResult;
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
		$params = array(&$data,$result);
		$eventResult = Event::trigger('YB_EDIT_ARTICLE_AFTER',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			return $eventResult;
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
		$params = array(&$data);
		$eventResult = Event::trigger('YB_SAVE_ARTICLE_BEFORE',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			return $eventResult;
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
		$eventResult = Event::trigger('YB_SAVE_ARTICLE_AFTER',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			return $eventResult;
		}
		return parent::__saveAfter($data,$result);
	}
	public function __deleteBefore(&$pkData)
	{
		$params = array(&$pkData);
		$eventResult = Event::trigger('YB_DELETE_ARTICLE_BEFORE',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			return $eventResult;
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
		$params = array($result);
		$eventResult = Event::trigger('YB_DELETE_ARTICLE_AFTER',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			return $eventResult;
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
}