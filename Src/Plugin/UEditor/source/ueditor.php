<if condition="$first">
<js src="__PLUGIN__/UEditor/source/ueditor.config.js"/>
<js src="__PLUGIN__/UEditor/source/ueditor.all.min.js"/>
<script>
function setEditorContent(editor,content)
{
	editor.setContent(content,false);
}
</script>
</if>
<script type="text/plain" id="<%=$data['name']%>" name="<%=$data['name']%>"<%=$attrsStr%>><%=$data['content']%></script>
<script type="text/javascript">
$(function(){
	<php>
		$varName = 'editor_' . $data['name'];
	</php>
	<%=$varName%> = UE.getEditor('<%=$data['name']%>');
	if(onEditorReady !== void 0)
	{
		<%=$varName%>.ready(onEditorReady);
	}
});
</script>