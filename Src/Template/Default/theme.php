<?php
/**
 * 前台页面加载事件
 */
Event::register('YB_HOME_ONLOAD',function($data){
	$view = $data['control']->view;
	$articleModel = new ArticleModel;
	$view->rightNewest = $articleModel->homeSelect()
													   ->orderByNew()
													   ->limit(10)
													   ->select();
	$commentModel = new CommentModel;
	$view->newestComments = $commentModel->exContent(true)
										 ->orderByNew()
										 ->limit(10)
										 ->select();
});
Event::register('YURUN_DISPLAY_BEFORE',function($data){
	static $first = false;
	if($first)
	{
		return;
	}
	else
	{
		$first = true;
	}
	if('Home' === Dispatch::module() && 'Article' === Dispatch::control() && 'view' === Dispatch::action())
	{
		$articleModel = new ArticleModel;
		$view = $data['thisObj'];
		$tagIDs = array_column($view->articleInfo['Tags'],'ID');
		$view->relatedContents = $articleModel->selectRelatedContentByTagIDs($tagIDs,$view->articleInfo['ID'],1,6);
	}
});