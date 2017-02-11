<!doctype html>
<html id="admin">
	<head>
	<?php $this->include('/public_head');?>
	</head>
	<body>
		<div id="body_content">
			<h3>
				<?php echo $title;?>
			</h3>
			<div class="wrap">
				<div class="shortcuts">
					<?php $index=-1; foreach($shortcuts as $title=>$url){++$index;?>
						<a href="<?php echo $url;?>" class="btn"><?php echo $title;?></a>
					<?php }?>
				</div>
				<?php if(empty($template)): $this->include('Category/#Dispatch::action()'); else: $this->include($template); endif;?>
			</div>
		</div>
	</body>
</html>
<!-- 处理时间：<?php echo microtime(true) - YURUN_START;?> -->