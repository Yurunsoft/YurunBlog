<?php
abstract class HomeBaseControl extends BaseControl
{
	public function __construct($isCancelCheckSession = false)
	{
		parent::__construct(true);
		$params = array('control'=>&$this);
		Event::trigger('YB_HOME_ONLOAD',$params);
	}
	protected function parseTitle($params = array(),$nameSuffix = '')
	{
		$this->view->title = getTitle(Config::get('@.TITLES.' . Dispatch::control() . '/' . Dispatch::action() . ('' === $nameSuffix ? '' : ('/' . $nameSuffix))),$params);
	}
}