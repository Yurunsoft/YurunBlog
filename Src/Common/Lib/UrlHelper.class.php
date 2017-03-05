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
		static $articleModel;
		if(!isset($data['param']['Alias']) && isset($data['param']['ID']))
		{
			if(null === $articleModel)
			{
				$articleModel = new CategoryModel;
			}
			$data['param'] = $articleModel->getByPk($data['param']['ID']);
		}
		$data['result'] = Dispatch::url('Home/Article/view',array('Alias'=>$data['param']['Alias']));
	}
	public function Page_view($data)
	{
		static $pageModel;
		if(!isset($data['param']['Alias']) && isset($data['param']['ID']))
		{
			if(null === $pageModel)
			{
				$pageModel = new CategoryModel;
			}
			$data['param'] = $pageModel->getByPk($data['param']['ID']);
		}
		$data['result'] = Dispatch::url('Home/Page/view',array('Alias'=>$data['param']['Alias']));
	}
	public function Article_list($data)
	{
		static $categoryModel;
		if(isset($data['param']['page']))
		{
			$page = $data['param']['page'];
		}
		else
		{
			$page = 1;
		}
		if(!isset($data['param']['Alias']) && isset($data['param']['ID']))
		{
			if(null === $categoryModel)
			{
				$categoryModel = new CategoryModel;
			}
			$data['param'] = $categoryModel->getByPk($data['param']['ID']);
		}
		$param = array('Alias'=>$data['param']['Alias']);
		if($page > 1)
		{
			$param['page'] = $page;
		}
		$data['result'] = Dispatch::url('Home/Article/list',$param);
	}
	public function Tag_view($data)
	{
		static $tagModel;
		if(!isset($data['param']['Code']) && isset($data['param']['ID']))
		{
			if(null === $tagModel)
			{
				$tagModel = new TagModel;
			}
			$data['param'] = $tagModel->getByPk($data['param']['ID']);
		}
		$data['result'] = Dispatch::url('Home/Tag/view',array('Alias'=>$data['param']['Code']));
	}
}