function bindBatch(url)
{
	$('[name=operate]').change(function(){
		var val = $(this).val();
		$('[name=operate]').each(function(){
			$(this).val(val);
		})
	})
	$('.batch_submit').click(function(){
		var ids = [];
		$('[groupname=ids]').each(function(){
			if($(this).prop('checked'))
			{
				ids.push($(this).val());
			}
		});
		ids = ids.join(',');
		var operate = $('[name=operate]').val();
		if(operate === '')
		{
			alert('请选择操作类型！');
			return;
		}
		if(ids === '')
		{
			alert('你还没有选择要操作的项！');
			return;
		}
		location = url + '&id=' + ids + '&operate=' + operate;
	})
}
function parsePassword(password)
{
	var result = password;
	result += password.split("").reverse().join("");
	for(var i=0;i<password.length;i+=2)
	{
		result += password[i];
	}
	for(var i=1;i<password.length;i+=2)
	{
		result += password[i];
	}
	result = $.md5(result);
	return result;
}
$(document).ready(function(e) {
	$('input[type="checkbox"]').each(function(index, element) {
		var groupName=$(this).attr('checkall');
		if(groupName!=null)
		{
			$(this).bind("change",function(){
				if($(this).is(":checked"))
				{
					$('input[type="checkbox"][groupname="'+groupName+'"]').each(function(index, element) {
						$(this).prop('checked',true);
					});
				}
				else
				{
					$('input[type="checkbox"][groupname="'+groupName+'"]').each(function(index, element) {
						$(this).prop('checked',false);
					});
				}
			});
		}
	});
});
var ModalHelper = (function (bodyCls) {
	var scrollTop;
	return {
		afterOpen: function () {
			var style = '<style id="__YBStyleModal">body.modal-open {position: fixed;width: 100%;';
			if(isScroll().scrollY)
			{
				style += 'overflow-y:scroll;';
			}
			style += '}</style>';
			$('body').append(style);
			scrollTop = document.scrollingElement.scrollTop;
			document.body.classList.add(bodyCls);
			document.body.style.top = -scrollTop + 'px';
		},
		beforeClose: function () {
			document.body.style.top = 0;
			document.body.classList.remove(bodyCls);
			document.scrollingElement.scrollTop = scrollTop;
			$('#__YBStyleModal').remove();
		}
	};
})('modal-open');
var lastSelectItem = null;
$(function(){
	$('body').on('click','.layui-form-select',function(){
		if(null !== lastSelectItem)
		{
			lastSelectItem = null;
			ModalHelper.beforeClose();
		}
		ModalHelper.afterOpen();
		lastSelectItem = $(this);
		var _this = lastSelectItem;
		var h = setInterval(function(){
			if(!$(_this).hasClass('layui-form-selected'))
			{
				if(_this === lastSelectItem)
				{
					lastSelectItem = null;
					ModalHelper.beforeClose();
				}
				clearInterval(h);
			}
		},100);
	});
});
$(function(){
	$('.left-menu-toggle').click(function(){
		var isShow = $('#left_nav').hasClass('show-item');
		if(isShow)
		{
			$('#left_nav').removeClass('show-item');
		}
		else
		{
			$('#left_nav').addClass('show-item');
		}
	});
	$('.menu-list > li > a').click(function(){
		// 节点
        var item = $(this).parent();
		if(item.find('.sub-list').length > 0)
        {
			if(item.hasClass('active'))
			{
				// 隐藏当前
				item.find('.sub-list').slideUp();
				item.removeClass('active');
			}
			else
			{
				// 隐藏旧的
				var old = $('.menu-list > li.active');
				old.find('.sub-list').slideUp();
				old.removeClass('active');
				// 显示新的
				item.find('.sub-list').slideDown();
				item.addClass('active');
			}
		}
		else
		{
			// 隐藏旧的
			var old = $('.menu-list li.active');
			old.find('.sub-list').slideUp();
			old.removeClass('active');
			// 显示新的
			$(this).parent().addClass('active');
			$('#left_nav').removeClass('show-item');
		}
	});
	$('.menu-list .sub-list > li > a').click(function(){
		// 隐藏旧的
		var old = $('.menu-list li.active');
		old.removeClass('active');
		// 显示新的
		$(this).parent().addClass('active');
		$(this).parent().parent().parent().addClass('active');
		$('#left_nav').removeClass('show-item');
	})
	$('.menu-list > li.active').addClass('active').find('.sub-list').slideDown();
})
var form;
$(function(){
	form = layui.form();
	form.render();
	// laydate.skin('molv');
})