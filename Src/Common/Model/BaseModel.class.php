<?php
class BaseModel extends Model
{
	// 主键
	public $pk = 'ID';
	// 是否查询后处理数据
	protected $isParseDataAfter = true;
	/**
	 * 处理静态文件规则
	 * @var type 
	 */
	public $dataStaticFileRule = array();
	/**
	 * 获得主键名称
	 * @return string
	 */
	public function pk()
	{
		return $this->pk;
	}
	/*
	 * 处理查询内容
	 */
	public function parseSelect($data)
	{
		return $this;
	}
	/**
	 * 处理查询条件
	 */
	public function parseCondition($data)
	{
		return $this;
	}
	/**
	 * 在查询数据后处理数据
	 * @param type $data
	 */
	public function parseDataAfter(&$data)
	{
	}
	/**
	 * 在查询数据后处理数据
	 * @param type $data
	 */
	public function parseDataListAfter(&$data)
	{
		$s = count($data);
		for($i=0;$i<$s;++$i)
		{
			$this->parseDataAfter($data[$i]);
		}
	}
	/**
	 * 获取一条记录
	 * @param type $id
	 * @return type
	 */
	public function getInfo($pkData,$data = array())
	{
		$data = $this->parseSelect($data)->getByPk($pkData);
		if($this->isParseDataAfter)
		{
			$this->parseDataAfter($data);
		}
		else
		{
			$this->isParseDataAfter = true;
		}
		return $data;
	}
	/**
	 * 查询多条记录，支持分页
	 * @param type $data
	 * @param type $page
	 * @param type $totalPages
	 * @return type
	 */
	public function selectList($data = array(),$page = null,$show = null,&$totalPages = null)
	{
		$this->parseSelect($data)
			 ->parseCondition($data);
		if(!empty($data['field_after']))
		{
			$this->field($data['field_after']);
		}
		if(null === $page)
		{
			$data = $this->select();
		}
		else
		{
			if(empty($show))
			{
				$show = Config::get('@.pagebar_default_page_show');
			}
			$data = $this->selectPage($page,$show,$records);
			$p = new Page($records,$show,$page);
			$totalPages = $p->getTotalPages();
		}
		if($this->isParseDataAfter)
		{
			$this->parseDataListAfter($data);
		}
		else
		{
			$this->isParseDataAfter = true;
		}
		return $data;
	}
	protected function concatField($old,$new)
	{
		if($old != '')
		{
			$new = trim($new);
			if(isset($new[0]) && $new[0] !== ',')
			{
				$old .= ',';
			}
		}
		return $old . $new;
	}
	public function dataAfter($isParse)
	{
		$this->isParseDataAfter = $isParse;
		return $this;
	}
}