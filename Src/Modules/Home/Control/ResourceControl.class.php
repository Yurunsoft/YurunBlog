<?php
class ResourceControl extends Control
{
	public function js()
	{
		$fileName = Request::get('filename');
		$first = substr($fileName,0,1);
		if(in_array($first,array('/','\\','.')))
		{
			Response::msg('文件不存在！', '', 404);
		}
		$filePath = APP_TEMPLATE . Config::get('@.THEME') . '/Home/Resource/js/' . $fileName . '.js';
		if(!is_file($filePath))
		{
			$filePath = APP_MODULE . 'Home/Template/Resource/js/' . $fileName . '.js';
			if(!is_file($filePath))
			{
				Response::msg('文件不存在！', '', 404);
			}
		}
		Response::setMime('js');
		header('Cache-Control:Public,max-age=' . Config::get('Custom.JS_CACHE_TIME'));
		$cacheName = 'Home/js/' . $fileName;
		$hash = Cache::get($cacheHashName);
		$_this = $this;
		$content = Cache::get($cacheName, function()use($_this,$cacheName,$filePath){
			// 页面数据处理
			$content = $_this->view->getHtml($filePath);
			Cache::set($cacheName,$content,array('expire'=>Config::get('Custom.JS_CACHE_TIME')));
			return $content;
		});
		// md4性能比md5快
		if(!Response::eTag(hash('md4',$content)))
		{
			if(extension_loaded('zlib') && !headers_sent() && isset($_SERVER['HTTP_ACCEPT_ENCODING']) && false !== strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
			{
				ob_start('ob_gzhandler');
			}
			echo $content;
			ob_end_flush();
		}
	}
}