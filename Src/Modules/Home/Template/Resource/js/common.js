function random(Min,Max)
{   
	var Range = Max - Min;   
	var Rand = Math.random();   
	return(Min + Math.round(Rand * Range));   
}
function setCookie(name,value,seconds)
{
	var exp = new Date();
	exp.setTime(exp.getTime() + seconds);
	document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}
function getCookie(name)
{
	var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
	if(arr=document.cookie.match(reg))
	{
		return unescape(arr[2]);
	}
	else
	{
		return null;
	}
}
function delCookie(name)
{
	var exp = new Date();
	exp.setTime(exp.getTime() - 1);
	var cval=getCookie(name);
	if(cval!=null)
	{
		document.cookie= name + "="+cval+";expires="+exp.toGMTString();
	}
}
function buildQuery(url,params)
{
	for(var key in params)
	{
		if(url.indexOf('?') >= 0)
		{
			url += '&';
		}
		else
		{
			url += '?';
		}
		url += encodeURI(key) + '=' + encodeURI(params[key]);
	}
	return url;
}
Array.prototype.del = function(n) {
	if (n < 0) return this;
	else return this.slice(0, n).concat(this.slice(n + 1, this.length));
}
function parseLine(str)
{
	return str.split("\r\n").join('<br/>');
}
var isScroll = function (el) {  
	// test targets  
	var elems = el ? [el] : [document.documentElement, document.body];  
	var scrollX = false, scrollY = false;  
	for (var i = 0; i < elems.length; i++) {  
		var o = elems[i];  
		// test horizontal  
		var sl = o.scrollLeft;  
		o.scrollLeft += (sl > 0) ? -1 : 1;  
		o.scrollLeft !== sl && (scrollX = scrollX || true);  
		o.scrollLeft = sl;  
		// test vertical  
		var st = o.scrollTop;  
		o.scrollTop += (st > 0) ? -1 : 1;  
		o.scrollTop !== st && (scrollY = scrollY || true);  
		o.scrollTop = st;  
	}  
	// ret  
	return {  
		scrollX: scrollX,  
		scrollY: scrollY  
	};  
};
/**
 * 把分类列表数据转为树形数据
 */
