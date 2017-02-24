function random(Min,Max)
{   
	var Range = Max - Min;   
	var Rand = Math.random();   
	return(Min + Math.round(Rand * Range));   
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
