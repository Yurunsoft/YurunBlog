var $_GET = (function () {
    var url = window.document.location.href.toString();
    var u = url.split("?");
    if (typeof (u[1]) == "string") {
        u = u[1].split("&");
        var get = {};
        for (var i = 0; i < u.length; ++i) {
            var j = u[i].split("=");
            get[j[0]] = j[1];
        }
        return get;
    }
    else {
        return {};
    }
})();
function parseTreeDataset(data, pk, parent, parentValue, first) {
    if (parentValue === void 0) { parentValue = 0; }
    if (first === void 0) { first = true; }
    var result = [];
    for (var i = 0; i < data.length; i++) {
        if (data[i][parent] == parentValue || (first && data[i][pk] == parentValue)) {
            result.push(data[i]);
            if (data[i][pk] != parentValue) {
                result = result.concat(parseTreeDataset(data, pk, parent, data[i][pk], false));
            }
        }
    }
    return result;
}
/**
 * param 将要转为URL参数字符串的对象 key URL参数字符串的前缀 encode true/false 是否进行URL编码,默认为true
 *
 * return URL参数字符串
 */
var urlEncode = function (param, key, encode) {
    if (key === void 0) { key = null; }
    if (encode === void 0) { encode = null; }
    if (param == null)
        return '';
    var paramStr = '';
    var t = typeof (param);
    if (t == 'string' || t == 'number' || t == 'boolean') {
        paramStr += '&'
            + key
            + '='
            + ((encode == null || encode) ? encodeURIComponent(param)
                : param);
    }
    else {
        for (var i in param) {
            var k = key == null ? i : key
                + (param instanceof Array ? '[' + i + ']' : '.' + i);
            paramStr += "&" + urlEncode(param[i], k, encode);
        }
    }
    return paramStr.substr(1);
};
function getFormJson(flag) {
    var form = $(flag);
    var data = {};
    var tData = form.serializeArray();
    for (var i = 0; i < tData.length; i++) {
        data[tData[i].name] = tData[i].value;
    }
    form.find('input[type=checkbox]').each(function (index, elem) {
        var e = $(elem);
        if (!e.is(':checked')) {
            var value = e.attr('un-checked-value');
            if (void 0 !== value) {
                data[e.attr('name')] = value;
            }
        }
    });
    return data;
}
function parseCallback(callback) {
    if (callback !== undefined) {
        if (typeof (callback) === 'object') {
            callback.callback.apply(null, callback.params);
        }
        else {
            callback();
        }
    }
}
/**
 * 解决 ie，火狐浏览器不兼容new Date(s)
 * @param strDate
 * 返回 date对象
 * add by zyf at 2015年11月5日
 */
