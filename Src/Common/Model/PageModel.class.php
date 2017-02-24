<?php
class PageModel extends ContentModel
{
	public $exDataType = EX_DATA_TYPE_PAGE;
	public $type = CONTENT_TYPE_PAGE;
	public function __selectOneAfter(&$data)
	{
		$data['Url'] = Dispatch::url('Page/view',$data);
	}
}