<?php
class SettingAPI extends BaseAPI
{
	/**
	 * @API
	 * @UserToken
	 * 登录接口
	 */
	public function save()
	{
		$data = Request::post();
		$groups = array();
		foreach($data as $key => $value)
		{
			list($group) = explode('@',$key);
			if(false === in_array($group,$groups))
			{
				$groups[] = $group;
			}
			Config::set(str_replace('@', '.', $key),$value);
		}
		foreach($groups as $group)
		{
			Config::save($group);
		}
		$this->success = true;
	}
}