<?php
class CommentAPI extends BaseAPI
{
	public function __construct()
	{
		$this->model = new CommentModel;
	}
	/**
	 * @API
	 * @UserToken
	 * 列表
	 */
	public function query()
	{
		$this->model->orderByNew();
		$this->__query(array(
			'dataGroupName'	=>	Config::get('@.QUERY_DATA_GROUP_NAME')
		));
	}
	/**
	 * @API
	 * 查询内容的评论
	 * @return mixed 
	 */
	public function queryByContent()
	{
		$this->model->orderByNew();
		$this->success = true;
		$page = (int)Request::get('page',1);
		$contentID = (int)Request::get('ContentID');
		$commentList = YurunComponent::getCommentList(array('content_id'=>$contentID,'page'=>$page));
		ob_start();
		$commentList->begin();
		$commentList->end();
		$this->data['content'] = ob_get_clean();
		$p = new Page($commentList->total_records,(int)Config::get('@.COMMENTS_SHOW'),1);
		$this->data['pages'] = $p->getTotalPages();
		$this->data['page'] = $page;
		$this->data['comments'] = $commentList->total_records;
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
	 * @Trans
	 * 添加
	 */
	public function add()
	{
		if(getCommentHash(Request::post('ContentID')) !== Request::post('hash'))
		{
			$this->message = '非法请求';
			return;
		}
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