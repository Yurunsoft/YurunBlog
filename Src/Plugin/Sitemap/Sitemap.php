<?php
class Sitemap
{
	private static $file;
	public static function create($data)
	{
		self::$file = fopen(WEB_ROOT_PATH . 'sitemap.xml','w');
		if(false === self::$file)
		{
			$data['result'] = '打开sitemap.xml文件失败';
			return;
		}
		if (false === flock(self::$file, LOCK_EX))
		{
			$data['result'] = 'sitemap.xml文件锁定失败';
			return;
		}
		fwrite(self::$file,<<<CONTENT
<?xml version="1.0" encoding="utf-8"?>
<urlset>
CONTENT
		);
		self::writeItem(Request::getHome('','http://'),date('Y-m-d'),'always','1.0');
		$db = Db::getInstance();
		// 单页
		$pageModel = new PageModel();
		$list = $db->queryA('select Alias,UpdateTime from ' . $pageModel->tableName() . ' where Type = ' . $pageModel->type);
		foreach($list as $item)
		{
			self::writeItem(Request::getHome('','http://'),date('Y-m-d',strtotime($item['UpdateTime'])),'monthly','0.5');
		}

		// 分类页
		$categoryModel = new CategoryModel();
		$list = $db->queryA('select Alias from ' . $categoryModel->tableName());
		foreach($list as $item)
		{
			self::writeItem(Dispatch::url('Article/list',$item),date('Y-m-d'),'monthly','0.6');
		}

		// 文章
		$articleModel = new ArticleModel();
		$list = $db->queryA('select Alias,UpdateTime from ' . $articleModel->tableName() . ' where Type = ' . $articleModel->type);
		foreach($list as $item)
		{
			self::writeItem(Dispatch::url('Article/view',$item),date('Y-m-d',strtotime($item['UpdateTime'])),'monthly','0.8');
		}


		fwrite(self::$file,'</urlset>');
		fclose(self::$file);
		// Session::start();
	}
	private static function writeItem($loc,$lastmod,$changefreq,$priority)
	{
		fwrite(self::$file,<<<CONTENT
<url><loc>{$loc}</loc><lastmod>{$lastmod}</lastmod><changefreq>{$changefreq}</changefreq><priority>{$priority}</priority></url>
CONTENT
		);
	}
}
Event::register('YB_SAVE_ARTICLE_AFTER','Sitemap::create');