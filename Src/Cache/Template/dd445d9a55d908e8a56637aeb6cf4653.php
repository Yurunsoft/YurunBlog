<body>
<nav id="header" class="navbar navbar-inverse navbar-fixed-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle left-menu-toggle">
				<span class="sr-only">Toggle navigation</span>
				<span class="glyphicon glyphicon-th-large"></span>
			</button>
			<button type="button" class="navbar-toggle collapsed top-menu-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a href="<?php echo Dispatch::url('Index/index');?>" class="navbar-brand"><img src="http://localhost:2222/Static/admin/images/logo2.png"/></a>
		</div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li><a href="<?php echo Dispatch::url('Index/home');?>" target="content_body">后台管理</a></li>
				<!--<li><a href="<?php echo Dispatch::url('Cache/index');?>">清理缓存</a></li>-->
			</ul>
			<ul class="nav navbar-nav navbar-right userinfo">
				<li><a href="<?php echo Dispatch::url('Home/Index/index');?>"><i class="home"></i>返回网站</a></li>
				<li><a class="btn-profile" href="javascript:;"><i class="profile"></i><span class="userinfo_name"><?php echo $user['Name'];?></span></a></li>
				<li><a href="<?php echo Dispatch::url('Index/logout');?>"><i class="logout"></i>退出</a></li>
			</ul>
		</div>
	</div>
</nav>
<script>
$('.btn-profile').click(function(){
	var action = new PopupOption({url:'<?php echo Dispatch::url('Member/profile');?>',title:'个人信息',size:['450px','350px'],onSuccess:function(data){
		$('.userinfo_name').html(data.post_data.Name);
	}});
});
</script>