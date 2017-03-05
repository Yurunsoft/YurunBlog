<?php
class YCComment extends YCBase
{
	/**
	 * 渲染类型
	 * @var unknown
	 */
	protected $renderMode = self::RENDER_MODE_NONE;
	/**
	 * 自定义渲染内容
	 */
	protected function __render()
	{
		$this->view->display(APP_TEMPLATE . Config::get('@.THEME') . '/Home/Comment/comment.html');
	}
}