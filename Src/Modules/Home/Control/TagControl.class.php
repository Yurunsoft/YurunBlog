<?php
class TagControl extends HomeBaseControl
{
	/**
	 * 标签浏览
	 * @param string $Alias 
	 */
	public function view($Alias,$page = 1)
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
		$this->view->display();
		Event::trigger('YB_TAG_VIEW',array('tag'=>$tagInfo));
	}
}