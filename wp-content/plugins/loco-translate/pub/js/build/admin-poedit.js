(function(z, A, e, M) {
    function U() {
        var d = B.stats(),
            c = d.t,
            n = d.f,
            h = d.u,
            c = K(D("1 string", "%s strings", c), c),
            a = [];
        Q && (c = K(D("%s%% translated"), d.p.replace("%", "")) + ", " + c, n && a.push(K(D("%s fuzzy"), n)), h && a.push(K(D("%s untranslated"), h)), a.length && (c += " (" + a.join(", ") + ")"));
        e("#loco-po-status").text(c)
    }

    function X(d, c, n) {
        function h(a, c, b) {
            b = b || D("Unknown error");
            Y(b);
            n && n(a, c, b);
            var k = e(d).find('input[name="action"]').val();
            I.debugError("Ajax failure for " + k + " action.", {
                status: a.status,
                error: c,
                message: b,
                response: a.responseText
            })
        }
        return e.ajax({
            url: Z,
            type: d.method,
            data: e(d).serialize(),
            dataType: "json",
            error: h,
            success: function(a, f, b) {
                !a || a.error ? h(b, f, a && a.error && a.error.message) : c && c(a, f, b)
            }
        })
    }
    var p = function() {
        var d = {};
        return {
            register: function(c, e) {
                d[c] = e
            },
            require: function(c, e) {
                var h = d[c];
                if (!h) throw Error('CommonJS error: failed to require("' + e + '")');
                return h
            }
        }
    }();
    p.register("$1", function(d, c, e) {
        Array.prototype.indexOf || (Array.prototype.indexOf = function(c) {
            if (null == this) throw new TypeError;
            var a,
                f = Object(this),
                b = f.length >>> 0;
            if (0 === b) return -1;
            a = 0;
            1 < arguments.length && (a = Number(arguments[1]), a != a ? a = 0 : 0 != a && Infinity != a && -Infinity != a && (a = (0 < a || -1) * Math.floor(Math.abs(a))));
            if (a >= b) return -1;
            for (a = 0 <= a ? a : Math.max(b - Math.abs(a), 0); a < b; a++)
                if (a in f && f[a] === c) return a;
            return -1
        });
        return d
    }({}, z, A));
    p.register("$22", function(d, c, e) {
        function h(k) {
            b || c._gat && (b = _gat._createTracker(a, "loco"));
            if (b) {
                var m = k.shift();
                b[m].apply(b, k)
            } else f && f.push(k);
            return d
        }
        var a, f, b;
        d._init = function(b) {
            if (a = b.code) {
                f =
                    c._gaq || (c._gaq = []);
                f.push(["_setAccount", a]);
                f.push(["_trackPageview"]);
                f.push(["_setDomainName", b.host]);
                b = e.createElement("script");
                b.type = "text/javascript";
                b.async = !0;
                b.src = ("https:" == e.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";
                var m = e.getElementsByTagName("script")[0];
                m.parentNode.insertBefore(b, m)
            }
            return d
        };
        d.event = function(b, a, c, f) {
            return h(["_trackEvent", b || "", a || "", c || "", f || 0])
        };
        d.page = function(b, a) {
            return h(["_trackPageview", {
                page: b || location.pathname + location.hash,
                title: a || e.title
            }])
        };
        return d
    }({}, z, A));
    p.register("$23", function(d, c, e) {
        function h(b, k) {
            if (a) a[b](k);
            else c.ga && ga(b, k);
            return d
        }
        var a, f;
        d._init = function(b) {
            b.code && (function(b, a, c, f, g, u, d) {
                    b.GoogleAnalyticsObject = g;
                    b[g] = b[g] || function() {
                        (b[g].q = b[g].q || []).push(arguments)
                    };
                    b[g].l = 1 * new Date;
                    u = a.createElement(c);
                    d = a.getElementsByTagName(c)[0];
                    u.async = 1;
                    u.src = f;
                    d.parentNode.insertBefore(u, d)
                }(c, e, "script", "//www.google-analytics.com/analytics.js", "ga"), ga("create", b.code, {
                    alwaysSendReferrer: !0,
                    userId: b.user
                }),
                b.custom && h("set", b.custom), d.page(), ga(function(b) {
                    a = b
                }));
            return d
        };
        d.event = function(b, a, c, f) {
            return h("send", {
                hitType: "event",
                eventCategory: b || "",
                eventAction: a || "",
                eventLabel: c || "",
                eventValue: Number(f || 0)
            })
        };
        d.page = function(b, a) {
            var c = {
                hitType: "pageview",
                page: b || location.pathname + location.hash,
                title: a || e.title
            };
            c.location = location.protocol + "//" + location.hostname + c.page;
            f && h("set", {
                referrer: f
            });
            f = c.location;
            return h("send", c)
        };
        d.reset = function() {
            f = location.href;
            h("set", {
                page: location.pathname + location.hash,
                title: e.title,
                location: f
            });
            return d
        };
        return d
    }({}, z, A));
    p.register("$10", function(d, c, n) {
        function h(b, k) {
            e(b).click(function(b) {
                a && a.event(k, "click", this.getAttribute("href") || "");
                return !0
            });
            b = null;
            return d
        }
        var a, f = location.hostname;
        d.init = function(b) {
            !a && b && (f = b.host || (b.host = f), a = b.legacy ? p.require("$22", "legacy.js") : p.require("$23", "universal.js"), a._init(b));
            return d
        };
        d.link = function(b) {
            for (var a = b.getAttribute("href"); a && "#" !== a;) {
                if (0 === a.indexOf("#")) return h(b, "anchor");
                if (0 === a.indexOf("http") ||
                    0 === a.indexOf("//")) {
                    if (-1 !== a.indexOf(f) && /^(https?:)*\/\/([^\/]+)/.exec(a) && f === RegExp.$2) break;
                    b.setAttribute("target", "_blank");
                    h(b, "external")
                }
                break
            }
            return d
        };
        d.page = function() {
            a && a.page.apply(a, arguments);
            return d
        };
        d.event = function() {
            a && a.event.apply(a, arguments);
            return d
        };
        d.reset = function() {
            a && a.reset && a.reset();
            return d
        };
        return d
    }({}, z, A));
    p.register("$29", function(d, c, n) {
        function h(b, a, c, l, d) {
            function g() {
                w && clearTimeout(w);
                v && v.fadeOut(400, function() {
                    e(this).remove();
                    v = null
                });
                return !1
            }

            function u() {
                y(); - 1 !== l && (w = setTimeout(g, l || 2E3));
                v.off("mouseleave").on("mouseenter", y)
            }

            function y() {
                w && clearTimeout(w);
                w = null;
                v.off("mouseenter").on("mouseleave", u)
            }
            var w;
            f || (f = n.createElement("div"), f.id = "growls", n.body.appendChild(f));
            var v = e('<div class="growl growl-' + c + '"><div><a class="close" href="#"><span>X</span></a><span class="badge"></span><p class="message"></p><small class="caption"></small></div></div>');
            v.find("p").text(b || "Empty message");
            a ? v.find("small").text(a) : v.find("small").remove();
            if (d.length) {
                d.push({
                    label: "Cancel",
                    callback: g,
                    css: "cancel"
                });
                var r, F = e('<form action="#" class="dialog"></form>');
                a = function(b, a) {
                    r = e('<input type="button" value="' + a.label + '" class="butt ' + (a.css || "") + '" />');
                    r.click(function(b) {
                        "function" === typeof a.callback && a.callback(b, {
                            close: g
                        })
                    });
                    F.append(r);
                    return r
                };
                for (b = 0; b < d.length; b++) a(b, d[b]);
                v.append(F)
            }
            e(f).prepend(v.hide().fadeIn(400));
            v.find("a").click(g);
            u()
        }
        var a, f;
        d.init = function() {
            if (!a) return a = c.alert, c.alert = function(b) {
                b = String(b).split("\n");
                var a = b[1] && b.slice(1).join("\n");
                d.alert(b[0], a)
            }, d
        };
        d.debug = function(b) {
            a(b);
            return d
        };
        d.alert = function(b, k, f, l, d) {
            try {
                return h(b, k || "", f || "alert", l || 4E3, d || []), !0
            } catch (g) {
                return b += "\n\n--\n" + (g.message || g), a.call(c, b), !1
            }
        };
        d.success = function(b, a, c) {
            return d.alert(b, a, "success", c || 2E3)
        };
        d.dialog = function(b, a, c, f) {
            return d.alert(b, a, f || "alert", -1, c)
        };
        d.login = function(b, a, c, f) {
            d.dialog(a || "You're not logged in", c || "Please log in to continue", [{
                label: f || "Log in",
                callback: function(a, k) {
                    location.assign(b || "/session/auth/login?r=" +
                        encodeURIComponent(location.href))
                }
            }])
        };
        return d
    }({}, z, A));
    p.register("$25", function(d, c, n) {
        function h(b) {
            return 27 === b.keyCode && u && y ? (C(), b.preventDefault(), !1) : !0
        }

        function a(a) {
            if (u) {
                var g = Math.max(b.height(), k.outerHeight(!0));
                g && l.css("height", g + "px");
                a && (E = e(c).innerWidth(), G(s))
            }
            return !0
        }

        function f(b) {
            b ? (t.show(), m.addClass("has-title")) : (t.hide(), m.removeClass("has-title"))
        }
        var b, k, m, l, t, g, u = !1,
            y = !1,
            w = !1,
            v, r, F, E, s, q = d.init = function() {
                if (!b) {
                    b = e('<div id="overlay"></div>');
                    k = e('<div class="overlay-frame"></div>');
                    m = e('<div class="overlay-container"></div>');
                    t = e('<div class="overlay-title"><span class="title">Untitled</span></div>');
                    g = e('<a class="overlay-close" href="#"><span>x</span></a>');
                    l = e('<div class="overlay-bg"></div>');
                    b.append(k.append(m)).append(l).prependTo(n.body);
                    e(n).on("keydown", h);
                    e(c).resize(a);
                    F = m.outerWidth(!0) - m.width() + (k.innerWidth() - k.width());
                    m.outerHeight(!0);
                    m.outerHeight(!1);
                    k.innerHeight();
                    k.height();
                    E = e(c).innerWidth();
                    v = parseInt(m.css("width"));
                    if (!v || isNaN(v)) v = m.width();
                    r =
                        parseInt(m.css("height"));
                    if (!r || isNaN(r)) r = m.height();
                    t.append(g.hide()).hide().prependTo(k);
                    b.hide()
                }
                return b
            },
            G = d.width = function(a) {
                q();
                if (null === a) k.css("width", ""), m.css("width", "");
                else {
                    a = a || v || 640;
                    x = a + F;
                    s = a;
                    var g = E;
                    x > g ? (x = g, a = x - F, b.addClass("spill")) : b.removeClass("spill");
                    k.css("width", x + "px");
                    m.css("width", a + "px")
                }
                return d
            };
        d.autoSize = function() {
            q();
            a();
            var b = v || 0;
            m.children().each(function(a, g) {
                b = Math.max(b, e(g).outerWidth(!0))
            });
            G(b);
            return d
        };
        d.css = function(b) {
            q().attr("class", b);
            return d
        };
        d.html = function(b) {
            q();
            c.innerShiv && (b = innerShiv(b, !1));
            return m.html(b)
        };
        d.append = function(b) {
            q();
            b instanceof jQuery || (b = e(b));
            m.append(b);
            return d
        };
        var C = d.close = function(a) {
            if (u) {
                var g = function() {
                    q().hide();
                    e(n.body).removeClass("has-overlay");
                    u = !1;
                    m.html("");
                    u = null;
                    b.trigger("overlayClosed", [d])
                };
                null == a && (a = 300);
                b.trigger("overlayClosing", [d]);
                a ? b.fadeOut(a, g) : g()
            }
            return d
        };
        d.title = function(b) {
            q();
            w = b || "";
            t.find("span.title").text(w);
            null != b ? f(!0) : y || f(!1);
            return d
        };
        d.enableClose = function() {
            q();
            y = !0;
            g.off("click").on("click", function(b) {
                C();
                return !1
            });
            f(!0);
            g.show();
            return d
        };
        d.disableClose = function() {
            q();
            y = !1;
            g.hide();
            u && w || f(!1);
            return d
        };
        d.open = function() {
            q();
            m.html("");
            G(v);
            b.attr("class", "");
            e(n.body).addClass("has-overlay");
            q().show();
            u = !0;
            a();
            d.title(null);
            y && f(!0);
            b.trigger("overlayOpened", [d]);
            return d
        };
        d.listen = function(b) {
            q().on("overlayClosed", b);
            return d
        };
        d.unlisten = function(b) {
            q().off("overlayClosed", b);
            return d
        };
        return d
    }({}, z, A));
    p.register("$24", function(d, c, n) {
        var h = {
            401: "You've been logged out",
            422: "Invalid data sent to server",
            404: "Not Found",
            500: "Server Error",
            502: "Bad Gateway",
            503: "Service unavailable",
            504: "Gateway timeout"
        };
        d.getErrors = function() {
            return h
        };
        d.jsonLink = function(a) {
            if (!a) return "";
            a = a.split("?");
            a[0] = a[0].replace(/(\.[a-z0-9]{1,4})?$/i, ".json");
            return a.join("?")
        };
        d.errorData = function(a, c, b) {
            var k, m;
            b = a.responseText;
            c = a.status;
            if (!b && 0 === c) return null;
            try {
                k = e.parseJSON(b) || {}
            } catch (l) {
                k = {}, m = h[a.status] || l.message || l
            }
            m || (m = k.statusText || a.statusText || h[c] || "Unknown Error");
            k.error = m;
            return k
        };
        d.ajax = function(a, f, b, k) {
            function m() {
                b && b()
            }

            function l(b) {
                b.alert && alert(b.alert);
                var a = b.success;
                a && p.require("$29", "growl.js").success.apply(this, a.push ? a : [a]);
                a = k || e(n.body);
                a.trigger("locoAjaxSuccess", [b]);
                var f = b.events;
                if (f && f.length)
                    for (var l, v = p.require("$10", "ga.js"); l = f.shift();) a.trigger(l, [b]), v.event("ajax", l);
                if (a = b.download) c.location.assign(a);
                else if (a = b.redirect)
                    if (0 === a.indexOf("/modal/")) b.modal = {
                        url: a
                    };
                    else return c.location.assign(a), !1;
                else if (b.reload) return c.location.reload(), !1;
                (a = b.modal) && p.require("$12", "modal.js").replace(a);
                m();
                return !0
            }

            function t(b, a, k) {
                if ("abort" !== a) {
                    var e = b.status,
                        v;
                    if (401 === e) p.require("$29", "growl.js").login(), f && !1 === f(null, h[e], e, b) && m();
                    else {
                        if ("parsererror" === a) c.console && console.error && console.error(e, b.responseText), v = 404 === e ? "Ajax service not found" : /^\s+Fatal error/.test(b.responseText) ? "Fatal server error from Ajax request" : "Bad Ajax response";
                        else {
                            var r = d.errorData(b, a, k);
                            r && r.error && (v = r.error)
                        }
                        "function" === typeof f && !1 === f(null,
                            v, e, b) ? m() : r && r.data && !l(r.data) || (alert(v || "Unknown Ajax error"), m())
                    }
                }
            }
            a.error = t;
            a.success = function(b, a, k) {
                if (!b || "object" !== typeof b) return t(k, "unknown");
                b.status && alert(b.statusText || "Unknown error");
                "function" === typeof f && !1 === f(b && b.data ? b.data : b, null, a, k) ? m() : b && b.data ? l(b.data) : m()
            }; - 1 !== a.url.indexOf(".json") && (a.dataType = "json");
            return e.ajax(a)
        };
        return d
    }({}, z, A));
    p.register("$12", function(d, c, n) {
        function h(b) {
            b.stopPropagation();
            b.preventDefault();
            return !1
        }

        function a() {
            m || (m = p.require("$25",
                "overlay.js"), m.listen(b));
            return m
        }

        function f(b, k) {
            a().autoSize();
            var c = m.init();
            p.require("$2", "html.js").init(c);
            c.find("[data-script]").each(function(b, a) {
                a = e(a);
                for (var c = -1, g, f = a.attr("data-script").split(" "); ++c < f.length;) g = f[c], t[g] ? t[g](a, k || {}) : alert("Unknown script " + g)
            });
            c.trigger("locoModalLoaded", [m, b || "", k || {}]);
            var g, f = c.find("form")[0],
                q;
            if (f) a: for (c = 0; c < f.elements.length; c++) switch (g = f.elements[c], g.type) {
                case "text":
                case "email":
                case "textarea":
                    q = Number(g.getAttribute("tabindex"));
                    if (isNaN(q) || 100 > q) continue a;
                    e(g).focus();
                    break a
            }
        }

        function b() {
            u = null;
            g = [];
            return !0
        }

        function k(b) {
            var a = e(b.currentTarget),
                k;
            k = a.attr("data-modal");
            if ("back" === k) {
                if (k = g.pop()) return u = null, y.apply(this, k), h(b);
                k = "close"
            }
            if ("close" === k) {
                w();
                var c = a.attr("href");
                if (c && -1 !== c.indexOf("#!")) return !0
            } else {
                var f = "submit" === b.type,
                    q = a.attr("title") || a.attr("data-title"),
                    c = a.attr("href") || a.attr("action"),
                    m = f ? a.serialize() : "",
                    a = f ? a.attr("method") : "get";
                k || (k = c.split("/").slice(1, 4).join("-"));
                y(c, q, a,
                    m, "modal " + k)
            }
            return h(b)
        }
        var m, l = p.require("$10", "ga.js"),
            t = {},
            g = [],
            u, y = d.load = function(b, k, c, t, w) {
                var q = u;
                u = arguments;
                g.length && b === g[g.length - 1][0] && (g.pop(), q = g[g.length - 1]);
                q && (g.push(q), b += -1 === b.indexOf("?") ? "?" : "&", b += "r=" + encodeURIComponent(q[0]));
                a().open().title("Loading ..").disableClose().css("modal").html('<div class="loading"></div>');
                w && m.width(null).css(w).autoSize();
                var q = p.require("$24", "http.js"),
                    h = {
                        type: c || "get",
                        data: t || "",
                        url: q.jsonLink(b)
                    };
                q.ajax(h, function(a, g, q) {
                    var d = a &&
                        a.html;
                    if (!d) return a && a.redirect ? y(a.redirect, k, c, t, w) : (a = e('<h3 class="error"></h3>').text(g || "Unknown error"), m.enableClose().title("Error " + q || "?").html("").append(a)), !1;
                    k = a.title || k || "Untitled";
                    m.enableClose().title(k).html(d);
                    f(b, a.js);
                    l.page(b, k);
                    m.init().one("overlayClosed", function() {
                        l.reset()
                    });
                    return !0
                });
                return d
            },
            w = d.close = function() {
                a().close();
                return d
            };
        d.initLink = function(b) {
            b.click(k)
        };
        d.initForm = function(b) {
            e(b).submit(k)
        };
        d.replace = function(b) {
            a();
            var k = b && b.html,
                c = b && b.url,
                g = b &&
                b.title,
                l = b && b.action;
            c ? (y(c, g), b = b && b.css || c.split("/").slice(1, 4).join("-"), m.width(null).css(" modal " + b).autoSize()) : k ? (m.open().html(k), g && m.enableClose().title(g), f("", b && b.js)) : "close" === l && w()
        };
        d.find = function(b) {
            return a().init().find(b)
        };
        d.script = function(b, a) {
            if (a) {
                if ("function" !== typeof a.run) throw Error(b + " macro has no run function");
                t[b] = a.run;
                return d
            }
            return t[b]
        };
        return d
    }({}, z, A));
    p.register("$7", function(d, c, n) {
        d.listen = function(d, a) {
            function f() {
                w[l ? "show" : "hide"]()
            }

            function b(b) {
                y &&
                    d.setAttribute("size", b.length || 1);
                l = b;
                f();
                return b
            }

            function k() {
                t = null;
                a(l)
            }

            function m() {
                var a = d.value;
                u && a === u && (a = "");
                a !== l && (t && clearTimeout(t), b(a), g ? t = setTimeout(k, g) : k())
            }
            var l, t;
            d = d instanceof jQuery ? d[0] : d;
            var g = 150,
                u = c.attachEvent && d.getAttribute("placeholder"),
                y = 1 === Number(d.size),
                w = e('<a href="#clear" tabindex="-1" class="icon clear"><span>clear</span></a>').click(function(b) {
                    d.value = "";
                    m();
                    return !1
                });
            b(d.value);
            e(d).on("input paste blur focus", function() {
                m();
                return !0
            }).after(w);
            f();
            return {
                delay: function(b) {
                    g =
                        b
                },
                ping: function(a) {
                    a ? (t && clearTimeout(t), a = d.value, u && a === u && (a = ""), b(a), k(), a = void 0) : a = m();
                    return a
                },
                val: function(a) {
                    if (null == a) return l;
                    t && clearTimeout(t);
                    d.value = b(a);
                    f()
                },
                el: function() {
                    return d
                },
                blur: function(b) {
                    return e(d).on("blur", b)
                }
            }
        };
        return d
    }({}, z, A));
    p.register("$5", function(d, c, e) {
        function h() {
            var a, c;
            this.clear = function() {
                this.length = 0;
                a = {};
                c = []
            };
            this.getTree = function() {
                return a
            };
            this.getData = function() {
                return c
            };
            this.clear()
        }
        d.create = function() {
            return new h
        };
        c = h.prototype;
        c.depth =
            0;
        c.matchall = !0;
        c.ignorecase = !0;
        c.boundary = /[\s.?!;:,*^+=~`"(){}<>[\]\/\\\u00a0\u1680\u180e\u2000-\u206f\u2e00-\u2e7f\u3000-\u303f]+/;
        c.nonword = /[\-'_]+/g;
        c.translit = function(a, c) {
            function b(b) {
                return a[b] || b
            }
            c = c || /[^a-z0-9]/g;
            this.trans = function(a) {
                return a.replace(c, b)
            }
        };
        c.stoppers = function(a) {
            this.stopped = function(c) {
                return Boolean(a[c])
            }
        };
        c.add = function(a, c) {
            var b = this.getData(),
                k = b.length;
            b.push(a);
            this.length++;
            for (var b = 0, d = arguments.length; ++b < d;) {
                c = arguments[b];
                for (var l = -1, e, g, u, y, w, v = this.normalize(c),
                        r = v.length; ++l < r;)
                    if (e = v[l], !this.stopped(e)) {
                        g = this.getTree();
                        u = Math.min(e.length, this.depth) || e.length;
                        for (y = 0; y < u; y++) w = e.charAt(y), g = g[w] || (g[w] = {});
                        e = g[" "] || (g[" "] = []);
                        e.push(k)
                    }
            }
            return this
        };
        c.find = function(a, c) {
            function b(a, c) {
                var k, g, d;
                for (u in a)
                    if (d = a[u], " " === u)
                        for (k in d) y = d[k], g = w[y] || (w[y] = {
                            length: 0,
                            words: {}
                        }), g.length += g.words[c] ? 0 : 1, g.words[c] = 1 + (g.words[c] || 0);
                    else b(d, c)
            }
            var k = -1,
                d, l, e, g, u, y, w = {},
                v = [],
                r = this.normalize(a),
                F = r.length,
                h = this.getData();
            a: for (; ++k < F;) {
                d = r[k];
                l = this.getTree();
                e = Math.min(d.length, this.depth) || d.length;
                for (g = 0; g < e; g++) {
                    u = d.charAt(g);
                    if (!l[u]) continue a;
                    l = l[u]
                }
                b(l, d)
            }
            for (y in w) this.matchall && w[y].length < F || v.push(h[y]);
            c && (c.query = a, c.words = r);
            return v
        };
        c.normalize = function(a) {
            for (var c = -1, b = {}, k = [], d = this.trans, l = this.split(a), e = l.length; ++c < e;)
                if (a = l[c])
                    if (this.ignorecase && (a = a.toLowerCase()), a = this.strip(a)) d && (a = d(a)), b[a] || (k.push(a), b[a] = !0);
            return k
        };
        c.stopped = function(a) {
            return 1 === a.length
        };
        c.split = function(a) {
            return a && a.split(this.boundary) || []
        };
        c.strip = function(a) {
            return a && a.replace(this.nonword, "") || ""
        };
        c.dump = function() {
            function a(a) {
                for (var c = -1, d = []; ++c < a.length;) d.push(b[a[c]]);
                return d
            }

            function c(b, d) {
                var l, e;
                for (l in b) e = b[l], " " === l ? console.log(d + ": [ " + a(e).join(", ") + " ]") : c(e, d + l)
            }
            var b = this.getData();
            c(this.getTree(), "")
        };
        c = null;
        return d
    }({}, z, A));
    p.register("$13", function(d, c, n) {
        function h(a) {
            -1 === a.indexOf("?") && (a = "/auto/" + a + ".json?q=");
            this.url = a;
            this.dead = {}
        }

        function a() {
            this.dict = p.require("$5", "dict.js").create()
        }
        d.init = function(c) {
            function b() {
                if ("hint" !== S) {
                    var b = C.val() && !(P && P.val()) && null == q && !F;
                    V[b ? "addClass" : "removeClass"]("error")
                }
            }

            function k(b) {
                P && P.val(b)
            }

            function d() {
                R.show();
                var b = C.outerWidth(!1),
                    a = C.outerHeight(!1),
                    c = C.css("margin-top");
                c && (c = parseInt(c), isNaN(c) || (a += c));
                b -= 2;
                R.css("top", a + "px").css("width", b + "px");
                F = !0
            }

            function l() {
                R.hide();
                F = !1
            }

            function t() {
                R.html("");
                l();
                r = 0;
                G = q = null
            }

            function g(a) {
                t();
                var c;
                for (c = 0; c < a.length; c++) {
                    var k = c,
                        g = a[c],
                        q = e('<span class="label"></span>').text(g.label),
                        l = e('<div class="auto-comp-result"></div>'),
                        f = void 0;
                    for (f in g) l.data(f, g[f]);
                    g.icon && l.append(e("<span></span>").attr("class", g.icon));
                    l.append(q);
                    u(k, l)
                }(r = a.length) ? (E && d(), y(0)) : (y(null), b(), V.trigger("locoAutonone", []))
            }

            function u(b, a) {
                R.append(a);
                a.click(function(c) {
                    c.stopPropagation();
                    y(b, a);
                    v();
                    return !1
                });
                return a
            }

            function y(b, a) {
                G && (G.removeClass("selected"), G = null);
                q = null;
                null == b ? k("") : (a || (a = R.find("div.auto-comp-result").eq(b)), a.length && (a.addClass("selected"), q = b, G = a))
            }

            function w(b) {
                if (r) {
                    var a =
                        r - 1;
                    null == q ? b = 0 < b ? 0 : a : (b = q + b, 0 > b ? b = a : b > a && (b = 0));
                    return y(b)
                }
            }

            function v() {
                if (null == q) z.val(""), k("");
                else {
                    var a = R.find("div.auto-comp-result").eq(q),
                        c = a.data() || {
                            label: "Error"
                        },
                        g = c.value,
                        d = c.label;
                    k(g);
                    z.val(d);
                    l();
                    a = a.clone();
                    a.data(c);
                    t();
                    u(0, a);
                    r = 1;
                    y(0, a);
                    b();
                    a.trigger("locoAutocomp", [g, d, a])
                }
            }
            var r = 0,
                F = !1,
                E = !1,
                s = c.form,
                q = null,
                G = null,
                C = e(c),
                n = C.attr("name"),
                S = C.attr("data-mode"),
                O = C.attr("data-provider"),
                P = "hint" !== S && e('<input type="hidden" value="" name="' + n + '" />').appendTo(s),
                V = e('<div class="auto-comp-wrap"></div>').replaceAll(C),
                R = e('<div class="auto-comp-drop"></div>');
            O && (O = new h(O));
            P && C.attr("name", "_" + n);
            C.attr("autocomplete") || C.attr("autocomplete", "off");
            V.append(C).append(R);
            l();
            C.focus(function(b) {
                E = !0;
                1 < r && d()
            }).blur(function(a) {
                E = !1;
                b()
            }).keydown(function(b) {
                function a() {
                    b.preventDefault();
                    b.stopPropagation();
                    return !1
                }
                switch (b.keyCode) {
                    case 27:
                        F && (b.stopPropagation(), l(), C.blur());
                        break;
                    case 40:
                        r && (F ? w(1) : d());
                        break;
                    case 38:
                        F && w(-1);
                        break;
                    case 13:
                        if (F) return v(), a();
                        if (!q && "hint" !== S) return a()
                }
                return !0
            });
            var z =
                p.require("$7", "LocoTextListener.js").listen(C, function(b) {
                    O && O.fetch(b, g)
                });
            (s = C.attr("data-pre")) && (s = e.parseJSON(s)) && s.value && s.label ? (g([s]), v()) : !c.value || P && P.val() || !O || O.fetch(c.value, function(b) {
                g(b);
                v()
            });
            return {
                $: C,
                val: function() {
                    return P && P.val()
                },
                clear: t,
                reset: function() {
                    t();
                    C.val("");
                    k("");
                    z.ping()
                },
                force: function(b, a) {
                    t();
                    k(a || "");
                    z.val(b)
                },
                preload: function(b) {
                    r && t();
                    O = new a;
                    var c, k;
                    for (c in b) k = b[c], O.add(k)
                },
                mode: function(b) {
                    S = b
                }
            }
        };
        h.prototype.fetch = function(a, b) {
            if (!a) return b &&
                b([]), this;
            var c, d = this.dead;
            for (c in d)
                if (0 === a.indexOf(c)) return b && b([]), this;
            c = {
                dataType: "json",
                url: this.url + encodeURIComponent(a)
            };
            p.require("$24", "http.js").ajax(c, function(c) {
                var k = c && c.results;
                k && (b && c.query && c.query === a && b(c.results), k.length || (d[a] = 0));
                return !0
            });
            return this
        };
        a.prototype.add = function(a) {
            var b = a.fulltext || a.label || a.value;
            b && this.dict.add(a, b)
        };
        a.prototype.fetch = function(a, b) {
            if (!a) return b && b([]), this;
            var c = this.dict.find(a);
            b(c)
        };
        return d
    }({}, z, A));
    p.register("$14", function(d,
        c, n) {
        function h(b) {
            b.stopPropagation();
            b.preventDefault();
            return !1
        }

        function a(b, a, c) {
            if (c = c || b.getElement(a)) c.off().mouseup(function(c) {
                c.stopPropagation();
                b.selectIndex(a, !0);
                return !1
            }).mouseover(function() {
                e(this).addClass("over");
                b.hover = a;
                return !0
            }).mouseout(function() {
                e(this).removeClass("over");
                b.hover = -1;
                return !0
            }), c = null;
            return b
        }

        function f(b) {
            if (b) {
                var a = this,
                    c = b[0];
                a.id = c.id || "";
                a.name = c.name || "";
                a.prefix = c.getAttribute("data-prefix");
                a.defaultIcon = c.getAttribute("data-icon") || "jshide";
                var d = c.selectedIndex,
                    f = [],
                    g, u, y;
                for (u = 0; u < c.options.length; u++) g = c.options[u], y = g.disabled, g = e(g), f.push([g.val(), g.text(), g.attr("data-icon") || "", y]);
                a.hidden = e('<input type="hidden" name="' + a.name + '" value="" />').appendTo(c.form);
                a.list = e('<ul class="clearfix"></ul>');
                a.icon = e('<span class="icon"> </span>');
                a.selection = e('<span class="label"></span>');
                a.handle = e('<a class="handle" href="#"></a>').attr("tabindex", b.attr("tabindex") || "").append(a.icon).append(a.selection);
                a.wrapper = e("<div></div>").addClass(c.className).addClass("selector").append(a.handle).append(a.list).replaceAll(c);
                this.id && a.wrapper.attr("id", this.id);
                for (a.clearOptions(); g = f.shift();) a.addOption.apply(a, g);
                a.handle.click(function(b) {
                    b.preventDefault();
                    return !1
                }).mouseover(function(b) {
                    return a.onRollover(b)
                }).mouseout(function(b) {
                    return a.onRollout(b)
                }).mousedown(function(b) {
                    return a.onPress(b)
                }).keydown(function(b) {
                    return a.onKeydown(b)
                });
                e(n.body).mouseup(function(b) {
                    return a.onRelease(b)
                }).keydown(function(b) {
                    return a.onGlobalKeydown(b)
                });
                a.close();
                a.selectIndex(d)
            }
        }
        d.create = function(b) {
            return new f(b)
        };
        d.extend = function(b) {
            b.prototype = new f
        };
        c = f.prototype;
        c.onRollover = function(b) {
            return this.over = !0
        };
        c.onRollout = function(b) {
            this.over = !1;
            return !0
        };
        c.onPress = function(b) {
            return this.active ? this.over ? (this.close(), b.stopPropagation(), b.preventDefault(), !1) : !0 : (this.open(), b.stopPropagation(), b.preventDefault(), this.handle.focus(), !1)
        };
        c.onRelease = function(b) {
            this.active && !this.over && this.close();
            return !0
        };
        c.onGlobalKeydown = function(b) {
            if (this.active) switch (b.keyCode) {
                case 27:
                    return this.close(), h(b);
                case 40:
                    return this.hoverNext(1), h(b);
                case 38:
                    return this.hoverNext(-1), h(b);
                case 13:
                    if (-1 != this.hover) return this.selectIndex(this.hover, !0), this.hoverItem(-1), this.close(), h(b)
            }
            return !0
        };
        c.onKeydown = function(b) {
            return this.active || 40 !== b.keyCode ? !0 : (this.open(), h(b))
        };
        c.open = function() {
            this.active = !0;
            this.hover = -1;
            this.wrapper.addClass("active");
            var b = this.handle.outerHeight() || 0;
            this.list.show().css("top", b + "px");
            var b = this.handle.outerWidth() || 0,
                a = this.list.outerWidth() || 0;
            b > a && (a -= this.list.width(),
                this.list.css("min-width", String(b - a) + "px"))
        };
        c.close = function() {
            this.list.hide();
            this.active = !1; - 1 !== this.hover && (this.getElement(this.hover).removeClass("over"), this.hover = -1);
            this.wrapper.removeClass("active")
        };
        c.hoverItem = function(b, a) {
            -1 !== this.hover && this.getElement(this.hover).removeClass("over");
            this.hover = b; - 1 !== b && (a = a || this.getElement(b), a.addClass("over"))
        };
        c.hoverNext = function(b) {
            var a = this.options.length;
            if (a) {
                a -= 1;
                if (-1 == this.hover) b = 0 < b ? 0 : a;
                else {
                    b = this.hover + b;
                    if (0 > b) {
                        this.close();
                        return
                    }
                    b > a && (b = 0)
                }
                this.hoverItem(b)
            }
        };
        c.enableChange = function(b) {
            this.eventName = b;
            this.eventData = [].slice.call(arguments, 1);
            return this
        };
        c.enableConfirm = function(b) {
            this.confirm = b;
            return this
        };
        c.clearOptions = function() {
            this.index = {};
            this.length = 0;
            this.options = [];
            this.list.html("");
            this.hidden.val("");
            this.idx = this.hover = -1;
            return this
        };
        c.addOption = function(b, c, d, l) {
            var f = this.options.length,
                g = e("<span></span>").addClass(d || "jshide"),
                u = e('<span class="label"></span>').text(c || b),
                g = e("<li></li>").append(g).append(u).appendTo(this.list);
            g.attr("data-option", f);
            this.options[f] = {
                value: b,
                text: c,
                icon: d
            };
            this.index[b] = f;
            this.length = f + 1;
            l ? this.disableIndex(f) : a(this, f, g);
            return f
        };
        c.disableOption = function(b) {
            return this.disableIndex(this.index[b])
        };
        c.disableIndex = function(b) {
            (b = this.getElement(b)) && b.addClass("disabled").off();
            return this
        };
        c.enableOption = function(b) {
            return this.enableIndex(this.index[b])
        };
        c.enableIndex = function(b) {
            return a(this, b)
        };
        c.reIndex = function() {
            this.index = {};
            this.length = 0;
            for (var b = this.options.length; - 1 < --b;) this.index[this.options[b].value] =
                b, this.length++
        };
        c.selectValue = function(b, a) {
            return this.selectIndex(this.index[b], a)
        };
        c.selectIndex = function(b, a) {
            var c = this.options[b];
            if (c) {
                var d = this,
                    f = c.value,
                    g = c.icon || d.defaultIcon,
                    u = function() {
                        d.hidden.val(f);
                        d.idx = b;
                        d.setLabel(c.text);
                        d.icon.attr("class", g);
                        a && d.change()
                    };
                d.idx === b ? d.hidden.val(f) : a ? "function" === typeof d.confirm ? d.confirm.call(null, c, function(b) {
                    b && u()
                }) : d.beforeChange(f) && u() : u();
                d.active && d.close()
            }
            return this
        };
        c.setLabel = function(b) {
            this.selection.text(b);
            this.prefix &&
                this.selection.prepend(e('<span class="prefix"></span>').text(this.prefix))
        };
        c.val = function() {
            var b = this.options[this.idx];
            return b && b.value
        };
        c.change = function() {
            var b = this.eventName || "change",
                a = [this.val()].concat(this.eventData || []);
            this.wrapper.trigger(b, a);
            return this
        };
        c.beforeChange = function(b) {
            var a = e.Event("locoBeforeSelect");
            this.wrapper.trigger(a, [b]);
            return !a.isDefaultPrevented()
        };
        c.renameOption = function(b, a) {
            var c = this.index[b],
                d = this.options[c];
            d && (d.text = a, this.getElement(c).find("span.label").text(a),
                c === this.idx && this.setLabel(a));
            return this
        };
        c.removeOption = function(b) {
            var a = this.index[b],
                c = this.options[a];
            c && (b = this.val(), this.getElement(a).remove(), this.options.splice(a, 1), this.reIndex(), b === c.value ? this.selectIndex(0, !0) : this.selectValue(b, !1))
        };
        c.getElement = function(b) {
            return this.list.find("li").eq(b)
        };
        c = null;
        return d
    }({}, z, A));
    p.register("$15", function(d, c, n) {
        function h(b, a) {
            this.$element = e(b);
            this.options = a;
            this.enabled = !0;
            this.fixTitle()
        }
        d.init = function(b) {
            var c = b.attr("data-gravity") ||
                "s";
            b.tipsy[c] && (c = b.tipsy[c]);
            b.tipsy({
                fade: !0,
                gravity: c,
                offset: 5,
                delayIn: a,
                delayOut: f,
                anchor: b.attr("data-anchor")
            })
        };
        d.delays = function(b, c) {
            a = b || 150;
            f = c || 100
        };
        d.kill = function() {
            e("div.tipsy").remove()
        };
        d.text = function(b, a) {
            a.data("tipsy").setTitle(b)
        };
        var a, f;
        d.delays();
        e(n.body).on("overlayOpened overlayClosing", function(b) {
            d.kill();
            return !0
        });
        h.prototype = {
            show: function() {
                var b = this.getTitle();
                if (b && this.enabled) {
                    var a = this.tip();
                    a.find(".tipsy-inner")[this.options.html ? "html" : "text"](b);
                    a[0].className =
                        "tipsy";
                    a.remove().css({
                        top: 0,
                        left: 0
                    }).prependTo(n.body);
                    var b = (b = this.options.anchor) ? this.$element.find(b) : this.$element,
                        b = e.extend({}, b.offset(), {
                            width: b[0].offsetWidth,
                            height: b[0].offsetHeight
                        }),
                        c = a[0].offsetWidth,
                        d = a[0].offsetHeight,
                        f = "function" == typeof this.options.gravity ? this.options.gravity.call(this.$element[0]) : this.options.gravity,
                        g;
                    switch (f.charAt(0)) {
                        case "n":
                            g = {
                                top: b.top + b.height + this.options.offset,
                                left: b.left + b.width / 2 - c / 2
                            };
                            break;
                        case "s":
                            g = {
                                top: b.top - d - this.options.offset,
                                left: b.left +
                                    b.width / 2 - c / 2
                            };
                            break;
                        case "e":
                            g = {
                                top: b.top + b.height / 2 - d / 2,
                                left: b.left - c - this.options.offset
                            };
                            break;
                        case "w":
                            g = {
                                top: b.top + b.height / 2 - d / 2,
                                left: b.left + b.width + this.options.offset
                            }
                    }
                    2 == f.length && ("w" == f.charAt(1) ? g.left = b.left + b.width / 2 - 15 : g.left = b.left + b.width / 2 - c + 15);
                    a.css(g).addClass("tipsy-" + f);
                    a.find(".tipsy-arrow")[0].className = "tipsy-arrow tipsy-arrow-" + f.charAt(0);
                    this.options.className && a.addClass("function" == typeof this.options.className ? this.options.className.call(this.$element[0]) : this.options.className);
                    a.addClass("in")
                }
            },
            hide: function() {
                this.tip().remove()
            },
            fixTitle: function() {
                var b = this.$element;
                (b.attr("title") || "string" != typeof b.attr("original-title")) && b.attr("original-title", b.attr("title") || "").removeAttr("title")
            },
            getTitle: function() {
                var b, a = this.$element,
                    c = this.options;
                this.fixTitle();
                c = this.options;
                "string" == typeof c.title ? b = a.attr("title" == c.title ? "original-title" : c.title) : "function" == typeof c.title && (b = c.title.call(a[0]));
                return (b = ("" + b).replace(/(^\s*|\s*$)/, "")) || c.fallback
            },
            setTitle: function(b) {
                var a =
                    this.$element;
                a.attr("default-title") || a.attr("default-title", this.getTitle());
                null == b && (b = a.attr("default-title") || this.getTitle());
                a.attr("original-title", b);
                if (this.$tip) this.$tip.find(".tipsy-inner")[this.options.html ? "html" : "text"](b)
            },
            tip: function() {
                this.$tip || (this.$tip = e('<div class="tipsy"></div>').html('<div class="tipsy-arrow"></div><div class="tipsy-inner"></div>'), this.$tip.data("tipsy-pointee", this.$element[0]));
                return this.$tip
            },
            validate: function() {
                this.$element[0].parentNode || (this.hide(),
                    this.options = this.$element = null)
            },
            enable: function() {
                this.enabled = !0
            },
            disable: function() {
                this.enabled = !1
            },
            toggleEnabled: function() {
                this.enabled = !this.enabled
            }
        };
        e.fn.tipsy = function(b) {
            function a(c) {
                var g = e.data(c, "tipsy");
                g || (g = new h(c, e.fn.tipsy.elementOptions(c, b)), e.data(c, "tipsy", g));
                return g
            }

            function c() {
                var g = a(this);
                g.hoverState = "in";
                0 == b.delayIn ? g.show() : (g.fixTitle(), setTimeout(function() {
                    "in" == g.hoverState && g.show()
                }, b.delayIn))
            }

            function d() {
                var c = a(this);
                c.hoverState = "out";
                0 == b.delayOut ?
                    c.hide() : (c.tip().removeClass("in"), setTimeout(function() {
                        "out" == c.hoverState && c.hide()
                    }, b.delayOut))
            }
            if (!0 === b) return this.data("tipsy");
            if ("string" == typeof b) {
                var f = this.data("tipsy");
                if (f) f[b]();
                return this
            }
            b = e.extend({}, e.fn.tipsy.defaults, b);
            b.live || this.each(function() {
                a(this)
            });
            if ("manual" != b.trigger) {
                var f = b.live ? "live" : "bind",
                    g = "hover" == b.trigger ? "mouseleave" : "blur";
                this[f]("hover" == b.trigger ? "mouseenter" : "focus", c)[f](g, d)
            }
            return this
        };
        e.fn.tipsy.defaults = {
            className: null,
            delayIn: 0,
            delayOut: 0,
            fade: !1,
            fallback: "",
            gravity: "n",
            html: !1,
            live: !1,
            offset: 0,
            opacity: 0.8,
            title: "title",
            trigger: "hover",
            anchor: null
        };
        e.fn.tipsy.revalidate = function() {
            e(".tipsy").each(function() {
                var b = e.data(this, "tipsy-pointee"),
                    a;
                if (!(a = !b)) {
                    a: {
                        for (; b = b.parentNode;)
                            if (b == n) {
                                b = !0;
                                break a
                            }
                        b = !1
                    }
                    a = !b
                }
                a && e(this).remove()
            })
        };
        e.fn.tipsy.elementOptions = function(b, a) {
            return e.metadata ? e.extend({}, a, e(b).metadata()) : a
        };
        e.fn.tipsy.autoNS = function() {
            return e(this).offset().top > e(n).scrollTop() + e(c).height() / 2 ? "s" : "n"
        };
        e.fn.tipsy.autoWE =
            function() {
                return e(this).offset().left > e(n).scrollLeft() + e(c).width() / 2 ? "e" : "w"
            };
        e.fn.tipsy.autoBounds = function(b, a) {
            return function() {
                var d = a[0],
                    f = 1 < a.length ? a[1] : !1,
                    t = e(n).scrollTop() + b,
                    g = e(n).scrollLeft() + b,
                    u = e(this);
                u.offset().top < t && (d = "n");
                u.offset().left < g && (f = "w");
                e(c).width() + e(n).scrollLeft() - u.offset().left < b && (f = "e");
                e(c).height() + e(n).scrollTop() - u.offset().top < b && (d = "s");
                return d + (f ? f : "")
            }
        };
        return d
    }({}, z, A));
    p.register("$2", function(d, c, n) {
        var h = c.ieVersion;
        d.ie = function(a) {
            return a ?
                h <= a : h
        };
        var a = d.init = function(c) {
            c ? c instanceof jQuery || (c = e(c)) : c = e(n.body);
            var b = p.require("$10", "ga.js"),
                k = p.require("$11", "forms.js"),
                m = p.require("$12", "modal.js"),
                l = p.require("$13", "LocoAutoComplete.js"),
                t = p.require("$14", "LocoSelector.js"),
                g = p.require("$15", "tooltip.js");
            c.find("form").each(function(b, a) {
                var c = e(a);
                h && 10 > h && k.placeholders(c);
                a.getAttribute("data-modal") ? m.initForm(a) : a.action && 0 !== c.attr("action").indexOf("#") && !a.target && k.jsonify(a);
                c.hasClass("hasreveal") && k.revealify(c);
                c.find("input.button").each(function(b, a) {
                    k.linkify(a)
                });
                c.find("input.auto-comp").each(function(b, a) {
                    l.init(a)
                });
                c.find("select.selector").each(function(b, a) {
                    t.create(e(a))
                })
            });
            c.find("a").each(function(c, f) {
                -1 !== f.className.indexOf("hastip") && g.init(e(f));
                if (-1 !== f.href.indexOf("/modal/") || f.getAttribute("data-modal")) m.initLink(e(f));
                else {
                    b.link(f);
                    var k = f.getAttribute("data-ajax-target");
                    k && (k = e("#" + k), e(f).click(function(b) {
                        b.preventDefault();
                        k.addClass("loading");
                        e.get(f.href, function(b) {
                            b =
                                d.$(b).replaceAll(k);
                            a(b);
                            b.trigger("locoAhah")
                        });
                        return !1
                    }))
                }
            });
            k = m = l = c = null;
            return d
        };
        d.$ = function(a) {
            return e(c.innerShiv ? innerShiv(a, !1) : a)
        };
        e.fn._html = function(d) {
            return null != d ? (d = this.html(c.innerShiv ? innerShiv(d, !1) : d), a(this), d) : j.html()
        };
        e.fn.macro = function(a, b) {
            if ("function" !== typeof a.run) throw Error("macro has no run function");
            a.run(this, b || {});
            return this
        };
        d.el = function(a, b) {
            var c = n.createElement(a || "div");
            b && (c.className = b);
            return c
        };
        d.txt = function(a) {
            return n.createTextNode(a || "")
        };
        d.noop = function(a) {
            a.preventDefault();
            a.stopPropagation();
            return !1
        };
        return d
    }({}, z, A));
    p.register("$11", function(d, c, n) {
        function h(b) {
            function a() {
                b.value === f && (b.value = "", d.removeClass("placeheld"));
                return !0
            }

            function c() {
                "" === b.value && (b.value = f, d.addClass("placeheld"));
                return !0
            }
            var d = e(b);
            if (!d.hasClass("auto-comp")) {
                var f = d.attr("placeholder");
                if (f) return d.focus(a).blur(c), c(), {
                    kill: function() {
                        a();
                        d.off("focus", a).off("blur", c)
                    }
                }
            }
        }
        var a = d.enable = function(b) {
                function a(b, c) {
                    c.getAttribute("data-was-disabled") ||
                        (c.disabled = !1)
                }
                b.find(".button").removeClass("loading");
                b.find("input").each(a);
                b.find("select").each(a);
                b.find("textarea").each(a);
                c.attachEvent && b.hasClass("has-placeholders") && d.placeholders(b);
                delete b._disabled
            },
            f = d.disable = function(b) {
                function a(b, c) {
                    c.disabled ? c.setAttribute("data-was-disabled", "true") : c.disabled = !0
                }
                b._disabled || (b.find(".button").addClass("loading"), b.find("input").each(a), b.find("select").each(a), b.find("textarea").each(a), b._disabled = !0)
            };
        d.jsonify = function(b, d, m) {
            b instanceof
            jQuery || (b = e(b));
            b.disable || (e.fn.disable = function() {
                f(this);
                return this
            }, e.fn.enable = function() {
                a(this);
                this.placehold && this.placehold();
                return this
            });
            var l = "";
            b.find('[type="submit"]').click(function(b) {
                b && b.target && b.target.name && (l = encodeURIComponent(b.target.name) + "=" + encodeURIComponent(b.target.value));
                return !0
            });
            b.submit(function(a) {
                if (a && a.isDefaultPrevented && a.isDefaultPrevented() || m && !1 === m(a)) return !1;
                var g = c.tinyMCE;
                g && b.find("textarea.editor").each(function(b, a) {
                    var c = g.get(a.id);
                    c &&
                        c.save()
                });
                var f = b.serialize(),
                    f = f.replace(/%0D%0A/g, "%0A");
                l && (f && (f += "&"), f += l, l = "");
                b.disable();
                var e = p.require("$24", "http.js"),
                    f = {
                        url: e.jsonLink(b.attr("action")),
                        type: b.attr("method"),
                        data: f
                    };
                e.ajax(f, d, function() {
                    b.enable()
                }, b);
                a.preventDefault();
                a.stopPropagation();
                return !1
            });
            if (b.hasClass("autopost")) {
                var t, g = Number(b.attr("data-autopost-delay") || 500);
                b.find('input[type="checkbox"]').change(function() {
                    t && clearTimeout(t);
                    t = setTimeout(function() {
                        b.submit()
                    }, g);
                    return !0
                })
            }
        };
        d.revealify = function(b) {
            b.find("div[data-reveal-if]").each(function(a,
                c) {
                function d(b) {
                    var a;
                    r = r || b.target;
                    if ("." === h) a = Boolean(r && r[v]);
                    else if ("=" === h) {
                        var c, g;
                        a = e(r.form).serializeArray();
                        for (g in a) a[g].name === y && (c = a[g].value);
                        a = v === c
                    }
                    if (a !== u)
                        if (u = a, b) f[u ? "slideDown" : "slideUp"](200);
                        else f[u ? "show" : "hide"]();
                    return !0
                }
                var f = e(c),
                    g = /^([_\w\-\[\]]+)(\.|=)(.+)$/.exec(f.attr("data-reveal-if"));
                if (g) {
                    var u, y = g[1],
                        h = g[2],
                        v = g[3],
                        g = b[0][y];
                    g.length || (g = [g]);
                    var r;
                    for (a = 0; a < g.length; a++) r = g[a], d(), e(r).change(d).removeClass("jshide");
                    g = g = r = null
                }
            });
            b = null
        };
        d.linkify = function(a) {
            var c =
                a.getAttribute("data-icon");
            if (c) {
                var d = e(a),
                    f = e("<a> </a>");
                f.attr("href", a.form.action);
                f.attr("class", d.attr("class"));
                f.attr("tabindex", d.attr("tabindex"));
                d.attr("tabindex", "-1");
                f.text(d.val());
                c && e("<span></span>").prependTo(f).addClass(c);
                d.hide().after(f);
                f.click(function(a) {
                    d.click();
                    return !1
                })
            }
        };
        d.placeholders = function(a) {
            var c, d = [];
            a.find("input[placeholder]").each(function(a, b) {
                "password" !== b.type && (c = h(b)) && d.push(c)
            });
            d.length && (a.submit(function() {
                    for (var a in d) d[a].kill()
                }), a.addClass("has-placeholders"),
                c = i = null)
        };
        return d
    }({}, z, A));
    p.register("$16", function(d, c, e) {
        function h(a) {
            this.reIndex([]);
            if (a)
                for (var c in a) this.add(c, a[c])
        }
        d.init = function(a) {
            return new h(a)
        };
        c = h.prototype;
        c.reIndex = function(a) {
            var c = -1;
            for (this.ords = {}; ++c < a.length;) this.ords[a[c]] = c;
            this.keys = a;
            this.length = c
        };
        c.key = function(a, c) {
            if (null == c) return this.keys[a];
            var b = this.keys[a],
                d = this.ords[c];
            if (c !== b) {
                if (null != d) throw Error("Clash with item at [" + d + "]");
                this.keys[a] = c;
                delete this.ords[b];
                this.ords[c] = a
            }
            return a
        };
        c.indexOf =
            function(a) {
                a = this.ords[a];
                return null == a ? -1 : a
            };
        c.add = function(a, c) {
            var b = this.ords[a];
            null == b && (this.keys[this.length] = a, b = this.ords[a] = this.length++);
            this[b] = c;
            return b
        };
        c.get = function(a) {
            return this[this.ords[a]]
        };
        c.cut = function(a, c) {
            var b = [].splice.call(this, a, c);
            this.keys.splice(a, c);
            this.reIndex(this.keys);
            return b
        };
        c.each = function(a) {
            for (var c = -1; ++c < this.length;) a(this.keys[c], this[c], c);
            return this
        };
        c = null;
        return d
    }({}, z, A));
    p.register("$20", function(d, c, e) {
        function h(a) {
            c.console && console.error &&
                console.error(a)
        }

        function a() {
            h("Method not implemented")
        }

        function f() {}

        function b(a) {}
        f.prototype.toString = function() {
            return "[Undefined]"
        };
        b.prototype._validate = function(b) {
            var c, d, e = !0;
            for (c in this) d = this[c], d === a ? (h(b + "." + c + "() must be implemented"), e = !1) : d instanceof f && (h(b + "." + c + " must be defined"), e = !1);
            return e
        };
        d.init = function(c, d) {
            var e, h = new b;
            if (c)
                for (e = c.length; 0 !== e--;) h[c[e]] = a;
            if (d)
                for (e = d.length; 0 !== e--;) h[d[e]] = new f;
            return h
        };
        d.validate = function(a) {
            var b = /function (\w+)\(/.exec(a.toString()) ?
                RegExp.$1 : "";
            a.prototype._validate(b || "Object")
        };
        return d
    }({}, z, A));
    p.register("$17", function(d, c, e) {
        function h() {}
        d.extend = function(a) {
            return a.prototype = new h
        };
        c = h.prototype = p.require("$20", "abstract.js").init(["add", "load"]);
        c.row = function(a) {
            return this.rows[a]
        };
        c.lock = function(a) {
            this.locale(a || {
                lang: "zz",
                region: "ZZ",
                label: "Unknown",
                nplurals: 1,
                pluraleq: "n!=1"
            });
            return this.loc
        };
        c.unlock = function() {
            var a = this.loc;
            this.loc = null;
            return a
        };
        c.locale = function(a) {
            if (null == a) return this.loc;
            if (this.loc =
                a) this.loc.toString = function() {
                return this.lang + "_" + this.region
            };
            return this
        };
        c.each = function(a) {
            this.rows.each(a);
            return this
        };
        c.indexOf = function(a) {
            "object" !== typeof a && (a = this.get(a));
            if (!a) return -1;
            null == a.idx && (a.idx = this.rows.indexOf(a.hash()));
            return a.idx
        };
        c.get = function(a) {
            return this.rows && this.rows.get(a)
        };
        c.del = function(a) {
            a = this.indexOf(a);
            if (-1 !== a) {
                var c = this.rows.cut(a, 1);
                if (c && c.length) return this.length = this.rows.length, this.rows.each(function(a, c, d) {
                    c.idx = d
                }), a
            }
        };
        c.reIndex = function(a,
            c) {
            var b = this.indexOf(a),
                d = a.hash(),
                e = this.rows.indexOf(d);
            return e === b ? b : -1 !== e ? (c = (c || 0) + 1, a.source("Error, duplicate " + String(c) + ": " + a.source()), this.reIndex(a, c)) : this.rows.key(b, d)
        };
        c = null;
        return d
    }({}, z, A));
    p.register("$18", function(d, c, e) {
        function h() {
            this.id = this._id = this.ref = this.cmt = this.xcmt = ""
        }
        d.extend = function(a) {
            return a.prototype = new h
        };
        c = h.prototype;
        c.flag = function(a, c) {
            var b = this.flg || (this.flg = []);
            if (null != c) b[c] = a;
            else
                for (var d = Math.max(b.length, this.src.length, this.msg.length); 0 !==
                    d--;) b[d] = a;
            return this
        };
        c.flagged = function(a) {
            var c = this.flg || [];
            if (null != a) return c[a] || 0;
            for (a = c.length; 0 !== a--;)
                if (c[a]) return !0;
            return !1
        };
        c.flaggedAs = function(a, c) {
            var b = this.flg || [];
            if (null != c) return a === b[c] || 0;
            for (var d = b.length; 0 !== d--;)
                if (b[d] === a) return !0;
            return !1
        };
        c.fuzzy = function(a, c) {
            var b = this.flaggedAs(4, a);
            null != c && this.flag(c ? 4 : 0, a);
            return b
        };
        c.source = function(a, c) {
            if (null == a) return this.src[c || 0] || "";
            this.src[c || 0] = a;
            return this
        };
        c.plural = function(a, c) {
            if (null == a) return this.src[c ||
                1] || "";
            this.src[c || 1] = a || "";
            return this
        };
        c.each = function(a) {
            for (var c = -1, b = this.src, d = this.msg, e = Math.max(b.length, d.length); ++c < e;) a(c, b[c], d[c]);
            return this
        };
        c.pluralized = function() {
            return 1 < this.src.length || 1 < this.msg.length
        };
        c.translate = function(a, c) {
            this.msg[c || 0] = a || "";
            return this
        };
        c.untranslate = function(a) {
            null != a && (this.msg[a] = "");
            for (a = 0; a < this.msg.length; a++) this.msg[a] = "";
            return this
        };
        c.translation = function(a) {
            return this.msg[a || 0] || ""
        };
        c.translated = function(a) {
            if (arguments.length) return !!this.translation(a);
            for (a = 0; a < this.msg.length; a++)
                if (!this.msg[a]) return !1;
            return !0
        };
        c.comment = function(a) {
            if (null == a) return this.cmt;
            this.cmt = a || "";
            return this
        };
        c.notes = function(a) {
            if (null == a) return this.xcmt;
            this.xcmt = a || "";
            return this
        };
        c.refs = function(a) {
            if (null == a) return this.ref;
            this.ref = a || "";
            return this
        };
        c.format = function(a) {
            if (null == a) return this.fmt;
            this.fmt = a;
            return this
        };
        c.context = function(a) {
            if (null == a) return this.ctx || "";
            this.ctx = a || "";
            return this
        };
        c.toString = c.toText = function() {
            return this.src.concat(this.msg, [this.id, this.ctx]).join(" ")
        };
        c.weight = function() {
            var a = 0;
            this.translation() || (a += 2);
            this.fuzzy() && (a += 1);
            return a
        };
        c.equals = function(a) {
            return this === a || this.hash() === a.hash()
        };
        c.hash = function() {
            return this.id
        };
        c.normalize = function() {
            for (var a = this.msg.length; 0 !== a--;) this.msg[a] = this.src[a] || ""
        };
        c.disabled = function(a) {
            return !!(this.lck || [])[a || 0]
        };
        c.disable = function(a) {
            (this.lck || (this.lck = []))[a || 0] = !0;
            return this
        };
        c.saved = function(a) {
            var c = this.drt;
            if (!c) return !0;
            if (null != a) return !c[a];
            for (a =
                c.length; 0 !== a--;)
                if (c[a]) return !1;
            return !0
        };
        c.unsave = function(a) {
            (this.drt || (this.drt = []))[a || 0] = !0;
            return this
        };
        c.save = function(a) {
            var c = this.drt;
            null == a ? this.drt = null : c[a] = !1;
            return this
        };
        c = null;
        return d
    }({}, z, A));
    p.register("$3", function(d, c, e) {
        function h(a) {
            return {
                "Project-Id-Version": "PACKAGE VERSION",
                "Report-Msgid-Bugs-To": "",
                "POT-Creation-Date": a || "",
                "POT-Revision-Date": a || "",
                "PO-Revision-Date": a || "",
                "Last-Translator": "",
                "Language-Team": "",
                Language: "",
                "Plural-Forms": "",
                "MIME-Version": "1.0",
                "Content-Type": "text/plain; charset=UTF-8",
                "Content-Transfer-Encoding": "8bit",
                "X-Poedit-SourceCharset": "UTF-8"
            }
        }

        function a(a, b) {
            var c = a || "";
            b && (c += "\x00" + b);
            return c
        }

        function f() {
            return p.require("$16", "collection.js").init()
        }

        function b(a) {
            return a.replace(/(["\\])/g, "\\$1").replace(/\n/g, "\\n")
        }

        function k(a) {
            this.head = {};
            this.headers(h(this.now()));
            this.locale(a);
            this.length = 0;
            this.rows = f()
        }

        function m(a, b) {
            this.src = [a || ""];
            this.msg = [b || ""]
        }
        d.create = function(a) {
            return new k(a)
        };
        var l = d.quote = function(a) {
                if (!a) return '""';
                for (var c = a.split(/(?:\r\n|\n|\r)/g), d = c.length - 1, e = [], f = -1, r; ++f < c.length;) {
                    a = c[f];
                    for (d && d !== f && (a += "\n"); a && a.charAt(77);) {
                        for (r = 77; --r;)
                            if (-1 !== " \n\r.?!,;:-".indexOf(a.charAt(r))) {
                                r++;
                                break
                            }
                        r || (r = 77);
                        e.push(b(a.substr(0, r)));
                        a = a.substr(r)
                    }
                    a && e.push(b(a))
                }
                e[1] && e.unshift("");
                return '"' + e.join('"\n"') + '"'
            },
            t = d.wrap = function(a, b, c) {
                if (0 !== c) {
                    null == c && (c = 79);
                    for (var d = c + 1, e = a.split(/(?:\r\n|\n|\r)/g), f = [], l = -1, k; ++l < e.length;) {
                        for (a = e[l]; a && a.charAt(d);) {
                            for (k = c; --k;)
                                if (-1 !== " \n\r.?!,;:-".indexOf(a.charAt(k))) {
                                    k++;
                                    break
                                }
                            k || (k = d);
                            f.push(a.substr(0, k));
                            a = a.substr(k)
                        }
                        a && f.push(a)
                    }
                    a = f
                } else a = a.split(/(?:\r\n|\n|\r)/g);
                return b + a.join("\n" + b)
            };
        c = p.require("$17", "messages.js").extend(k);
        c.now = function() {
            return (new Date).toString()
        };
        c.header = function(a, b) {
            if (null == b) return this.headers()[a] || "";
            this.head[a] = b || "";
            return this
        };
        c.headers = function(a) {
            var b;
            if (null != a) {
                for (b in a) this.head[b] = a[b];
                return this
            }
            var c = this.locale(),
                d = this.now();
            a = {};
            for (b in this.head) a[b] = String(this.head[b]);
            c ? (a.Language = c.label || "Unknown locale",
                a["Plural-Forms"] = "nplurals=" + (c.nplurals || "2") + "; plural=" + (c.pluraleq || "n!=1"), a["X-Loco-Target-Locale"] = (c.lang || "en") + "_" + (c.region || "GB"), a["PO-Revision-Date"] = d, delete a["POT-Revision-Date"]) : (a.Language = "", a["Plural-Forms"] = "nplurals=INTEGER; plural=EXPRESSION", a["POT-Revision-Date"] = d, a["PO-Revision-Date"] = "YEAR-MO-DA HO:MI+ZONE");
            a["X-Generator"] = "Loco - https://localise.biz/";
            return a
        };
        c.locale = function(a) {
            if (null == a) return this.loc;
            this.loc = a || {
                lang: "en",
                region: "GB",
                plurals: ["one", "other"],
                pluraleg: [1, 0],
                nplurals: 2,
                pluraleq: "n!=1",
                label: "English"
            };
            this.loc.toString = function() {
                return this.lang + "_" + this.region
            };
            return this
        };
        c.get = function(b, c) {
            var d = a(b, c);
            return this.rows.get(d)
        };
        c.add = function(a, b) {
            a instanceof m || (a = new m(a));
            b && a.context(b);
            var c = a.hash();
            if (this.rows.get(c)) throw Error("Duplicate message at index " + this.indexOf(a));
            a.idx = this.rows.add(c, a);
            this.length = this.rows.length;
            return a
        };
        c.load = function(a) {
            for (var b = -1, c, d, e, f, l, k = [], h = [], q = [], t = []; ++b < a.length;)
                if (c = a[b],
                    null == c.parent) {
                    if (d = c.source || c.id, e = c.context, d || e) f = new m(d, c.target || ""), f._id = c._id, e && f.context(e), c.flag && f.flag(c.flag, 0), c.comment && f.comment(c.comment), c.notes && f.notes(c.notes), c.refs && f.refs(c.refs), null !== c.format && f.format(c.format), c.message = f, f.translation() ? f.fuzzy() ? q.push(f) : h.push(f) : t.push(f)
                } else k.push(c);
            for (b = -1; ++b < k.length;) try {
                c = k[b];
                d = c.source || c.id;
                f = a[c.parent] && a[c.parent].message;
                if (!f) throw Error("parent missing for plural " + d);
                l = c.plural;
                1 === l && f.plural(d);
                c.flag &&
                    f.flag(c.flag, l);
                f.translate(c.target || "", l)
            } catch (C) {}
            return this._add(t, q, h)
        };
        c._add = function(a, b, c) {
            c = [a, b, c];
            for (i = 0; 3 > i; i++)
                for (b = c[i], a = -1; ++a < b.length;) try {
                    this.add(b[a])
                } catch (d) {}
            return this
        };
        c.merge = function(a) {
            var b, c = this.rows,
                d = [],
                e = [],
                l = [],
                k = {
                    add: [],
                    del: []
                };
            a = a.rows;
            this.rows.each(function(b, c) {
                a.get(b) || k.del.push(c)
            });
            a.each(function(a, g) {
                try {
                    (b = c.get(a)) ? (b.ref = g.ref, b.fmt = g.fmt) : (b = g, k.add.push(b)), b.translation() ? b.fuzzy() ? e.push(b) : l.push(b) : d.push(b)
                } catch (q) {}
            });
            this.rows = f();
            this._add(d, e, l);
            return k
        };
        c.toString = function() {
            var a, b = [],
                c = [],
                d = this.headers(),
                e = !this.loc;
            for (a in d) c.push(a + ": " + d[a]);
            c = new m("", c.join("\n"));
            e && (c.comment("Loco Gettext template"), c.fuzzy(0, !0));
            b.push(c.toString());
            b.push("");
            this.rows.each(function(a, c) {
                a && (b.push(c.toString(e)), b.push(""))
            });
            return b.join("\n")
        };
        c = p.require("$18", "message.js").extend(m);
        c.hash = function() {
            return a(this.source(), this.context())
        };
        c.source = function(a, b) {
            if (null == a) return this.src[0];
            this.src[0] = a;
            null != b &&
                this.plural(b);
            return this
        };
        c.toString = function(a) {
            var b, c = [],
                d;
            (d = this.cmt) && c.push(t(d, "# ", 0));
            (d = this.xcmt) && c.push(t(d, "#. ", 0));
            b = this.ref;
            if (d = this._id) b += (b ? " " : "") + "loco:" + d;
            b && /\S/.test(b) && c.push(t(b, "#: ", 79));
            !a && this.fuzzy() && c.push("#, fuzzy");
            (d = this.fmt) ? c.push("#, " + d + "-format"): null != d && c.push("#, no-c-format");
            (d = this.ctx) && c.push("msgctxt " + l(d));
            c.push("msgid " + l(this.src[0]));
            if (null == this.src[1]) c.push("msgstr " + l(a ? "" : this.msg[0]));
            else
                for (b = -1, c.push("msgid_plural " + l(this.src[1])); ++b <
                    this.msg.length;) c.push("msgstr[" + b + "] " + l(a ? "" : this.msg[b]));
            return c.join("\n")
        };
        c.compare = function(a, b) {
            var c = this.weight(),
                d = a.weight();
            if (c > d) return 1;
            if (c < d) return -1;
            if (b) {
                c = this.hash().toLowerCase();
                d = a.hash().toLowerCase();
                if (c < d) return 1;
                if (c > d) return -1
            }
            return 0
        };
        c = c = null;
        return d
    }({}, z, A));
    p.register("$19", function(d, c, e) {
        function h(a) {
            return Number(1 != a)
        }

        function a(a) {
            var b = 0,
                c, d = [].slice.call(arguments, 1);
            return a.replace(/%(s|u|%)/g, function(a, e) {
                if ("%" === e) return "%";
                c = d[b++];
                return String(c) ||
                    ""
            })
        }
        d.create = function(c, b) {
            function d(a) {
                return c[a] || a || ""
            }
            b || (b = h);
            return {
                s: a,
                _: d,
                _n: function(a, d, e) {
                    var g = c[a];
                    g instanceof Object && (g = g[pluralForms[b(e)] || "one"]);
                    return g || (1 === e ? a : d) || a || ""
                },
                _s: function(b) {
                    arguments[0] = d(b);
                    return a.apply(null, arguments)
                }
            }
        };
        return d
    }({}, z, A));
    p.register("$30", function(d, c, e) {
        var h = c.requestAnimationFrame,
            a = c.cancelAnimationFrame,
            f = 0;
        if (!h || !a)
            for (var b in {
                    ms: 1,
                    moz: 1,
                    webkit: 1,
                    o: 1
                })
                if (h = c[b + "RequestAnimationFrame"])
                    if (a = c[b + "CancelAnimationFrame"] || c[b + "CancelRequestAnimationFrame"]) break;
        h && a || (h = function(a) {
            var b = k();
            timeToCall = Math.max(0, 16 - (b - f));
            nextTime = b + timeToCall;
            timerId = c.setTimeout(function() {
                a(nextTime)
            }, timeToCall);
            f = nextTime;
            return timerId
        }, a = function(a) {
            clearTimeout(a)
        });
        var k = Date.now || function() {
            return (new Date).getTime()
        };
        d.loop = function(b, c) {
            function d() {
                f = h(d, c);
                b(e++)
            }
            var e = 0,
                f;
            d();
            return {
                stop: function() {
                    f && a(f);
                    f = null
                }
            }
        };
        return d
    }({}, z, A));
    p.register("$26", function(d, c, e) {
        function h(a, c, d, e) {
            if (b) {
                var f = d;
                d = function(a) {
                    if ((a.MSPOINTER_TYPE_TOUCH || "touch") === a.pointerType) return f(a)
                }
            }
            a.addEventListener(c,
                d, e);
            return {
                unbind: function() {
                    a.removeEventListener(c, d, e)
                }
            }
        }

        function a(a) {
            a.preventDefault();
            a.stopPropagation();
            return !1
        }
        var f, b = !!c.navigator.msPointerEnabled,
            k = b ? "MSPointerDown" : "touchstart",
            m = b ? "MSPointerMove" : "touchmove",
            l = b ? "MSPointerUp" : "touchend";
        d.ok = function(a) {
            null == f && (f = "function" === typeof e.body.addEventListener);
            f && a && a(d);
            return f
        };
        d.ms = function() {
            return b
        };
        d.dragger = function(b, c) {
            function d(a) {
                b.addEventListener(a, f[a], !1)
            }

            function e(a) {
                b.removeEventListener(a, f[a], !1)
            }
            var f = {};
            f[k] = function(a) {
                t(a, function(b, d) {
                    d.type = k;
                    c(a, d, g)
                });
                d(m);
                d(l);
                return !0
            };
            f[l] = function(a) {
                e(m);
                e(l);
                t(a, function(b, d) {
                    d.type = l;
                    c(a, d, g)
                });
                return !0
            };
            f[m] = function(b) {
                t(b, function(a, d) {
                    d.type = m;
                    c(b, d, g)
                });
                return a(b)
            };
            d(k);
            var g = {
                kill: function() {
                    e(k);
                    e(m);
                    e(l);
                    b = g = c = null
                }
            };
            return g
        };
        d.swiper = function(c, d, e) {
            function f(a) {
                c.addEventListener(a, G[a], !1)
            }

            function r(a) {
                c.removeEventListener(a, G[a], !1)
            }

            function h() {
                E && E.stop();
                E = null
            }
            var E, s, q, G = {},
                C = [],
                n = [],
                S = [];
            G[k] = function(a) {
                s = !1;
                h();
                var b = g();
                t(a,
                    function(a, c) {
                        C[a] = b;
                        n[a] = c.clientX;
                        S[a] = c.clientY
                    });
                q = c.scrollLeft;
                return !0
            };
            G[l] = function(a) {
                t(a, function(a, b) {
                    var c = g() - C[a],
                        e = n[a] - b.clientX,
                        c = Math.abs(e) / c;
                    d(c, e ? 0 > e ? -1 : 1 : 0)
                });
                q = null;
                return !0
            };
            G[m] = function(b) {
                var d, e;
                null == q || t(b, function(a, b) {
                    d = n[a] - b.clientX;
                    e = S[a] - b.clientY
                });
                if (e && Math.abs(e) > Math.abs(d)) return s = !0;
                d && (s = !0, c.scrollLeft = Math.max(0, q + d));
                return a(b)
            };
            if (!b || e) f(k), f(m), f(l), b && (c.className += " mstouch");
            return {
                kill: function() {
                    r(k);
                    r(m);
                    r(l);
                    h()
                },
                swiped: function() {
                    return s
                },
                ms: function() {
                    return b
                },
                snap: function(a) {
                    b && !e && (c.style["-ms-scroll-snap-points-x"] = "snapInterval(0px," + a + "px)", c.style["-ms-scroll-snap-type"] = "mandatory", c.style["-ms-scroll-chaining"] = "none")
                },
                scroll: function(a, b, d) {
                    h();
                    var e = c.scrollLeft,
                        f = a > e ? 1 : -1,
                        q = Math[1 === f ? "min" : "max"],
                        g = Math.round(16 * b * f);
                    return E = p.require("$30", "fps.js").loop(function(b) {
                        b && (e = Math.max(0, q(a, e + g)), c.scrollLeft = e, a === e && (h(), d && d(e)))
                    }, c)
                }
            }
        };
        d.start = function(a, b) {
            return h(a, k, b, !1)
        };
        d.move = function(a, b) {
            return h(a, m, b, !1)
        };
        d.end = function(a, b) {
            return h(a, l, b, !1)
        };
        var t = d.each = function(a, c) {
                if (b)(a.MSPOINTER_TYPE_TOUCH || "touch") === a.pointerType && c(0, a);
                else
                    for (var d = -1, e = (a.originalEvent || a).changedTouches || []; ++d < e.length;) c(d, e[d])
            },
            g = Date.now || function() {
                return (new Date).getTime()
            };
        return d
    }({}, z, A));
    p.register("$31", function(d, c, n) {
        d.init = function(c, a) {
            function d(c) {
                if (b !== c) {
                    u.text(String(c));
                    var e = a === c,
                        f = e || c < a;
                    changedState = e ? 2 : f ? 1 : 3;
                    if (changedState !== k) {
                        var g = n;
                        e && (g += " maxed");
                        f || (g += " invalid");
                        l.attr("class",
                            g);
                        k = changedState
                    }
                    b = c
                }
            }
            var b, k, m = p.require("$2", "html.js"),
                l = e(c.parent()).on("changing", function(a, b) {
                    d(b.length)
                }),
                t = e(m.el("span", "total")).text(String(a)),
                g = e(m.el("span", "separ")).text("/"),
                u = e(m.el("span", "count")),
                n = l.attr("class") || "";
            e(m.el("div", "counter")).append(u).append(g).append(t).appendTo(l);
            d(c.val().length);
            m = t = g = null
        };
        return d
    }({}, z, A));
    p.register("$27", function(d, c, n) {
        function h(a) {
            function c() {
                var e = a.value;
                e !== l && (l = e, d.trigger("changing", [e]))
            }
            var d = e(a),
                l = a.value,
                h;
            d.blur(function() {
                d.off("input paste");
                c();
                f = null;
                h !== l && d.trigger("changed", [l]);
                d.trigger("editBlur");
                return !0
            }).focus(function(e) {
                f = a;
                h = l;
                d.on("input paste", c);
                d.trigger("editFocus");
                return !0
            });
            return {
                kill: function() {
                    d.off("input paste blur focus")
                },
                fire: function() {
                    l = null;
                    c()
                }
            }
        }

        function a(a) {
            this.e = a
        }
        var f;
        d.init = function(b) {
            var c = new a(b);
            b.disabled ? c.disable() : c.enable();
            (b = c.attr("lang")) && c.locale(b);
            (b = c.attr("maxlength")) && c.max(Number(b));
            return c
        };
        d.create = function(b, c) {
            var d = n.createElement("textarea"),
                d = new a(d);
            c ? d.enable() :
                d.disable();
            return d.attr("wrap", "virtual")
        };
        TextAreaPrototype = a.prototype;
        TextAreaPrototype.val = function(a) {
            if (null == a) return this.e.value;
            this.e.value = a;
            return this
        };
        TextAreaPrototype.fire = function() {
            this.l && this.l.fire();
            return this
        };
        TextAreaPrototype.focus = function() {
            return e(this.e).focus()
        };
        TextAreaPrototype.focused = function() {
            return f && f === this.el
        };
        TextAreaPrototype.parent = function() {
            return this.e.parentNode
        };
        TextAreaPrototype.attr = function(a, c) {
            var d = this.e;
            if (1 === arguments.length) return d.getAttribute(a);
            null == c ? d.removeAttribute(a) : d.setAttribute(a, c);
            return this
        };
        TextAreaPrototype.editable = function() {
            return !!this.l
        };
        TextAreaPrototype.enable = function() {
            var a = this.e;
            a.removeAttribute("readonly");
            a.removeAttribute("disabled");
            this.listen();
            return this
        };
        TextAreaPrototype.disable = function() {
            this.e.setAttribute("disabled", !0);
            this.unlisten();
            return this
        };
        TextAreaPrototype.listen = function() {
            var a = this.l;
            a && a.kill();
            this.l = h(this.e);
            return this
        };
        TextAreaPrototype.unlisten = function() {
            this.l && this.l.kill();
            this.l = null;
            return this
        };
        TextAreaPrototype.locale = function(a) {
            if (null == a) return this.loc;
            this.loc = a = String(a);
            this.attr("lang", a);
            return this.rtl(-1 !== "ar,ps,he,ur,ckb".indexOf(a.substr(0, 2)))
        };
        TextAreaPrototype.rtl = function(a) {
            this.attr("dir", a ? "RTL" : "LTR");
            return this
        };
        TextAreaPrototype.max = function(a) {
            if (0 === arguments.length) return this.n || 0;
            this.n = a;
            p.require("$31", "counter.js").init(this, a);
            return this
        };
        TextAreaPrototype = null;
        return d
    }({}, z, A));
    p.register("$28", function(d, c, n) {
        function h(a) {
            return function() {
                a.redraw();
                return this
            }
        }

        function a(a) {
            return function(b) {
                var c = b.target.$r;
                if (null == c) return !0;
                a.select(c);
                b.stopPropagation();
                b.preventDefault();
                return !1
            }
        }

        function f(a) {
            var b = a.p.style;
            a = null;
            return function() {
                b.backfaceVisibility = "hidden";
                return !0
            }
        }

        function b(a) {
            var b = a.p.style;
            a = null;
            return function() {
                b.backfaceVisibility = "";
                return !0
            }
        }

        function k(a) {
            return function(b) {
                var c;
                c = b.keyCode;
                if (40 === c) c = 1;
                else if (38 === c) c = -1;
                else return !0;
                if (b.shiftKey || b.ctrlKey || b.metaKey || b.altKey) return !0;
                a.selectNext(c);
                b.stopPropagation();
                b.preventDefault();
                return !1
            }
        }

        function m(a) {
            this.w = a
        }
        d.create = function(a) {
            return new m(a)
        };
        c = m.prototype;
        c.init = function(c) {
            function d(a) {
                var b = n.createElement("div");
                a && b.setAttribute("class", a);
                return b
            }
            var g = this.w,
                m = g.id,
                p = g.splity(m + "-thead", m + "-tbody"),
                w = p[0],
                p = p[1],
                v = [],
                r = [],
                F = [];
            w.css.push("wg-thead");
            p.css.push("wg-tbody");
            c.eachCol(function(a, b) {
                v.push(m + "-col" + a);
                F.push(b)
            });
            for (var E = -1, s = v.length, q = d("wg-cols"), G = w.splitx.apply(w, v); ++E < s;) G[E].header(F[E]), q.appendChild(r[E] = d());
            var C = [],
                aa = n.createElement("div");
            c.eachRow(function(a, b, c) {
                for (var d, e = [], f = -1, q = b.length; ++f < q;) d = aa.cloneNode(!1), d.textContent = b[f] || "\u00a0", c && d.setAttribute("class", c), e[f] = d, d.$r = a;
                C[a] = e
            });
            this.d = c;
            this.c = r;
            this.t = C;
            this._ = q;
            this.p = p.body;
            w.redraw = h(this);
            c = p.fixed = G[0].bodyY() || 20;
            g.lock().resize(c, p);
            g.css.push("is-table");
            g.restyle();
            this.render();
            e(q).attr("tabindex", "-1").on("keydown", k(this)).on("mousedown", a(this)).on("mouseenter", f(this)).on("mouseleave", b(this));
            return this
        };
        c.redraw =
            function() {
                var a = -1,
                    b = this.c,
                    c = b.length,
                    d = this.w,
                    e = d.cells[0],
                    f = e.body.childNodes;
                for (d.redraw.call(e); ++a < c;) b[a].style.width = f[a].style.width
            };
        c.visible = function(a) {
            if (this.f) {
                var b;
                a = this.t[a];
                return (a && (b = a[0]) && b.parentNode) === this.c[0]
            }
            return !0
        };
        c.selected = function() {
            return this.r
        };
        c.tr = function(a) {
            return this.t[a]
        };
        c.td = function(a, b) {
            return (this.t[a] || [])[b]
        };
        c.scroll = function(a) {
            var b = this._;
            if (0 == arguments.length) return b && b.scrollTop || 0;
            b.scrollTop = a || 0;
            return this
        };
        c.focus = function() {
            e(this._).focus();
            return this
        };
        c.next = function(a, b, c) {
            null == c && (c = this.r);
            for (var d = c, e = this.t.length; c !== (d += a);)
                if (0 <= d && e > d) {
                    if (this.visible(d)) break
                } else if (b && e) d = 1 === a ? -1 : e, b = !1;
            else {
                d = null;
                break
            }
            return d
        };
        c.selectNext = function(a, b, c) {
            a = this.next(a, b);
            null != a && this.r !== a && this.select(a, c);
            return this
        };
        c.deselect = function(a) {
            var b = this.r;
            null != b && (e(this.t[b]).removeClass("selected"), this.r = null, this.w.fire("wgRowDeselect", [b, a]));
            return this
        };
        c.select = function(a, b) {
            var c = this.visible(a);
            this.deselect(c);
            if (!c) return !1;
            var c = this.t[a],
                d = this.w.cells[1],
                f = this.d.getRow(a);
            if (!c) return !1;
            e(c).addClass("selected");
            this.r = a;
            b || this.focus();
            d.scrollTo(c[0], !0);
            this.w.fire("wgRowSelect", [a, f]);
            return this
        };
        c.clear = function() {
            var a = this._,
                b = this.c,
                c, d = b.length;
            a.parentNode.removeChild(a);
            for (c = 0; c < d; c++) b[c] = a.appendChild(a.removeChild(b[c]).cloneNode(!1));
            return this
        };
        c.render = function() {
            var a = this.f,
                b = this._,
                c = this.r,
                d = this._r,
                e = this.t,
                f, k = this.c,
                r, h, m = e.length,
                s, q = m && e[0].length || 0;
            if (a)
                for (m = a.length, h = 0; h < m; h++)
                    for (r =
                        a[h], f = e[r], s = 0; s < q; s++) k[s].appendChild(f[s]);
            else
                for (r = 0; r < m; r++)
                    for (f = e[r], s = 0; s < q; s++) k[s].appendChild(f[s]);
            this.p.appendChild(b);
            null == c ? null != d && this.visible(d) && (delete this._r, this.select(d, !0)) : a && !this.visible(c) ? (this.deselect(), this._r = c) : this.w.cells[1].scrollTo(e[c][0], !0);
            return this
        };
        c.promote = function() {
            this.p.style.backfaceVisibility = "hidden";
            return this
        };
        c.demote = function() {
            this.p.style.backfaceVisibility = "";
            return this
        };
        c.unfilter = function() {
            this.f && (this.f = null, this.clear().render());
            return this
        };
        c.filter = function(a) {
            this.f = a;
            return this.clear().render()
        };
        c = null;
        return d
    }({}, z, A));
    p.register("$21", function(d, c, n) {
        function h(a, b) {
            var c = a.id,
                d = c && y[c],
                e = d && d.parent();
            if (!d || !e) return null;
            var f = e.dir === u,
                c = f ? "X" : "Y",
                g = "page" + c,
                f = f ? k : m,
                h = f(e.el),
                c = b["offset" + c],
                l = e.el,
                n = l.className;
            null == c && (c = b[g] - f(a));
            c && (h += c);
            l.className = n + " is-resizing";
            return {
                done: function() {
                    l.className = n
                },
                move: function(a) {
                    e.resize(a[g] - h, d);
                    return !0
                }
            }
        }

        function a(a, c) {
            function d() {
                e(n).off("mousemove", f);
                w && (w.done(), w = null);
                return !0
            }

            function f(a) {
                w ? w.move(a) : d();
                return !0
            }
            if (w) return !0;
            w = h(a.target, a);
            if (!w) return !0;
            e(n).one("mouseup", d).on("mousemove", f);
            return b(a)
        }

        function f(a, b) {
            var c = b.type;
            "touchmove" === c ? w && w.move(b) : "touchstart" === c ? w = h(a.target, b) : "touchend" === c && w && (w.done(), w = null)
        }

        function b(a) {
            a.stopPropagation();
            a.preventDefault();
            return !1
        }

        function k(a, b) {
            b || (b = n.body);
            for (var c = a.offsetLeft || 0;
                (a = a.offsetParent) && a !== b;) c += a.offsetLeft || 0;
            return c
        }

        function m(a, b) {
            b || (b = n.body);
            for (var c =
                    a.offsetTop || 0;
                (a = a.offsetParent) && a !== b;) c += a.offsetTop || 0;
            return c
        }

        function l(a, b) {
            var c = e(b).on("editFocus", function(b) {
                c.trigger("wgFocus", [a])
            }).on("editBlur", function(a) {
                c.trigger("wgBlur")
            })
        }

        function t(a) {
            var b = this.id = a.id;
            this.el = a;
            this.pos = this.index = 0;
            this.css = ["wg-cell"];
            y[b] = this;
            this.clear()
        }
        var g = p.require("$2", "html.js"),
            u = 1,
            y = {},
            w = !1;
        d.init = function(b) {
            var c = new t(b);
            c.css.push("wg-root");
            c.redraw();
            p.require("$26", "touch.js").ok(function(a) {
                a.dragger(b, f)
            });
            e(b).mousedown(a);
            return c
        };
        c = t.prototype;
        c.fire = function(a, b) {
            var c = e.Event(a);
            c.cell = this;
            e(this.el).trigger(c, b);
            return this
        };
        c.each = function(a) {
            for (var b = -1, c = this.cells, d = this.length; ++b < d;) a(b, c[b]);
            return this
        };
        c.on = function() {
            return this.$("on", arguments)
        };
        c.off = function() {
            return this.$("off", arguments)
        };
        c.find = function(a) {
            return e(this.el).find(a)
        };
        c.$ = function(a, b) {
            e.fn[a].apply(e(this.el), b);
            return this
        };
        c.parent = function() {
            return this.pid && y[this.pid]
        };
        c.splitx = function() {
            return this._split(u, arguments)
        };
        c.splity =
            function() {
                return this._split(2, arguments)
            };
        c._split = function(a, b) {
            this.length && this.clear();
            for (var c = -1, d, e = b.length, f = 1 / e, k = 0; ++c < e;) {
                d = g.el();
                this.body.appendChild(d);
                for (var h = d, l = b[c], m = l, n = 1; y[l];) l = m + "-" + ++n;
                h.id = l;
                d = new t(d);
                d.index = c;
                d.pid = this.id;
                d.pos = k;
                k += f;
                this.cells.push(d);
                this.length++
            }
            this.dir = a;
            this.redraw();
            return this.cells
        };
        c.destroy = function() {
            this.clear();
            delete y[this.id];
            var a = this.el;
            a.innerHTML = "";
            e(a).off();
            return this
        };
        c.exists = function() {
            return this === y[this.id]
        };
        c.clear =
            function() {
                for (var a = this.el, b = this.lang, c = this.cells, d = this.field, f = this.body, q = this.nav, k = this.length || 0; 0 !== k--;) delete y[c[k].destroy().id];
                this.cells = [];
                this.length = 0;
                q && (a.removeChild(q), this.nav = null);
                f && (d && (g.ie() && e(f).triggerHandler("blur"), d.unlisten(), this.field = null), this.table && (this.table = null), a.removeChild(f));
                this.body = a.appendChild(g.el("", "wg-body"));
                b && this.locale(b);
                return this
            };
        c.resize = function(a, b) {
            if (!b && (b = this.cells[1], !b)) return;
            var c = b.index,
                d = this.cells;
            this.parent();
            var f = e(this.el)[this.dir === u ? "width" : "height"](),
                q = d[c + 1],
                c = d[c - 1];
            pad = (b.body || b.el.firstChild).offsetTop || 0;
            max = (q ? q.pos * f : f) - pad;
            min = c ? c.pos * f : 0;
            b.pos = Math.min(max, Math.max(min, a)) / f;
            this.redraw();
            return this
        };
        c.distribute = function() {
            for (var a, b = 0, c = this.cells, d = arguments.length; b < d;) a = arguments[b], c[++b].pos = Math.max(0, Math.min(1, a));
            this.redraw();
            return this
        };
        c.distribution = function() {
            for (var a = [], b = 0, c = this.cells, d = c.length - 1; b < d;) a[b] = c[++b].pos;
            return a
        };
        c.restyle = function() {
            var a = this.css.concat();
            0 === this.index ? a.push("first") : a.push("not-first");
            this.dir && (a.push("wg-split"), 2 === this.dir ? a.push("wg-split-y") : a.push("wg-split-x"));
            this.t && a.push("has-title");
            this.nav && a.push("has-nav");
            null != this.field && (a.push("is-field"), this.field ? a.push("is-editable") : a.push("is-readonly"));
            a = a.join(" ");
            a !== this._css && (this._css = this.el.className = a);
            return this
        };
        c.redraw = function() {
            this.restyle();
            var a = this.el,
                b = this.body;
            if (b) {
                var c = a.clientHeight || 0,
                    d = b.offsetTop || 0;
                d < c && (c -= d);
                b.style.height = String(c) +
                    "px"
            }
            for (var b = this.length, f = 1, q = this.nav, g = 2 === this.dir ? "height" : "width"; 0 !== b--;) c = this.cells[b], q ? d = 1 : (c.fixed && (c.pos = c.fixed / e(a)[g]()), d = f - c.pos, f = c.pos), c.el.style[g] = String(100 * d) + "%", c.redraw();
            return this
        };
        c.contents = function(a) {
            var b = this.el,
                c = this.lang,
                d = this.body;
            if (null == a) return d.innerHTML;
            this.length ? this.clear() : d && (b.removeChild(d), d = null);
            d || (d = this.body = b.appendChild(g.el("", "wg-content")), c && this.locale(c));
            "string" === typeof a ? e(d)._html(a) : a && this.append(a);
            this.redraw();
            return this
        };
        c.textarea = function(a, b) {
            var c = this.field;
            b ? c ? a !== c.val() && this.field.val(a) : (c = g.el("textarea", "wg-field"), c.value = a, c.name = this.id, this.contents(c), this.field = p.require("$27", "basic.js").init(c).attr("wrap", "virtual"), this.restyle(), l(this, c)) : (this.contents(g.txt(a)), c && c.unlisten(), this.field = !1, this.restyle());
            return this
        };
        c.locale = function(a) {
            a = String(a);
            var b = a.split("_"),
                c = b[0],
                d = this.body;
            b[1] || (a = c);
            d && (d.setAttribute("lang", a.replace("_", "-")), d.setAttribute("dir", -1 !== "ar,ps,he,ur,ckb".indexOf(c) ?
                "RTL" : "LTR"));
            this.lang = a;
            return this
        };
        c.editable = function() {
            var a = this.field;
            if (!a || !a.editable())
                for (var b = -1, c = this.length; ++b < c && !(a = this.cells[b].editable()););
            return a
        };
        c.append = function(a) {
            a && (a.nodeType ? g.init(this.body.appendChild(a)) : g.init(e(a).appendTo(this.body)));
            return this
        };
        c.prepend = function(a) {
            var b = this.body;
            if (a.nodeType) {
                var c = b.firstChild;
                g.init(c ? b.insertBefore(a, c) : b.appendChild(a))
            } else g.init(e(a).prependTo(b));
            return this
        };
        c.header = function(a, b) {
            if (0 === arguments.length) return this.el.getElementsByTagName("h2")[0];
            var c = ["wg-title"];
            b && c.push(b);
            this.t = g.txt(a || "");
            this.el.insertBefore(g.el("h2", c.join(" ")), this.body).appendChild(this.t);
            this.redraw();
            return this
        };
        c.title = function(a) {
            var b = this.t;
            if (b) return b.nodeValue = a || "", b;
            this.header(a);
            return this.t
        };
        c.titled = function() {
            var a = this.t;
            return a && a.nodeValue
        };
        c.bodyY = function() {
            return m(this.body, this.el)
        };
        c.tabulate = function(a) {
            return this.table = p.require("$28", "wgtable.js").create(this).init(a)
        };
        c.lock = function() {
            this.body.className += " locked";
            return this
        };
        c.scrollTo = function(a, b) {
            var c, d = this.body;
            c = d.scrollTop;
            var f = m(a, d);
            if (c > f) c = f;
            else {
                var q = d.clientHeight,
                    f = f + e(a).outerHeight();
                if (q + c < f) c = f - q;
                else return
            }
            b ? d.scrollTop = c : e(d).stop(!0).animate({
                scrollTop: c
            }, 250)
        };
        c.navigize = function(a, c) {
            function d(a) {
                var b = k[a],
                    c = l[a],
                    f = e(b.el).show();
                c.addClass("active");
                h = a;
                m.data("idx", a);
                b.fire("wgTabSelect", [a]);
                return f
            }
            var f = this,
                k = f.cells,
                q = f.nav,
                h, l = [];
            q && f.el.removeChild(q);
            var q = f.nav = f.el.insertBefore(g.el("nav", "wg-tabs"), f.body),
                m = e(q).on("click",
                    function(a) {
                        var c = e(a.target).data("idx");
                        if (null == c) return !0;
                        if (null != h) {
                            var q = l[h];
                            e(k[h].el).hide();
                            q.removeClass("active")
                        }
                        d(c);
                        f.redraw();
                        return b(a)
                    });
            null == c && (c = m.data("idx") || 0);
            f.each(function(b, c) {
                l[b] = e('<a href="#' + c.id + '"></a>').data("idx", b).text(a[b]).appendTo(m);
                c.pos = 0;
                e(c.el).hide()
            });
            d(k[c] ? c : 0);
            f.lock();
            f.redraw();
            return f
        };
        c.navigated = function() {
            var a = this.nav;
            if (a) return e(a).data("idx")
        };
        c = null;
        return d
    }({}, z, A));
    p.register("$4", function(d, c, n) {
        function h(a) {
            var b = [];
            a && (a.saved() ||
                b.push("po-unsaved"), a.fuzzy() ? b.push("po-fuzzy") : a.flagged() && b.push("po-flagged"), a.translation() || b.push("po-empty"), a.comment() && b.push("po-comment"));
            return b.join(" ")
        }

        function a(a, b, c) {
            b = e(a.title(b).parentNode);
            var d = b.find("span").hide();
            c && (a.locale(c), a = c.icon, c = c.region, a || c && "ZZ" !== c) && (d.length || (d = e("<span></span>").prependTo(b)), d.attr("class", a || "flag flag-" + c.toLowerCase()).show())
        }

        function f() {}
        var b = "poUpdate",
            k = "changing",
            m = "changed",
            l = 0,
            t = 1,
            g = 2,
            u = 3,
            y = 4,
            w = 5,
            v = /^[ \t\n\r]/,
            r, z;
        d.extend = function(a) {
            return a.prototype = new f
        };
        d.localise = function(a, b) {
            return r = p.require("$19", "t.js").create(a || {}, b)
        };
        var A = function() {
                var a = n.createElement("p");
                return function(b) {
                    a.innerHTML = b;
                    return a.textContent
                }
            }(),
            s = f.prototype = p.require("$20", "abstract.js").init(["getListColumns", "getListHeadings", "getListEntry"], ["editable", "t"]);
        s.init = function() {
            this.localise();
            this.editable = {
                source: !0,
                target: !0
            };
            return this
        };
        s.localise = function(a) {
            this.t = a || (a = r || d.localise());
            var b = a._,
                c = this.labels = [];
            c[l] = b("Source text") + ":";
            c[u] = b("Translation") + ":";
            c[y] = b("%s translation") + ":";
            c[t] = b("Single") + ":";
            c[g] = b("Plural") + ":";
            c[w] = b("Context") + ":";
            return a
        };
        s.setRootCell = function(a) {
            function b() {
                d.redraw(!0);
                return !0
            }
            var d = p.require("$21", "wingrid.js").init(a);
            e(c).on("resize", b);
            e(a).on("wgFocus wgBlur", function(a, b) {
                z = b
            });
            this.destroy = function() {
                d.destroy();
                e(c).off("resize", b)
            };
            this.rootDiv = a;
            return d
        };
        s.on = function(a, b) {
            return e(this.rootDiv).on(a, b)
        };
        s.setListCell = function(a) {
            var b = this;
            b.listCell =
                a;
            a.on("wgRowSelect", function(a, c) {
                b.loadMessage(b.po.row(c));
                return !0
            }).on("wgRowDeselect", function(a, c, d) {
                d || b.loadNothing();
                return !0
            })
        };
        s.setSourceCell = function(a) {
            this.sourceCell = a;
            var b = a.find("p.notes");
            b.length || (b = e('<p class="notes"></p>').insertAfter(a.header()).hide());
            this.notesPara = b
        };
        s.next = function(a, b, c) {
            for (var d = this.listTable, e = d.selected(), f = e, g, k = this.po; null != (e = d.next(a, c, e));) {
                if (f === e) {
                    e = null;
                    break
                }
                if (b && (g = k.row(e), g.translated(0))) continue;
                break
            }
            null != e && d.select(e, !0);
            return e
        };
        s.current = function(a) {
            if (null == a) return this.active;
            a ? this.loadMessage(a) : this.unloadActive();
            return this
        };
        s.getTargetEditable = function() {
            return this.editable.target && this.targetCell && this.targetCell.editable()
        };
        s.getSourceEditable = function() {
            return this.editable.source && this.sourceCell && this.sourceCell.editable()
        };
        s.getContextEditable = function() {
            return this.editable.context && this.contextCell && this.contextCell.editable()
        };
        s.getFirstEditable = function() {
            return this.getTargetEditable() || this.getSourceEditable() ||
                this.getContextEditable()
        };
        s.searchable = function(a) {
            a && (this.dict = a, this.po && this.rebuildSearch());
            return this.dict && !0
        };
        s.rebuildSearch = function() {
            var a = this.dict;
            a.clear();
            this.po.each(function(b, c, d) {
                a.add(d, c.toText())
            });
            this.lastSearch = "";
            this.lastFound = this.po.length
        };
        s.filtered = function() {
            return this.lastSearch || ""
        };
        s.filter = function(a, b) {
            var c, d = {},
                e = this.listTable,
                f = this.lastFound,
                g = this.lastSearch;
            if (a) {
                if (g === a) return f || 0;
                if (g && !f && 0 === a.indexOf(g)) return 0;
                c = this.dict.find(a, d);
                d.words.length ||
                    (a = "")
            }
            g = this.lastSearch = a;
            f = this.lastFound = c ? c.length : this.po.length;
            c ? e.filter(c) : e.unfilter();
            b || this.fire("poFilter", [g, f]);
            return f
        };
        s.unsave = function(a, b) {
            var c = !1;
            if (a = a || self.active) {
                if (c = a.saved(b)) this.dirty = !0, a.unsave(b), this.fire("poUnsaved", [a, b]);
                this.markUnsaved(a)
            }
            return c
        };
        s.markUnsaved = function(a) {
            var b = this.po.indexOf(a),
                b = this.listTable.tr(b),
                c = b[0].className;
            changedStyle = c.replace(/(?:^| +)po-[a-z]+/g, "") + " " + h(a);
            changedStyle !== c && e(b).attr("class", changedStyle)
        };
        s.save = function(a) {
            var b =
                this.po;
            if (this.dirty || a) b.each(function(a, b) {
                b.save()
            }), this.listCell.find("div.po-unsaved").removeClass("po-unsaved"), this.dirty = !1, this.fire("poSave");
            return b
        };
        s.fire = function(a, b) {
            var c = this.on;
            if (c && c[a] && (c = c[a].apply(this, b || []), !1 === c)) return !1;
            c = e.Event(a);
            e(this.rootDiv).trigger(c, b);
            return !c.isDefaultPrevented()
        };
        s.reload = function() {
            var a = this,
                b = a.listCell,
                c = a.listTable,
                d = a.po,
                e = d && d.length || 0;
            if (d && d.row) {
                a.lastSearch && (a.lastSearch = "", a.lastFound = e, a.fire("poFilter", [a.lastSearch, a.lastFound]));
                var f = c && c.scroll(),
                    c = a.listTable = b.tabulate({
                        length: e,
                        getRow: function(b) {
                            return a.getListEntry(d.row(b))
                        },
                        getCss: function(b) {
                            return a.getListEntry(d.row(b))
                        },
                        eachCol: function(b) {
                            for (var c = -1, d = a.getListHeadings(), e = d.length; ++c < e;) b(c, d[c])
                        },
                        eachRow: function(b) {
                            var c = 0;
                            d.each(function(d, e) {
                                b(c++, a.getListEntry(e), h(e))
                            })
                        }
                    });
                f && c.scroll(f);
                a.targetLocale = a.po.locale();
                a.fire("poLoad");
                return !!a.po.length
            }
            b && b.clear().header("Error").contents("Invalid messages list")
        };
        s.load = function(a, b) {
            this.po =
                a;
            this.dict && this.rebuildSearch();
            this.reload() && -1 !== b && this.listTable.select(b || 0)
        };
        s.loadMessage = function(c) {
            function d() {
                var e, f = s,
                    n = s.id,
                    f = T[l];
                E && (N ? E.text(N).show() : E.text("").hide());
                s.titled() !== f && a(s, f, h.sourceLocale);
                J ? (e = s.splity(n + "-singular", n + "-plural"), f = e[0], e = e[1], f.header(T[t]).textarea(K, H), e.header(T[g]).textarea(J, H), s.lock()) : s.textarea(K, H);
                H && s.on(k, function(a, b) {
                    J && a.target.name === n + "-plural" ? c.plural(b) : (c.source(b), h.updateListCell(c, "source"));
                    h.unsave(c, r)
                }).on(m, function(a) {
                    J &&
                        a.target.name === n + "-plural" || h.po.reIndex(c);
                    h.dict && h.rebuildSearch();
                    h.fire(b, [c])
                })
            }

            function e(d, f) {
                var g = d.label,
                    g = g && -1 === g.indexOf("Unknown") ? U(T[y], g) : T[u];
                v.titled() !== g && a(v, g, d);
                if (c.pluralized()) {
                    var l = [],
                        n = d.plurals || ["One", "Other"],
                        t = p.require("$16", "collection.js").init();
                    for (c.each(function(a, b, c) {
                            if (c || n[a]) l.push(n[a] || "Form " + a), t.add("plural-" + a, c)
                        });
                        (g = l.length) < d.nplurals;) l.push(n[g] || "Form " + t.length), t.add("plural-" + g, c.translation(g));
                    children = v.splitx.apply(v, t.keys);
                    v.each(function(a,
                        b) {
                        var d = D && !c.disabled(a);
                        b.textarea(t[a], d)
                    });
                    v.navigize(l, f || null).on("wgTabSelect", function(a, b) {
                        var c = D && a.cell.editable();
                        c && c.focus();
                        f = b;
                        h.fire("poTab", [b])
                    })
                } else D = D && !c.disabled(0), v.textarea(c.translation(), D);
                D && v.on(k, function(a, b) {
                    c.translate(b, f);
                    0 === f && h.updateListCell(c, "target");
                    c.fuzzy(f) ? h.fuzzy(!1, c, f) : h.unsave(c, f)
                }).on(m, function(a) {
                    h.dict && h.rebuildSearch();
                    h.fire(b, [c])
                })
            }

            function f() {
                a(A, T[w]);
                A.textarea(c.context(), !0);
                L && A.on(k, function(a, b) {
                    c.context(b);
                    h.updateListCell(c,
                        "source");
                    h.unsave(c, r)
                }).on(m, function() {
                    h.po.reIndex(c);
                    h.dict && h.rebuildSearch();
                    h.fire(b, [c])
                })
            }
            var h = this,
                n = c === h.active,
                r = 0,
                s = h.sourceCell,
                v = h.targetCell,
                A = h.contextCell,
                B = h.commentCell,
                E = h.notesPara,
                D = h.editable.target,
                H = h.editable.source,
                L = h.editable.context,
                K = c.source() || "",
                J = c.plural() || "",
                N = c.notes(),
                I = z,
                M = n && I,
                Q = h.targetLocale,
                U = h.t.s,
                T = h.labels;
            n || (h.active = c);
            s && s !== M && (s.off().clear(), d());
            A && A !== M && (A.off().clear(), f());
            v && Q && v !== M && (r = v.navigated() || 0, v.off().clear(), e(Q, r));
            if (B &&
                B !== M) B.off().clear().textarea(c.comment(), !0).on(k, function(a, b) {
                c.comment(b);
                h.fire("poComment", [c, b]);
                h.unsave(c, r)
            });
            I && (I.exists() || (I = I.parent()), (B = I.editable()) && B.focus());
            n || h.fire("poSelected", [c])
        };
        s.unloadActive = function() {
            var a;
            (a = this.notesPara) && a.text("").hide();
            (a = this.sourceCell) && a.off().clear();
            (a = this.contextCell) && a.off().clear();
            (a = this.targetCell) && a.off().clear();
            (a = this.commentCell) && a.off();
            this.active && (this.fire("poDeselected", [this.active]), this.active = null);
            return this
        };
        s.loadNothing = function() {
            var a, b = this.t._;
            this.unloadActive();
            (a = this.commentCell) && a.textarea("", !1);
            (a = this.sourceCell) && a.textarea("", !1).title(b("Source text not loaded") + ":");
            (a = this.contextCell) && a.textarea("", !1).title(b("Context not loaded") + ":");
            (a = this.targetCell) && a.textarea("", !1).title(b("Translation not loaded") + ":");
            this.fire("poSelected", [null])
        };
        s.updateListCell = function(a, b) {
            var c = this.getListColumns()[b],
                d = this.getListEntry(a)[c || 0],
                e = this.po.indexOf(a);
            this.listTable.td(e, c).textContent =
                d
        };
        s.cellText = function(a) {
            if (-1 !== a.indexOf("<") || -1 !== a.indexOf("&")) a = A(a);
            "" === a ? a = "\u00a0" : v.test(a) && (a = "\u00a0" + a);
            return a
        };
        s.fuzzy = function(a, c, d) {
            if (!c) {
                c = this.active;
                if (!c) return null;
                null == d && (d = this.targetCell && this.targetCell.navigated() || 0)
            }
            var e = c.fuzzy(d);
            null != a && e != a && this.fire("poFuzzy", [c, a, d]) && (c.fuzzy(d, a), this.fire(b, [c]) && this.unsave(c, d));
            return e
        };
        s.add = function(a, c) {
            var d, e = this.po.get(a, c);
            e ? d = this.po.indexOf(e) : (d = this.po.length, e = this.po.add(a, c), this.load(this.po),
                this.fire("poAdd", [e]), this.fire(b, [e]));
            this.lastSearch && this.filter("");
            this.listTable.select(d);
            return e
        };
        s.del = function(a) {
            if (a = a || this.active) {
                var c = this.lastSearch,
                    d = this.po.del(a);
                null != d && (this.unsave(a), this.fire("poDel", [a]), this.fire(b, [a]), this.reload(), this.dict && this.rebuildSearch(), this.active && this.active.equals(a) && this.unloadActive(), this.po.length && (c && this.filter(c), this.active || (d = Math.min(d, this.po.length - 1), this.listTable.select(d))))
            }
        };
        s = null;
        return d
    }({}, z, A));
    p.register("$6", {
        "\u00e1": "a",
        "\u00e0": "a",
        "\u0103": "a",
        "\u1eaf": "a",
        "\u1eb1": "a",
        "\u1eb5": "a",
        "\u1eb3": "a",
        "\u00e2": "a",
        "\u1ea5": "a",
        "\u1ea7": "a",
        "\u1eab": "a",
        "\u1ea9": "a",
        "\u01ce": "a",
        "\u00e5": "a",
        "\u01fb": "a",
        "\u00e4": "a",
        "\u01df": "a",
        "\u00e3": "a",
        "\u0227": "a",
        "\u01e1": "a",
        "\u0105": "a",
        "\u0101": "a",
        "\u1ea3": "a",
        "\u0201": "a",
        "\u0203": "a",
        "\u1ea1": "a",
        "\u1eb7": "a",
        "\u1ead": "a",
        "\u1e01": "a",
        "\u01fd": "\u00e6",
        "\u01e3": "\u00e6",
        "\u1e03": "b",
        "\u1e05": "b",
        "\u1e07": "b",
        "\u0107": "c",
        "\u0109": "c",
        "\u010d": "c",
        "\u010b": "c",
        "\u00e7": "c",
        "\u1e09": "c",
        "\u010f": "d",
        "\u1e0b": "d",
        "\u1e11": "d",
        "\u0111": "d",
        "\u1e0d": "d",
        "\u1e13": "d",
        "\u1e0f": "d",
        "\u00f0": "d",
        "\ua77a": "d",
        "\u01c6": "\u01f3",
        "\u00e9": "e",
        "\u00e8": "e",
        "\u0115": "e",
        "\u00ea": "e",
        "\u1ebf": "e",
        "\u1ec1": "e",
        "\u1ec5": "e",
        "\u1ec3": "e",
        "\u011b": "e",
        "\u00eb": "e",
        "\u1ebd": "e",
        "\u0117": "e",
        "\u0229": "e",
        "\u1e1d": "e",
        "\u0119": "e",
        "\u0113": "e",
        "\u1e17": "e",
        "\u1e15": "e",
        "\u1ebb": "e",
        "\u0205": "e",
        "\u0207": "e",
        "\u1eb9": "e",
        "\u1ec7": "e",
        "\u1e19": "e",
        "\u1e1b": "e",
        "\u1e1f": "f",
        "\ua77c": "f",
        "\u01f5": "g",
        "\u011f": "g",
        "\u011d": "g",
        "\u01e7": "g",
        "\u0121": "g",
        "\u0123": "g",
        "\u1e21": "g",
        "\ua7a1": "g",
        "\u1d79": "g",
        "\u0125": "h",
        "\u021f": "h",
        "\u1e27": "h",
        "\u1e23": "h",
        "\u1e29": "h",
        "\u0127": "h",
        "\u210f": "h",
        "\u1e25": "h",
        "\u1e2b": "h",
        "\u1e96": "h",
        "\u00ed": "i",
        "\u00ec": "i",
        "\u012d": "i",
        "\u00ee": "i",
        "\u01d0": "i",
        "\u00ef": "i",
        "\u1e2f": "i",
        "\u0129": "i",
        "\u012f": "i",
        "\u012b": "i",
        "\u1ec9": "i",
        "\u0209": "i",
        "\u020b": "i",
        "\u1ecb": "i",
        "\u1e2d": "i",
        "\u0135": "j",
        "\u01f0": "j",
        "\u1e31": "k",
        "\u01e9": "k",
        "\u0137": "k",
        "\ua7a3": "k",
        "\u1e33": "k",
        "\u1e35": "k",
        "\u013a": "l",
        "\u013e": "l",
        "\u013c": "l",
        "\u0142": "l",
        "\u1e37": "l",
        "\u1e39": "l",
        "\u1e3d": "l",
        "\u1e3b": "l",
        "\u0140": "l",
        "\u1e3f": "m",
        "\u1e41": "m",
        "\u1e43": "m",
        "\u0144": "n",
        "\u01f9": "n",
        "\u0148": "n",
        "\u00f1": "n",
        "\u1e45": "n",
        "\u0146": "n",
        "\ua7a5": "n",
        "\u1e47": "n",
        "\u1e4b": "n",
        "\u1e49": "n",
        "\u00f3": "o",
        "\u00f2": "o",
        "\u014f": "o",
        "\u00f4": "o",
        "\u1ed1": "o",
        "\u1ed3": "o",
        "\u1ed7": "o",
        "\u1ed5": "o",
        "\u01d2": "o",
        "\u00f6": "o",
        "\u022b": "o",
        "\u0151": "o",
        "\u00f5": "o",
        "\u1e4d": "o",
        "\u1e4f": "o",
        "\u022d": "o",
        "\u022f": "o",
        "\u0231": "o",
        "\u00f8": "o",
        "\u01ff": "o",
        "\u01eb": "o",
        "\u01ed": "o",
        "\u014d": "o",
        "\u1e53": "o",
        "\u1e51": "o",
        "\u1ecf": "o",
        "\u020d": "o",
        "\u020f": "o",
        "\u01a1": "o",
        "\u1edb": "o",
        "\u1edd": "o",
        "\u1ee1": "o",
        "\u1edf": "o",
        "\u1ee3": "o",
        "\u1ecd": "o",
        "\u1ed9": "o",
        "\u1e55": "p",
        "\u1e57": "p",
        "\u0155": "r",
        "\u0159": "r",
        "\u1e59": "r",
        "\u0157": "r",
        "\ua7a7": "r",
        "\u0211": "r",
        "\u0213": "r",
        "\u1e5b": "r",
        "\u1e5d": "r",
        "\u1e5f": "r",
        "\ua783": "r",
        "\u015b": "s",
        "\u1e65": "s",
        "\u015d": "s",
        "\u0161": "s",
        "\u1e67": "s",
        "\u1e61": "s",
        "\u015f": "s",
        "\ua7a9": "s",
        "\u1e63": "s",
        "\u1e69": "s",
        "\u0219": "s",
        "\u017f": "s",
        "\ua785": "s",
        "\u1e9b": "s",
        "\u0165": "t",
        "\u1e97": "t",
        "\u1e6b": "t",
        "\u0163": "t",
        "\u1e6d": "t",
        "\u021b": "t",
        "\u1e71": "t",
        "\u1e6f": "t",
        "\ua787": "t",
        "\u00fa": "u",
        "\u00f9": "u",
        "\u016d": "u",
        "\u00fb": "u",
        "\u01d4": "u",
        "\u016f": "u",
        "\u00fc": "u",
        "\u01d8": "u",
        "\u01dc": "u",
        "\u01da": "u",
        "\u01d6": "u",
        "\u0171": "u",
        "\u0169": "u",
        "\u1e79": "u",
        "\u0173": "u",
        "\u016b": "u",
        "\u1e7b": "u",
        "\u1ee7": "u",
        "\u0215": "u",
        "\u0217": "u",
        "\u01b0": "u",
        "\u1ee9": "u",
        "\u1eeb": "u",
        "\u1eef": "u",
        "\u1eed": "u",
        "\u1ef1": "u",
        "\u1ee5": "u",
        "\u1e73": "u",
        "\u1e77": "u",
        "\u1e75": "u",
        "\u1e7d": "v",
        "\u1e7f": "v",
        "\u1e83": "w",
        "\u1e81": "w",
        "\u0175": "w",
        "\u1e98": "w",
        "\u1e85": "w",
        "\u1e87": "w",
        "\u1e89": "w",
        "\u1e8d": "x",
        "\u1e8b": "x",
        "\u00fd": "y",
        "\u1ef3": "y",
        "\u0177": "y",
        "\u1e99": "y",
        "\u00ff": "y",
        "\u1ef9": "y",
        "\u1e8f": "y",
        "\u0233": "y",
        "\u1ef7": "y",
        "\u1ef5": "y",
        "\u017a": "z",
        "\u1e91": "z",
        "\u017e": "z",
        "\u017c": "z",
        "\u1e93": "z",
        "\u1e95": "z",
        "\u01ef": "\u0292",
        "\u1f00": "\u03b1",
        "\u1f04": "\u03b1",
        "\u1f84": "\u03b1",
        "\u1f02": "\u03b1",
        "\u1f82": "\u03b1",
        "\u1f06": "\u03b1",
        "\u1f86": "\u03b1",
        "\u1f80": "\u03b1",
        "\u1f01": "\u03b1",
        "\u1f05": "\u03b1",
        "\u1f85": "\u03b1",
        "\u1f03": "\u03b1",
        "\u1f83": "\u03b1",
        "\u1f07": "\u03b1",
        "\u1f87": "\u03b1",
        "\u1f81": "\u03b1",
        "\u03ac": "\u03b1",
        "\u1f71": "\u03b1",
        "\u1fb4": "\u03b1",
        "\u1f70": "\u03b1",
        "\u1fb2": "\u03b1",
        "\u1fb0": "\u03b1",
        "\u1fb6": "\u03b1",
        "\u1fb7": "\u03b1",
        "\u1fb1": "\u03b1",
        "\u1fb3": "\u03b1",
        "\u1f10": "\u03b5",
        "\u1f14": "\u03b5",
        "\u1f12": "\u03b5",
        "\u1f11": "\u03b5",
        "\u1f15": "\u03b5",
        "\u1f13": "\u03b5",
        "\u03ad": "\u03b5",
        "\u1f73": "\u03b5",
        "\u1f72": "\u03b5",
        "\u1f20": "\u03b7",
        "\u1f24": "\u03b7",
        "\u1f94": "\u03b7",
        "\u1f22": "\u03b7",
        "\u1f92": "\u03b7",
        "\u1f26": "\u03b7",
        "\u1f96": "\u03b7",
        "\u1f90": "\u03b7",
        "\u1f21": "\u03b7",
        "\u1f25": "\u03b7",
        "\u1f95": "\u03b7",
        "\u1f23": "\u03b7",
        "\u1f93": "\u03b7",
        "\u1f27": "\u03b7",
        "\u1f97": "\u03b7",
        "\u1f91": "\u03b7",
        "\u03ae": "\u03b7",
        "\u1f75": "\u03b7",
        "\u1fc4": "\u03b7",
        "\u1f74": "\u03b7",
        "\u1fc2": "\u03b7",
        "\u1fc6": "\u03b7",
        "\u1fc7": "\u03b7",
        "\u1fc3": "\u03b7",
        "\u1f30": "\u03b9",
        "\u1f34": "\u03b9",
        "\u1f32": "\u03b9",
        "\u1f36": "\u03b9",
        "\u1f31": "\u03b9",
        "\u1f35": "\u03b9",
        "\u1f33": "\u03b9",
        "\u1f37": "\u03b9",
        "\u03af": "\u03b9",
        "\u1f77": "\u03b9",
        "\u1f76": "\u03b9",
        "\u1fd0": "\u03b9",
        "\u1fd6": "\u03b9",
        "\u03ca": "\u03b9",
        "\u0390": "\u03b9",
        "\u1fd3": "\u03b9",
        "\u1fd2": "\u03b9",
        "\u1fd7": "\u03b9",
        "\u1fd1": "\u03b9",
        "\u1f40": "\u03bf",
        "\u1f44": "\u03bf",
        "\u1f42": "\u03bf",
        "\u1f41": "\u03bf",
        "\u1f45": "\u03bf",
        "\u1f43": "\u03bf",
        "\u03cc": "\u03bf",
        "\u1f79": "\u03bf",
        "\u1f78": "\u03bf",
        "\u1fe4": "\u03c1",
        "\u1fe5": "\u03c1",
        "\u1f50": "\u03c5",
        "\u1f54": "\u03c5",
        "\u1f52": "\u03c5",
        "\u1f56": "\u03c5",
        "\u1f51": "\u03c5",
        "\u1f55": "\u03c5",
        "\u1f53": "\u03c5",
        "\u1f57": "\u03c5",
        "\u03cd": "\u03c5",
        "\u1f7b": "\u03c5",
        "\u1f7a": "\u03c5",
        "\u1fe0": "\u03c5",
        "\u1fe6": "\u03c5",
        "\u03cb": "\u03c5",
        "\u03b0": "\u03c5",
        "\u1fe3": "\u03c5",
        "\u1fe2": "\u03c5",
        "\u1fe7": "\u03c5",
        "\u1fe1": "\u03c5",
        "\u1f60": "\u03c9",
        "\u1f64": "\u03c9",
        "\u1fa4": "\u03c9",
        "\u1f62": "\u03c9",
        "\u1fa2": "\u03c9",
        "\u1f66": "\u03c9",
        "\u1fa6": "\u03c9",
        "\u1fa0": "\u03c9",
        "\u1f61": "\u03c9",
        "\u1f65": "\u03c9",
        "\u1fa5": "\u03c9",
        "\u1f63": "\u03c9",
        "\u1fa3": "\u03c9",
        "\u1f67": "\u03c9",
        "\u1fa7": "\u03c9",
        "\u1fa1": "\u03c9",
        "\u03ce": "\u03c9",
        "\u1f7d": "\u03c9",
        "\u1ff4": "\u03c9",
        "\u1f7c": "\u03c9",
        "\u1ff2": "\u03c9",
        "\u1ff6": "\u03c9",
        "\u1ff7": "\u03c9",
        "\u1ff3": "\u03c9",
        "\u0491": "\u0433",
        "\u0450": "\u0435",
        "\u0451": "\u0435",
        "\u04c2": "\u0436",
        "\u045d": "\u0438",
        "\u04e3": "\u0438",
        "\u04ef": "\u0443"
    });
    p.register("$8", function(d, c, e) {
        function h() {
            this.init()._validate();
            var a = this.t._;
            this.sourceLocale = {
                lang: "en",
                label: "English",
                plurals: [a("Single"), a("Plural")]
            }
        }
        d.init = function(a) {
            var c = new h,
                b = c.t._;
            a = c.setRootCell(a);
            var d = a.splity("po-list", "po-edit"),
                e = d[0],
                l = d[1],
                d = l.splitx("po-trans", "po-comment"),
                n = d[0],
                g = d[1].header(b("Comments") + ":"),
                d = n.splity("po-source", "po-target"),
                n = d[0].header(b("Source text") + ":"),
                b = d[1].header(b("Translation") + ":");
            a.distribute(0.34);
            l.distribute(0.8);
            c.setListCell(e);
            c.setSourceCell(n);
            c.targetCell = b;
            c.commentCell = g;
            c.editable.source = !1;
            return c
        };
        c = h.prototype = p.require("$4", "base.js").extend(h);
        c.getListHeadings = function() {
            return [this.t._("Source text"), this.t._("Translation")]
        };
        c.getListColumns = function() {
            return {
                source: 0,
                target: 1
            }
        };
        c.getListEntry = function(a) {
            if (!a) return ["", ""];
            var c = this.cellText,
                b = [c(a.id || a.source() || ""), c(a.translation() || "")];
            if (a = a.context()) b[0] += " [ " + c(a) + " ]";
            return b
        };
        c.stats = function() {
            var a = this.po.length,
                c = 0,
                b = 0,
                d = 0;
            this.po.each(function(a, e) {
                e.translation() ? e.fuzzy() ? d++ : c++ : b++
            });
            return {
                t: a,
                p: String(c ? Math.round(100 * (c / a)) : 0) + "%",
                f: d,
                u: b
            }
        };
        c.unlock = function() {
            this._unlocked || (this._unlocked = this.targetLocale, delete this.targetLocale, this.po && this.po.unlock(), this.editable = {
                source: !0,
                context: !0,
                target: !1
            }, this.contextCell = this.targetCell, delete this.targetCell, this.fire("poLock", [!1]), this.active && this.loadMessage(this.active))
        };
        c.lock = function() {
            var a;
            this._unlocked && (a = this.targetLocale = this._unlocked, delete this._unlocked, this.po && this.po.lock(a), this.editable = {
                source: !1,
                context: !1,
                target: !0
            }, this.targetCell = this.contextCell, delete this.contextCell, this.fire("poLock", [!0, a]), this.active && this.loadMessage(this.active))
        };
        c.locked = function() {
            return !this._unlocked
        };
        return d
    }({}, z, A));
    p.register("$9", function(d, c, n) {
        function h(a) {
            (a || (a = B.getFirstEditable())) && a.fire();
            return a
        }
        var a = {
                copy: 66,
                clear: 75,
                save: 83,
                fuzzy: 85,
                next: 40,
                prev: 38,
                enter: 13
            },
            f = {
                38: !0,
                40: !0
            },
            b = {
                66: function(a, b) {
                    var c = b.current(),
                        d;
                    c && (c.normalize(), b.current(!1).current(c), (d = b.getTargetEditable()) && h(d))
                },
                75: function(a,
                    b) {
                    var c = b.current(),
                        d;
                    c && (c.untranslate(), b.current(!1).current(c), (d = b.getTargetEditable()) && h(d))
                },
                85: function(a, b) {
                    b.fuzzy(!b.fuzzy())
                },
                13: function(a, b) {
                    b.getFirstEditable() && b.next(1, !0, !0)
                },
                40: function(a, b) {
                    var c = a.shiftKey;
                    b.next(1, c, c)
                },
                38: function(a, b) {
                    var c = a.shiftKey;
                    b.next(-1, c, c)
                }
            };
        d.init = function(d, h) {
            function l(a) {
                if (a.isPropagationStopped() || !a.metaKey && !a.ctrlKey) return !0;
                var c = a.which;
                if (!n[c]) return !0;
                var e = b[c];
                if (!e) throw Error("No such command");
                if (a.altKey || a.shiftKey && !f[c]) return !0;
                e(a, d);
                a.preventDefault();
                return !1
            }
            var n = {};
            e(h || c).on("keydown", l);
            return {
                add: function(c, d) {
                    b[a[c]] = d;
                    return this
                },
                enable: function() {
                    var b, c;
                    for (c in arguments) b = a[arguments[c]], n[b] = !0;
                    return this
                },
                disable: function() {
                    e(h || c).off("keydown", l);
                    d = h = n = null
                }
            }
        };
        return d
    }({}, z, A));
    p.require("$1", "array.js");
    p.require("$2", "html.js");
    var I = z.loco,
        D = I.t,
        N = I.killEvent,
        Y = I.showError,
        $ = I.showSuccess,
        Z = z.ajaxurl || "/wp-admin/admin-ajax.php";
    M = I.conf || {};
    var Q = M.locale,
        L = p.require("$3", "po.js").create(Q),
        H = {},
        J = A.getElementById("loco-poedit-inner"),
        W = p.require("$4", "base.js").localise({}),
        K = W.s;
    W._ = W._n = D;
    e(z).resize(function() {
        function d() {
            var d;
            d = J;
            for (var a = d.offsetTop || 0;
                (d = d.offsetParent) && void 0 !== d;) a += d.offsetTop || 0;
            d = a;
            a = e(z).innerHeight();
            d = Math.max(n, a - d - c);
            J.style.height = String(d) + "px";
            return !0
        }
        var c = 20,
            n = parseInt(e(J).css("min-height") || 0);
        d();
        return d
    }());
    H.save = function(d) {
        function c() {
            d.disabled = !1
        }
        c();
        B.on("poUnsaved", function() {
            e(d).addClass("button-primary loco-flagged")
        }).on("poSave",
            function() {
                e(d).removeClass("button-primary loco-flagged")
            });
        e(d.form).submit(function(n) {
            var h = n.target;
            n.target.po.value = L.toString();
            d.disabled = !0;
            X(h, function(a) {
                c();
                e("#loco-po-modified").text(a.modified);
                B.save(!0);
                var d = D("PO file saved"),
                    b = a.filename;
                a = a.compiled;
                null != a && (/\D/.test(a) ? Y(a) : (d += " " + D("and MO file compiled"), b = b.replace(/\.po$/i, ".mo")));
                $(d + " - " + b)
            }, c);
            return N(n)
        });
        return !0
    };
    H.download_po = H.download_mo = function(d) {
        d.disabled = !1;
        d.form.setAttribute("action", Z);
        e(d).click(function(c) {
            d.form.po.value =
                L.toString();
            e(J).trigger("poSave");
            return !0
        });
        return !0
    };
    H.add = !Q && function(d) {
        d.disabled = !1;
        e(d).click(function(c) {
            var d = 1,
                e;
            for (e = "New message"; L.get(e);) d = /(\d+)/.exec(e) ? Math.max(d, RegExp.$1) : d, e = "New message " + ++d;
            B.add(e);
            return N(c)
        });
        return !0
    };
    H.del = !Q && function(d) {
        d.disabled = !1;
        e(d).click(function(c) {
            B.del();
            return N(c)
        });
        return !0
    };
    H.fuzzy = Q && function(d) {
        function c(c) {
            d.disabled = null == c;
            e(d)[c ? "addClass" : "removeClass"]("loco-inverted")
        }
        B.on("poSelected", function(d, e) {
            c(e && B.locked() ? e.fuzzy() :
                null)
        }).on("poFuzzy", function(d, e, a) {
            c(B.locked() ? a : null)
        });
        e(d).click(function(c) {
            var d = !B.fuzzy();
            B.fuzzy(d);
            return N(c)
        });
        return !0
    };
    H.sync = function(d) {
        function c() {
            d.disabled = !1
        }
        c();
        B.on("poUnsaved", function() {
            d.disabled = !0
        }).on("poSave", function() {
            c()
        });
        e(d.form).submit(function(n) {
            d.disabled = !0;
            X(n.target, function(d) {
                    c();
                    var a = p.require("$3", "po.js").create(void 0);
                    a.load(d.exp);
                    var f = [],
                        a = L.merge(a),
                        b = a.add.length,
                        k = a.del.length;
                    B.load(L);
                    if (b || k) {
                        f.push(d.pot ? K(D("Merged from %s"), d.pot) : D("Merged from source code"));
                        b && f.push(K(D("1 new string added", "%s new strings added", b), b));
                        k && f.push(K(D("1 obsolete string removed", "%s obsolete strings removed", k), k));
                        e(J).trigger("poUnsaved", []);
                        if (z.console && console.log) {
                            for (b = -1; ++b < a.add.length;) console.log(" + " + a.add[b].source());
                            for (b = -1; ++b < a.del.length;) console.log(" - " + a.del[b].source())
                        }
                        U()
                    } else f.push(d.pot ? K(D("Already up to date with %s"), d.pot) : D("Already up to date with source code"));
                    L.headers(d.headers || {});
                    $(f.join(". "));
                    e(J).trigger("poMerge", [d])
                },
                c);
            return N(n)
        });
        return !0
    };
    H.revert = function(d) {
        B.on("poUnsaved", function() {
            d.disabled = !1
        }).on("poSave", function() {
            d.disabled = !0
        });
        e(d).click(function(c) {
            location.reload();
            return N(c)
        });
        return !0
    };
    H.help = function() {
        return !0
    };
    J.innerHTML = "";
    var B = p.require("$8", "poedit.js").init(J);
    B._validate("POEdit");
    p.require("$9", "hotkeys.js").init(B).add("save", function(d, c) {
        e("#loco-poedit-save").submit()
    }).enable("copy", "clear", "enter", "next", "prev", "fuzzy", "save");
    e("#loco-nav").find("button").each(function(d,
        c) {
        var n = c.getAttribute("data-loco");
        H[n] && H[n](c) || e(c).hide()
    });
    H = null;
    (function(d) {
        function c(a) {
            e(d.parentNode)[a || null == a ? "removeClass" : "addClass"]("invalid")
        }
        e(d.form).submit(function(a) {
            return N(a)
        });
        var n = p.require("$5", "dict.js").create();
        n.depth = 10;
        n.translit(p.require("$6", "flatten.json"));
        B.searchable(n);
        d.disabled = !1;
        d.value = "";
        var h = p.require("$7", "LocoTextListener.js").listen(d, function(a) {
            a = B.filter(a, !0);
            c(a)
        });
        B.on("poFilter", function(a, d, b) {
            h.val(d || "");
            c(b)
        }).on("poMerge", function(a,
            c) {
            var b = h.val();
            b && B.filter(b)
        });
        n = null
    })(A.getElementById("loco-search"));
    B.on("poUnsaved", function() {
        z.onbeforeunload = function() {
            return D("Your changes will be lost if you continue without saving")
        }
    }).on("poSave", function() {
        z.onbeforeunload = null
    }).on("poUpdate", U);
    L.load(M.po || M.pot || {});
    L.headers(M.headers);
    B.load(L);
    B.targetLocale || B.unlock();
    U()
})(window, document, window.jQuery);
