<?php defined('YURUN_START') or exit;?><!DOCTYPE>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>错误</title>
</head>
<body>
	<h1>错误信息：<?php echo nl2br($data['message']);?></h1>
	<p><b>文件：</b><?php echo $data['file'];?> <b>行数：</b><?php echo $data['line'];?></p>
	<p><b>跟踪：</b><br/><?php echo nl2br($data['trace']);?></p>
	<?php if(isset($data['lastsql'])){?>
	<p><b>最后执行的SQL语句：</b><?php echo $data['lastsql'];?></p>
	<?php }?>
	<hr/>
	<h1>访问信息</h1>
	<p><b>地址：</b><?php echo 'http://',$_SERVER['HTTP_HOST'],$_SERVER['REQUEST_URI'];?></p>
	<p>YurunPHP <?php echo Yurun::YURUN_VERSION;?></p>
</body>
</html>