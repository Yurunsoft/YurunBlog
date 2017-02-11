<?php
class YCApi extends YCBase
{
	/**
	 * 属性默认值们
	 * @var unknown
	 */
	protected $attrsDefault = array(
		'control'	=> 'name',
		'action'	=> 'id',
	);
	/**
	 * 初始化
	 */
	protected function init()
	{
		$this->renderMode = self::RENDER_MODE_NONE;
		parent::init();
	}
	/**
	 * 自定义渲染内容
	 */
	protected function __render()
	{
		echo Dispatch::url('Public/Api/call',array('control'=>$this->control,'action'=>$this->action));
	}
}