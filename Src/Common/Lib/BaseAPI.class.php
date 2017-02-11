<?php
class BaseAPI
{
	public $control;
	public $success = false;
	public $message = '';
	public $data = array();
	public $options = array();
	/**
	 * 模型
	 * @var BaseModel
	 */
	public $model;
	protected function __query($option = array())
	{
		if(!isset($option['model']))
		{
			$option['model'] = $this->model;
		}
		// API配置
		$this->options = new APIOption($option);
		$isPage = false;
		// 判断是否允许分页
		if($this->options->allowPage)
		{
			if(isset($_POST[$this->options->pageFieldName]))
			{
				$page = Request::post($this->options->pageFieldName,1);
				$isPage = true;
			}
			else if(!$this->options->allowNoPage)
			{
				$this->message = '没有传页码';
				return;
			}
			else
			{
				$page = null;
			}
		}
		// 判断是否允许不分页
		else if($this->options->allowNoPage)
		{
			$page = null;
		}
		else
		{
			$this->message = 'allowPage和allowNoPage都为false';
			return;
		}
		$data = getDataByGroup($this->options->dataGroupName,$this->options->dataFromMethod);
		$this->parseData($data);
		if($isPage && $this->options->allowFieldPageShow)
		{
			$show = min(Request::post($this->options->pageShowFieldName),$this->options->maxPageShow);
		}
		$this->data['list'] = $this->options->model->selectList($data, $page, $show, $totalPages);
		if($isPage)
		{
			$this->data['pages'] = $totalPages;
			$this->data['curr_page'] = (int)$page;
		}
		$this->success = true;
	}
	protected function __find($option = array())
	{
		if(!isset($option['model']))
		{
			$option['model'] = $this->model;
		}
		// API配置
		$this->options = new APIOption($option);
		$this->data['data'] = $this->options->model->getInfo(Request::post($this->options->model->pk()));
		if(empty($this->data['data']))
		{
			$this->message = '数据不存在';
		}
		else
		{
			$this->success = true;
		}
	}
	protected function __add($option = array())
	{
		$this->__save($option,true);
	}
	protected function __update($option = array())
	{
		$this->__save($option,false);
	}
	protected function __save($option,$isAdd)
	{
		if(!isset($option['model']))
		{
			$option['model'] = $this->model;
		}
		// API配置
		$this->options = new APIOption($option);
		$result = true;
		foreach($this->options->saveOptions as $key => $item)
		{
			if(empty($this->options->saveOptions[$key]->model))
			{
				$this->options->saveOptions[$key]->model = $this->options->model;
			}
			$result = $this->__saveItem($this->options->saveOptions[$key],$isAdd);
			if(true !== $result)
			{
				break;
			}
		}
		if(true === $result)
		{
			$this->success = true;
			if(null !== $this->options->onSuccess)
			{
				call_user_func_array($this->options->onSuccess, array($this->options));
			}
		}
		else
		{
			$this->message = $result;
			if(null !== $this->options->onFail)
			{
				call_user_func_array($this->options->onFail, array($this->options));
			}
		}
	}
	/**
	 * 
	 * @param APISaveOptionItem $option
	 * @param boolean $isAdd
	 */
	protected function __saveItem(&$option,$isAdd)
	{
		if(APISaveOptionItem::TYPE_SINGLE === $option->type)
		{
			return $this->__saveItemSingle($option,$isAdd);
		}
		else if(APISaveOptionItem::TYPE_MUILT === $option->type)
		{
			return $this->__saveItemMuilt($option,$isAdd);
		}
		else
		{
			return false;
		}
	}
	/**
	 * 
	 * @param APISaveOptionItem $option
	 * @param boolean $isAdd
	 */
	protected function __saveItemSingle(&$option,$isAdd)
	{
		$option->data = getDataByGroup($option->dataGroupName,$option->dataFromMethod);
		$this->parseDataRelation($option);
		$this->parseData($option->data);
		if(!empty($option->dataCallback))
		{
			$result = call_user_func_array($option->dataCallback, array(&$option->data));
			if(is_string($result))
			{
				return $result;
			}
		}
		$pkName = $option->model->pk();
		if($isAdd)
		{
			$option->data[$pkName] = $option->model->add($option->data,Db::RETURN_INSERT_ID);
			return $option->data[$pkName] > 0 ? true : '新增失败';
		}
		else
		{
			$data = $option->data;
			$pk = $data[$pkName];
			unset($data[$pkName]);
			return $option->model->where(array($option->model->pk()=>$pk))->edit($data) ? true : '更新失败';
		}
	}
	/**
	 * 
	 * @param APISaveOptionItem $option
	 * @param boolean $isAdd
	 */
	protected function __saveItemMuilt(&$option,$isAdd)
	{
		$option->data = getDataArrayByGroup($option->dataGroupName,$option->dataFromMethod);
		$this->parseDataRelation($option);
		$pkName = $option->model->pk();
		$result = true;
		// 旧的数据
		$oldPks = array();
		list($key,$name) = explode('.',$option->parentRelationKeyFieldName);
		$parentPk = $this->options->saveOptions[$key]->data[$name];
		$list = $option->model->where(array($option->relationParentFieldName=>$parentPk))->select();
		foreach($list as $item)
		{
			$oldPks[] = $item[$pkName];
		}
		// 添加修改的主键们
		$pks = array();
		// 循环处理
		foreach($option->data as $item)
		{
			$item[$option->relationParentFieldName] = $parentPk;
			$this->parseData($item);
			if(!empty($option->dataCallback))
			{
				$r = call_user_func_array($option->dataCallback, array(&$item));
				if(is_string($r))
				{
					$result = $r;
					break;
				}
			}
			if($isAdd)
			{
				$pk = $option->model->add($item,Db::RETURN_INSERT_ID);
				if($pk > 0)
				{
					$pks[] = $pk;
				}
				else
				{
					$result = '新增失败';
				}
			}
			else
			{
				if(empty($item[$pkName]))
				{
					$pk = $option->model->add($item,Db::RETURN_INSERT_ID);
					if($pk > 0)
					{
						$pks[] = $pk;
					}
					else
					{
						$result = '更新失败';
					}
				}
				else
				{
					// 修改
					$pk = $item[$pkName];
					unset($item[$pkName]);
					$result = $option->model->where(array($pkName=>$pk))->edit($item);
					$pks[] = $pk;
				}
			}
			if(true !== $result)
			{
				break;
			}
		}
		if(true === $result)
		{
			$deletePks = array_diff($oldPks,$pks);
			if(!empty($deletePks))
			{
				$result = $option->model->where(array($pkName=>array('in',$deletePks)))->delete() ? true : '删除旧数据失败';
			}
		}
		return $result;
	}
	/**
	 * 
	 * @param APISaveOptionItem $option
	 */
	protected function parseDataRelation(&$option)
	{
		foreach($option->dataRelation as $key => $value)
		{
			list($optionKey,$name) = explode('.',$value);
			$val = $option->saveOptions[$optionKey]->data[$name];
			if(APISaveOptionItem::TYPE_SINGLE === $option->type)
			{
				$option->data[$key] = $val;
			}
			else if(APISaveOptionItem::TYPE_MUILT === $option->type)
			{
				$s = count($option->data);
				for($i=0;$i<$s;++$i)
				{
					$option->data[$i][$key] = $val;
				}
			}
		}
	}
	protected function __delete($option = array())
	{
		if(!isset($option['model']))
		{
			$option['model'] = $this->model;
		}
		$this->options = new APIOption($option);
		$this->success = $this->options->model->where(array($this->options->model->pk()=>Request::post($this->options->pkFieldName)))->delete();
		if(!$this->success)
		{
			$this->message = '删除失败';
		}
	}
	private function parseData(&$data)
	{
		unset($data['control'],$data['action']);
	}
}