<?php
abstract class CategoryBaseModel extends BaseModel
{
	public $levelFieldName = 'Level';
	public $parentFieldName = 'Parent';
	public $aliasFieldName = 'Alias';
	public $childrenFieldName = 'Children';
	public $itemTableName = '';
	public $itemTableNumFieldName = 'Nums';
	public $itemTableCategoryFieldName = 'CategoryID';
	public $oldCategoryID = 0;
	public function __saveBefore(&$data)
	{
		if(isset($data['Name']))
		{
			if(isEmpty($data['Name']))
			{
				$this->error = '分类名称不能为空';
				return false;
			}
		}
		if(isset($data[$this->parentFieldName]))
		{
			if(0 == $data[$this->parentFieldName])
			{
				$data[$this->levelFieldName] = 0;
			}
			else
			{
				$info = $this->getByPk($data[$this->parentFieldName]);
				$data[$this->levelFieldName] = $info[$this->levelFieldName] + 1;
			}
		}
		if(isset($data[$this->pk]) && isset($data[$this->parentFieldName]))
		{
			$info = $this->getByPk($data[$this->pk]);
			$this->oldCategoryID = $info[$this->parentFieldName];
		}
		return parent::__saveBefore($data);
	}
	public function __addAfter(&$data,$result)
	{
		$this->updateChildren($result);
		return parent::__addAfter($data,$result);
	}
	public function __editAfter(&$data,$result)
	{
		if(isset($data[$this->pk]))
		{
			$this->updateChildren($data[$this->pk]);
		}
		return parent::__editAfter($data,$result);
	}
	public function __saveAfter(&$data,$result)
	{
		if(null !== $this->oldCategoryID)
		{
			$this->updateParent($data[$this->parentFieldName]);
			$this->oldCategoryID = null;
		}
		return parent::__saveAfter($data,$result);
	}
	/**
	 * 获取关联列表
	 * @param unknown $id
	 */
	public function getAssocList()
	{
		// 查询出所有分类记录
		$arr1 = $this->selectList();
		$arr2 = array();
		// 处理成ID为键名的数组
		foreach($arr1 as $val)
		{
			$arr2[$val[$this->pk]] = $val;
		}
		// 节省内存
		unset($arr1,$val);
		// 结果数组
		$result = array();
		// 循环处理关联列表
		foreach($arr2 as $item)
		{
			if(isset($arr2[$item[$this->parentFieldName]]))
			{
				$arr2[$item[$this->parentFieldName]][$this->childrenFieldName][] = &$arr2[$item[$this->pk]];
			}
			else
			{
				$result[] = &$arr2[$item[$this->pk]];
			}
		}
		return $result;
	}
	/**
	 * 获取下属所有级的子分类的ID
	 * @param number $id
	 * @param string $first
	 * @return array
	 */
	public function getChildsIds($id=0,$first=true)
	{
		if(is_array($id))
		{
			$ids = $id;
			if(!isset($ids[0]))
			{
				return array();
			}
		}
		else
		{
			$ids = array($id);
		}
		$tids = array_column($this->selectBefore(false)->field($this->pk)->where(array($this->parentFieldName=>array('in',$ids)))->select(),$this->pk);
		if(isset($tids[0]))
		{
			$ids = array_merge($ids,$tids,$this->getChildsIds($tids,false));
		}
		return $first ? array_unique($ids) : $ids;
	}
	/**
	 * 获取一级子分类的ID们
	 * @param number $parent
	 */
	public function getChildId($parent = 0)
	{
		$children = $this->selectBefore(false)->field($this->pk)->where(array($this->parentFieldName=>$parent))->select();
		return array_column($children,$this->pk);
	}
	/**
	 * 检测别名是否存在。存在返回true，不存在或别名为空返回false
	 * @param unknown $alias
	 * @return boolean
	 */
	public function aliasExists($alias,$currID = null)
	{
		if(null !== $currID)
		{
			$this->where(array($this->pk=>array('<>',$currID)));
		}
		return '' !== $alias && $this->where(array($this->aliasFieldName=>$alias))->count() > 0;
	}
	/**
	 * 获取父级所有级的ID
	 * @param number $id
	 * @param string $first
	 * @return array
	 */
	public function getParentIds($id = 0,$first = true)
	{
		if(is_array($id))
		{
			$ids = array();
			// 多个分类
			foreach($id as $value)
			{
				$ids = array_merge($ids,$this->getParentIds($value,false));
			}
		}
		else
		{
			$ids = array($id);
			$parent = $this->field($this->parentFieldName)->where(array($this->pk=>$id))->select(true);
			if(isset($parent[$this->parentFieldName]))
			{
				$ids = array_merge($ids,$this->getParentIds($parent[$this->parentFieldName],false));
			}
		}
		return $first?array_unique($ids):$ids;
	}
	/**
	 * 添加站点数
	 * @param type $categoryID
	 * @param type $num
	 */
	public function addItems($categoryID,$num = 1)
	{
		$ids = $this->getParentIds($categoryID);
		return $this->where(array($this->pk=>array('in',$ids)))->inc(array($this->itemTableNumFieldName=>$num));
	}
	/**
	 * 减少站点数
	 * @param type $categoryID
	 * @param type $num
	 */
	public function deleteItems($categoryID,$num = 1)
	{
		$ids = $this->getParentIds($categoryID);
		return $this->where(array($this->pk=>array('in',$ids)))->dec(array($this->itemTableNumFieldName=>$num));
	}
	/**
	 * 更新父级
	 * @param type $id
	 */
	public function updateParent($id,$first = true)
	{
		$this->updateItemNumsByChildren($id);
		$parentID = $this->field($this->parentFieldName)->where(array($this->pk=>$id))->selectValue();
		if($parentID > 0)
		{
			$this->updateParent($parentID,false);
		}
		if($first && $this->oldCategoryID != $id && $this->oldCategoryID > 0)
		{
			$this->updateParent($this->oldCategoryID,false);
		}
	}
	/*
	 * 根据子节点更新站点数
	 */
	public function updateItemNumsByChildren($id)
	{
		if(empty($this->itemTableName))
		{
			return;
		}
		$tableName = $this->tableName();
		$itemTableName = $this->tableName($this->itemTableName);
		return $this->getDb()->execute(
<<<SQL
UPDATE {$tableName}
SET {$this->itemTableNumFieldName} = COALESCE (
	(
		SELECT
			nums
		FROM
			(
				SELECT
					sum({$this->itemTableNumFieldName}) AS nums
				FROM
					{$tableName}
				WHERE
					{$this->parentFieldName} = {$id}
			) AS t
	),
	0
) + (select count(*) from {$itemTableName} where {$this->itemTableCategoryFieldName} = {$id})
where {$this->pk} = {$id}
SQL
);
	}
	/**
	 * 更新子级
	 * @param type $id
	 */
	public function updateChildren($id)
	{
		$ids = $this->getChildId($id);
		if(!empty($ids))
		{
			$data = $this->getByPk($id);
			$level = $data[$this->levelFieldName] + 1;
			$this->where(array($this->pk=>array('in',$ids)))->edit(array(($this->levelFieldName)=>$level));
			foreach($ids as $id)
			{
				$this->updateChildren($id);
			}
		}
	}
	/**
	 * 刷新文章数量
	 */
	public function refreshArticles()
	{
		$data = $this->order(array($this->levelFieldName=>'desc'))
					 ->field(array($this->pk))
					 ->select();
		$data = array_column($data, $this->pk);
		$result = true;
		foreach($data as $id)
		{
			$result = $result && $this->updateItemNumsByChildren($id);
			if(!$result)
			{
				break;
			}
		}
		return $result;
	}
	
	public function selectToSelect($parentID = 0,$currID = -1,$list = null,&$result = null)
	{
		if(null === $list)
		{
			$result = array();
			$list = $this->selectList();
		}
		foreach($list as $item)
		{
			if($item[$this->parentFieldName] == $parentID)
			{
				$item['disabled'] = ($item[$this->pk] == $currID || $item[$this->parentFieldName] == $currID);
				$result[] = $item;
				$this->selectToSelect($item[$this->pk],$item['disabled'] ? $item[$this->pk] : $currID,$list,$result);
			}
		}
		return $result;
	}
	public function __selectBefore()
	{
		$this->order(array('Index','ID'));
	}
}