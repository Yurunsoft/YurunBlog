<?php
class UserModel extends UserSession
{
	/**
	 * 构造方法
	 * @param array $option
	 */
	function __construct()
	{
		parent::__construct(Config::get('@.USER_SESSION'));
	}
    /**
	 * 处理查询内容
	 */
	public function parseSelect($data)
	{
        $tableName = $this->tableName();
        $levelTable = $this->tableName('user_level');
		return $this->field("{$tableName}.*,{$levelTable}.Name as Level")
                    ->join('left',$levelTable,$levelTable . '.ID=' . $tableName . '.LevelID');
	}
	/**
	 * 处理查询条件
	 */
	public function parseCondition($data)
	{
        if(!isEmpty($data['search']))
        {
            $this->where(array(
				'and'	=>	array(
					'Username'	=>	array('like','%' . $data['search'] . '%'),
					'or'		=>	array(
						$this->tableName() . '.Name'	=>	array('like','%' . $data['search'] . '%'),
					)
				)
			));
        }
		return $this;
	}
	/**
	 * 处理数据
	 */
	public function parseData(&$data)
	{
		if(isEmpty($data['Password']))
		{
			unset($data['Password']);
		}
		else
		{
			$data['Password'] = $this->parsePassword($data['Password']);
		}
	}
	/**
	 * 保存用户资料
	 * @param type $data
	 */
	public function saveProfile($data)
	{
		$this->parseData($data);
		return $this->where(array('ID'=>$this->userInfo['ID']))->edit($data) ? true : '保存失败';
	}
}