<?php
class ApiControl extends BaseControl
{
	private $apiData;
	public $control,$action;
	function __construct()
	{
		parent::__construct(true);
		$this->control = Request::get('control');
		$this->action = Request::get('action');
		$_this = $this;
		set_exception_handler(function($exception)use($_this){
			if(Config::get('@.LOG_ERROR'))
			{
				Log::add('错误:'.$exception->getMessage().' 文件:'.$exception->getFile().' 行数:'.$exception->getLine());
			}
			Log::add("API/{$_this->control}/{$_this->action}:" . $exception->getMessage());
			$_this->parseResult(false,'服务器出错');
			$_this->returnData($this->apiData);
		});
	}
	public function call()
	{
		$isTrans = false;
		try {
			try {
				$reflection = new ReflectionClass($this->control . 'API');
				if($reflection->isAbstract())
				{
					throw new Exception('API不存在');
				}
				if($reflection->hasMethod($this->action))
				{
					$method = $reflection->getMethod($this->action);
				}
				else
				{
					$method = $reflection->getMethod('_R_' . $this->action);
				}
				if ($method->isPublic())
				{
					$comment = $method->getDocComment();
					if (false === stripos($comment, '@api'))
					{
						throw new Exception('没有@api标记');
					}
					if (false !== stripos($comment, '@UserToken'))
					{
						if($this->checkUserSession(false))
						{
							Globals::$user = $this->userSession->userInfo;
						}
						else
						{
							$this->parseResult(false, '需要登录后才可调用API');
						}
					}
					if (false !== stripos($comment, '@Trans'))
					{
						$isTrans = true;
					}
				}
				else
				{
					throw new Exception('不为public');
				}
			} catch (Exception $exc) {
				$this->parseResult(false,'API不存在');
				throw $exc;
			}
		} catch (Exception $exc) {
			Log::add("API/{$this->control}/{$this->action}:" . $exc->getMessage() . ' 文件:' . $exc->getFile() . ' 行数:' . $exc->getLine());
		}
		if(empty($this->apiData))
		{
			$instance = $reflection->newInstanceArgs();
			$instance->control = &$this;
			if($isTrans)
			{
				$m = new Model;
				$m->getDb()->begin();
			}
			$method->invoke($instance);
			if($isTrans)
			{
				if($instance->success)
				{
					$m->getDb()->commit();
				}
				else
				{
					$m->getDb()->rollback();
				}
			}
			$this->parseResult($instance->success,$instance->message,$instance->data);
		}
		$this->returnData($this->apiData);
	}
	protected function parseResult($success,$message = '',$data = array())
	{
		$this->apiData = array_merge(array('success'=>$success,'message'=>$message),$data);
	}
}