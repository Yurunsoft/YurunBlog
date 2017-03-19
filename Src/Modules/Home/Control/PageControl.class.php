<?php
class PageControl extends HomeBaseControl
{
	/**
	 * 浏览页面
	 * @param string $Alias 
	 */
	public function view($Alias)
	{
		$cacheName = 'Home/Page/view/' . $Alias;
		$cache = Cache::get($cacheName);
		if(false === $cache)
		{
			$pageModel = new PageModel;
			$pageInfo = $pageModel->getByAlias($Alias);
			if(!isset($pageInfo['ID']))
			{
				Response::msg('页面不存在',null,404);
			}
			++$pageInfo['View'];
			$this->parseHeadInfo(array(
				'Page'	=>	$pageInfo
			));
			$this->view->pageInfo = $pageInfo;
			$cache = $this->view->getHtml('@theme/@module/@control/view/' . $pageInfo['Template']);
			Cache::set($cacheName,$cache,array('expire'=>Config::get('@.CONTENT_CACHE_TIME')));
			Event::trigger('YB_PAGE_VIEW',array('page'=>$pageInfo));
		}
		header('Cache-Control:Public,max-age=' . Config::get('Custom.CONTENT_CACHE_TIME'));
		echo $cache;
	}
}