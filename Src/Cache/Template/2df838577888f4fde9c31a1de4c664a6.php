<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台登录 - <?php echo Config::get('@.SYSTEM_NAME');?></title>
<?php $this->include('/public_head');?>
</head>
<body id="login_body">
	<div id="header">
		<div class="logo">
			<a href=""><img src="http://localhost:2222/Static/admin/images/logo.png"/></a>
		</div>
	</div>
	<div id="content_login">
		<form class="form-horizontal" id="login_form">
			<div class="form-group">
				<input type="text" class="form-control" placeholder="用户名" name="Username" id="username"/>
			</div>
			<div class="form-group">
				<input type="password" class="form-control" placeholder="密码" data-name="password" id="password"/>
			</div>
			<div class="form-group">
				<div class="input-group">
					<input type="text" class="form-control" placeholder="验证码" name="vcode" id="vcode">
					<span class="input-group-addon" style="padding:0"><a href="javascript:changeVcode()"><img id="vcode_img" src="<?php echo Dispatch::url('Admin/Vcode/show');?>"/></a></span>
				</div>
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-theme" style="width:100%">登录</button>
			</div>
		</form>
	</div>
	<script>
		function changeVcode()
		{
			$('#vcode_img').attr('src','<?php echo Dispatch::url('Admin/Vcode/show');?>'+'?'+Math.random());
		}
		$('#login_form').submit(function(){
			$(this).prop('disabled',true);
			$('#login_form').append($("<input>").attr({type:'hidden',name:'Password',value:parsePassword($('[data-name=password]').val())}));
			var data = $('#login_form').serialize();
			$('[name=Password]').remove();
			$.ajax({
				type: "post",
				url: '<?php $c589eabfba0d28909168683=YurunComponent::getApi(array('runat'=>'server','control'=>'Member','action'=>'login','innerHtml'=>''));if(false!==$c589eabfba0d28909168683->begin()): endif;$c589eabfba0d28909168683->end();?>', 
				data: data,
				success: function(data) {
					if(data.success)
					{
						location = '<?php echo Dispatch::url('index');?>';
					}
					else
					{
						layer.alert(data.message,{icon:7},onFailed);
					}

				},
				error: function(error){
					layer.alert('服务器错误，请稍后重试！',{icon:2},onFailed);
				}
 			});
			return false;
		})
		function onFailed(index)
		{
			changeVcode();
			$('#vcode').val('').focus();
			layer.close(index)
		}
	</script>
</body>
</html>