<?php
class ArticleControl extends AdminControl
{
	public function manage()
	{
		$this->view->title = '文章管理';
		$this->display();
	}
	public function add()
	{
		$this->display('@module/@control/update','Popup');
	}
	public function update()
	{
		$this->display('','Popup');
	}
}