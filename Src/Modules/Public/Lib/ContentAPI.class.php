<?php
class ContentAPI extends BaseAPI
{
	public function __construct()
	{
		$this->model = new ContentModel;
	}
	/**
	 * @API
	 * 内容访问ping
	 */
	public function ping()
	{
		$id = (int)Request::all('ID');
		$this->model->incView($id);
		$this->data['view'] = $this->model->getView($id);
		$this->success = true;
	}
}