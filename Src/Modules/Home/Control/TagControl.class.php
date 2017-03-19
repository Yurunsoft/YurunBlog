<?php
class TagControl extends HomeBaseControl
{
	/**
	 * 标签浏览
	 * @param string $Alias 
	 */
	public function view($Alias,$page = 1)
	{
		$cacheName = 'Home/Tag/view/' . $Alias . '/' . $page;
		$cache = Cache::get($cacheName);
		if(false === $cache)
		{
			$tagModel = new TagModel;
			$tagInfo = $tagModel->getByCode($Alias);
			if(!isset($tagInfo['ID']))
			{
				Response::msg('页面不存在',null,404);
			}
			$this->parseHeadInfo(array(
				'Tag'		=>	$tagInfo,
				'CurrPage'	=>	$page
			));
			$this->view->tagInfo = $tagInfo;
			$articleModel = new ArticleModel;
			$this->view->articleList = $articleModel->homeSelect()
													->orderByNew()
													->selectRelatedContentByTagIDs(array($tagInfo['ID']),0,$page,Config::get('@.SHOW_NUMBER.ArticleList'),$totalPages);
			$this->view->totalPages = $totalPages;
			$this->view->currPage = $page;
			$cache = $this->view->getHtml();
			Cache::set($cacheName,$cache,array('expire'=>Config::get('@.LIST_CACHE_TIME')));
			Event::trigger('YB_TAG_VIEW',array('tag'=>$tagInfo));
		}
		header('Cache-Control:Public,max-age=' . Config::get('Custom.LIST_CACHE_TIME'));
		echo $cache;
	}
}