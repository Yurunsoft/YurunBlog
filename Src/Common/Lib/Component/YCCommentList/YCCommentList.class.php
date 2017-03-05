<?php
class YCCommentList extends YCBase
{
	/**
	 * 属性默认值们
	 * @var unknown
	 */
	protected $attrsDefault = array(
		'content_id'	=>	0,
		'comments'		=>	false,
		'page'			=>	1,
		'is_child'		=>	false,
		'total_records'	=>	0
	);
	/**
	 * 渲染类型
	 * @var unknown
	 */
	protected $renderMode = self::RENDER_MODE_NONE;
	/**
	 * comments模版文件路径
	 */
	private $commentsTemplatePath;
	/**
	 * 自定义渲染内容
	 */
	protected function __render()
	{
		if(Config::get('Custom.CONTENT_SHOW_STATIC_COMMENTS'))
		{
			if(false === $this->comments)
			{
				$commentModel = new CommentModel;
				$this->comments = $commentModel->where(array('Status'=>COMMENT_STATUS_NORMAL))->orderByNew()->getAssocList($this->content_id,(int)Config::get('@.COMMENTS_SHOW'),$this->page,$totalRecords);
				$this->total_records = $totalRecords;
			}
			$this->renderItem();
		}
	}
	private function renderItem()
	{
		$this->commentsTemplatePath = APP_TEMPLATE . Config::get('@.THEME') . '/Home/Comment/comments.html';
		$this->view->comments = $this->comments;
		$this->view->display($this->commentsTemplatePath);
	}
	public function renderSubItem($comments)
	{
		$view = new View(null,$this);
		$view->comments = $comments;
		$view->display($this->commentsTemplatePath);
	}
}