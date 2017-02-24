<?php
abstract class HomeBaseControl extends BaseControl
{
	public function __construct($isCancelCheckSession = false)
	{
		parent::__construct(true);
		$params = array('control'=>&$this);
		Event::trigger('YB_HOME_ONLOAD',$params);
	}
	protected function parseHeadInfo($params = array(),$nameSuffix = '')
	{
		$ca = Dispatch::control() . '/' . Dispatch::action();
		$this->view->title = getRuleResult(Config::get('@.TITLES.' . $ca . ('' === $nameSuffix ? '' : ('/' . $nameSuffix))),$params);
		$this->view->seoDescription = getRuleResult(Config::get('@.SEO_DESCRIPTION.' . $ca),$params);
		$this->view->seoKeywords = getRuleResult(Config::get('@.SEO_KEYWORDS.' . $ca),$params);
	}
}