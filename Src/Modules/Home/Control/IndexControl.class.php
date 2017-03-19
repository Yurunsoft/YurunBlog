<?php
class IndexControl extends HomeBaseControl
{
	/**
	 * 首页
	 * @param int $page 
	 */
	public function index($page = 1)
	{
		$cacheName = 'Home/Index/index/' . $page;
		$cache = Cache::get($cacheName);
		if(false === $cache)
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
			$cache = $this->view->getHtml();
			Cache::set($cacheName,$cache,array('expire'=>Config::get('@.INDEX_CACHE_TIME')));
		}
		header('Cache-Control:Public,max-age=' . Config::get('Custom.INDEX_CACHE_TIME'));
		echo $cache;
	}
}