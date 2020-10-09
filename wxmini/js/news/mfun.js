!
function(e) {
	"use strict";
	var t = document,
		n = "querySelectorAll",
		i = "getElementsByClassName",
		a = function(e) {
			return t[n](e)
		},
		r = {
			type: 0,
			shade: !0,
			shadeClose: !0,
			fixed: !0,
			anim: "scale"
		},
		o = {
			extend: function(e) {
				var t = JSON.parse(JSON.stringify(r));
				for (var n in e) t[n] = e[n];
				return t
			},
			timer: {},
			end: {}
		};
	o.touch = function(e, t) {
		e.addEventListener("click", function(e) {
			t.call(this, e)
		}, !1)
	};
	var s = 0,
		c = ["layui-m-layer"],
		l = function(e) {
			var t = this;
			t.config = o.extend(e), t.view()
		};
	l.prototype.view = function() {
		var e = this,
			n = e.config,
			r = t.createElement("div");
		e.id = r.id = c[0] + s, r.setAttribute("class", c[0] + " " + c[0] + (n.type || 0)), r.setAttribute("index", s);
		var o = function() {
				var e = "object" == typeof n.title;
				return n.title ? '<h3 style="' + (e ? n.title[1] : "") + '">' + (e ? n.title[0] : n.title) + "</h3>" : ""
			}(),
			l = function() {
				"string" == typeof n.btn && (n.btn = [n.btn]);
				var e, t = (n.btn || []).length;
				return 0 !== t && n.btn ? (e = '<span yes type="1">' + n.btn[0] + "</span>", 2 === t && (e = '<span no type="0">' + n.btn[1] + "</span>" + e), '<div class="layui-m-layerbtn">' + e + "</div>") : ""
			}();
		if (n.fixed || (n.top = n.hasOwnProperty("top") ? n.top : 100, n.style = n.style || "", n.style += " top:" + (t.body.scrollTop + n.top) + "px"), 2 === n.type && (n.content = '<i></i><i class="layui-m-layerload"></i><i></i><p>' + (n.content || "") + "</p>"), n.skin && (n.anim = "up"), "msg" === n.skin && (n.shade = !1), r.innerHTML = (n.shade ? "<div " + ("string" == typeof n.shade ? 'style="' + n.shade + '"' : "") + ' class="layui-m-layershade"></div>' : "") + '<div class="layui-m-layermain" ' + (n.fixed ? "" : 'style="position:static;"') + '><div class="layui-m-layersection"><div class="layui-m-layerchild ' + (n.skin ? "layui-m-layer-" + n.skin + " " : "") + (n.className ? n.className : "") + " " + (n.anim ? "layui-m-anim-" + n.anim : "") + '" ' + (n.style ? 'style="' + n.style + '"' : "") + ">" + o + '<div class="layui-m-layercont">' + n.content + "</div>" + l + "</div></div></div>", !n.type || 2 === n.type) {
			var u = t[i](c[0] + n.type),
				d = u.length;
			d >= 1 && layer.close(u[0].getAttribute("index"))
		}
		document.body.appendChild(r);
		var p = e.elem = a("#" + e.id)[0];
		n.success && n.success(p), e.index = s++, e.action(n, p)
	}, l.prototype.action = function(e, t) {
		var n = this;
		e.time && (o.timer[n.index] = setTimeout(function() {
			layer.close(n.index)
		}, 1e3 * e.time));
		var a = function() {
				var t = this.getAttribute("type");
				0 == t ? (e.no && e.no(), layer.close(n.index)) : e.yes ? e.yes(n.index) : layer.close(n.index)
			};
		if (e.btn) for (var r = t[i]("layui-m-layerbtn")[0].children, s = r.length, c = 0; s > c; c++) o.touch(r[c], a);
		if (e.shade && e.shadeClose) {
			var l = t[i]("layui-m-layershade")[0];
			o.touch(l, function() {
				layer.close(n.index, e.end)
			})
		}
		e.end && (o.end[n.index] = e.end)
	}, e.layer = {
		v: "2.0",
		index: s,
		open: function(e) {
			var t = new l(e || {});
			return t.index
		},
		close: function(e) {
			var n = a("#" + c[0] + e)[0];
			n && (n.innerHTML = "", t.body.removeChild(n), clearTimeout(o.timer[e]), delete o.timer[e], "function" == typeof o.end[e] && o.end[e](), delete o.end[e])
		},
		closeAll: function() {
			for (var e = t[i](c[0]), n = 0, a = e.length; a > n; n++) layer.close(0 | e[0].getAttribute("index"))
		}
	}, "function" == typeof define ? define(function() {
		return layer
	}) : function() {
		var e = document.scripts,
			t = e[e.length - 1],
			n = t.src;
		n.substring(0, n.lastIndexOf("/") + 1);
		t.getAttribute("merge")
	}()
}(window), String.prototype.getParams = function(e) {
	for (var t, n = {}, i = this.split("&"), a = i.length, r = 0; r < a; r++) i[r] && (t = i[r].split("="), n[t[0]] = t[1]);
	return e ? n[e] ? n[e] : "" : n
}, String.prototype.htmlEncode = function() {
	var e = this,
		t = "";
	return 0 == e.length ? "" : (t = e.replace(/</g, "&lt;"), t = t.replace(/>/g, "&gt;"), t = t.replace(/ /g, "&nbsp;"), t = t.replace(/\'/g, "&#39;"), t = t.replace(/\"/g, "&quot;"), t = t.replace(/(&lt;)br(&gt;)/gi, function() {
		if (arguments.length > 1) return "<br>"
	}), t = t.replace(/&/g, "&amp;"))
}, String.prototype.htmlDecode = function() {
	var e = this,
		t = "";
	return 0 == e.length ? "" : (t = e.replace(/&amp;/g, "&"), t = t.replace(/&lt;/g, "<"), t = t.replace(/&gt;/g, ">"), t = t.replace(/&nbsp;/g, " "), t = t.replace(/&#39;/g, "'"), t = t.replace(/&quot;/g, '"'))
}, Array.prototype.unique = function() {
	for (var e, t, n = this, i = n.concat(), a = {
		obj: "[object Object]",
		fun: "[object Function]",
		arr: "[object Array]",
		num: "[object Number]"
	}, r = /(\u3000|\s|\t)*(\n)+(\u3000|\s|\t)*/gi, o = i.length; o--;) t = i[o], e = Object.prototype.toString.call(t), e == a.num && "NaN" == t.toString() && (i[o] = t.toString()), e == a.obj && (i[o] = JSON.stringify(t)), e == a.fun && (i[o] = t.toString().replace(r, ""));
	for (var o = i.length; o--;) t = i[o], e = Object.prototype.toString.call(t), e == a.arr && t.unique(), i.indexOf(t) != i.lastIndexOf(t) ? (n.splice(o, 1), i.splice(o, 1)) : Object.prototype.toString.call(n[o]) != e && (i[o] = n[o]);
	return i
}, Date.prototype.format = function(e, t, n) {
	var i = e,
		a = ["日", "一", "二", "三", "四", "五", "六"];
	if (i = i.replace(/yyyy|YYYY/, this.getFullYear()), i = i.replace(/yy|YY/, this.getYear() % 100 > 9 ? (this.getYear() % 100).toString() : "0" + this.getYear() % 100), i = i.replace(/MM/, this.getMonth() + 1 > 9 ? (this.getMonth() + 1).toString() : "0" + (this.getMonth() + 1)), i = i.replace(/M/g, this.getMonth() + 1), i = i.replace(/w|W/g, a[this.getDay()]), i = i.replace(/dd|DD/, this.getDate() > 9 ? this.getDate().toString() : "0" + this.getDate()), i = i.replace(/d|D/g, this.getDate()), i = i.replace(/hh|HH/, this.getHours() > 9 ? this.getHours().toString() : "0" + this.getHours()), i = i.replace(/h|H/g, this.getHours()), i = i.replace(/mm/, this.getMinutes() > 9 ? this.getMinutes().toString() : "0" + this.getMinutes()), i = i.replace(/m/g, this.getMinutes()), i = i.replace(/ss|SS/, this.getSeconds() > 9 ? this.getSeconds().toString() : "0" + this.getSeconds()), i = i.replace(/s|S/g, this.getSeconds()), "pretty" == t) {
		var r = n || new Date,
			o = (r - this) / 1e3,
			s = e.indexOf("h") == -1 ? e.indexOf("H") : e.indexOf("h");
		r.getDate() == this.getDate() && r.getMonth() == this.getMonth() ? (i = "今天", s > -1 && (o < 60 && o > 0 ? i = "刚刚" : o >= 60 && o < 3600 ? i = parseInt(o / 60) + "分钟前" : o >= 3600 && parseInt(o / 3600) <= r.getHours() ? i = parseInt(o / 3600) + "小时前" : -o >= 60 && -o < 3600 ? i = -parseInt(o / 60) + "分钟后" : -o >= 3600 && -parseInt(o / 3600) <= 24 - r.getHours() && (i = -parseInt(o / 3600) + "小时后"))) : r.getDate() - this.getDate() == 1 && r.getMonth() == this.getMonth() ? (i = "昨天", s > -1 && (i += this.format(e.substr(s)))) : r.getDate() - this.getDate() == 2 && r.getMonth() == this.getMonth() ? (i = "前天", s > -1 && (i += this.format(e.substr(s)))) : r.getDate() - this.getDate() == -1 && r.getMonth() == this.getMonth() ? (i = "明天", s > -1 && (i += this.format(e.substr(s)))) : r.getDate() - this.getDate() == -2 && r.getMonth() == this.getMonth() ? (i = "后天", s > -1 && (i += this.format(e.substr(s)))) : this.getFullYear() == r.getFullYear() ? e.indexOf("M") > -1 && (e = e.substr(e.indexOf("M")), i = this.format(e)) : this.getFullYear() - r.getFullYear() == 1 ? (i = "明年", e.indexOf("M") > -1 && (e = e.substr(e.indexOf("M")), 11 == r.getMonth() ? i = this.format(e) : i += this.format(e))) : this.getFullYear() - r.getFullYear() == -1 && (i = "去年", e.indexOf("M") > -1 && (e = e.substr(e.indexOf("M")), i += this.format(e)))
	}
	return i
}, Date.prototype.addDays = function(e) {
	var t = new Date(this.setDate(this.getDate() + e)),
		n = new Date(t.format("yyyy/MM/dd"));
	return this.setDate(t.getDate() - e), n
};
var Url = {
		href: function() {
			return window.location.href
		},
		params: function(e) {
			return window.location.search.replace(/^\?/, "").getParams(e)
		},
		hash: function(e) {
			return window.location.hash.indexOf("=") < 0 ? window.location.hash.replace("#", "") : window.location.hash.replace(/^\#/, "").getParams(e)
		},
		hashChange: function(e) {
			"function" == typeof e && (Url.hash() && e.call(this, Url.hash()), window.addEventListener("hashchange", function() {
				e.call(this, Url.hash())
			}))
		}
	},
	Validate = {
		mobile: function(e) {
			return /^1[3|4|5|7|8][0-9]\d{8}$/.test(e)
		},
		email: function(e) {
			return /^([a-zA-Z0-9]+[_|\_|-|\-|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/.test(e)
		},
		number: function(e) {
			return /^[0-9]*$/.test(e)
		}
	},
	isEmptyObject = function(e) {
		var t;
		for (t in e) return !1;
		return !0
	},
	extendObject = function() {
		var e, t = arguments,
			n = t.length,
			i = t[0],
			a = t[1],
			r = !(!t[n - 1] || "boolean" != typeof t[n - 1]) && t[n - 1],
			o = {},
			s = function(e) {
				return "[object Array]" === Object.prototype.toString.call(e)
			};
		if (0 == n || "object" != typeof t[0] || isEmptyObject(a) || s(t[0])) return i;
		for (e in i) if (i.hasOwnProperty(e)) if (r) for (var c in a) a.hasOwnProperty(c) && e == c && (i[e] = a[c]);
		else {
			o[e] = i[e];
			for (var l in a) a.hasOwnProperty(l) && (e == l ? o[e] = a[l] : o[l] = a[l])
		}
		return r ? i : o
	},
	Jsonp = function(e, t, n) {
		var i = (new Date).getTime(),
			a = null,
			r = function(e, t) {
				var n = document.getElementsByTagName("head")[0],
					i = document.createElement("script");
				i.id = t, i.src = e, i.charset = "utf-8", n.appendChild(i)
			},
			o = function(e) {
				return a = "jsonp" + i++, window[a] = function(t) {
					e(t), window[a] = void 0;
					try {
						delete window[a]
					} catch (e) {}
				}, a
			};
		t.callback = o(n), e += e.indexOf("?") > 0 ? "" : "?";
		for (var s in t) e += "&" + s + "=" + t[s];
		r(e, a)
	},
	Ajax = function(e) {
		var t = {
			type: "get",
			url: "",
			data: {},
			async: !0,
			timeout: 2e4,
			dataType: "json",
			success: null,
			error: null,
			ontimeout: function() {}
		};
		if (extendObject(t, e, !0), "jsonp" == t.dataType.toLowerCase()) return void Jsonp(t.url, t.data, t.success);
		var n = new XMLHttpRequest,
			i = new FormData;
		if ("get" == t.type.toLowerCase()) {
			i = null;
			for (var a in t.data) {
				var r = "&";
				t.url.indexOf("?") < 0 && (r = "?"), t.url += r + a + "=" + t.data[a]
			}
		} else for (var a in t.data) i.append(a, t.data[a]);
		n.open(t.type, t.url, t.async), n.timeout = t.timeout, n.ontimeout = t.ontimeout, n.onload = function() {
			if (4 == n.readyState && (n.status >= 200 && n.status < 300 || 304 == n.status)) {
				var e = n.responseText;
				if ("json" == t.dataType.toLowerCase() && e) try {
					e = JSON.parse(n.responseText)
				} catch (e) {
					window.console && console.log("返回JSON格式错误")
				}
				"function" == typeof t.success && t.success.call(this, e)
			} else "function" == typeof t.error && t.error.call(this, n.statusText)
		}, n.send(i)
	},
	mark = function() {
		var e = document.querySelectorAll(".bg-mark-mask"),
			t = e.length,
			n = null;
		return "hide" == arguments[0] ? void(t > 0 && e[t - 1].parentNode.removeChild(e[t - 1])) : (n = document.createElement("div"), n.className = "bg-mark-mask", n.style.cssText = "position:fixed;width:100%;height:100%;background:rgba(0,0,0,.3);z-index:99;top:0;left:0;", "number" == typeof arguments[0] && (n.style.zIndex = arguments[0]), void document.body.appendChild(n))
	}