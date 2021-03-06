<?php
class MemberAPI extends BaseAPI
{
	/**
	 * @API
	 * 登录接口
	 */
	public function login()
	{
		if(!checkAdminVcode(Request::post('vcode')))
		{
			$this->success = false;
			$this->message = '请输入正确的验证码';
			return;
		}
		$result = $this->control->userSession->login();
		if(true === $result)
		{
			$this->success = true;
		}
		else
		{
			Log::add($result);
			$this->success = false;
			$this->message = '登录失败';
		}
	}
	/**
	 * @API
	 * @UserToken
	 * @Trans
	 * 个人信息修改
	 */
	public function profile()
	{
		$m = UserModel::getInstance();
		$data = Request::post();
		$result = $m->saveProfile($data);
		$this->success = (true === $result);
		if(!$this->success)
		{
			$this->message = $result;
		}
		$this->data['post_data'] = $data;
	}
	/**
	 * @API
	 * @UserToken
	 * 获取登录用户信息
	 */
	public function userinfo()
	{
		$data = array(
			'ID'	=>	Globals::$user['ID'],
			'Name'	=>	Globals::$user['Name'],
		);
		$this->data['data'] = $data;
		$this->success = true;
	}
}