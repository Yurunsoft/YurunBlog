<?php
class IndexControl extends AdminControl
{
	/**
	 * 登录页面
	 */
	public function login()
	{
		$this->view->display();
	}
	/**
	 * 退出登录
	 */
	public function logout()
	{
		$this->userSession->logout();
		Response::redirectU('Index/login');
	}
	/**
	 * 后台首页
	 */
	public function index()
	{
		$this->view->title = '后台管理';
		$this->view->display();
	}
	/**
	 * 后台首页
	 */
	public function home()
	{
		$this->view->title = '后台管理';
		$this->display();
	}
}