<?php
class ArticleModel extends ContentModel
{
	public $exDataType = EX_DATA_TYPE_ARTICLE;
	public $type = CONTENT_TYPE_ARTICLE;
	public function __selectOneAfter(&$data)
	{
		$data['Url'] = Dispatch::url('Article/view',$data);
	}
}