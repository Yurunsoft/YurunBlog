<?php
class PageAPI extends BaseAPI
{
	public function __construct()
	{
		$this->model = new PageModel;
	}
	/**
	 * @API
	 * @UserToken
	 * 列表
	 */
	public function query()
	{
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
		$result = $this->model->add(Request::post(),Db::RETURN_INSERT_ID);
		if($result)
		{
			$this->success = true;
		}
		else
		{
			$this->message = $this->model->error;
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
		$result = $this->model->wherePk(Request::post('ID'))->edit(Request::post());
		if($result)
		{
			$this->success = true;
		}
		else
		{
			$this->message = $this->model->error;
		}
	}
	/**
	 * @API
	 * @UserToken
	 * @Trans
	 * 删除
	 */
	public function delete()
	{
		$this->__delete();
	}
}