<?php
abstract class BaseControl extends Control
{
	protected $lastSaveIDs = array();
	protected $info;
	public $userSession;
	public function __construct($isCancelCheckSession = false)
	{
		parent::__construct();
		$this->userSession = UserModel::getInstance();
		if(!$isCancelCheckSession)
		{
			$this->checkUserSession(true);
		}
	}
	public function display($template = '',$motherBoard = '')
	{
		if(empty($template))
		{
			$this->view->template = '/' . Dispatch::module() . '/' . Dispatch::control() . '/' .Dispatch::action();
		}
		else
		{
			$this->view->template = '/' . $template;
		}
		$this->view->display(APP_TEMPLATE . '/MotherBoard/' . $motherBoard . '.html');
	}
	public function getHtml($template = '',$motherBoard = '')
	{
		if(empty($template))
		{
			$this->view->template = '/' . Dispatch::module() . '/' . Dispatch::control() . '/' .Dispatch::action();
		}
		else
		{
			$this->view->template = '/' . $template;
		}
		return $this->view->getHtml(APP_TEMPLATE . '/MotherBoard/' . $motherBoard . '.html');
	}
	protected function checkUserSession($isJumpToLogin)
	{
		$result = false;
		if($this->userSession->checkMCA())
		{
			$result = $this->userSession->check($isJumpToLogin);
			if($result)
			{
				Globals::$user = $this->userSession->userInfo;
				$this->view->user = $this->userSession->userInfo;
			}
		}
		else
		{
			$result = true;
		}
		return $result;
	}
}