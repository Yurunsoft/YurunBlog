function resizeCode()
{
	var guttelines=$('.gutter .line');
	var codelines=$('.code .line');
	for(var i=0;i<guttelines.length;i++){
		guttelines.eq(i).css('height',codelines.eq(i).css('height'))
	}
}
function parseContentImg()
{
	$('.article-content').each(function(i,e){
		var group = 'group' + i;
		$(e).find('img').each(function(i,e){
			var element = $(e);
			if('A' !== element.parent().get(0).tagName)
			{
				var a = $('<a target="_blank" class="fancybox"></a>').attr({href:element.attr('src'),title:element.attr('title'),'data-fancybox':group}).append(element.clone());
				element.replaceWith(a);
			}
		});
	});
	$(".fancybox").fancybox({
		// Options will go here
	});
}
initYurunBlog();
$(function(){
	parseContentImg();
	SyntaxHighlighter.all();
	// 微信不允许浮动，不然会强制给你转网页格式
	if(!isWeiXin())
	{
		$('#navbar').addClass('navbar-fixed-top');
		$('body').addClass('nav-top');
	}
});
$(window).load(function(){
	setTimeout(function(){
		resizeCode();
	},1);
	$(window).resize(resizeCode);
});