<?php
class YCEditor extends YCBase
{
	/**
	 * 属性默认值们
	 * @var unknown
	 */
	protected $attrsDefault = array(
		'content'			=> '',
		'type'				=> '',
	);
	/**
	 * 构造方法
	 * @param unknown $attrs
	 * @param string $tagName
	 */
	public function __construct($attrs = array(), $tagName = null)
	{
		parent::__construct($attrs,$tagName);
		$this->excludeAttrs = array_merge($this->excludeAttrs,array(
				'content',
				'type'
		));
	}
	/**
	 * 渲染控件
	 */
	public function render()
	{
		$type = $this->type;
		$cfgType = Config::get('@.EDITOR_TYPE');
		if(empty($type) && empty($cfgType))
		{
			parent::render();
		}
		else
		{
			$param = array('data' => &$this->data, 'attrsStr' => $this->attrsStr);
			Event::trigger('YB_EDITOR_' . strtoupper(empty($type) ? $cfgType : $type),$param);
		}
	}
}