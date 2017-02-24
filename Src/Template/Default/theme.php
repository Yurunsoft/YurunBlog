<?php
/**
 * 前台页面加载事件
 */
Event::register('YB_HOME_ONLOAD',function($data){
	$articleModel = new ArticleModel();
	$data['control']->view->rightNewest = $articleModel->homeSelect()
													   ->orderByNew()
													   ->limit(10)
													   ->select();
});