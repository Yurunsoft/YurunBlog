<?php
class PageControl extends HomeBaseControl
{
	/**
	 * 浏览页面
	 * @param string $Alias 
	 */
	public function view($Alias)
	{
		$pageModel = new PageModel;
		$pageInfo = $pageModel->getByAlias($Alias);
		if(!isset($pageInfo['ID']))
		{
			Response::msg('页面不存在',null,404);
		}
		// 页面浏览量+1
		$articleModel->incView($pageInfo['ID']);
		++$pageInfo['View'];
		$this->parseHeadInfo(array(
			'Page'	=>	$pageInfo
		));
		$this->view->pageInfo = $pageInfo;
		$this->view->display('@theme/@module/@control/view/' . $pageInfo['Template']);
		Event::trigger('YB_PAGE_VIEW',array('page'=>$pageInfo));
	}
}