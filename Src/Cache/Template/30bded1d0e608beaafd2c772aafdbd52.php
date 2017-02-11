<form id="form1" class="layui-form layui-form-pane" action="<?php $c589eb8ae24d53345986647=YurunComponent::getApi(array('runat'=>'server','control'=>Dispatch::control(),'action'=>Dispatch::action(),'innerHtml'=>''));if(false!==$c589eb8ae24d53345986647->begin()): endif;$c589eb8ae24d53345986647->end();?>" method="post">
	<div class="col-md-8 col-sm-6">
		<div class="layui-form-item">
			<label class="layui-form-label">名称 <span class="red">*</span></label>
			<div class="layui-input-block">
				<input type="text" name="Name" placeholder="显示的分类名称" class="layui-input"/>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">别名</label>
			<div class="layui-input-block">
				<input type="text" name="Alias" placeholder="默认：父分类别名-分类名" class="layui-input"/>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">SEO标题</label>
			<div class="layui-input-block">
				<input type="text" name="Title" placeholder="分类页面标题，用于SEO优化" class="layui-input"/>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">SEO关键词</label>
			<div class="layui-input-block">
				<input type="text" name="Keywords" placeholder="分类页关键词，用于SEO优化" class="layui-input"/>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">SEO描述</label>
			<div class="layui-input-block">
				<textarea name="Description" class="layui-textarea" placeholder="分类页描述，用于SEO优化"></textarea>
			</div>
		</div>
	</div>
	<div class="col-md-4 col-sm-6">
		<div class="layui-form-item">
			<label class="layui-form-label">父分类</label>
			<div class="layui-input-block">
				<?php $c589eb8ae25554850987884=YurunComponent::getselect(array('runat'=>'server','name'=>'Parent','data_func'=>'Category/select','text_field'=>'Name','value_field'=>'ID','first_item_text'=>'无','first_item_value'=>'0','innerHtml'=>''));if(false!==$c589eb8ae25554850987884->begin()): endif;$c589eb8ae25554850987884->end();?>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">顺序</label>
			<div class="layui-input-block">
				<input type="text" name="Index" placeholder="前-后：0-255" value="0" class="layui-input"/>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">分类模版</label>
			<div class="layui-input-block">
				<?php $c589eb8ae25941049772032=YurunComponent::getselect(array('runat'=>'server','name'=>'CategoryTemplate','data_func'=>'getCategoryTemplates','text_field'=>'value','innerHtml'=>''));if(false!==$c589eb8ae25941049772032->begin()): endif;$c589eb8ae25941049772032->end();?>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">文章模版</label>
			<div class="layui-input-block">
				<?php $c589eb8ae2598a461443563=YurunComponent::getselect(array('runat'=>'server','name'=>'ArticleTemplate','data_func'=>'getArticleTemplates','text_field'=>'value','innerHtml'=>''));if(false!==$c589eb8ae2598a461443563->begin()): endif;$c589eb8ae2598a461443563->end();?>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">显示</label>
			<div class="layui-input-block">
				<input type="checkbox" name="IsShow" value="1" title="前台"/>
      			<input type="checkbox" name="NavigationShow" value="1" title="导航栏"/>
			</div>
		</div>
		<div class="center">
			<?php if(isset($_GET['id'])):?>
				<input type="hidden" name="ID"/>
			<?php endif;?>
			<button class="layui-btn" lay-submit style="width:100%">保存</button>
		</div>
	</div>
</form>
<script>
	<?php if(isset($_GET['id'])):?>
	$(function(){
		var fo = new FindOption({url:'<?php $c589eb8ae259c3739075815=YurunComponent::getApi(array('runat'=>'server','control'=>Dispatch::control(),'action'=>'find','innerHtml'=>''));if(false!==$c589eb8ae259c3739075815->begin()): endif;$c589eb8ae259c3739075815->end();?>',params:{ID:<?php echo Request::get('id');?>},formElement:$('#form1')});
	});
	<?php endif;?>
</script>