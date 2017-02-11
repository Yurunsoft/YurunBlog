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
