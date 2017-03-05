<?php
class CommentModel extends BaseModel
{
	/**
	 * 扩展数据类型：以 EX_DATA_TYPE_ 开头的常量
	 * @var int
	 */
	public $exDataType = EX_DATA_TYPE_COMMENT;
	/**
	 * 结果扩展Content
	 * @var bool
	 */
	public $exContent = false;
	/**
	 * 处理查询条件
	 */
	public function parseCondition($data)
	{
		if(!isEmpty($data['SearchContentPk']))
		{
			$this->where(array('and'=>array('ContentID'=>$data['SearchContentPk'],'or'=>array('content.Alias'=>$data['SearchContentPk']))));
		}
		if(!empty($data['Status']))
		{
			$this->where(array($this->tableName() . '.Status'=>$data['Status']));
		}
		if(!isEmpty($data['SearchCommentContent']))
		{
			$this->where(array($this->tableName() . '.Content'=>array('like','%' . $data['SearchCommentContent'] . '%')));
		}
		return $this;
	}
	/**
	 * 查询多条记录后置方法
	 * @param array $data 
	 * @return mixed
	 */
	public function __selectAfter(&$data)
	{
		$result = parent::__selectAfter($data);
		$this->exContent = false;
		return $result;
	}
	/*
	 * 处理查询内容
	 */
	public function __selectBefore()
	{
		$tableName = $this->tableName();
		$this->field("{$tableName}.*,
					dictStatus.Text as StatusText,
					user.Name as UserName,
					user.Email as UserEmail,
					user.QQ as UserQQ,
					content.Alias as ContentAlias
					")
			 ->join('left',$this->tableName('content') . ' as content',"{$tableName}.ContentID = content.ID")
			 ->join('left',$this->tableName('dict') . ' as dictStatus',"dictStatus.Type = 'COMMENT_STATUS' and dictStatus.Value = {$tableName}.Status")
			 ->join('left',$this->tableName('user') . ' as user',"{$tableName}.UserID = user.ID")
			 ;
	}
	public function __selectOneAfter(&$data)
	{
		// IP转为明码
		if(isset($data['IP']))
		{
			$data['IP'] = inet_ntop($data['IP']);
		}
		if(isset($data['UserIP']))
		{
			$data['UserIP'] = inet_ntop($data['UserIP']);
		}
		if($data['UserID'] > 0)
		{
			$data['Name'] = $data['UserName'];
			$data['Email'] = $data['UserEmail'];
			$data['QQ'] = $data['UserQQ'];
			unset($data['UserName'],$data['UserEmail'],$data['UserQQ']);
		}
		static $contentModel;
		if($this->exContent)
		{
			if(null === $contentModel)
			{
				$contentModel = new ContentModel;
			}
			$data['ExContent'] = $contentModel->getByPk($data['ContentID']);
		}
		return parent::__selectOneAfter($data);
	}
	public function __addBefore(&$data)
	{
		$params = array('data'=>&$data,'result'=>&$eventResult);
		Event::trigger('YB_ADD_COMMENT_BEFORE',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			$this->error = $eventResult;
			return false;
		}
		$contentModel = new ContentModel;
		$canComment = $contentModel->field('CanComment')->wherePk($data['ContentID'])->selectValue();
		if(!$canComment)
		{
			$this->error = '不允许评论该内容';
			return false;
		}
		if(isset(Globals::$user['ID']))
		{
			$data['UserID'] = Globals::$user['ID'];
			unset($data['Name'],$data['Email'],$data['QQ']);
		}
		else
		{
			if(isEmpty($data['Name']))
			{
				$this->error = '请填写昵称';
				return false;
			}
			$data['Name'] = htmlspecialchars($data['Name']);
			if(!isEmpty($data['Email']) && !Validator::email($data['Email']))
			{
				$this->error = '邮箱格式不正确';
				return false;
			}
			if(!isEmpty($data['QQ']) && !Validator::qq($data['QQ']))
			{
				$this->error = 'QQ格式不正确';
				return false;
			}
		}
		if(isEmpty($data['Content']))
		{
			$this->error = '评论内容不能为空';
			return false;
		}
		$data['Content'] = htmlspecialchars($data['Content']);
		$data['UA'] = $_SERVER['HTTP_USER_AGENT'];
		// IP转码保存
		$data['IP'] = inet_pton(Request::getIP());
		$data['UserIP'] = inet_pton(Request::getIP(true));

		$data['Status'] = Config::get('@.DEFAULT_COMMENT_STATUS');
		return parent::__addBefore($data);
	}
	public function __addAfter(&$data,$result)
	{
		$params = array('data'=>&$data,'addResult'=>$result,'result'=>&$eventResult);
		Event::trigger('YB_ADD_COMMENT_AFTER',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			$this->error = $eventResult;
			return false;
		}
		$contentModel = new ContentModel;
		if(!$contentModel->incComments($data['ContentID']))
		{
			$this->error = $contentModel->error;
			return false;
		}
		return parent::__addAfter($data,$result);
	}
	public function __saveBefore(&$data)
	{
		$params = array('data'=>&$data,'result'=>&$eventResult);
		Event::trigger('YB_SAVE_COMMENT_BEFORE',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			$this->error = $eventResult;
			return false;
		}
		return parent::__saveBefore($data);
	}
	public function __saveAfter(&$data,$result)
	{
		$params = array('data'=>&$data,'saveResult'=>$result,'result'=>&$eventResult);
		Event::trigger('YB_SAVE_COMMENT_AFTER',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			$this->error = $eventResult;
			return false;
		}
		return parent::__saveAfter($data,$result);
	}
	public function __deleteBefore(&$pkData)
	{
		$params = array('pkData'=>&$pkData,'result'=>&$eventResult);
		Event::trigger('YB_DELETE_COMMENT_BEFORE',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			$this->error = $eventResult;
			return false;
		}
		return parent::__deleteBefore($pkData);
	}
	public function __deleteAfter($result)
	{
		$params = array('deleteResult'=>$result,'result'=>&$eventResult);
		Event::trigger('YB_DELETE_COMMENT_AFTER',$params);
		if(null !== $eventResult && true !== $eventResult)
		{
			$this->error = $eventResult;
			return false;
		}
		return parent::__deleteAfter($result);
	}
	public function orderByNew()
	{
		return $this->order(array(
			'ID'			=>	'desc',
		));
	}
	
	/**
	 * 获取下属所有级的子分类的ID
	 * @param number $id
	 * @param string $first
	 * @return multitype
	 */
	public function getChildsIds($id = 0,$first = true)
	{
		if(is_array($id))
		{
			$ids = $id;
			if(!isset($ids[0]))
			{
				return array();
			}
		}
		else
		{
			$ids = array($id);
		}
		$tids = array_column($this->selectBefore(false)->field($this->tableName() . '.ID')->where(array('CommentID'=>array('in',$ids)))->select(),'ID');
		if(isset($tids[0]))
		{
			$ids = array_merge($ids,$tids,$this->getChildsIds($tids,false));
		}
		return $first ? array_unique($ids) : $ids;
	}
	public function getAssocList($contentID,$limits,$page = 1,&$totalRecords = 0)
	{
		$options = $this->options;
		$list = $this->selectBefore(false)
					 ->field($this->tableName() . '.ID')
					 ->where(array('ContentID'=>$contentID,'CommentID'=>0))
					//  ->limit(,$limits)
					 ->selectPage($page,$limits,$totalRecords);
		$ids = $this->getChildsIds(array_column($list,'ID'));
		if(empty($ids))
		{
			return array();
		}
		unset($list);
		// 查询出所有分类记录
		if(isset($options['order']))
		{
			$this->options['order'] = $options['order'];
		}
		$arr1 = $this->where(array($this->tableName() . '.ID'=>array('in',$ids)))->selectList();
		$arr2 = array();
		// 处理成ID为键名的数组
		foreach($arr1 as $item)
		{
			$arr2[$item['ID']] = $item;
		}
		// 节省内存
		unset($arr1);
		// 结果数组
		$result = array();
		// 循环处理关联列表
		foreach($arr2 as $item)
		{
			if(isset($arr2[$item['CommentID']]))
			{
				$arr2[$item['CommentID']]['Children'][] = &$arr2[$item['ID']];
			}
			else
			{
				$result[] = &$arr2[$item['ID']];
			}
		}
		return $result;
	}
	public function getPages($contentID,$limits)
	{
		$records = $this->selectBefore(false)
						->where(array('ContentID'=>$contentID,'CommentID'=>0))
						->count('ID');
		$p = new Page($records,$limits,1);
		return $p->getTotalPages();
	}
	public function exContent($exContent)
	{
		$this->exContent = $exContent;
		return $this;
	}
}