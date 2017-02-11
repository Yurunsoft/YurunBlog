<form id="form1" class="layui-form layui-form-pane" action="<?php $c589e7e990a712633040045=YurunComponent::getApi(array('runat'=>'server','control'=>'Member','action'=>'profile','innerHtml'=>''));if(false!==$c589e7e990a712633040045->begin()): endif;$c589e7e990a712633040045->end();?>" method="post">
	<div class="layui-form-item">
		<label class="layui-form-label">用户名</label>
		<div class="layui-input-inline">
			<input type="text" readonly value="<?php echo $user['Username'];?>" class="layui-input"/>
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">昵称</label>
		<div class="layui-input-inline">
			<input type="text" name="Name" value="<?php echo $user['Name'];?>" class="layui-input"/>
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">密码</label>
		<div class="layui-input-inline">
			<input type="text" data-name="password" placeholder="不修改则留空" autocomplete="off" class="layui-input"/>
		</div>
		<div class="layui-form-mid layui-word-aux"></div>
	</div>
	<div class="center">
		<button class="layui-btn" lay-submit>保存</button>
	</div>
</form>
<script>
function getPostData()
{
	if($('[data-name=password]').val() === '')
	{
		return $('#form1').serialize();
	}
	else
	{
		$('#form1').append($("<input>").attr({type:'hidden',name:'Password',value:parsePassword($('[data-name=password]').val())}));
		var data = $('#form1').serialize();
		$('[name=Password]').remove();
		return data;
	}
}
</script>