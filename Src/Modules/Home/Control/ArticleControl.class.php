<?php
class ArticleControl extends HomeBaseControl
{
	/**
	 * 浏览文章
	 * @param string $Alias 
	 */
	public function view($Alias)
	{
		$cacheName = 'Home/Article/view/' . $Alias;
		$cache = Cache::get($cacheName);
		if(false === $cache)
		{
			$articleModel = new ArticleModel;
			$articleInfo = $articleModel->getByAlias($Alias);
			if(!isset($articleInfo['ID']))
			{
				Response::msg('文章不存在',null,404);
			}
			++$articleInfo['View'];
			$this->parseHeadInfo(array(
				'Article'	=>	$articleInfo
			));
			$this->view->articleInfo = $articleInfo;
			$cache = $this->view->getHtml('@theme/@module/@control/view/' . $articleInfo['Template']);
			Cache::set($cacheName,$cache,array('expire'=>Config::get('@.CONTENT_CACHE_TIME')));
			Event::trigger('YB_ARTICLE_VIEW',array('article'=>$articleInfo));
		}
		header('Cache-Control:Public,max-age=' . Config::get('Custom.CONTENT_CACHE_TIME'));
		echo $cache;
	}
	/**
	 * 文章列表
	 * @param string $Alias 
	 */
	public function _R_list($Alias,$page = 1)
	{
		$cacheName = 'Home/Article/list/' . $Alias . '/' . $page;
		$cache = Cache::get($cacheName);
		if(false === $cache)
		{
			$categoryModel = new CategoryModel;
			$categoryInfo = $categoryModel->getByAlias($Alias);
			if(!isset($categoryInfo['ID']))
			{
				Response::msg('分类不存在',null,404);
			}
			$this->parseHeadInfo(array(
				'Category'	=>	$categoryInfo,
				'CurrPage'	=>	$page
			));
			$this->view->categoryInfo = $categoryInfo;
			$articleModel = new ArticleModel;
			$this->view->articleList = $articleModel->homeSelect()
													->orderByNew()
													->where(array($articleModel->tableName() . '.CategoryID'=>$categoryInfo['ID']))
													->selectList(array(),$page,Config::get('@.SHOW_NUMBER.ArticleList'),$totalPages);
			$this->view->totalPages = $totalPages;
			$this->view->currPage = $page;
			$cache = $this->view->getHtml('@theme/@module/@control/list/' . $categoryInfo['CategoryTemplate']);
			Cache::set($cacheName,$cache,array('expire'=>Config::get('@.LIST_CACHE_TIME')));
			Event::trigger('YB_ARTICLE_LIST_VIEW',array('category'=>$categoryInfo));
		}
		header('Cache-Control:Public,max-age=' . Config::get('Custom.LIST_CACHE_TIME'));
		echo $cache;
	}
}