<!doctype html>
<html id="admin">
<head>
<title><?php echo $title;?> - <?php echo Config::get('@.SYSTEM_NAME');?></title>
<?php $this->include('/public_head');?>
</head>
<?php $this->include('/top'); $this->include('/left');?>
	<div id="body">
		<iframe id="content_body" name="content_body" style="width:100%;height:100%;border:none"/>
	</div>
</body>
</html>
<!-- 处理时间：<?php echo microtime(true) - YURUN_START;?> -->