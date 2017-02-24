<?php
class IndexControl extends HomeBaseControl
{
	public function index($page = 1)
	{
		if(1 === $page)
		{
			$this->parseTitle(array(),'first');
		}
		else
		{
			$this->parseTitle(array(
				'CurrPage'	=>	$page
			));
		}
		$articleModel = new ArticleModel;
		$this->view->articleList = $articleModel->order(array(
													'Top'			=>	'desc',
													'Index',
													'UpdateTime'	=>	'desc'
												))
												->selectList(array(),$page,Config::get('@.SHOW_NUMBER.Home'),$totalPages);
		$this->view->display();
	}
}