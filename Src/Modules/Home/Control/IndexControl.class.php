<?php
class IndexControl extends HomeBaseControl
{
	/**
	 * 首页
	 * @param int $page 
	 */
	public function index($page = 1)
	{
		if(1 === $page)
		{
			$this->parseHeadInfo(array(),'first');
		}
		else
		{
			$this->parseHeadInfo(array(
				'CurrPage'	=>	$page
			));
		}
		$articleModel = new ArticleModel;
		$this->view->articleList = $articleModel->homeSelect()
												->orderByNew()
												->selectList(array(),$page,Config::get('@.SHOW_NUMBER.Home'),$totalPages);
		$this->view->totalPages = $totalPages;
		$this->view->currPage = $page;
		$this->view->display();
	}
}