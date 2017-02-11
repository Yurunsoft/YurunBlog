<!doctype html>
<html id="admin">
	<head>
	<?php $this->include('/public_head');?>
	</head>
  	<body id="popup">
  		<div>
			<script>
				var layerIndex = -1;
				var onClose = null;
				var onSuccess = null;
				var nomessage = <?php echo Request::get('nomessage',0);?>;
				function closePopup(data)
				{
					if(onClose != null)
					{
						parseCallback({callback:onClose,params:[data]});
					}
					parent.layer.close(layerIndex);
				}
				function getPostData()
				{
					return $('#form1').serialize();
				}
				function submit(e)
				{
					var formJson = getFormJson('#form1');
					$.ajax({
					    type: "post",
					    url: $('#form1').attr('action'), 
					    data: getPostData(),
					    success: function(data) {
					        if(data.success)
					        {
								if(null != onSuccess)
								{
									parseCallback({callback:onSuccess,params:[data,formJson]});
								}
								if(!nomessage)
								{
									parent.layer.msg('操作成功！', {time: 3000, icon:6});
								}
								closePopup(data);
					        }
					        else
					        {
					        	parent.layer.alert(data.message,{icon: 2});
					        }
					    },
					    error: function(error){
					    	parent.layer.alert('服务器出现错误，请重试！',{icon: 2});
					    }
					});
					e.preventDefault();
				}
				$(function(){
					$('#form1').submit(submit);
					form.render();
				});
			</script>
			<?php if(empty($template)): 
					$file = Dispatch::control() . '/' . Dispatch::action();
				 $this->include($file); else: $this->include($template); endif;?>
		</div>
  	</body>
</html>
