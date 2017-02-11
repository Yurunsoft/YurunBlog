<?php
class UserSession extends BaseModel
{
	// 表名
	public $table = 'user';
	// 主键
	public $pk = 'id';
	// 用户名字段名
	public $usernameFieldName = 'username';
	// 密码字段名
	public $passwordFieldName = 'password';
	// 登录后保存用户ID的Session名字
	public $sessionName = '@.USER';
	// 是否开启记住登录状态功能
	public $rememberOn = false;
	// 记住登录时间，单位：秒，默认7天
	public $rememberTime = 604800;
	// 记住登录状态的字段名，为1记住，其它值不记住
	public $rememberFieldName = 'remember';
	// 记住登录用cookie的用户标识
	public $rememberCookieUIDFieldName = 'lgu';
	// 用户标识在表中的字段名
	public $rememberCookieUIDFieldNameFieldName = 'id';
	// 记住登录用cookie的时间名
	public $rememberCookieTimeFieldName = 'lgt';
	// 记住登录用cookie的hash名
	public $rememberCookieHashFieldName = 'lgh';
	// 记住登录用cookie计算hash的盐
	public $rememberCookieSalt = 'YurunPHPCookieSalt';
	// cookie加盐算法，为空则使用默认算法
	public $rememberCookieSaltFunction = null;
	// 排除的检查MCA，数组，每个元素中以/隔开MCA
	public $excludeCheckMCA = array();
	// 登录页面地址
	public $loginUrl = '';
	// 登录页面地址MCA，支持字符串或数组。数组支持第二个元素为参数。
	public $loginUrlMCA = array();
	// 用户数据
	public $userInfo = array();
	// 密码盐
	public $passwordSalt = 'YurunPHP';
	// 密码加盐算法，为空则使用默认算法
	public $passwordSaltFunction = null;
	// 是否记住当前登录
	private $isRemember = false;
	// 实例们
	protected static $instances = array();
	/**
	 * 构造方法
	 * @param array $option
	 */
	function __construct($option)
	{
		// 动态赋值参数
		foreach($option as $item => $value)
		{
			if(isset($this->$item) && !empty($value))
			{
				$this->$item = $value;
			}
		}
		parent::__construct();
	}
	public static function getInstance($option = null)
	{
		$class = get_called_class();
		if(!isset(self::$instances[$class]))
		{
			self::$instances[$class] = new $class($option);
		}
		return self::$instances[$class];
	}
	/**
	 * 检测当前MCA是否需要经过检查登录状态
	 */
	public function checkMCA()
	{
		$module = Dispatch::module();
		$control = Dispatch::control();
		$action = Dispatch::action();
		foreach($this->excludeCheckMCA as $mca)
		{
			list($m,$c,$a) = explode('/',$mca);
			if($module === $m && $control === $c && $action === $a)
			{
				return false;
			}
		}
		return true;
	}
	/**
	 * 检查登录状态
	 * @return boolean
	 */
	public function check($isJumpToLogin = false)
	{
		// 从session检查
		if(!$this->checkSession($isJumpToLogin))
		{
			return false;
		}
		// 从cookie记住获取
		if($this->rememberOn)
		{
			if($this->checkCookie($isJumpToLogin))
			{
				$this->setLoginStatus();
			}
			else
			{
				return false;
			}
		}
		return true;
	}
	/**
	 * 登录
	 * @return boolean|string
	 */
	public function login()
	{
		// 根据用户名查询用户信息
		$this->userInfo = $this->getBy($this->usernameFieldName, Request::post($this->usernameFieldName));
		// 判断用户是否存在
		if(!isset($this->userInfo[$this->pk]))
		{
			return 'USER_IS_NOT_EXITS';
		}
		// 密码验证
		if($this->userInfo[$this->passwordFieldName] !== $this->parsePassword(Request::post($this->passwordFieldName)))
		{
			return 'PASSWORD_IS_FAIL';
		}
		if($this->rememberOn)
		{
			$this->isRemember = (int)Request::post($this->rememberCookieHashFieldName);
		}
		$this->setLoginStatus();
		return true;
	}
	/**
	 * 退出登录
	 */
	public function logout()
	{
		Cookie::delete(array($this->rememberCookieUIDFieldName,$this->rememberCookieTimeFieldName,$this->rememberCookieHashFieldName));
		Session::delete($this->sessionName);
	}
	/**
	 * 设置登录状态
	 */
	private function setLoginStatus()
	{
		Session::set($this->sessionName, $this->userInfo[$this->pk]);
		if($this->isRemember)
		{
			Cookie::set($this->rememberCookieUIDFieldName,$this->userInfo[$this->rememberCookieUIDFieldNameFieldName]);
			Cookie::set($this->rememberCookieTimeFieldName,$_SERVER['REQUEST_TIME']);
			Cookie::set($this->rememberCookieHashFieldName,$this->parseCookieHash($this->userInfo[$this->rememberCookieUIDFieldNameFieldName],$_SERVER['REQUEST_TIME']));
		}
	}
	/**
	 * 处理密码加盐
	 */
	public function parsePassword($password)
	{
		if(null !== $this->passwordSaltFunction)
		{
			return call_user_func_array($this->passwordSaltFunction, array($password,$this->passwordSalt));
		}
		return md5($password . '###' . $this->passwordSalt);
	}
	/**
	 * 检查session
	 * @param type $isJumpToLogin
	 * @return boolean
	 */
	private function checkSession($isJumpToLogin = false)
	{
		$sessionValue = Session::get($this->sessionName,false);
		if(false === $sessionValue)
		{
			return $this->parseCheckResult(false,$isJumpToLogin);
		}
		// 获取用户数据
		$this->userInfo = $this->getByPk($sessionValue);
		// 判断是否有这个用户
		if(!isset($this->userInfo[$this->pk]))
		{
			return $this->parseCheckResult(false,$isJumpToLogin);
		}
		return true;
	}
	/**
	 * 检查cookie
	 * @param type $isJumpToLogin
	 * @return boolean
	 */
	private function checkCookie($isJumpToLogin = false)
	{
		$uid = Cookie::get($this->rememberCookieUIDFieldName);
		$time = Cookie::get($this->rememberCookieTimeFieldName);
		$hash = Cookie::get($this->rememberCookieHashFieldName);
		// 检测时间是否过期
		if($time + $this->rememberTime < $_SERVER['REQUEST_TIME'])
		{
			return $this->parseCheckResult(false,$isJumpToLogin);
		}
		// 根据UID获取用户信息
		$userInfo = $this->getBy($this->rememberCookieUIDFieldNameFieldName, $uid);
		// 判断用户信息是否存在
		if(!isset($userInfo[$this->pk]))
		{
			return $this->parseCheckResult(false,$isJumpToLogin);
		}
		return true;
	}
	/**
	 * 处理密码加盐
	 */
	public function parseCookieHash($uid,$time)
	{
		if(null !== $this->rememberCookieSaltFunction)
		{
			return call_user_func_array($this->rememberCookieSaltFunction, array($uid,$time,$this->passwordSalt));
		}
		return md5($uid . '###' . $time . '###' . $this->rememberCookieSalt);
	}
	/**
	 * 处理检测结果，用于做跳转和返回值
	 * @param type $result
	 * @param type $isJumpToLogin
	 * @return type
	 */
	private function parseCheckResult($result,$isJumpToLogin = false)
	{
		if(!$result && $isJumpToLogin)
		{
			if(empty($this->loginUrlMCA))
			{
				Response::redirect($this->loginUrl);
			}
			else if(is_array($this->loginUrlMCA))
			{
				Response::redirectU($this->loginUrlMCA[0],302,$this->loginUrlMCA[1]);
			}
			else
			{
				Response::redirectU($this->loginUrlMCA);
			}
		}
		return $result;
	}
}