function parseCategoryTree(list)
{
	var indexData = {};
	for(var i=0;i<list.length;++i)
	{
		indexData[list[i].ID] = list[i];
	}
	var result = [];
	for(var i in indexData)
	{
		var value = indexData[i];
		if(void 0 === indexData[value.Parent])
		{
			result.push(indexData[value.ID]);
		}
		else
		{
			if(void 0 === indexData[value.Parent].Children)
			{
				indexData[value.Parent].Children = [];
			}
			indexData[value.Parent].Children.push(indexData[value.ID]);
		}
	}
	return result;
}
function htmlspecialchars_decode(str){
	str = str.replace(/&amp;/g, '&');
	str = str.replace(/&lt;/g, '<');
	str = str.replace(/&gt;/g, '>');
	str = str.replace(/&quot;/g, "''");
	str = str.replace(/&#039;/g, "'");
	return str;
}
function isWeiXin(){
    var ua = window.navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i) == 'micromessenger'){
        return true;
    }else{
        return false;
    }
}
var YBUserInfo = null;
var YBEventGetUserInfo = [];
function getUserInfo()
{
	$.ajax({
		type: 'GET',
		url: '<Api runat="server" control="Member" action="userinfo"/>',
		success: function(data){
			if(void 0 !== typeof(data.success) && data.success)
			{
				YBUserInfo = data.data;
				for(var i = 0; i < YBEventGetUserInfo.length; ++i)
				{
					YBEventGetUserInfo[i].apply();
				}
			}
		}
	});
}
var CommentCurrPage = 1;
function loadComments(contentID,page)
{
	if(void 0 === this.first)
	{
		this.first = true;
	}
	else
	{
		this.first = false;
	}
	var _this = this;
	$.ajax({
		type: 'GET',
		url: '<Api runat="server" control="Comment" action="queryByContent"/>',
		data: {ContentID:contentID,page:page},
		success: function(data){
			if(void 0 !== typeof(data.success) && data.success)
			{
				$('#CommentList').html(data.content);
				$('#YBArticleComments').html(data.comments);
				CommentCurrPage = data.page;
				layui.laypage({
					cont: 'CommentPagebar'
					,pages: data.pages
					,first: 1
					,last: data.pages
					,curr: data.page
					,prev: '<em><</em>'
					,next: '<em>></em>'
					,jump: function(obj,first){
						if(!first)
						{
							loadComments($('#CommentForm').find('[name=ContentID]').val(),obj.curr);
						}
					}
				});
			}
			if(_this.first)
			{
				$(window).scrollTop($(location.hash).offset().top);
			}
		}
	});
}
function parseComment()
{
	var form = $('#CommentForm');
	if(form.length == 0)
	{
		return;
	}
	YBEventGetUserInfo.push(function(){
		if(null === YBUserInfo)
		{
			$('#CommentForm .userinfo').hide();
			$('#CommentForm .comment-submit-info').show();
		}
		else
		{
			var userinfo = $('#CommentForm .userinfo');
			userinfo.find('.username').html(YBUserInfo.Name);
			userinfo.css('display','inline-block');
			$('#CommentForm .comment-submit-info').hide();
		}
	});
	// 加载评论
	loadComments(form.find('[name=ContentID]').val(),1);
	// 设置cookie保存的信息
	form.find('[name=Name]').val(getCookie('comment_name'));
	form.find('[name=Email]').val(getCookie('comment_email'));
	form.find('[name=QQ]').val(getCookie('comment_qq'));
	// 回复按钮事件
	$('body').on('click','.btn-reply-comment',function(){
		var commentID = $(this).attr('comment-id');
		var form = $($('#CommentForm').clone());
		form.attr('id','');
		form.find('.btn-close-comment-box').attr('comment-id',commentID);
		form.find('input[name="CommentID"]').val(commentID);
		$('.reply-box[comment-id='+commentID+']').html(form);
	});
	// 关闭按钮事件
	$('body').on('click','.btn-close-comment-box',function(){
		$('.reply-box[comment-id='+$(this).attr('comment-id')+']').html('');
	});
	// 表单提交
	$('body').on('submit','.CommentForm',function(){
		var form = $(this);
		$.ajax({
			type: 'POST',
			url: '<Api runat="server" control="Comment" action="add"/>',
			data: form.serialize(),
			success: function(data){
				if(void 0 === typeof(data.success))
				{
					layer.alert('服务器错误，请稍后重试！',{icon:2});
				}
				else if(data.success)
				{
					if(form.find('input[name="CommentID"]').val() == 0)
					{
						loadComments($('#CommentForm').find('[name=ContentID]').val(),1);
					}
					else
					{
						loadComments($('#CommentForm').find('[name=ContentID]').val(),CommentCurrPage);
					}
					layer.msg('评论成功！',{icon:6});
					form.find('[name=Content]').val('');
				}
				else
				{
					layer.alert(data.message,{icon:2});
				}
			},
			error: function(data){
				layer.alert('服务器错误，请稍后重试！',{icon:2});
			}
		});
		var seconds = 365 * 24 * 60 * 60;
		setCookie('comment_name',form.find('[name=Name]').val(),seconds);
		setCookie('comment_email',form.find('[name=Email]').val(),seconds);
		setCookie('comment_qq',form.find('[name=QQ]').val(),seconds);
		return false;
	});
}
function contentPing(id)
{
	$.ajax({
		type: 'GET',
		url: buildQuery('<Api runat="server" control="Content" action="ping"/>',{ID:id}),
		success:function(data){
			$('#YBArticleView').html(data.view);
		}
	});
}
function initYurunBlog()
{
	$(function(){
		parseComment();
		getUserInfo();
	});
}