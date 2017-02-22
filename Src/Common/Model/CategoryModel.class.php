<?php
class CategoryModel extends CategoryBaseModel
{
	// public $itemTableName = '';
	public $itemTableNumFieldName = 'Articles';
	public $itemTableCategoryFieldName = 'CategoryID';
	public $exDataType = EX_DATA_TYPE_CATEGORY;
	
	public function __addBefore(&$data)
	{
		$result = Event::trigger('YB_ADD_CATEGORY_BEFORE',$data);
		if(null !== $result && true !== $result)
		{
			return $result;
		}
		return parent::__addBefore($data);
	}
	public function __addAfter(&$data,$result)
	{
		$result = Event::trigger('YB_ADD_CATEGORY_AFTER',$data);
		if(null !== $result && true !== $result)
		{
			return $result;
		}
		return parent::__addAfter($data,$result);
	}
	public function __editBefore(&$data)
	{
		$result = Event::trigger('YB_EDIT_CATEGORY_BEFORE',$data);
		if(null !== $result && true !== $result)
		{
			return $result;
		}
		return parent::__editBefore($data);
	}
	public function __editAfter(&$data,$result)
	{
		$result = Event::trigger('YB_EDIT_CATEGORY_AFTER',$data);
		if(null !== $result && true !== $result)
		{
			return $result;
		}
		return parent::__editAfter($data,$result);
	}
	public function __saveBefore(&$data)
	{
		$result = Event::trigger('YB_SAVE_CATEGORY_BEFORE',$data);
		if(null !== $result && true !== $result)
		{
			return $result;
		}
		if(isset($data['IsShow']))
		{
			$data['IsShow'] = (int)$data['IsShow'];
		}
		if(isset($data['NavigationShow']))
		{
			$data['NavigationShow'] = (int)$data['NavigationShow'];
		}
		return parent::__saveBefore($data);
	}
	public function __saveAfter(&$data,$result)
	{
		$result = Event::trigger('YB_SAVE_CATEGORY_AFTER',$data);
		if(null !== $result && true !== $result)
		{
			return $result;
		}
		return parent::__saveAfter($data,$result);
	}
	public function __deleteBefore(&$pkData)
	{
		$result = Event::trigger('YB_DELETE_CATEGORY_BEFORE',$data);
		if(null !== $result && true !== $result)
		{
			return $result;
		}
		return parent::__deleteBefore($pkData);
	}
	public function __deleteAfter($result)
	{
		$result = Event::trigger('YB_DELETE_CATEGORY_AFTER',$data);
		if(null !== $result && true !== $result)
		{
			return $result;
		}
		return parent::__deleteAfter($result);
	}
}