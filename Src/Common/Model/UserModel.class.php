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
	public function __selectBefore()
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
	public function __saveBefore(&$data)
	{
		if(isEmpty($data['Password']))
		{
			if(!isset($data['ID']))
			{
				$this->error = '请输入密码';
				return false;
			}
			unset($data['Password']);
		}
		else
		{
			$data['Password'] = $this->parsePassword($data['Password']);
		}
		return parent::__saveBefore($data);
	}
	/**
	 * 保存用户资料
	 * @param type $data
	 */
	public function saveProfile($data)
	{
		return $this->where(array('ID'=>$this->userInfo['ID']))->edit($data) ? true : $this->error;
	}
}