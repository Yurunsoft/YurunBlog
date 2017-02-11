<?php
class YCDateTimeBox extends YCBase
{
	/**
	 * 属性默认值们
	 * @var unknown
	 */
	protected $attrsDefault = array(
		'is_time'=>0
	);
	public function prepareView()
	{
		if(!$this->exists('id'))
		{
			$this->set('id','DateTimeBox-' . createHash(5));
		}
		parent::prepareView();
	}
}