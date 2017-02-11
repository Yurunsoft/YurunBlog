<div class="table-toolbar">
	<form class="layui-form form-inline" id="form1">
		<div class="pull-left">
			<div class="form-group">
				<a class="layui-btn layui-btn-primary btn-add"><i class="glyphicon glyphicon-plus green"></i> 新建</a>
			</div>
		</div>
	</form>
</div>
<div class="table-parent table-responsive cb">
	<table class="table" id="table">
		<thead>
			<tr>
				<th width="80">ID</th>
				<th>名称</th>
				<th>别名</th>
				<th>分类模版</th>
				<th>文章模版</th>
				<th width="80">文章数量</th>
				<th width="100" class="center">操作</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
<script id="template" type="text/html">
{{# layui.each(parseTreeDataset(d.list,'ID','Parent',0), function(index, item){ }}
	<tr>
		<td>{{ item.ID }}</td>
		<td>
			{{# if(item.Level > 0){ }}
			{{# for(var i=0;i<item.Level;i++){ }}
			&nbsp;
			{{# } }}
			└
			{{# } }}
			<a href="{{ item.Url }}" target="_blank">{{ item.Name }}</a>
		</td>
		<td>{{ item.Alias }}</td>
		<td>{{ item.CategoryTemplate }}</td>
		<td>{{ item.ArticleTemplate }}</td>
		<td>{{ item.Articles }}</td>
		<td class="center">
			<button data-id="{{ item.ID }}" class="btn btn-sm btn-sm-edit glyphicon glyphicon-edit" title="编辑"></button>
			<button data-id="{{ item.ID }}" class="btn btn-sm btn-sm-delete glyphicon glyphicon-remove" title="删除"></button>
		</td>
	</tr>
{{#  }); }}
</script>
<script>
	var queryOption = new QueryOption({
		template:$('#template').html(),
		contentElement:$('#table tbody'),
		queryUrl:'<?php $c589eb89be1b54172998225=YurunComponent::getApi(array('runat'=>'server','control'=>Dispatch::control(),'action'=>'query','innerHtml'=>''));if(false!==$c589eb89be1b54172998225->begin()): endif;$c589eb89be1b54172998225->end();?>',
		isBindForm:true,
		bindFormElement:$('#form1')
	});
	$('.btn-add').click(function(){
		var action = new PopupOption({url:'<?php echo Dispatch::url('add');?>',title:'新建分类',size:['1100px','425px'],onSuccess:function(data){
			queryOption.query();
		}});
	});
	$('body').on('click','.btn-sm-edit',function(){
		var action = new PopupOption({url:'<?php echo Dispatch::url('update');?>',params:{id:$(this).attr('data-id')},title:'编辑分类',size:['1100px','425px'],onSuccess:function(data){
			queryOption.query();
		}});
	})
	$('body').on('click','.btn-sm-delete',function(){
		var action = new ActionOption({url:'<?php $c589eb89be2260257222204=YurunComponent::getApi(array('runat'=>'server','control'=>Dispatch::control(),'action'=>'delete','innerHtml'=>''));if(false!==$c589eb89be2260257222204->begin()): endif;$c589eb89be2260257222204->end();?>',params:{ID:$(this).attr('data-id')},onSuccess:function(data){
			queryOption.query();
		}});
	})
</script>