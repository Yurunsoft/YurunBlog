<?php
class CategoryAPI extends BaseAPI
{
	public function __construct()
	{
		$this->model = new CategoryModel;
	}
	/**
	 * @API
	 * @UserToken
	 * 列表
	 */
	public function query()
	{
		$this->model->order(array('ID'=>'desc'));
		$this->__query(array(
			'dataGroupName'	=>	Config::get('@.QUERY_DATA_GROUP_NAME')
		));
	}
	/**
	 * @API
	 * @UserToken
	 * 获取一条记录
	 */
	public function find()
	{
		$this->__find();
	}
	/**
	 * @API
	 * @UserToken
	 * @Trans
	 * 添加
	 */
	public function add()
	{
		$result = $this->model->create(Request::post());
		if(true === $result)
		{
			$this->success = true;
		}
		else
		{
			$this->message = $result;
		}
	}
	/**
	 * @API
	 * @UserToken
	 * @Trans
	 * 修改
	 */
	public function update()
	{
		$result = $this->model->update(Request::post());
		if(true === $result)
		{
			$this->success = true;
		}
		else
		{
			$this->message = $result;
		}
	}
	public function onSaveSuccess($options)
	{
		$this->model->updateParent($options->saveOptions['main']->data['Parent']);
		$this->model->updateChildren($options->saveOptions['main']->data['ID']);
	}
	/**
	 * @API
	 * @UserToken
	 * @Trans
	 * 删除
	 */
	public function delete()
	{
		// $this->__delete();
	}
}