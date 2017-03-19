<?php
/**
 * RSS生成类
 * @author Yurun <yurun@yurunsoft.com>
 * @copyright 宇润软件(Yurunsoft.Com) All rights reserved.
 */
class YurunRSSWriter
{
	/**
	 * XML操作对象
	 * @var DOMDocument
	 */
	public $xml;

	/**
	 * RSS协议版本号
	 * @var string
	 */
	public $rssVersion = '2.0';

	/**
	 * 错误信息
	 * @var string
	 */
	public $error = '';
	/**
	 * channel节点数据
	 * @var array
	 */
	public $channel = array();

	/**
	 * item节点数据
	 * @var array
	 */
	public $items = array();

	/**
	 * 保存前置事件
	 * @var array
	 */
	private $onSaveBefore = array();

	/**
	 * 保存前置事件
	 * @param callback $callback 
	 */
	public function onSaveBefore($callback)
	{
		$this->onSaveBefore[] = $callback;
	}

	/**
	 * 保存RSS到文件
	 * @param string $filePath 
	 * @return bool
	 */
	public function saveToFile($filePath,$format = false)
	{
		$rss = $this->getRss();
		if(!$rss)
		{
			return false;
		}
		return file_put_contents($filePath, $rss, LOCK_EX) > 0;
	}

	/**
	 * 获取RSS内容
	 * @return mixed
	 */
	public function getRss($format = false)
	{
		$this->xml = new DomDocument('1.0', 'UTF-8');
		// 根节点
		$root = $this->xml->createElement('rss');
		$root->setAttribute('version',$this->rssVersion);
		$this->xml->appendChild($root);
		// channel
		$channelNode = $this->xml->createElement('channel');
		$this->xml->documentElement->appendChild($channelNode);
		if(!$this->parseChannelInfo($channelNode))
		{
			return false;
		}
		if(!$this->parseItem($channelNode))
		{
			return false;
		}
		$this->xml->formatOutput = $format;
		// 保存前置操作
		foreach($this->onSaveBefore as $callback)
		{
			call_user_func($callback,array('rss'=>$this));
		}
		return $this->xml->saveXML();
	}

	/**
	 * 处理channel节点
	 * @param DOMElement $channelNode 
	 * @return bool 
	 */
	protected function parseChannelInfo($channelNode)
	{
		// 必须的节点
		static $mustNode = array('title','link','description');
		// 可选的节点
		static $optionalNode = array(
			'language',
			'copyright',
			'managingEditor',
			'webMaster',
			'docs',
			'ttl',
			'rating',
		);
		// 必须节点处理
		foreach($mustNode as $nodeName)
		{
			if(!isset($this->channel[$nodeName]))
			{
				$this->error = "channel 节点缺少 {$nodeName} 子节点数据";
				return false;
			}
			$node = $this->xml->createElement($nodeName);
			$node->appendChild($this->xml->createCDATASection($this->channel[$nodeName]));
			$channelNode->appendChild($node);
		}
		// 可选节点处理
		foreach($optionalNode as $nodeName)
		{
			if(isset($this->channel[$nodeName]))
			{
				$node = $this->xml->createElement($nodeName);
				$node->appendChild($this->xml->createCDATASection($this->channel[$nodeName]));
				$channelNode->appendChild($node);
			}
		}
		// 需要特别处理的可选节点
		if(isset($this->channel['pubDate']) && !$this->parseNode('pubDate',$this->parseDate($this->channel['pubDate']),$channelNode))
		{
			return false;
		}
		if(isset($this->channel['lastBuildDate']) && !$this->parseNode('lastBuildDate',$this->parseDate($this->channel['lastBuildDate']),$channelNode))
		{
			return false;
		}
		if(isset($this->channel['category']) && !$this->parseCategory($this->channel['category'],$channelNode))
		{
			return false;
		}
		if(!$this->parseGenerator($channelNode))
		{
			return false;
		}
		if(isset($this->channel['cloud']) && !$this->parseCloud($this->channel['cloud'],$channelNode))
		{
			return false;
		}
		if(isset($this->channel['image']) && !$this->parseImage($this->channel['image'],$channelNode))
		{
			return false;
		}
		if(isset($this->channel['textInput']) && !$this->parseTextInput($this->channel['textInput'],$channelNode))
		{
			return false;
		}
		if(isset($this->channel['skipHours']) && !$this->parseSkipHours($this->channel['skipHours'],$channelNode))
		{
			return false;
		}
		if(isset($this->channel['skipDays']) && !$this->parseSkipDays($this->channel['skipDays'],$channelNode))
		{
			return false;
		}
		return true;
	}

