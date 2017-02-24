<?php
class ArticleControl extends HomeBaseControl
{
	public function view($Alias)
	{
		$articleModel = new ArticleModel;
		$articleInfo = $articleModel->getByAlias($Alias);
		if(!isset($articleInfo['ID']))
		{
			Response::msg('文章不存在',null,404);
		}
		$this->parseTitle(array(
			'Article'	=>	$articleInfo
		));
		$this->view->articleInfo = $articleInfo;
		$this->view->display('@theme/@module/@control/view/' . $articleInfo['Template']);
	}
}