<?php
class Ueditor extends Control
{
	public function getEditor($data)
	{
		static $first = true;
		$this->view->set($data);
		$this->view->first = $first;
		$this->view->display(dirname(__FILE__)."/source/ueditor.php");
		$first = false;
	}
}
Event::register('YB_EDITOR_UEDITOR',function($data){
	$ueditor = new Ueditor;
	$ueditor->getEditor($data);
});