<?php
class TagManageAPI extends BaseAPI
{
	public function __construct()
	{
		$this->model = TagManageModel::getInstance(Request::post('Type'));
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
	 * @Trans
	 * 添加
	 */
	public function add()
	{
		$this->__add(array(
			'saveOptions'	=>	array(
				'main'	=>	new APISaveOptionItem(array('type'=>APISaveOptionItem::TYPE_SINGLE,'dataFromMethod'=>'post','dataCallback'=>array($this->model,'parseData')))
			),
		));
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