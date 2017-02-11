<?php
class VcodeControl extends Control
{
	public function show()
	{
		$v = new VCode(true);
		$v->doimg();
		Session::set('@.USER_VCODE',$v->getCode());
	}
}