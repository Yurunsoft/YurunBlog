function MuiltlineItem(opt) {
	this.box = opt.box ? opt.box : '';
	this.template = opt.template ? opt.template : '';
	this.addBtn = opt.addBtn ? opt.addBtn : '';
	this.data = opt.data ? opt.data : '';
	this.removeBtn = opt.removeBtn ? opt.removeBtn : '';
}
MuiltlineItem.prototype.init = function(){
	this.index = 0;
	var _this = this;
	$(this.addBtn).click(function(){
		_this.addRow();
	});
	$(this.box).on('click',this.removeBtn,function(){
		_this.removeRow($(this).attr('data-index'));
	});
	$(this.box).html('');
	for(var i=0;i<this.data.length;i++)
	{
		this.addRow(this.data[i]);
	}
};
MuiltlineItem.prototype.addRow = function(data){
	var item = $(this.template);
	var index = ++this.index;
	item.attr('data-index',index);
	item.find(this.removeBtn).attr('data-index',index);
	for(var varName in data)
	{
		var element = item.find('[data-name=\''+varName+'\']');
		for(var i=0;i<element.length;i++)
		{
			switch(element.get(i).tagName.toUpperCase())
			{
				case 'SELECT':
					element.find('option[value=\''+data[varName]+'\']').prop('selected',true)
					break;
				case "INPUT":
					var val = '';
					if(element.attr('data-control')=='datetimebox')
					{
						if(!isDateInvalid(data[varName]))
						{
							val = data[varName];
						}
					}
					else
					{
						val = data[varName];
					}
					element.val(val)
					break;
				case "TEXTAREA":
					element.html(data[varName])
					break;
			}
		}
	}
	$(this.box).append(item);
};
MuiltlineItem.prototype.removeRow = function(index){
	$(this.box).find('[data-index='+index+']').remove();
};
/**
 * 解决 ie，火狐浏览器不兼容new Date(s)
 * @param strDate
 * 返回 date对象
 * add by zyf at 2015年11月5日
 */
function getDateForStringDate(strDate){
	//切割年月日与时分秒称为数组
	var s = strDate.split(" "); 
	var s1 = s[0].split("-"); 
	if(s.length > 1)
	{
		var s2 = s[1].split(":");
		if(s2.length==2){
			s2.push("00");
		}
	}
	return new Date(s1[0],s1[1]-1,s1[2],s2?s2[0]:0,s2?s2[1]:0,s2?s2[2]:0);
}
function isDateInvalid(date)
{
	var d = new getDateForStringDate(date);
	return d.toString() == 'Invalid Date' || (date.indexOf('1899')<0 && d.toString().indexOf('1899')>-1);
}