function getDateForStringDate(strDate) {
    //切割年月日与时分秒称为数组
    var s = strDate.split(" ");
    var s1 = s[0].split("-");
    if (s.length > 1) {
        var s2 = s[1].split(":");
        if (s2.length == 2) {
            s2.push("00");
        }
    }
    return new Date(s1[0], s1[1] - 1, s1[2], s2 ? s2[0] : 0, s2 ? s2[1] : 0, s2 ? s2[2] : 0);
}
function isDateInvalid(date) {
    var d = getDateForStringDate(date);
    return d.toString() == 'Invalid Date' || (date.indexOf('1899') < 0 && d.toString().indexOf('1899') > -1);
}
function databindFormElement(data, formElement) {
    for (var key in data) {
        var element = formElement.find('[name=' + key + ']');
        if (element.length == 0) {
            continue;
        }
        var filter = element.attr('filter');
        if (void 0 === filter) {
            var filterValue = data[key];
        }
        else {
            var filterValue = window[filter](data[key]);
        }
        switch (element.get(0).tagName.toUpperCase()) {
            case 'SELECT':
                element.find('option[value=\'' + data[key] + '\']').prop('selected', true);
                break;
            case "INPUT":
                var type = element.attr('type').toUpperCase();
                switch (type) {
                    case 'CHECKBOX':
                        element.attr('checked', element.val() == data[key]);
                        break;
                    case 'DATETIME-LOCAL':
                        element.val(data[key].replace(' ', 'T'));
                        break;
                    case 'RADIO':
                        $('input[type=radio][name=' + key + '][value="' + data[key] + '"]').attr('checked', true);
                        break;
                    case 'NUMBER':
                    case 'TEXT':
                        element.val(filterValue);
                        break;
                    default:
                        var val = '';
                        if (element.attr('data-control') == 'datetimebox') {
                            if (!isDateInvalid(data[key])) {
                                val = data[key];
                            }
                        }
                        else {
                            val = data[key];
                        }
                        element.val(val);
                        break;
                }
                break;
            case "TEXTAREA":
                element.html(filterValue);
                break;
            default:
                element.html(data[key]);
                break;
        }
    }
}
var QueryOption = (function () {
    function QueryOption(option) {
        if (option === void 0) { option = null; }
        /**
         * 模版内容
         */
        this.template = '';
        /**
         * 内容元素
         */
        this.contentElement = null;
        /**
         * 分页条元素
         */
        this.pagebarElement = null;
        /**
         * 查询URL
         */
        this.queryUrl = '';
        /**
         * 查询接口方法，默认为POST
         */
        this.queryMethod = 'POST';
        /**
         * 是否绑定表单
         */
        this.isBindForm = false;
        /**
         * 绑定的表单
         */
        this.bindFormElement = null;
        /**
         * 当前页码
         */
        this.page = 1;
        /**
         * 总页数
         */
        this.pages = 1;
        /**
         * ajax返回数据
         */
        this.data = {};
        /**
         * 查询成功事件
         */
        this.onSuccess = null;
        /**
         * 处理渲染后事件
         */
        this.onRenderParsed = null;
        /**
         * 是否启用
         */
        this.enabled = true;
        if (option !== null) {
            for (var key in option) {
                this[key] = option[key];
            }
        }
        this.init();
    }
    QueryOption.prototype.init = function () {
        var _this = this;
        if (this.isBindForm) {
            this.bindFormElement.submit(function (e) {
                if (!_this.enabled) {
                    return;
                }
                _this.query();
                e.preventDefault();
            });
        }
        if (this.pagebarElement !== null) {
            this.page = 1;
        }
        this.query();
    };
    QueryOption.prototype.query = function () {
        var _this = this;
        var data = this.isBindForm ? getFormJson(this.bindFormElement) : {};
        if (this.pagebarElement !== null) {
            data.page = this.page;
        }
        $.ajax({
            type: this.queryMethod,
            url: this.queryUrl,
            data: data,
            success: function (data) {
                _this.data = data;
                if (data.success) {
                    layui.laytpl(_this.template).render(data, function (html) {
                        _this.contentElement.html(html);
                        if (_this.onRenderParsed !== null) {
                            _this.onRenderParsed(data);
                        }
                    });
                    if (_this.pagebarElement !== null) {
                        _this.page = data.curr_page;
                        _this.pages = data.pages;
                        layui.laypage({
                            cont: _this.pagebarElement,
                            pages: _this.pages,
                            curr: _this.page,
                            jump: function (obj, first) {
                                if (!first) {
                                    _this.page = obj.curr;
                                    _this.query();
                                }
                            }
                        });
                    }
                    if (_this.onSuccess !== null) {
                        _this.onSuccess(data);
                    }
                }
                else {
                    top.layer.alert(data.message, { icon: 2 });
                }
            },
            error: function (error) {
                top.layer.alert('服务器出现错误，请重试！', { icon: 2 });
            }
        });
    };
    return QueryOption;
}());
var maxLayers = {};
var PopupOption = (function () {
    function PopupOption(option) {
        if (option === void 0) { option = null; }
        /**
         * 弹出框容器，默认为self
         */
        this.popupContainer = self;
        /**
         * 弹出页面URL地址
         */
        this.url = '';
        /**
         * url参数
         */
        this.params = {};
        /**
         * 弹出窗口标题
         */
        this.title = '';
        /**
         * 弹出窗大小
         */
        this.size = ['550px', '480px'];
        /**
         * 是否最大化
         */
        this.isMax = false;
        /**
         * 弹出窗的序号
         */
        this.layerIndex = -1;
        /**
         * 加载成功事件
         */
        this.onLoad = null;
        /**
         * 不提交直接关闭弹窗事件
         */
        this.onClose = null;
        /**
         * 操作成功事件
         */
        this.onSuccess = null;
        if (option !== null) {
            for (var key in option) {
                this[key] = option[key];
            }
        }
        this.init();
    }
    PopupOption.prototype.init = function () {
        var _this = this;
        var params = urlEncode(this.params);
        var size = this.size;
        if ($(this.popupContainer).innerWidth() < 768) {
            size[0] = '100%';
            size[1] = '100%';
        }
        else {
            if (parseInt(size[0]) > $(this.popupContainer).innerWidth()) {
                size[0] = '100%';
            }
            if (parseInt(size[1]) > $(this.popupContainer).innerHeight()) {
                size[1] = '100%';
            }
        }
        this.layerIndex = this.popupContainer.layer.open({
            type: 2,
            title: this.title,
            area: size,
            shade: 0.2,
            content: this.url + (this.url.indexOf('?') >= 0 ? '&' : '?') + params,
            maxmin: this.isMax,
            full: function (e) {
                maxLayers[e.attr('times')] = e;
            },
            min: function (e) {
            },
            restore: function (e) {
                delete maxLayers[e.attr('times')];
                $(window).resize();
            },
            cancel: this.onClose,
            success: function (layero, index) {
                $(layero).ready(function () {
                    var win = layero.find('iframe')[0].contentWindow;
                    win.layerIndex = index;
                    win.onSuccess = _this.onSuccess;
                    if (_this.onLoad !== null) {
                        _this.onLoad(layero, index, win);
                    }
                });
            }
        });
        if (this.isMax) {
            this.popupContainer.layer.full(this.layerIndex);
            maxLayers[this.layerIndex] = $('#layui-layer-shade' + this.layerIndex);
        }
    };
    return PopupOption;
}());
var FindOption = (function () {
    function FindOption(option) {
        if (option === void 0) { option = null; }
        /**
         * 弹出页面URL地址
         */
        this.url = '';
        /**
         * url参数
         */
        this.params = {};
        /**
         * 查询接口方法，默认为POST
         */
        this.queryMethod = 'POST';
        /**
         * 表单元素
         */
        this.formElement = null;
        /**
         * 成功获取数据事件
         */
        this.onSuccess = null;
        /**
         * ajax返回数据
         */
        this.data = {};
        if (option !== null) {
            for (var key in option) {
                this[key] = option[key];
            }
        }
        this.init();
    }
    FindOption.prototype.init = function () {
        var _this = this;
        var url = this.url;
        var data = '';
        if (this.queryMethod === 'GET') {
            url += (this.url.indexOf('?') >= 0 ? '&' : '?') + urlEncode(this.params);
        }
        else {
            data = this.params;
        }
        $.ajax({
            type: this.queryMethod,
            url: url,
            data: data,
            success: function (data) {
                _this.data = data;
                if (data.success) {
                    databindFormElement(data.data, _this.formElement);
                    layui.form().render();
                    if (_this.onSuccess !== null) {
                        _this.onSuccess(data);
                    }
                }
                else {
                    top.layer.alert(data.message, { icon: 2 });
                }
            },
            error: function (error) {
                top.layer.alert('服务器出现错误，请重试！', { icon: 2 });
            }
        });
    };
    return FindOption;
}());
var ActionOption = (function () {
    function ActionOption(option) {
        if (option === void 0) { option = null; }
        /**
         * 操作接口URL地址
         */
        this.url = '';
        /**
         * url参数
         */
        this.params = {};
        /**
         * 查询接口方法，默认为POST
         */
        this.queryMethod = 'POST';
        /**
         * 操作前是否弹出询问
         */
        this.isConfirm = true;
        /**
         * 操作询问提示
         */
        this.confirmText = '是否执行该操作？';
        /**
         * 操作成功提示
         */
        this.successText = '操作成功！';
        /**
         * 确认操作后执行
         */
        this.onBefore = null;
        /**
         * 操作成功事件
         */
        this.onSuccess = null;
        if (option !== null) {
            for (var key in option) {
                this[key] = option[key];
            }
        }
        this.init();
    }
    ActionOption.prototype.init = function () {
        var _this = this;
        if (this.isConfirm) {
            top.layer.confirm(this.confirmText, {
                btn: ['确认', '取消'],
                icon: 3,
                title: '询问'
            }, function (index) {
                _this.action();
                top.layer.close(index);
            });
        }
        else {
            this.action();
        }
    };
    ActionOption.prototype.action = function () {
        var _this = this;
        if (this.onBefore !== null) {
            parseCallback({ callback: this.onBefore });
        }
        var url = this.url;
        var data = '';
        if (this.queryMethod === 'GET') {
            url += (this.url.indexOf('?') >= 0 ? '&' : '?') + urlEncode(this.params);
        }
        else {
            data = this.params;
        }
        $.ajax({
            type: this.queryMethod,
            url: url,
            data: data,
            success: function (data) {
                if (data.success) {
                    top.layer.msg(_this.successText, { time: 3000, icon: 6 });
                    if (_this.onSuccess !== null) {
                        parseCallback({ callback: _this.onSuccess, params: [data] });
                    }
                }
                else {
                    top.layer.alert(data.message, { icon: 2 });
                }
            },
            error: function (error) {
                top.layer.alert('服务器出现错误，请重试！', { icon: 2 });
            }
        });
    };
    return ActionOption;
}());
$(window).resize(function () {
    for (var v in maxLayers) {
        $('.layui-layer[times=' + maxLayers[v].attr('times') + ']').css('width', '100%').css('height', '100%').css('top', 0).css('left', 0);
    }
});
