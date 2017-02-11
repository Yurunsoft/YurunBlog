<?php
class DictModel extends Model
{
	private $file;
	private $dict;
	public $filename;
	public function __construct($table = null, $dbAlias = null) {
		parent::__construct($table, $dbAlias);
		$this->filename = APP_CONFIG . 'const.php';
	}
	public function generateConst()
	{
		$data = $this->order(array('Type','Value'))->select();
		$this->file = fopen($this->filename, 'w');
		flock($this->file, LOCK_EX);
		fwrite($this->file, <<<EOF
<?php

EOF
);
		$this->dict = array();
		foreach($data as $item)
		{
			$this->writeConst($item);
			$this->parseDict($item);
		}
		$this->writeDict();
		fclose($this->file);
	}
	private function writeConst($item)
	{
		if($item['Type'] == '')
		{
			$val = "'{$item['Name']}'";
		}
		else if(is_numeric($item['Value']))
		{
			$val = $item['Value'];
		}
		else
		{
			$val = "'{$item['Value']}'";
		}
		fwrite($this->file, <<<EOF
/**
 * {$item['Text']}
 */
define('{$item['Name']}',{$val});

EOF
);
	}
	private function parseDict($item)
	{
		if($item['Type'] != '')
		{
			// 组
			if(!isset($this->dict[$item['Type']]))
			{
				$this->dict[$item['Type']] = array();
			}
			$this->dict[$item['Type']][$item['Name']] = array('value'=>$item['Value'],'text'=>$item['Text']);
		}
	}
	private function writeDict()
	{
		fwrite($this->file, 'return ' . var_export($this->dict, true) . ';');
	}
	public function loadDict()
	{
		Config::create(array(
			'type'		=>	'Php',
			'option'	=>	array(
				'filename'	=>	$this->filename
			)),'BaseDict');
	}
}