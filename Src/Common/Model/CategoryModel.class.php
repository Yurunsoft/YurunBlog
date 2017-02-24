<?php
class CategoryModel extends CategoryBaseModel
{
	public $itemTableName = 'content';
	public $itemTableNumFieldName = 'Articles';
	public $itemTableCategoryFieldName = 'CategoryID';
	public $exDataType = EX_DATA_TYPE_CATEGORY;
	
	public function __addBefore(&$data)
	{
		$params = array(&$data);
		$eventResult = Event::trigger('YB_ADD_CATEGORY_BEFORE',$params);
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
		return parent::__addBefore($data);
	}
	public function __addAfter(&$data,$result)
	{
		$params = array(&$data,$result);
		$eventResult = Event::trigger('YB_ADD_CATEGORY_AFTER',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			return $eventResult;
		}
		if($data['AliasAuto'] && !$this->wherePk($result)->edit(array('ID'=>$result,'Alias'=>$result)))
		{
			return false;
		}
		return parent::__addAfter($data,$result);
	}
	public function __editBefore(&$data)
	{
		$params = array(&$data);
		$eventResult = Event::trigger('YB_EDIT_CATEGORY_BEFORE',$params);
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
		return parent::__editBefore($data);
	}
	public function __editAfter(&$data,$result)
	{
		$params = array(&$data,$result);
		$eventResult = Event::trigger('YB_EDIT_CATEGORY_AFTER',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			return $eventResult;
		}
		return parent::__editAfter($data,$result);
	}
	public function __saveBefore(&$data)
	{
		$params = array(&$data);
		$eventResult = Event::trigger('YB_SAVE_CATEGORY_BEFORE',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			return $eventResult;
		}
		if(isset($data['IsShow']))
		{
			$data['IsShow'] = (int)$data['IsShow'];
		}
		if(isset($data['NavigationShow']))
		{
			$data['NavigationShow'] = (int)$data['NavigationShow'];
		}
		if(isset($data['Title']))
		{
			if('' === $data['Title'])
			{
				$data['Title'] = $data['Name'];
			}
		}
		return parent::__saveBefore($data);
	}
	public function __saveAfter(&$data,$result)
	{
		$params = array(&$data,$result);
		$eventResult = Event::trigger('YB_SAVE_CATEGORY_AFTER',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			return $eventResult;
		}
		return parent::__saveAfter($data,$result);
	}
	public function __deleteBefore(&$pkData)
	{
		$params = array(&$pkData);
		$eventResult = Event::trigger('YB_DELETE_CATEGORY_BEFORE',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			return $eventResult;
		}
		return parent::__deleteBefore($pkData);
	}
	public function __deleteAfter($result)
	{
		$params = array($result);
		$eventResult = Event::trigger('YB_DELETE_CATEGORY_AFTER',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			return $eventResult;
		}
		return parent::__deleteAfter($result);
	}
	public function __selectOneAfter(&$data)
	{
		$data['Url'] = Dispatch::url('Article/list',$data);
	}
	public function onlyGetShow()
	{
		return $this->where(array('IsShow'=>true));
	}
	public function onlyGetNavShow()
	{
		return $this->where(array('IsShow'=>true,'NavigationShow'=>true));
	}
}