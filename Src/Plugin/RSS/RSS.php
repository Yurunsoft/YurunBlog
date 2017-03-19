<?php
class RSS
{
	public static function create($data)
	{
		require_once dirname(__FILE__) . '/YurunRSSWriter.class.php';
		$rss = new YurunRSSWriter;
		$rss->channel['title'] = Config::get('@.SYSTEM_NAME');
		$rss->channel['link'] = Dispatch::url('Home/Index/index');
		$rss->channel['description'] = Config::get('@.SUB_TITLE');
		$rss->channel['generator'] = 'YurunBlog';
		$categoryModel = new CategoryModel;
		$list = $categoryModel->select();
		$rss->channel['category'] = array();
		foreach($list as $category)
		{
			$rss->channel['category'][$category['ID']] = array('name'=>$category['Name'],'domain'=>'http:' . $category['Url']);
		}
		// $rss->channel['category'] = array(
		// 	array('name'=>'分类1','domain'=>'http://www.baidu.com/1'),
		// 	array('name'=>'分类2'),
		// );
		$articleModel = new ArticleModel;
		$list = $articleModel->orderByNew()->limit(Config::get('PluginRSS.RSS_CONTENT_SHOW'))->select();
		foreach($list as $item)
		{
			$item['Url'] = 'http:' . $item['Url'];
			$rss->items[] = array(
				'title'			=>	$item['Title'],
				'link'			=>	$item['Url'],
				'description'	=>	$item['Summary'],
				'comments'		=>	$item['Url'] . '#comment',
				'pubDate'		=>	$item['UpdateTime'],
				'category'		=>	array($rss->channel['category'][$item['CategoryID']]),
			);
		}
		unset($list);
		$rss->saveToFile(WEB_ROOT_PATH . '/rss.xml');
	}
}
// 加载配置文件
Config::create(array(
	'type'	=>	'PHP',
	'option'=>	array(
		'filename'	=>	dirname(__FILE__) . '/config.php'
	)
), 'PluginRSS');
Event::register('YB_SAVE_ARTICLE_AFTER','RSS::create');