function downloadFile(url)
{
	$('body').append($('iframe').attr('src',url).css('display','none'));
}
var downloadIndex = 0;
function downloadFileByForm(formFlag)
{
	// 获取form的target属性，以便使用后恢复
	var target = $(formFlag).attr('target');
	if(target == undefined)
	{
		target = '';
	}
	// 备份
	$(formFlag).attr('target-bak',target);
	// iframe的id和name
	var id = 'download-'+downloadIndex;
	$(formFlag).attr('target',id);
	var iframe = $('iframe').attr('id',id).attr('name',id).css('display','none');
	// 向网页尾部添加iframe
	$('body').append(iframe);
	// 提交表单
	$(formFlag).submit();
	// 恢复target
	$(formFlag).attr('target',$(formFlag).attr('target-bak'));
	$(formFlag).removeAttr('target-bak');
	// 计数器累加
	downloadIndex++;
}