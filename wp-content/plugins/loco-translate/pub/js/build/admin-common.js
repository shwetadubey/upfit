! function(e, h, c) {
    function m(b) {
        b.stopPropagation();
        b.preventDefault();
        return !1
    }

    function n(b, a) {
        function d() {
            g();
            f = setTimeout(function() {
                c(b).fadeOut(1E3, a)
            }, e)
        }

        function g() {
            f && clearTimeout(f);
            f = null
        }
        var f, e = 5E3;
        d();
        c(b).mouseenter(g).mouseleave(d)
    }

    function k(b, a) {
        function d(a) {
            c(b).remove();
            c(e).triggerHandler("resize");
            return a && m(a)
        }
        c('<a class="dismiss" href="#">&times;</a>').appendTo(b).click(d);
        a || n(b, d)
    }
    c("#wpbody-content").find("div.loco-message").each(function(b, a) {
        k(a, !0)
    });
    var a = e.loco ||
        (e.loco = {});
    a.killEvent = m;
    a.initMessage = k;
    a.showMessage = function(b, a, d, g) {
        var f = "loco-js-" + d;
        d = h.getElementById(f) || c('<div id="' + f + '" class="loco-message ' + (g || d) + '"></div>').insertBefore(c("#loco-poedit"));
        b = c(h.createElement("p")).text(b);
        a = c(h.createElement("strong")).text(a + ": ");
        b.prepend(a).appendTo(c(d).html(""));
        c(e).triggerHandler("resize");
        k(d);
        c("div.loco-warning").remove()
    };
    a.showError = function(b) {
        return a.showMessage(b, l("Error"), "error")
    };
    a.showWarning = function(b) {
        return a.showMessage(b,
            l("Warning"), "updated loco-warning")
    };
    a.showSuccess = function(b) {
        return a.showMessage(b, l("OK"), "updated loco-success")
    };
    a.debugError = function(a, c) {
        e.console && console.error && (console.error("Loco Error: " + a), c && console.debug(c))
    };
    var l = a.t || (a.t = function(a) {
        return a
    })
}(window, document, window.jQuery);