	/**
	 * 处理节点
	 * @param string $name 
	 * @param string $value 
	 * @param DOMElement $parentNode 
	 * @return bool 
	 */
	protected function parseNode($name,$value,$parentNode)
	{
		$node = $this->xml->createElement($name);
		$node->appendChild($this->xml->createCDATASection($value));
		$parentNode->appendChild($node);
		return true;
	}

	/**
	 * 处理category节点
	 * @param array $category 
	 * @param DOMElement $parentNode 
	 * @return bool 
	 */
	protected function parseCategory($category, $parentNode)
	{
		if(!is_array($category))
		{
			$this->error = 'channel[\'category\'] 必须是数组';
			return false;
		}
		foreach($category as $category)
		{
			$node = $this->xml->createElement('category');
			$node->appendChild($this->xml->createCDATASection($category['name']));
			if(isset($category['domain']))
			{
				$node->setAttribute('domain',$category['domain']);
			}
			$parentNode->appendChild($node);
		}
		return true;
	}

	/**
	 * 处理generator
	 * @param DOMElement $parentNode 
	 * @return bool 
	 */
	protected function parseGenerator($parentNode)
	{
		if(isset($this->channel['generator']))
		{
			$generator = $this->channel['generator'];
		}
		else
		{
			$generator = 'YurunPHPRSS';
		}
		$node = $this->xml->createElement('generator');
		$node->appendChild($this->xml->createCDATASection($generator));
		unset($generator);
		$parentNode->appendChild($node);
		return true;
	}

	/**
	 * 处理cloud
	 * @param array $cloud 
	 * @param DOMElement $parentNode 
	 * @return bool 
	 */
	protected function parseCloud($cloud, $parentNode)
	{
		static $options = array(
			'domain',
			'port',
			'path',
			'registerProcedure',
			'protocol'
		);
		$cloudNode = $this->xml->createElement('cloud');
		foreach($options as $option)
		{
			if(isset($cloud[$option]))
			{
				$cloudNode->setAttribute($option, $cloud[$option]);
			}
			else
			{
				$this->error = "channel['cloud']['{$option}'] 不能为空";
				return false;
			}
		}
		$parentNode->appendChild($cloudNode);
		return true;
	}

	/**
	 * 处理image
	 * @param array $image 
	 * @param DOMElement $parentNode 
	 * @return bool 
	 */
	protected function parseImage($image, $parentNode)
	{
		// 必须的节点
		static $mustNode = array('url','title','link');
		// 可选的节点
		static $optionalNode = array(
			'width',
			'height',
			'description',
		);
		$imageNode = $this->xml->createElement('image');
		$parentNode->appendChild($imageNode);
		// 必须节点处理
		foreach($mustNode as $nodeName)
		{
			if(!isset($image[$nodeName]))
			{
				$this->error = "channel['image'] 节点缺少 {$nodeName} 子节点数据";
				return false;
			}
			$node = $this->xml->createElement($nodeName);
			$node->appendChild($this->xml->createCDATASection($image[$nodeName]));
			$imageNode->appendChild($node);
		}
		// 可选节点处理
		foreach($optionalNode as $nodeName)
		{
			if(isset($image[$nodeName]))
			{
				$node = $this->xml->createElement($nodeName);
				$node->appendChild($this->xml->createCDATASection($image[$nodeName]));
				$imageNode->appendChild($node);
			}
		}
		return true;
	}

