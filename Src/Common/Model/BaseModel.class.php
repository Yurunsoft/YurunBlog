<?php
class BaseModel extends Model
{
	// 主键
	public $pk = 'ID';
	/**
	 * 处理静态文件规则
	 * @var type 
	 */
	public $dataStaticFileRule = array();
	/**
	 * 扩展数据类型：以 EX_DATA_TYPE_ 开头的常量
	 * @var int
	 */
	public $exDataType = null;
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
	 * 在操作前处理数据
	 * @param array $data
	 */
	public function parseData(&$data)
	{
		if(null !== $this->exDataType)
		{
			if(isset($data['ExData']))
			{
				$data['ExData'] = serialize($data['ExData']);
			}
		}
	}
	public function __saveBefore(&$data,$result)
	{
		return $this->parseData($data);
	}
	public function __selectOneAfter(&$data)
	{
		if(null !== $this->exDataType)
		{
			$data['ExData'] = unserialize($data['ExData']);
		}
	}
	/**
	 * 获取一条记录
	 * @param type $id
	 * @return array
	 */
	public function getInfo($pkData,$data = array())
	{
		return $this->parseSelect($data)->getByPk($pkData);
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
		return $data;
	}
}