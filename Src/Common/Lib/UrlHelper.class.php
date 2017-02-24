<?php
abstract class UrlHelper
{
	public static function parse(&$data)
	{
		$func = str_replace('/', '_', $data['rule']);
		if(method_exists('UrlHelper', $func))
		{
			self::$func($data);
		}
	}
	public function Home_Index_index($data)
	{
		if(isset($data['param']['page']) && 1 == $data['param']['page'])
		{
			unset($data['param']['page']);
		}
		$data['result'] = Dispatch::url('Home/Index/index',$data['param'],null,true);
	}
	public function Article_view($data)
	{
		$data['result'] = Dispatch::url('Home/Article/view',array('Alias'=>$data['param']['Alias']));
	}
	public function Article_list($data)
	{
		static $categoryModel;
		if(isset($data['param']['page']) && 1 == $data['param']['page'])
		{
			unset($data['param']['page']);
		}
		if(!isset($data['param']['Alias']) && isset($data['param']['ID']))
		{
			if(null === $categoryModel)
			{
				$categoryModel = new CategoryModel;
			}
			$data['param'] = $categoryModel->getByPk($data['param']['ID']);
		}
		$data['result'] = Dispatch::url('Home/Article/list',array('Alias'=>$data['param']['Alias']));
	}
}