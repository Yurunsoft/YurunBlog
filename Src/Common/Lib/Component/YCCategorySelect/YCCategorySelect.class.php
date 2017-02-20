<?php
class YCCategorySelect extends YCSelect
{
	// data_func="Category/selectToSelect" text_field="Name" value_field="ID"

	/**
	 * 属性默认值们
	 * @var unknown
	 */
	protected $attrsDefault = array(
			'text_field'		=> 'Name',
			'value_field'		=> 'ID',
			'group_field'		=> 'group',
			'group_field_value'	=>	0,
			'show_group'		=>	false,
			'parent_id'			=>	0,
			'curr_id'			=>	-1,
			'disabled_field'	=>	'disabled'
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
			'parent_id','curr_id'
		));
	}
	protected function renderOptionItem($option,$key = '')
	{
		$prefix = str_repeat('&nbsp;',$option['Level'] * 2);
		if($option['Level'] > 0)
		{
			$prefix .= '└';
		}
		$option[$this->text_field] = $prefix . $option[$this->text_field];
		parent::renderOptionItem($option,$key);
	}
	/**
	 * 处理获取数据集
	 */
	protected function parseDataset()
	{
		$model = new CategoryModel;
		$this->dataset = $model->selectToSelect($this->parent_id,$this->curr_id);
	}
}