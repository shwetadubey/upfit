! function(f, m, b) {
    function e(a) {
        var c = b(d).find('input[type="submit"]')[0];
        if (/^([a-z]{2,3})(?:[ _\-]([A-Z]{2}))?$/i.exec(a)) return a = RegExp.$1.toLowerCase(), RegExp.$2 && (a += "_" + RegExp.$2.toUpperCase()), c.disabled = !1, a;
        c.disabled = !0
    }

    function h(a) {
        function c(a, c, b) {
            console.error(b || "FAIL");
            n(b || p("Unknown error"))
        }
        g("");
        return b.ajax({
            url: q,
            type: "POST",
            data: {
                action: "loco-data",
                locale: a
            },
            dataType: "json",
            error: c,
            success: function(b, d, f) {
                var e = b && b.locales && b.locales[a];
                if (!e) return c(f, d, b && b.error && b.error.message);
                g(e.icon)
            }
        })
    }

    function g(a) {
        var c = b(d).find("span.icon");
        c[a ? "show" : "hide"]();
        a = "icon " + a;
        c.each(function(b, c) {
            c.className = a
        })
    }
    var k = f.loco,
        p = k.t,
        n = k.showError,
        q = f.ajaxurl || "/wp-admin/admin-ajax.php",
        d = m.getElementById("loco-msginit"),
        l = d["custom-locale"];
    b(d["common-locale"]).change(function() {
        var a = e(l.value = b(this).val());
        a && h(a);
        return !0
    });
    b(l).on("input", function(a) {
        (a = e(a.target.value)) && h(a);
        return !0
    });
    g("")
}(window, document, window.jQuery);