	/**
	 * 处理textInput
	 * @param array $textInput 
	 * @param DOMElement $parentNode 
	 * @return bool 
	 */
	protected function parseTextInput($textInput, $parentNode)
	{
		// 必须的节点
		static $mustNode = array('title','description','name','link');
		$textInputNode = $this->xml->createElement('textInput');
		$parentNode->appendChild($textInputNode);
		// 必须节点处理
		foreach($mustNode as $nodeName)
		{
			if(!isset($textInput[$nodeName]))
			{
				$this->error = "channel['textInput'] 节点缺少 {$nodeName} 子节点数据";
				return false;
			}
			$node = $this->xml->createElement($nodeName);
			$node->appendChild($this->xml->createCDATASection($textInput[$nodeName]));
			$textInputNode->appendChild($node);
		}
		return true;
	}

	/**
	 * 处理skipHours
	 * @param array $skipHours 
	 * @param DOMElement $parentNode 
	 * @return bool 
	 */
	protected function parseSkipHours($skipHours, $parentNode)
	{
		if(!is_array($skipHours))
		{
			$this->error = 'channel[\'skipHours\'] 必须是数组';
			return false;
		}
		$skipHoursNode = $this->xml->createElement('skipHours');
		$parentNode->appendChild($skipHoursNode);
		foreach($skipHours as $hour)
		{
			$node = $this->xml->createElement('hour',$hour);
			$skipHoursNode->appendChild($node);
		}
		return true;
	}

	/**
	 * 处理skipDays
	 * @param array $skipDays 
	 * @param DOMElement $parentNode 
	 * @return bool 
	 */
	protected function parseSkipDays($skipDays, $parentNode)
	{
		if(!is_array($skipDays))
		{
			$this->error = 'channel[\'skipDays\'] 必须是数组';
			return false;
		}
		$skipDaysNode = $this->xml->createElement('skipDays');
		$parentNode->appendChild($skipDaysNode);
		foreach($skipDays as $day)
		{
			$node = $this->xml->createElement('day',$this->parseDayWeek($day));
			$skipDaysNode->appendChild($node);
		}
		return true;
	}

	/**
	 * 处理Item
	 * @param DOMElement $parentNode 
	 * @return bool 
	 */
	protected function parseItem($parentNode)
	{
		static $nodes = array(
			'title',
			'link',
			'description',
			'author',
			'comments',
			'enclosure',
			'guid',
			'source'
		);
		foreach($this->items as $item)
		{
			$itemNode = $this->xml->createElement('item');
			$parentNode->appendChild($itemNode);
			foreach($nodes as $node)
			{
				if(isset($item[$node]))
				{
					$childNode = $this->xml->createElement($node);
					$childNode->appendChild($this->xml->createCDATASection($item[$node]));
					$itemNode->appendChild($childNode);
				}
			}
			// 需要特别处理的可选节点
			if(isset($item['pubDate']) && !$this->parseNode('pubDate',$this->parseDate($item['pubDate']),$itemNode))
			{
				return false;
			}
			if(isset($item['category']) && !$this->parseCategory($item['category'],$itemNode))
			{
				return false;
			}
		}
		return true;
	}

	/**
	 * 处理日期为RFC 822格式
	 * @param mixed $date 
	 * @return string 
	 */
	protected function parseDate($date)
	{
		if(!is_numeric($date))
		{
			$date = strtotime($date);
		}
		return date(DATE_RFC822,$date);
	}

	/**
	 * 处理星期几
	 * @param mixed $day 
	 * @return string 
	 */
	protected function parseDayWeek($day)
	{
		static $dayWeek = array(
			1 => 'Monday',
			2 => 'Tuesday',
			3 => 'Wednesday',
			4 => 'Thursday',
			5 => 'Friday',
			6 => 'Saturday',
			7 => 'Sunday'
		);
		if(is_numeric($day))
		{
			return isset($dayWeek[$day]) ? $dayWeek[$day] : $day;
		}
		else
		{
			return $day;
		}
	}
}