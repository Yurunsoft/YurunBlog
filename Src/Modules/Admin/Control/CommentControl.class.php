<?php
class CommentControl extends AdminControl
{
	public function manage()
	{
		$this->view->title = '评论管理';
		$this->display();
	}
	public function update()
	{
		$this->display('','Popup');
	}
}