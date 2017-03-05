<?php
class TagControl extends AdminControl
{
	public function _R_list()
	{
		$this->view->title = '标签管理';
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
	public function manage()
	{
		$tagModel = new TagModel;
		$tag = $tagModel->getInfo(Request::get('id'));
		$typeCode = BaseDict::getName(TAG_TYPE, (int)$tag['Type']);
		$type = strtolower(substr($typeCode,9));
		$this->view->tag = $tag;
		$this->display('@module/@control/manage_' . $type,'Popup');
	}
}