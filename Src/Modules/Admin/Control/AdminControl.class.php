<?php
abstract class AdminControl extends BaseControl
{
	protected $selectLeft,$shortcuts;
	public $userSession;
	function __construct($isCancelCheckSession = false)
	{
		parent::__construct($isCancelCheckSession);
	}
	public function display($template = '',$motherBoard = 'Admin')
	{
		parent::display($template,$motherBoard);
	}
	public function getHtml($template = '',$motherBoard = 'Admin')
	{
		return parent::getHtml($template,$motherBoard);
	}
	protected function setShortcuts($shortcuts)
	{
		if(is_array($shortcuts))
		{
			$this->shortcuts = $shortcuts;
		}
	}
	protected function addShortcut($title,$url,$param=array())
	{
		$this->shortcuts[$title] = Dispatch::url($url,$param);
	}
}