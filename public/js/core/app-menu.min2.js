!(function (e, n, a) {
  "use strict";
  a.app = a.app || {};
  var t = a("body"),
    i = a(e),
    s = a('div[data-menu="menu-wrapper"]').html(),
    o = a('div[data-menu="menu-wrapper"]').attr("class");
  (a.app.menu = {
    expanded: null,
    collapsed: null,
    hidden: null,
    container: null,
    horizontalMenu: !1,
    is_touch_device: function () {
      var a = " -webkit- -moz- -o- -ms- ".split(" ");
      return (
        !!(
          "ontouchstart" in e ||
          (e.DocumentTouch && n instanceof DocumentTouch)
        ) ||
        (function (n) {
          return e.matchMedia(n).matches;
        })(["(", a.join("touch-enabled),("), "heartz", ")"].join(""))
      );
    },
    manualScroller: {
      obj: null,
      init: function () {
        a.app.menu.is_touch_device()
          ? a(".main-menu").addClass("menu-native-scroll")
          : (this.obj = new PerfectScrollbar(".main-menu-content", {
              suppressScrollX: !0,
              wheelPropagation: !1,
            }));
      },
      update: function () {
        if (this.obj) {
          if (!0 === a(".main-menu").data("scroll-to-active")) {
            var e, i, s;
            if (
              ((e = n.querySelector(".main-menu-content li.active")),
              (i = n.querySelector(".main-menu-content")),
              t.hasClass("menu-collapsed") &&
                a(".main-menu-content li.menu-collapsed-open").length &&
                (e = n.querySelector(
                  ".main-menu-content li.menu-collapsed-open"
                )),
              e && (s = e.getBoundingClientRect().top + i.scrollTop),
              s > parseInt((2 * i.clientHeight) / 3))
            )
              var o = s - i.scrollTop - parseInt(i.clientHeight / 2);
            setTimeout(function () {
              a.app.menu.container.stop().animate({ scrollTop: o }, 300),
                a(".main-menu").data("scroll-to-active", "false");
            }, 300);
          }
          this.obj.update();
        }
      },
      enable: function () {
        a(".main-menu-content").hasClass("ps") || this.init();
      },
      disable: function () {
        this.obj && this.obj.destroy();
      },
      updateHeight: function () {
        ("vertical-menu" != t.data("menu") &&
          "vertical-menu-modern" != t.data("menu") &&
          "vertical-overlay-menu" != t.data("menu")) ||
          !a(".main-menu").hasClass("menu-fixed") ||
          (a(".main-menu-content").css(
            "height",
            a(e).height() -
              a(".header-navbar").height() -
              a(".main-menu-header").outerHeight()
          ),
          this.update());
      },
    },
    init: function (e) {
      if (a(".main-menu-content").length > 0) {
        this.container = a(".main-menu-content");
        var n = "";
        !0 === e && (n = "collapsed"), this.change(n);
      }
      "collapsed" === n && this.collapse();
    },
    change: function (n) {
      var i = Unison.fetch.now();
      this.reset();
      var s = t.data("menu");
      if (i)
        switch (i.name) {
          case "xl":
            "vertical-overlay-menu" === s
              ? this.hide()
              : "vertical-compact-menu" === s
              ? this.open()
              : "collapsed" === n
              ? this.collapse(n)
              : this.expand();
            break;
          case "lg":
            "vertical-overlay-menu" === s
              ? this.hide()
              : "vertical-compact-menu" === s
              ? this.open()
              : "horizontal-menu" === s
              ? this.hide()
              : "collapsed" === n
              ? this.collapse(n)
              : this.expand();
            break;
          case "md":
            "vertical-overlay-menu" === s || "vertical-menu-modern" === s
              ? this.hide()
              : "vertical-compact-menu" === s
              ? this.open()
              : "horizontal-menu" === s
              ? this.hide()
              : this.collapse();
            break;
          case "sm":
          case "xs":
            this.hide();
        }
      ("vertical-menu" !== s &&
        "vertical-compact-menu" !== s &&
        "vertical-content-menu" !== s &&
        "vertical-menu-modern" !== s) ||
        this.toOverlayMenu(i.name, s),
        t.is(".horizontal-layout") &&
          !t.hasClass(".horizontal-menu-demo") &&
          (this.changeMenu(i.name), a(".menu-toggle").removeClass("is-active")),
        "xl" == i.name &&
          a('body[data-open="hover"] .dropdown')
            .off("mouseenter")
            .on("mouseenter", function (e) {
              a(this).hasClass("show")
                ? a(this).removeClass("show")
                : a(this).addClass("show");
            })
            .off("mouseleave")
            .on("mouseleave", function (e) {
              a(this).removeClass("show");
            }),
        a(".header-navbar").hasClass("navbar-brand-center") &&
          a(".header-navbar").attr("data-nav", "brand-center"),
        "sm" == i.name || "xs" == i.name
          ? a(".header-navbar[data-nav=brand-center]").removeClass(
              "navbar-brand-center"
            )
          : a(".header-navbar[data-nav=brand-center]").addClass(
              "navbar-brand-center"
            ),
        a("ul.dropdown-menu [data-toggle=dropdown]").on("click", function (e) {
          a(this).siblings("ul.dropdown-menu").length > 0 && e.preventDefault(),
            e.stopPropagation(),
            a(this).parent().siblings().removeClass("show"),
            a(this).parent().toggleClass("show");
        }),
        "horizontal-menu" == s &&
          (a("li.dropdown-submenu .dropdown-item").on("click", function () {
            var n = a(this).parent().find(".dropdown-menu");
            if (n.length) {
              var t = a(e).height(),
                i = n.offset().top,
                s = n.offset().left,
                o = n.width();
              if (t - i - n.height() - 28 < 1) {
                var l = t - i - 25;
                if ("click" === a("body").data("open")) {
                  a(this)
                    .parent()
                    .find(".dropdown-menu")
                    .css({
                      "max-height": l + "px",
                      "overflow-y": "auto",
                      "overflow-x": "hidden",
                    });
                  new PerfectScrollbar(
                    "li.dropdown-submenu.show .dropdown-menu",
                    { wheelPropagation: !1 }
                  );
                }
              }
              "ltr" === a("html").data("textdirection") &&
              s + o - (e.innerWidth - 16) >= 0
                ? a(this).parent().find(".dropdown-menu").addClass("open-left")
                : "rtl" === a("html").data("textdirection") &&
                  s + o - (e.innerWidth - 1e3) <= 0 &&
                  a(this).parent().find(".dropdown-menu").addClass("open-left");
            }
          }),
          a("li.dropdown-submenu").on("mouseenter", function () {
            var n = a(this).find(".dropdown-menu");
            if (n.length) {
              var t = a(e).height(),
                i = n.offset().top,
                s = n.offset().left,
                o = n.width();
              if (t - i - n.height() - 28 < 1) {
                var l = t - i - 25;
                if ("hover" === a("body").data("open")) {
                  a(this)
                    .find(".dropdown-menu")
                    .css({
                      "max-height": l + "px",
                      "overflow-y": "auto",
                      "overflow-x": "hidden",
                    });
                  new PerfectScrollbar(
                    "li.dropdown-submenu.show .dropdown-menu",
                    { wheelPropagation: !1 }
                  );
                }
              }
              "ltr" === a("html").data("textdirection") &&
              s + o - (e.innerWidth - 16) >= 0
                ? a(this).parent().find(".dropdown-menu").addClass("open-left")
                : "rtl" === a("html").data("textdirection") &&
                  s + o - (e.innerWidth - 1e3) <= 0 &&
                  a(this).parent().find(".dropdown-menu").addClass("open-left");
            }
          })),
        "horizontal-menu" == s &&
          ("xl" == i.name
            ? a(".navbar-fixed").length && a(".navbar-fixed").sticky()
            : a(".menu-fixed").length && a(".menu-fixed").unstick());
    },
    transit: function (e, n) {
      var i = this;
      t.addClass("changing-menu"),
        e.call(i),
        t.hasClass("vertical-layout") &&
          (t.hasClass("menu-open") || t.hasClass("menu-expanded")
            ? (a(".menu-toggle").addClass("is-active"),
              ("vertical-menu" !== t.data("menu") &&
                "vertical-content-menu" !== t.data("menu")) ||
                (a(".main-menu-header") && a(".main-menu-header").show()))
            : (a(".menu-toggle").removeClass("is-active"),
              ("vertical-menu" !== t.data("menu") &&
                "vertical-content-menu" !== t.data("menu")) ||
                (a(".main-menu-header") && a(".main-menu-header").hide()))),
        setTimeout(function () {
          n.call(i), t.removeClass("changing-menu"), i.update();
        }, 500);
    },
    open: function () {
      this.transit(
        function () {
          t.removeClass("menu-hide menu-collapsed").addClass("menu-open"),
            (this.hidden = !1),
            (this.expanded = !0),
            t.hasClass("vertical-overlay-menu") &&
              (a(".sidenav-overlay").removeClass("d-none").addClass("d-block"),
              t.css("overflow", "hidden"));
        },
        function () {
          !a(".main-menu").hasClass("menu-native-scroll") &&
            a(".main-menu").hasClass("menu-fixed") &&
            (this.manualScroller.enable(),
            a(".main-menu-content").css(
              "height",
              a(e).height() -
                a(".header-navbar").height() -
                a(".main-menu-header").outerHeight()
            )),
            "vertical-compact-menu" != t.data("menu") ||
              t.hasClass("vertical-overlay-menu") ||
              (a(".sidenav-overlay").removeClass("d-block d-none"),
              a("body").css("overflow", "auto"));
        }
      );
    },
    hide: function () {
      this.transit(
        function () {
          t.removeClass("menu-open menu-expanded").addClass("menu-hide"),
            (this.hidden = !0),
            (this.expanded = !1),
            t.hasClass("vertical-overlay-menu") &&
              (a(".sidenav-overlay").removeClass("d-block").addClass("d-none"),
              a("body").css("overflow", "auto"));
        },
        function () {
          !a(".main-menu").hasClass("menu-native-scroll") &&
            a(".main-menu").hasClass("menu-fixed") &&
            this.manualScroller.enable(),
            "vertical-compact-menu" != t.data("menu") ||
              t.hasClass("vertical-overlay-menu") ||
              (a(".sidenav-overlay").removeClass("d-block d-none"),
              a("body").css("overflow", "auto"));
        }
      );
    },
    expand: function () {
      !1 === this.expanded &&
        ("vertical-menu-modern" == t.data("menu") &&
          a(".modern-nav-toggle")
            .find(".toggle-icon")
            .removeClass("ft-toggle-left")
            .addClass("ft-toggle-right"),
        this.transit(
          function () {
            t.removeClass("menu-collapsed").addClass("menu-expanded"),
              (this.collapsed = !1),
              (this.expanded = !0),
              a(".sidenav-overlay").removeClass("d-block d-none");
          },
          function () {
            a(".main-menu").hasClass("menu-native-scroll") ||
            "horizontal-menu" == t.data("menu")
              ? this.manualScroller.disable()
              : a(".main-menu").hasClass("menu-fixed") &&
                this.manualScroller.enable(),
              ("vertical-menu" != t.data("menu") &&
                "vertical-menu-modern" != t.data("menu")) ||
                !a(".main-menu").hasClass("menu-fixed") ||
                a(".main-menu-content").css(
                  "height",
                  a(e).height() -
                    a(".header-navbar").height() -
                    a(".main-menu-header").outerHeight()
                );
          }
        ));
    },
    collapse: function (n) {
      !1 === this.collapsed &&
        ("vertical-menu-modern" == t.data("menu") &&
          a(".modern-nav-toggle")
            .find(".toggle-icon")
            .removeClass("ft-toggle-right")
            .addClass("ft-toggle-left"),
        this.transit(
          function () {
            t.removeClass("menu-expanded").addClass("menu-collapsed"),
              (this.collapsed = !0),
              (this.expanded = !1),
              a(".content-overlay").removeClass("d-block d-none");
          },
          function () {
            "vertical-content-menu" == t.data("menu") &&
              this.manualScroller.disable(),
              "horizontal-menu" == t.data("menu") &&
                t.hasClass("vertical-overlay-menu") &&
                a(".main-menu").hasClass("menu-fixed") &&
                this.manualScroller.enable(),
              ("vertical-menu" != t.data("menu") &&
                "vertical-menu-modern" != t.data("menu")) ||
                !a(".main-menu").hasClass("menu-fixed") ||
                (a(".main-menu-content").css(
                  "height",
                  a(e).height() - a(".header-navbar").height()
                ),
                a(".main-menu-content").hasClass("ps") ||
                  this.manualScroller.enable());
          }
        ));
    },
    toOverlayMenu: function (e, n) {
      var i = t.data("menu");
      "vertical-menu-modern" == n
        ? "md" == e || "sm" == e || "xs" == e
          ? t.hasClass(i) && t.removeClass(i).addClass("vertical-overlay-menu")
          : t.hasClass("vertical-overlay-menu") &&
            t.removeClass("vertical-overlay-menu").addClass(i)
        : "sm" == e || "xs" == e
        ? (t.hasClass(i) && t.removeClass(i).addClass("vertical-overlay-menu"),
          "vertical-content-menu" == i &&
            a(".main-menu").addClass("menu-fixed"))
        : (t.hasClass("vertical-overlay-menu") &&
            t.removeClass("vertical-overlay-menu").addClass(i),
          "vertical-content-menu" == i &&
            a(".main-menu").removeClass("menu-fixed"));
    },
    changeMenu: function (e) {
      a('div[data-menu="menu-wrapper"]').html(""),
        a('div[data-menu="menu-wrapper"]').html(s);
      var n = a('div[data-menu="menu-wrapper"]'),
        i =
          (a('div[data-menu="menu-container"]'),
          a('ul[data-menu="menu-navigation"]')),
        l = a('li[data-menu="megamenu"]'),
        r = a("li[data-mega-col]"),
        d = a('li[data-menu="dropdown"]'),
        m = a('li[data-menu="dropdown-submenu"]');
      "xl" === e
        ? (t
            .removeClass("vertical-layout vertical-overlay-menu fixed-navbar")
            .addClass(t.data("menu")),
          a("nav.header-navbar").removeClass("fixed-top"),
          n.removeClass().addClass(o),
          a("a.dropdown-item.nav-has-children").on("click", function () {
            event.preventDefault(), event.stopPropagation();
          }),
          a("a.dropdown-item.nav-has-parent").on("click", function () {
            event.preventDefault(), event.stopPropagation();
          }))
        : (t
            .removeClass(t.data("menu"))
            .addClass("vertical-layout vertical-overlay-menu fixed-navbar"),
          a("nav.header-navbar").addClass("fixed-top"),
          n
            .removeClass()
            .addClass("main-menu menu-light menu-fixed menu-shadow"),
          i.removeClass().addClass("navigation navigation-main"),
          l.removeClass("dropdown mega-dropdown").addClass("has-sub"),
          l.children("ul").removeClass(),
          r.each(function (e, n) {
            a(n)
              .find(".mega-menu-sub")
              .find("li")
              .has("ul")
              .addClass("has-sub");
            var t = a(n).children().first(),
              i = "";
            t.is("h6") &&
              ((i = t.html()),
              t.remove(),
              a(n)
                .prepend('<a href="#">' + i + "</a>")
                .addClass("has-sub mega-menu-title"));
          }),
          l.find("a").removeClass("dropdown-toggle"),
          l.find("a").removeClass("dropdown-item"),
          d.removeClass("dropdown").addClass("has-sub"),
          d.find("a").removeClass("dropdown-toggle nav-link"),
          d.children("ul").find("a").removeClass("dropdown-item"),
          d.find("ul").removeClass("dropdown-menu"),
          m.removeClass().addClass("has-sub"),
          a.app.nav.init(),
          a("ul.dropdown-menu [data-toggle=dropdown]").on(
            "click",
            function (e) {
              e.preventDefault(),
                e.stopPropagation(),
                a(this).parent().siblings().removeClass("open"),
                a(this).parent().toggleClass("open");
            }
          ));
    },
    toggle: function () {
      var e = Unison.fetch.now(),
        n = (this.collapsed, this.expanded),
        a = this.hidden,
        i = t.data("menu");
      switch (e.name) {
        case "xl":
          !0 === n
            ? "vertical-compact-menu" == i || "vertical-overlay-menu" == i
              ? this.hide()
              : this.collapse()
            : "vertical-compact-menu" == i || "vertical-overlay-menu" == i
            ? this.open()
            : this.expand();
          break;
        case "lg":
          !0 === n
            ? "vertical-compact-menu" == i ||
              "vertical-overlay-menu" == i ||
              "horizontal-menu" == i
              ? this.hide()
              : this.collapse()
            : "vertical-compact-menu" == i ||
              "vertical-overlay-menu" == i ||
              "horizontal-menu" == i
            ? this.open()
            : this.expand();
          break;
        case "md":
          !0 === n
            ? "vertical-compact-menu" == i ||
              "vertical-overlay-menu" == i ||
              "vertical-menu-modern" == i ||
              "horizontal-menu" == i
              ? this.hide()
              : this.collapse()
            : "vertical-compact-menu" == i ||
              "vertical-overlay-menu" == i ||
              "vertical-menu-modern" == i ||
              "horizontal-menu" == i
            ? this.open()
            : this.expand();
          break;
        case "sm":
        case "xs":
          !0 === a ? this.open() : this.hide();
      }
    },
    update: function () {
      this.manualScroller.update();
    },
    reset: function () {
      (this.expanded = !1),
        (this.collapsed = !1),
        (this.hidden = !1),
        t.removeClass("menu-hide menu-open menu-collapsed menu-expanded");
    },
  }),
    (a.app.nav = {
      container: a(".navigation-main"),
      initialized: !1,
      navItem: a(".navigation-main").find("li").not(".navigation-category"),
      config: { speed: 300 },
      init: function (e) {
        (this.initialized = !0), a.extend(this.config, e), this.bind_events();
      },
      detectIE: function (n) {
        var a = e.navigator.userAgent,
          t = a.indexOf("MSIE ");
        if (t > 0) return parseInt(a.substring(t + 5, a.indexOf(".", t)), 10);
        if (a.indexOf("Trident/") > 0) {
          var i = a.indexOf("rv:");
          return parseInt(a.substring(i + 3, a.indexOf(".", i)), 10);
        }
        var s = a.indexOf("Edge/");
        return s > 0 && parseInt(a.substring(s + 5, a.indexOf(".", s)), 10);
      },
      bind_events: function () {
        var e = this;
        a(".navigation-main")
          .on("mouseenter.app.menu", "li", function () {
            var n = a(this);
            if (
              (a(".hover", ".navigation-main").removeClass("hover"),
              (t.hasClass("menu-collapsed") &&
                "vertical-menu-modern" != t.data("menu")) ||
                ("vertical-compact-menu" == t.data("menu") &&
                  !t.hasClass("vertical-overlay-menu")))
            ) {
              a(".main-menu-content").children("span.menu-title").remove(),
                a(".main-menu-content").children("a.menu-title").remove(),
                a(".main-menu-content").children("ul.menu-content").remove();
              var i,
                s,
                o,
                l = n.find("span.menu-title").clone();
              if (
                (n.hasClass("has-sub") ||
                  ((i = n.find("span.menu-title").text()),
                  (s = n.children("a").attr("href")),
                  "" !== i &&
                    ((l = a("<a>")).attr("href", s),
                    l.attr("title", i),
                    l.text(i),
                    l.addClass("menu-title"))),
                (o = n.css("border-top")
                  ? n.position().top + parseInt(n.css("border-top"), 10)
                  : n.position().top),
                t.hasClass("material-vertical-layout") &&
                  (o = a(".user-profile").height() + n.position().top),
                !1 !== e.detectIE() &&
                  t.hasClass("material-vertical-layout") &&
                  (o =
                    a(".user-profile").height() +
                    n.position().top +
                    a(".header-navbar").height()),
                "vertical-compact-menu" !== t.data("menu") &&
                  l
                    .appendTo(".main-menu-content")
                    .css({ position: "fixed", top: o }),
                n.hasClass("has-sub") && n.hasClass("nav-item"))
              ) {
                n.children("ul:first");
                "vertical-compact-menu" !== t.data("menu")
                  ? e.adjustSubmenu(n)
                  : e.fullSubmenu(n);
              }
            }
            n.addClass("hover");
          })
          .on("mouseleave.app.menu", "li", function () {})
          .on("active.app.menu", "li", function (e) {
            a(this).addClass("active"), e.stopPropagation();
          })
          .on("deactive.app.menu", "li.active", function (e) {
            a(this).removeClass("active"), e.stopPropagation();
          })
          .on("open.app.menu", "li", function (n) {
            var t = a(this);
            if (
              (t.addClass("open"),
              e.expand(t),
              a(".main-menu").hasClass("menu-collapsible"))
            )
              return !1;
            t.siblings(".open").find("li.open").trigger("close.app.menu"),
              t.siblings(".open").trigger("close.app.menu"),
              n.stopPropagation();
          })
          .on("close.app.menu", "li.open", function (n) {
            var t = a(this);
            t.removeClass("open"), e.collapse(t), n.stopPropagation();
          })
          .on("click.app.menu", "li", function (e) {
            var n = a(this);
            n.is(".disabled")
              ? e.preventDefault()
              : (t.hasClass("menu-collapsed") &&
                  "vertical-menu-modern" != t.data("menu")) ||
                ("vertical-compact-menu" == t.data("menu") &&
                  n.is(".has-sub") &&
                  !t.hasClass("vertical-overlay-menu"))
              ? e.preventDefault()
              : n.has("ul")
              ? n.is(".open")
                ? n.trigger("close.app.menu")
                : n.trigger("open.app.menu")
              : n.is(".active") ||
                (n.siblings(".active").trigger("deactive.app.menu"),
                n.trigger("active.app.menu")),
              e.stopPropagation();
          }),
          a(".navbar-header, .main-menu")
            .on("mouseenter", function () {
              if (
                "vertical-menu-modern" == t.data("menu") &&
                (a(".main-menu, .navbar-header").addClass("expanded"),
                t.hasClass("menu-collapsed"))
              ) {
                var e = a(".main-menu li.menu-collapsed-open");
                e
                  .children("ul")
                  .hide()
                  .slideDown(200, function () {
                    a(this).css("display", "");
                  }),
                  e.addClass("open").removeClass("menu-collapsed-open");
              }
            })
            .on("mouseleave", function () {
              t.hasClass("menu-collapsed") &&
                "vertical-menu-modern" == t.data("menu") &&
                setTimeout(function () {
                  if (
                    0 === a(".main-menu:hover").length &&
                    0 === a(".navbar-header:hover").length &&
                    (a(".main-menu, .navbar-header").removeClass("expanded"),
                    a(".user-profile .user-info .dropdown").hasClass("show") &&
                      (a(".user-profile .user-info .dropdown").removeClass(
                        "show"
                      ),
                      a(
                        ".user-profile .user-info .dropdown .dropdown-menu"
                      ).removeClass("show")),
                    t.hasClass("menu-collapsed"))
                  ) {
                    var e = a(".main-menu li.open"),
                      n = e.children("ul");
                    e.addClass("menu-collapsed-open"),
                      n.show().slideUp(200, function () {
                        a(this).css("display", "");
                      }),
                      e.removeClass("open");
                  }
                }, 1);
            }),
          a(".main-menu-content").on("mouseleave", function () {
            (t.hasClass("menu-collapsed") ||
              "vertical-compact-menu" == t.data("menu")) &&
              (a(".main-menu-content").children("span.menu-title").remove(),
              a(".main-menu-content").children("a.menu-title").remove(),
              a(".main-menu-content").children("ul.menu-content").remove()),
              a(".hover", ".navigation-main").removeClass("hover");
          }),
          a(".navigation-main li.has-sub > a").on("click", function (e) {
            e.preventDefault();
          }),
          a("ul.menu-content").on("click", "li", function (n) {
            var t = a(this);
            if (t.is(".disabled")) n.preventDefault();
            else if (t.has("ul"))
              if (t.is(".open")) t.removeClass("open"), e.collapse(t);
              else {
                if (
                  (t.addClass("open"),
                  e.expand(t),
                  a(".main-menu").hasClass("menu-collapsible"))
                )
                  return !1;
                t.siblings(".open").find("li.open").trigger("close.app.menu"),
                  t.siblings(".open").trigger("close.app.menu"),
                  n.stopPropagation();
              }
            else
              t.is(".active") ||
                (t.siblings(".active").trigger("deactive.app.menu"),
                t.trigger("active.app.menu"));
            n.stopPropagation();
          });
      },
      adjustSubmenu: function (e) {
        var n,
          s,
          o,
          l,
          r,
          d = e.children("ul:first"),
          m = d.clone(!0);
        if (
          (a(".main-menu-header").height(),
          (n = e.position().top),
          (o = i.height() - a(".header-navbar").height()),
          (r = 0),
          d.height(),
          parseInt(e.css("border-top"), 10) > 0 &&
            (r = parseInt(e.css("border-top"), 10)),
          (l = o - n - e.height() - 30),
          a(".main-menu").hasClass("menu-dark") ? "light" : "dark",
          "vertical-compact-menu" === t.data("menu")
            ? ((s = n + r), (l = o - n - 30))
            : "vertical-content-menu" === t.data("menu")
            ? ((s = n + e.height() + r - 5),
              (l = o - a(".content-header").height() - e.height() - n - 60),
              t.hasClass("material-vertical-layout") &&
                (s = n + e.height() + a(".user-profile").height() + r))
            : (s = t.hasClass("material-vertical-layout")
                ? n + e.height() + a(".user-profile").height() + r
                : n + e.height() + r),
          !1 !== this.detectIE() &&
            t.hasClass("material-vertical-layout") &&
            (s =
              n +
              e.height() +
              a(".user-profile").height() +
              r +
              a(".header-navbar").height()),
          "vertical-content-menu" == t.data("menu"))
        )
          m.addClass("menu-popout")
            .appendTo(".main-menu-content")
            .css({ top: s, position: "fixed" });
        else {
          m.addClass("menu-popout")
            .appendTo(".main-menu-content")
            .css({ top: s, position: "fixed", "max-height": l });
          new PerfectScrollbar(".main-menu-content > ul.menu-content");
        }
      },
      fullSubmenu: function (e) {
        e.children("ul:first")
          .clone(!0)
          .addClass("menu-popout")
          .appendTo(".main-menu-content")
          .css({ top: 0, position: "fixed", height: "100%" });
        new PerfectScrollbar(".main-menu-content > ul.menu-content");
      },
      collapse: function (e, n) {
        e.children("ul")
          .show()
          .slideUp(a.app.nav.config.speed, function () {
            a(this).css("display", ""),
              a(this).find("> li").removeClass("is-shown"),
              n && n(),
              a.app.nav.container.trigger("collapsed.app.menu");
          });
      },
      expand: function (e, n) {
        var t = e.children("ul"),
          i = t.children("li").addClass("is-hidden");
        t.hide().slideDown(a.app.nav.config.speed, function () {
          a(this).css("display", ""),
            n && n(),
            a.app.nav.container.trigger("expanded.app.menu");
        }),
          setTimeout(function () {
            i.addClass("is-shown"), i.removeClass("is-hidden");
          }, 0);
      },
      refresh: function () {
        a.app.nav.container.find(".open").removeClass("open");
      },
    });
})(window, document, jQuery);
