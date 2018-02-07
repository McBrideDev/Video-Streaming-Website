/*
    Codo Player Pro 2.1
    codoplayer.com

    Licensed domain: N/A (for testing only)

    Date: 09 06 2015

    Copyright (C) Donato Software House
*/
window.Codo = function(e) {
    "use strict";
    var t = [];
    return e && ("string" == typeof e ? t = document.querySelectorAll(e) : t.push(e)), {
        get: function() {
            return t
        },
        domReady: function(e) {
            e && (document.addEventListener ? document.addEventListener("DOMContentLoaded", e) : document.attachEvent("onreadystatechange", function() {
                "interactive" === document.readyState && e()
            }))
        },
        script: function(e, t, n) {
            var o = document.createElement("script");
            o.type = "text/javascript", o.async = !0, o.onreadystatechange = function(e) {
                "complete" != this.readyState && "loaded" != this.readyState || e || (t && t(!0, n), e = !0)
            }, o.onload = function() {
                t && t(!0, n)
            }, o.onerror = function() {
                t && t(!1, n)
            }, o.src = e, document.getElementsByTagName("head")[0].appendChild(o)
        },
        link: function(e) {
            var t = document.createElement("link");
            t.rel = "stylesheet", t.type = "text/css", t.href = e, document.getElementsByTagName("head")[0].appendChild(t)
        },
        load: function(e, n, o) {
            var i = new XMLHttpRequest;
            i.open(e.action || "GET", e.url, !0), i.onreadystatechange = function() {
                if (4 === this.readyState)
                    if (this.status >= 200 && this.status < 400) {
                        if (!t[0]) return n(this.responseText, o), this.responseText;
                        t[0].innerHTML = this.responseText
                    } else n("error", o)
            }, e.contentType && i.setRequestHeader("Content-Type", e.contentType), i.send()
        },
        on: function(e, n, o) {
            if (t[0]) {
                for (var i = 0; i < t.length; i++) t[i].addEventListener ? t[i].addEventListener(e, n, o || !1) : t[i].attachEvent("on" + e, n);
                return t
            }
        },
        off: function(e, n) {
            if (t[0]) {
                for (var o = 0; o < t.length; o++) t[o].removeEventListener ? t[o].removeEventListener(e, n) : t[o].detachEvent("on" + e, n);
                return t
            }
        },
        add: function(e) {
            if (t[0]) {
                for (var n = 0; n < t.length; n++) {
                    var o = document.createElement(e.el);
                    for (var i in e) "el" != i && (o.key ? o.key = e[i] : "className" == i ? o.className = e[i] : "style" == i ? o.style.cssText = e[i] : "innerHTML" == i ? o.innerHTML = e[i] : o.setAttribute(i, e[i]));
                    t[n] && t[n].appendChild(o)
                }
                return o
            }
        },
        remove: function() {
            if (t[0])
                for (var e = 0; e < t.length; e++) t[e].parentNode.removeChild(t[e]), t[e] = void 0
        },
        addClass: function(e) {
            if (t[0])
                for (var n = 0; n < t.length; n++) t[n].classList ? t[n].classList.add(e) : t[n].className += " " + e
        },
        removeClass: function(e) {
            if (t[0])
                for (var n = 0; n < t.length; n++) t[n].classList ? t[n].classList.remove(e) : t[n].className = t[n].className.replace(new RegExp("(^|\\b)" + e.split(" ").join("|") + "(\\b|$)", "gi"), " ")
        },
        toggle: function() {
            t[0] && ("block" == t[0].style.display ? t[0].style.display = "none" : t[0].style.display = "block")
        },
        getTop: function() {
            return t[0] ? t[0].getBoundingClientRect().top : void 0
        },
        getLeft: function() {
            return t[0] ? t[0].getBoundingClientRect().left : void 0
        },
        getWidth: function() {
            return t[0] ? t[0].clientWidth || t[0].offsetWidth : void 0
        },
        getHeight: function() {
            return t[0] ? t[0].clientHeight || t[0].offsetHeight : void 0
        },
        screen: function() {
            var e = {};
            return e.width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0), e.height = Math.max(document.documentElement.clientHeight, window.innerHeight || 0), e
        },
        scrollX: function() {
            return void 0 !== window.pageXOffset ? window.pageXOffset : (document.documentElement || document.parentNode || document).scrollLeft
        },
        scrollY: function() {
            return void 0 !== window.pageYOffset ? window.pageYOffset : (document.documentElement || document.parentNode || document).scrollTop
        },
        mouse: function(e) {
            e = e || window.event;
            var t = e.pageX,
                n = e.pageY;
            void 0 === t && (t = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft, n = e.clientY + document.body.scrollTop + document.documentElement.scrollTop);
            var o = {};
            return o.x = t, o.y = n, o
        },
        fadeIn: function(e, n) {
            if (t[0]) {
                var o = 0;
                e = e || 2, n = n || 100, t[0].style.display = "block", t[0].style.visibility = "visible";
                var i = function() {
                    o += e, t[0].style.opacity = o / 100, t[0].style.filter = "alpha(opacity=" + o + ")", n > o && (window.requestAnimationFrame && requestAnimationFrame(i) || setTimeout(i, 16))
                };
                i()
            }
        },
        fadeOut: function(e, n) {
            if (t[0]) {
                var o = 100;
                e = e || 2, n = n || 0;
                var i = function() {
                    o -= e, t[0].style.opacity = o / 100, t[0].style.filter = "alpha(opacity=" + o + ")", o > n ? window.requestAnimationFrame && requestAnimationFrame(i) || setTimeout(i, 16) : t[0].style.display = "none"
                };
                i()
            }
        },
        log: function(e) {
            window.console && console.log(e)
        },
        isTouch: function() {
            return !!("ontouchstart" in window)
        },
        isMobile: function() {
            return /webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ? !0 : void 0
        },
        isIphone: function() {
            return navigator.userAgent.match(/iPhone|iPod/i)
        },
        isFlash: function() {
            var e = !1;
            if ("ActiveXObject" in window) try {
                e = !!new ActiveXObject("ShockwaveFlash.ShockwaveFlash")
            } catch (t) {
                e = !1
            } else e = !!navigator.mimeTypes["application/x-shockwave-flash"];
            return e
        },
        p: function() {
            return location.protocol
        },
        h: function() {
            return location.hostname
        },
        getScriptTag: function(e) {
            for (var t = document.scripts, n = 0; n < t.length; n++)
                if (t[n].attributes.src && t[n].attributes.src.value.search(e) > -1) return t[n]
        },
        getVideoHeight: function(e, t, n) {
            return e / (t / n)
        },
        secsToTime: function(e) {
            var t = ":",
                n = ":",
                o = Math.floor(e / 3600);
            10 > o && (o = "0" + o), "00" === o && (o = "", t = "");
            var i = e % 3600,
                r = Math.floor(i / 60);
            10 > r && (r = "0" + r);
            var s = i % 60,
                a = Math.round(s);
            return 10 > a && (a = "0" + a), o + t + r + n + a
        }
    }
}, document.querySelectorAll || function(e, t) {
    e = document, t = e.createStyleSheet(), e.querySelectorAll = function(n, o, i, r, s) {
        for (s = e.all, o = [], n = n.replace(/\[for\b/gi, "[htmlFor").split(","), i = n.length; i--;) {
            for (t.addRule(n[i], "k:v"), r = s.length; r--;) s[r].currentStyle.k && o.push(s[r]);
            t.removeRule(0)
        }
        return o
    }
}(), window.CodoPlayerAPI = [], window.CodoPlayer = function(e, t, n) {
    "use strict";

    function o(o, i, r) {
        e || (e = {}), t || (t = {}), t.controls || (t.controls = {});
        var s = Codo().isTouch() ? "touchstart" : "click",
            a = !0,
            l = "data:image/gif;base64,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",
            u = function(e) {
                var t, n = "codo-player";
                t = e.id ? e.id : "codo-player-" + CodoPlayerAPI.length;
                var o = Codo().getScriptTag("CodoPlayer.js").src.replace("CodoPlayer.js", ""),
                    i = {
                        instance: CodoPlayerAPI.length,
                        id: t,
                        className: n,
                        DOM: {
                            parent: void 0,
                            container: void 0,
                            containerScreen: void 0,
                            containerScreenCanvas: void 0,
                            overlay: void 0,
                            controls: void 0
                        },
                        settings: {
                            responsive: function() {
                                return e.width ? !1 : !0
                            }(),
                            style: e.style || "standard",
                            ratio: e.ratio || [16, 9],
                            width: e.width,
                            height: e.height,
                            currentWidth: void 0,
                            currentHeight: void 0,
                            mediaWidth: void 0,
                            mediaHeight: void 0,
                            autoplay: e.autoplay,
                            poster: e.poster,
                            volume: function() {
                                return 0 === e.volume ? 0 : function() {
                                    return e.volume ? e.volume : 80
                                }()
                            }(),
                            loop: e.loop,
                            preload: function() {
                                return Codo().isTouch() ? !0 : e.preload === !1 ? !1 : !0
                            }(),
                            engine: e.engine || "auto",
                            loader: e.loader || o + "loader.gif",
                            logo: e.logo,
                            cuepoints: e.cuepoints,
                            playlist: e.playlist,
                            priority: e.priority || "src"
                        },
                        playlist: {},
                        about: {
                            product: "{{name}} {{kind}} {{version}}"
                        },
                        media: {},
                        system: {
                            initClickMade: !1,
                            firstClipOver: !1,
                            initPlayMade: !1,
                            isFullScreen: !1,
                            rootPath: o
                        },
                        plugins: e.plugins || {},
                        play: function(e, t) {
                            e ? i.playlist.set(e, t) : i.settings.preload ? i.media.toggle() : (i.system.initClickMade ? i.media.toggle() : (i.media.toggle(), Codo().isTouch() && i.media.getParent().play()), i.system.initClickMade = !0)
                        },
                        pause: function() {
                            i.media.pause()
                        },
                        resize: function(e, t) {
                            e && t ? (i.settings.width = i.settings.currentWidth = e, i.settings.height = i.settings.currentHeight = t) : i.settings.mediaWidth && i.settings.mediaHeight && (i.settings.width = i.settings.currentWidth = Codo(i.DOM.parent.parentNode).getWidth(), i.settings.height = i.settings.currentHeight = Codo().getVideoHeight(i.settings.width, i.settings.mediaWidth, i.settings.mediaHeight)), i.media.getPoster || i.media.getParent ? i.settings.mediaWidth && i.settings.mediaHeight && (z.resize(i.media.getPoster(), i.settings.mediaWidth, i.settings.mediaHeight), z.resize(i.media.getParent(), i.settings.mediaWidth, i.settings.mediaHeight)) : z.resize(null, i.settings.currentWidth, i.settings.currentHeight)
                        },
                        destroy: function() {
                            i.media.destroy(), Codo(R).remove();
                            for (var e = 0; e < CodoPlayerAPI.length; e++) CodoPlayerAPI[e].instance === i.instance && CodoPlayerAPI.splice(i.instance, 1)
                        },
                        onReady: e.onReady
                    };
                return i.settings.controls = {
                    hideDelay: e.controls.hideDelay || 5,
                    fadeDelay: e.controls.fadeDelay || 20,
                    show: function() {
                        var t = e.controls.show || "auto";
                        return Codo().isTouch() && (t = "never"), Codo().isMobile() && (t = "never"), Codo().isTouch() && !Codo().isMobile() && (t = "always"), t
                    }(),
                    all: function() {
                        return e.controls.all === !1 ? !1 : !0
                    }(),
                    play: function() {
                        return e.controls.play === !1 ? !1 : !0
                    }(),
                    seek: function() {
                        return e.controls.seek === !1 ? !1 : !0
                    }(),
                    seeking: function() {
                        return e.controls.seeking === !1 ? !1 : !0
                    }(),
                    volume: function() {
                        return Codo().isTouch() ? void 0 : e.controls.volume ? e.controls.volume : e.controls.volume !== !1 ? "horizontal" : void 0
                    }(),
                    fullscreen: function() {
                        return e.controls.fullscreen === !1 || Codo().isMobile() ? !1 : !0
                    }(),
                    title: function() {
                        return e.controls.title === !1 ? !1 : !0
                    }(),
                    time: function() {
                        return e.controls.time === !1 ? !1 : !0
                    }(),
                    hd: function() {
                        return e.controls.hd === !1 ? !1 : !0
                    }(),
                    playBtn: function() {
                        return Codo().isMobile() ? !1 : e.controls.playBtn === !1 ? !1 : !0
                    }(),
                    loadingText: e.controls.loadingText || "Loading...",
                    foreColor: e.controls.foreColor || "white",
                    backColor: e.controls.backColor || "#454545",
                    bufferColor: e.controls.bufferColor || "#666666",
                    progressColor: e.controls.progressColor || "#ff0000"
                }, i
            },
            c = [],
            d = [],
            f = [],
            A = [],
            g = [],
            p = [],
            h = [],
            m = [],
            v = [],
            y = [],
            C = [],
            w = [],
            T = [],
            M = [],
            b = [],
            k = [],
            D = [],
            N = [],
            S = [],
            x = [],
            P = function() {
                function e(e, t, n) {
                    if (e) {
                        if (n = n || e.priority, K && K.off(), X && (X.reset(), X.pause(), X.title(U.settings.controls.loadingText), X.setVolume(U.settings.volume), U.settings.controls.hd && (e.src && e.srcHD ? e.src && e.srcHD && (X.hd.show(), "srcHD" === n ? X.hd.on() : X.hd.off()) : (n = "src", X.hd.off(), X.hd.hide())), X.on()), q && q.on(), e && (e.engine = e.engine || "auto", e[n] && e[n].length > 0))
                            for (var o = 0; o < e[n].length; o++) {
                                var i = e[n][o];
                                if ("youtube" == e.engine) return e.activeUrl = i, e.platformName = "YOUTUBE", e.mediaType = "video", void(e.platformName != U.media.platformName ? (U.media.destroy && U.media.destroy(), U.media = new V(e, e.mediaType, t, n)) : U.media.play(e, t, n));
                                if (e.rtmp) return e.engine = "flash", e.activeUrl = i, e.platformName = "videoSWF", e.mediaType = "video", void(e.platformName != U.media.platformName ? (U.media.destroy && U.media.destroy(), U.media = new Q(e, e.mediaType, t, n)) : U.media.play(e, t, n));
                                var r = document.createElement("video"),
                                    s = document.createElement("audio"),
                                    a = i.match(/\.[0-9a-z]+$/i);
                                if (a = a ? a[0].replace(".", "") : "mp4", r.canPlayType)
                                    if (r.canPlayType("video/" + a).length > 0 || "m3u8" == a) {
                                        if ("html5" == e.engine || "auto" == e.engine) return e.activeUrl = i, e.platformName = "videoHTML5", e.mediaType = "video", "m3u8" == a && (e.m3u8 = !0), void(e.platformName != U.media.platformName ? (U.media.destroy && U.media.destroy(), U.media = new L(e, e.mediaType, t, n)) : U.media.play(e, t, n))
                                    } else if (s.canPlayType("audio/" + a).length > 0 && ("html5" == e.engine || "auto" == e.engine)) return e.activeUrl = i, e.platformName = "audioHTML5", e.mediaType = "audio", void(e.platformName != U.media.platformName ? (U.media.destroy && U.media.destroy(), U.media = new L(e, e.mediaType, t, n)) : U.media.play(e, t, n));
                                if ("mp4" == a || "flv" == a) {
                                    if ("flash" == e.engine || "auto" == e.engine) return e.activeUrl = i, e.platformName = "videoSWF", e.mediaType = "video", void(U.media = new Q(e, e.mediaType, t, n))
                                } else if (!("mp3" != a && "wav" != a || "flash" != e.engine && "auto" != e.engine)) return e.activeUrl = i, e.platformName = "audioSWF", e.mediaType = "audio", void(e.platformName != U.media.platformName ? (U.media.destroy && U.media.destroy(), U.media = new Q(e, e.mediaType, t, n)) : U.media.play(e, t, n))
                            }
                        K.on("source not recognized");
                        for (var o = 0; o < M.length; o++) M[o] && M[o]()
                    }
                }
                return {
                    set: function(t, n, o) {
                        e(t, n, o)
                    }
                }
            },
            E = function(e) {
                function t(t) {
                    if (t) {
                        var n = [];
                        if ("string" == typeof t) n.push({
                            src: [t]
                        });
                        else if ("object" == typeof t)
                            if (t[0])
                                for (var o = 0; o < t.length; o++) "string" == typeof t[o] ? n.push(t[o]) : "object" == typeof t[o] && t[o].src && ("string" == typeof t[o].src ? (n.push(t[o]), n[n.length - 1].src = [t[o].src]) : t[o].src[0] && n.push(t[o]));
                            else t.src && ("string" == typeof t.src ? (n.push(t), n[n.length - 1].src = [t.src]) : t.src[0] && n.push(t));
                        for (o = 0; o < n.length; o++) n[o].id = o, n[o].hasPrevious = 0 !== o ? !0 : !1, n[o].hasNext = o < n.length - 1 ? !0 : !1, n[o].poster = n[o].poster || e.settings.poster, n[o].engine = n[o].engine || e.settings.engine, n[o].rtmp = n[o].rtmp || e.settings.rtmp, n[o].cuepoints = n[o].cuepoints || e.settings.cuepoints, n[o].priority = n[o].priority || e.settings.priority;
                        return n
                    }
                }

                function n(e) {
                    if (e) {
                        var t, n = [];
                        if ("string" == typeof e) n.push(e);
                        else if ("object" == typeof e)
                            if (e[0])
                                for (t = 0; t < e.length; t++) "string" == typeof e[t] ? n.push(e[t]) : "object" == typeof e[t] && e[t].src && ("string" == typeof e[t].src ? (n.push(e[t]), n[n.length - 1].src = [e[t].src]) : e[t].src[0] && n.push(e[t]));
                            else e.src && ("string" == typeof e.src ? (n.push(e), n[n.length - 1].src = [e.src]) : e.src[0] && n.push(e));
                        return n
                    }
                    return !1
                }

                function o(t) {
                    i && Codo(i).remove(), i = Codo(e.DOM.parent).add({
                        el: "div",
                        className: e.className + "-playlist-wrap"
                    });
                    for (var n = Codo(i).add({
                            el: "ul",
                            className: e.className + "-playlist-ul",
                            style: "position: relative; width: 100%;"
                        }), o = 0; o < r.clips.length; o++) {
                        var a = Codo(n).add({
                                el: "li",
                                style: "cursor: pointer; overflow: auto;"
                            }),
                            l = Codo(a).add({
                                el: "span",
                                className: e.className + "-playlist-ul-id",
                                style: "float: left;",
                                innerHTML: r.clips[o].id + 1
                            }),
                            u = Codo(a).add({
                                el: "span",
                                className: e.className + "-playlist-ul-title",
                                style: "float: left;",
                                innerHTML: r.clips[o].title || ""
                            });
                        a.setAttribute("data-row", o), l.setAttribute("data-row", o), u.setAttribute("data-row", o), Codo(a).on(s, function(t) {
                            t.stopPropagation && t.preventDefault && (t.stopPropagation(), t.preventDefault()), t = t || window.event;
                            var n = t.target || t.srcElement;
                            e.plugins && e.plugins.advertising && e.plugins.advertising.isAd || (e.system.initClickMade = !0, e.playlist.next(n.getAttribute("data-row"), "autoplay"), Codo().isTouch() && e.media.play())
                        })
                    }
                    for (var c = n.getElementsByTagName("li"), o = 0; o < c.length; o++) Codo(c[o]).removeClass(e.className + "-playlist-currentClip"), c[o].getAttribute("data-row") == r.currentIndex && Codo(c[o]).addClass(e.className + "-playlist-currentClip")
                }
                var i, r = {
                    breakTime: "0",
                    currentIndex: "0",
                    set: function(e, o, i) {
                        i ? this.currentIndex = i : this.currentIndex = "0", this.clips = t(e);
                        for (var r = 0; r < this.clips.length; r++) this.clips[r].srcHD && (this.clips[r].srcHD = n(this.clips[r].srcHD));
                        this.next(this.currentIndex, o)
                    },
                    next: function(t, n) {
                        return this.breakTime = "0", !a && q && 4670 != q.getImage().src.length ? void K.on() : (t && t >= 0 && t < this.clips.length ? this.currentIndex = t : this.currentIndex < this.clips.length - 1 ? this.currentIndex++ : this.currentIndex = "0", e.settings.playlist && o(), void G.set(this.clips[this.currentIndex], n))
                    },
                    same: function(t) {
                        this.breakTime = e.media.getCurrentTime ? e.media.getCurrentTime() : "0", G.set(this.clips[this.currentIndex], "autoplay", t)
                    },
                    getCurrentClip: function() {
                        return this.clips[this.currentIndex]
                    }
                };
                return r
            },
            I = function(e) {
                function t() {
                    M ? (o(), M = !1) : (n(), M = !0)
                }

                function n() {
                    b = e.media.getVolume(), e.media.setVolume("0")
                }

                function o() {
                    e.media.setVolume(b)
                }

                function i(t) {
                    var n, o;
                    if ("horizontal" == e.settings.controls.volume) var i = Codo().mouse(t).x,
                        r = Codo(C).getLeft(),
                        s = Codo(C).getWidth(),
                        n = Math.round(i - r);
                    else if ("vertical" == e.settings.controls.volume) var i = Codo().mouse(t).y,
                        r = Codo(C).getTop(),
                        s = Codo(C).getHeight(),
                        n = Math.round(r - i + s);
                    n >= 0 && s >= n && (o = Math.round(100 * n / s), e.media.setVolume && e.media.setVolume(o))
                }

                function r(t) {
                    if (e.media.isMetaDataLoaded && e.media.isMetaDataLoaded()) {
                        if (e.settings.controls.volume && !Codo().isTouch()) {
                            if ("horizontal" == e.settings.controls.volume) var n = Codo(C).getWidth();
                            else if ("vertical" == e.settings.controls.volume) var n = Codo(C).getHeight();
                            if (t >= 0 && 100 >= t) {
                                var o = Math.round(n * t / 100);
                                "horizontal" == e.settings.controls.volume ? w.style.width = o + "px" : "vertical" == e.settings.controls.volume && (w.style.marginTop = n - o + "px", w.style.height = o + "px")
                            }
                        }
                    } else "horizontal" == e.settings.controls.volume ? w.style.width = t + "%" : "vertical" == e.settings.controls.volume && (w.style.marginTop = n - t + "%", w.style.height = t + "%")
                }

                function a() {
                    e.settings.controls.time && (e.playlist.getCurrentClip() && e.playlist.getCurrentClip().rtmp || e.playlist.getCurrentClip() && e.playlist.getCurrentClip().m3u8 ? D.innerHTML = "LIVE" : e.media.getCurrentTime() && e.media.getDuration() && (D.innerHTML = Codo().secsToTime(e.media.getCurrentTime()) + " / " + Codo().secsToTime(e.media.getDuration())))
                }
                var l, u, c = !1,
                    d = !1,
                    f = 100,
                    A = function(t) {
                        switch (t) {
                            case "in":
                                clearTimeout(l), clearTimeout(u), f = 100, g.style.opacity = f / 100, g.style.filter = "alpha(opacity=" + f + ")";
                                break;
                            case "out":
                                u = setTimeout(function() {
                                    l = setInterval(function() {
                                        f >= 0 && 0 == c ? (g.style.opacity = f / 100, g.style.filter = "alpha(opacity=" + f + ")", f -= 10) : (clearInterval(l), clearTimeout(u))
                                    }, 20)
                                }, 1e3 * e.settings.controls.hideDelay)
                        }
                    },
                    g = e.DOM.controls = Codo(e.DOM.container).add({
                        el: "div",
                        className: e.className + "-controls-wrap",
                        style: "display: none;"
                    });
                "never" != e.settings.controls.show && (g.style.display = "block"), Codo(g).on("mouseover", function() {
                    d = !0
                }), Codo(g).on("mouseout", function() {
                    d = !1
                });
                var p = (Codo(g).add({
                    el: "div",
                    id: e.id + "-controls-shade",
                    className: e.className + "-controls-shade"
                }), Codo(g).add({
                    el: "div",
                    id: e.id + "-controls",
                    className: e.className + "-controls"
                }));
                if (e.settings.controls.play) {
                    var h = Codo(p).add({
                        el: "div",
                        className: e.className + "-controls-play-button"
                    });
                    Codo(h).on(s, function(t) {
                        t.stopPropagation && t.preventDefault && (t.stopPropagation(), t.preventDefault()), e.media.toggle && (e.settings.preload ? e.media.toggle() : (e.system.initClickMade ? e.media.toggle() : (e.media.toggle(), Codo().isTouch() && e.media.getParent().play()), e.system.initClickMade = !0))
                    })
                }
                if (e.settings.controls.title) var m = Codo(p).add({
                    el: "div",
                    id: e.id + "-controls-title-text",
                    className: e.className + "-controls-title-text"
                });
                if (e.settings.controls.fullscreen) {
                    var v = Codo(p).add({
                        el: "div",
                        className: e.className + "-controls-fullscreen-off-button"
                    });
                    Codo(v).on(s, function(t) {
                        t.stopPropagation && t.preventDefault && (t.stopPropagation(), t.preventDefault()), e.media.toggleFullScreen && e.media.toggleFullScreen(t)
                    })
                }
                var y = !1;
                if (e.settings.controls.volume) {
                    var C = Codo(p).add({
                            el: "div",
                            className: e.className + "-controls-volume-" + e.settings.controls.volume
                        }),
                        w = Codo(C).add({
                            el: "div",
                            className: e.className + "-controls-volume-" + e.settings.controls.volume + "-bar"
                        }),
                        T = Codo(w).add({
                            el: "div",
                            className: e.className + "-controls-volume-handle"
                        });
                    Codo(C).on("mousedown", function(e) {
                        y = !0, i(e)
                    }), Codo(T).on("dblclick", function(e) {
                        t()
                    })
                }
                var M, b;
                if (e.settings.controls.hd) var k = function() {
                    function t(t) {
                        t ? (Codo(o).addClass("active"), e.playlist.getCurrentClip().priority = "srcHD", e.playlist.same("srcHD"), n = t) : (Codo(o).removeClass("active"), e.playlist.getCurrentClip().priority = "src", e.playlist.same("src"), n = t)
                    }
                    var n = "srcHD" === e.settings.priority ? !0 : !1,
                        o = Codo(p).add({
                            el: "div",
                            className: e.className + "-controls-hd-text",
                            innerHTML: "HD",
                            style: "display: none;"
                        });
                    return Codo(o).on(s, function(e) {
                        e.stopPropagation && e.preventDefault && (e.stopPropagation(), e.preventDefault()), n ? (t(!1), n = !1) : (t(!0), n = !0)
                    }), {
                        on: function() {
                            n = !0, Codo(o).addClass("active")
                        },
                        off: function() {
                            n = !1, Codo(o).removeClass("active")
                        },
                        show: function() {
                            o.style.display = "block"
                        },
                        hide: function() {
                            o.style.display = "none"
                        },
                        setHD: function(e) {
                            t(e)
                        }
                    }
                }();
                if (e.settings.controls.time) var D = Codo(p).add({
                    el: "div",
                    className: e.className + "-controls-time-text",
                    innerHTML: Codo().secsToTime(0) + " / " + Codo().secsToTime(0)
                });
                var N = !1;
                if (e.settings.controls.seek) {
                    var S = Codo(p).add({
                            el: "div",
                            className: e.className + "-controls-seek"
                        }),
                        x = Codo(S).add({
                            el: "div",
                            className: e.className + "-controls-seek-buffer-bar",
                            style: "width: 0px;"
                        }),
                        P = Codo(S).add({
                            el: "div",
                            className: e.className + "-controls-seek-progress-bar",
                            style: "width: 0px;"
                        });
                    Codo(P).add({
                        el: "div",
                        className: e.className + "-controls-seek-handle"
                    });
                    Codo(S).on("mousedown", function(t) {
                        e.media.isMetaDataLoaded && e.media.isMetaDataLoaded() && e.settings.controls.seeking && (N = !0, e.media.setCurrentTime && e.media.setCurrentTime(X.seek(t)))
                    })
                }
                Codo(document).on("mouseup", function(t) {
                    y = !1, N && e.settings.controls.seeking && (e.media.setCurrentTime && e.media.setCurrentTime(X.seek(t)), N = !1)
                });
                var E;
                return Codo(document).on("mousemove", function(t) {
                    N && X.seek(t), y && i(t);
                    var n, o, r, s, a = Codo().mouse(t).x - Codo().scrollX(),
                        l = Codo().mouse(t).y - Codo().scrollY();
                    $.getState() ? (n = 0, o = 0, r = Codo().screen().width, s = Codo().screen().height) : (n = Codo(e.DOM.container).getTop(), o = Codo(e.DOM.container).getLeft(), r = o + e.settings.currentWidth, s = n + e.settings.currentHeight), a >= o && r >= a && l >= n && s >= l ? (e.settings.controls.seek && Codo(S).addClass("hover"), !c && e.system.initClickMade && (c = !0, A("in")), clearTimeout(E), "always" != e.settings.controls.show && !d && $.getState() && (E = setTimeout(function() {
                        c = !1, A("out")
                    }, 500))) : (e.settings.controls.seek && Codo(S).removeClass("hover"), c && !d && e.system.initClickMade && e.playlist.getCurrentClip() && "always" != e.settings.controls.show && "audio" != e.playlist.getCurrentClip().mediaType && (c = !1, clearTimeout(E), A("out")))
                }), {
                    reset: function() {
                        P && (P.style.width = 0), x && (x.style.width = 0)
                    },
                    on: function() {
                        "never" != e.settings.controls.show && (A("in"), "always" != e.settings.controls.show && e.system.initClickMade && A("out"))
                    },
                    off: function(e) {
                        "nofade" != e ? A("out") : g.style.display = "none"
                    },
                    play: function() {
                        e.settings.controls.play && Codo(h).addClass(e.className + "-controls-pause-button")
                    },
                    pause: function() {
                        e.settings.controls.play && Codo(h).removeClass(e.className + "-controls-pause-button")
                    },
                    title: function(t) {
                        if (e.settings.controls.title && m) {
                            if (!t) return m.innerHTML;
                            m.innerHTML = t
                        }
                    },
                    time: function() {
                        a()
                    },
                    hd: e.settings.controls.hd ? k : void 0,
                    progress: function(t) {
                        if (e.settings.controls.seek && !N) {
                            var n = t ? e.media.getDuration() : .1;
                            if (P && n && e.playlist.getCurrentClip()) {
                                var o = e.playlist.getCurrentClip().rtmp ? 0 : Codo(S).getWidth() * (t || 0) / n;
                                P.style.width = o + "px"
                            }
                            a()
                        }
                    },
                    buffer: function(t) {
                        if (e.settings.controls.seek) {
                            var n = t ? e.media.getDuration() : .1;
                            x && n && (x.style.width = Codo(S).getWidth() * (t || 0) / n + "px")
                        }
                    },
                    seek: function(t) {
                        if (e.settings.controls.seek && !e.playlist.getCurrentClip().rtmp) {
                            var n;
                            if (e.media.getDuration) {
                                var o = Codo().mouse(t).x - Codo(S).getLeft();
                                n = e.media.getDuration() * o / Codo(S).getWidth(), o >= 0 && o <= S.offsetWidth && (P.style.width = o + "px", e.settings.controls.time && n && e.media.getDuration() && (D.innerHTML = Codo().secsToTime(n) + " / " + Codo().secsToTime(e.media.getDuration())))
                            }
                            return n
                        }
                    },
                    setVolume: function(t) {
                        e.settings.controls.volume && r(t || "0")
                    },
                    showFullScreen: function() {
                        e.settings.controls.fullscreen && Codo(v).addClass(e.className + "-controls-fullscreen-on-button")
                    },
                    hideFullScreen: function() {
                        e.settings.controls.fullscreen && Codo(v).removeClass(e.className + "-controls-fullscreen-on-button")
                    }
                }
            },
            H = function() {
                var e, t, n, o = 0,
                    i = 10,
                    r = !1,
                    s = Codo(U.DOM.container).add({
                        el: "div",
                        id: U.id + "-loading-wrap",
                        className: U.className + "-loading-wrap",
                        style: "position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: #000; opacity: 0; filter: alpha(opacity=0); visibility: hidden;"
                    }),
                    u = new Image;
                u.src = a ? U.settings.loader : l, u.onload = function() {
                    e = u.width, t = u.height, n = Codo(s).add({
                        el: "img",
                        src: u.src,
                        style: "position: absolute; top: " + (U.settings.currentHeight / 2 - t / 2) + "px; left: " + (U.settings.currentWidth / 2 - e / 2) + "px;"
                    })
                }, u.onerror = function() {
                    r = !0, u = null, n = Codo(n).remove()
                };
                var c;
                return {
                    getImage: function() {
                        return u
                    },
                    resize: function(o, i) {
                        n && (n.style.top = i / 2 - t / 2 + "px", n.style.left = o / 2 - e / 2 + "px")
                    },
                    on: function() {
                        clearInterval(c), Z && Z.getState() && Z.off();
                        var e = 80;
                        s.style.opacity = o / 100, s.style.filter = "alpha(opacity=" + o + ")", s.style.visibility = "visible", c = setInterval(function() {
                            e > o ? (o += i, s.style.opacity = o / 100, s.style.filter = "alpha(opacity=" + o + ")") : (s.style.opacity = e / 100, s.style.filter = "alpha(opacity=" + e + ")", clearInterval(c))
                        }, 20)
                    },
                    off: function(e) {
                        clearInterval(c), "cover" == e && Z && !Z.getState() && Z.on();
                        var t = 0;
                        s.style.opacity = o / 100, s.style.filter = "alpha(opacity=" + o + ")", c = setInterval(function() {
                            o > t ? (o -= i, s.style.opacity = o / 100, s.style.filter = "alpha(opacity=" + o + ")") : (s.style.opacity = t / 100, s.style.filter = "alpha(opacity=" + t + ")", s.style.visibility = "hidden", clearInterval(c))
                        }, 20)
                    }
                }
            },
            B = function() {
                function e() {
                    U.media.toggle && (U.settings.preload ? U.media.toggle() : (U.system.initClickMade ? U.media.toggle() : (U.media.toggle(), Z.off(), q && q.on(), Codo().isTouch() && U.media.getParent().play()), U.system.initClickMade = !0))
                }
                var t, n, o, i, r = !1,
                    a = 0,
                    u = 25,
                    c = U.DOM.overlay = Codo(U.DOM.container).add({
                        el: "div",
                        className: U.className + "-overlay-wrap",
                        style: "position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
                    });
                Codo(c).on(s, function(t) {
                    t.stopPropagation && t.preventDefault && (t.stopPropagation(), t.preventDefault()), e()
                });
                var d = Codo(c).add({
                    el: "div",
                    className: U.className + "-overlay-wrap-bg",
                    style: "position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity:0; filter: alpha(opacity=0); visibility: hidden; cursor: pointer;"
                });
                if (U.settings.controls.playBtn) {
                    var f = Codo(d).add({
                            el: "div",
                            className: U.className + "-overlay-play-button",
                            style: "cursor: pointer;"
                        }),
                        A = Codo(U.DOM.container).add({
                            el: "div",
                            className: U.className + "-overlay-menu",
                            style: "position: absolute; min-width: 200px; max-width: 80%; max-height: 60%; vertical-align: middle; font-size: 20px; text-shadow: 0px 0px 1px #000; background: black; background: rgba(0,0,0,.8); visibility: hidden; text-align: center;"
                        }),
                        g = Codo(A).add({
                            el: "div",
                            className: U.className + "-overlay--menu-title",
                            style: "line-height: 20px; padding: 0 2px; background: #454545; text-align: right; cursor: pointer;",
                            innerHTML: "&#10006;"
                        });
                    Codo(g).on(s, function(e) {
                        e.stopPropagation && e.preventDefault && (e.stopPropagation(), e.preventDefault()), Codo(A).fadeOut(20)
                    });
                    var p = (Codo(A).add({
                        el: "a",
                        href: "http://codoplayer.com/?ref=" + location.href,
                        target: "_blank",
                        style: "position: relative; width: 100%; color: white; background: url(" + l + ") no-repeat center 20px; margin: 0; padding: 50px 0 20px; text-decoration: none; display: block;",
                        innerHTML: "{{version}} {{kind}}"
                    }), setInterval(function() {
                        t = Codo(f).getWidth(), n = Codo(f).getHeight(), o = Codo(A).getWidth(), i = Codo(A).getHeight(), t > 0 && n > 0 && (f.style.top = (U.settings.currentHeight - n) / 2 + "px", f.style.left = (U.settings.currentWidth - t) / 2 + "px", A.style.top = (U.settings.currentHeight - i) / 2 + "px", A.style.left = (U.settings.currentWidth - o) / 2 + "px", clearInterval(p))
                    }, 20));
                    setTimeout(function() {
                        clearInterval(p)
                    }, 3e4)
                }
                var h;
                return {
                    resize: function(e, r) {
                        f && t && n && (f.style.top = (r - n) / 2 + "px", f.style.left = (e - t) / 2 + "px", A.style.top = (r - i) / 2 + "px", A.style.left = (e - o) / 2 + "px")
                    },
                    menu: function() {
                        Codo(A).fadeIn(20)
                    },
                    on: function() {
                        clearInterval(h), r = !0;
                        var e = 100;
                        d.style.opacity = a / 100, d.style.filter = "alpha(opacity=" + a + ")", d.style.visibility = "visible", h = setInterval(function() {
                            e > a ? (a += u, d.style.opacity = a / 100, d.style.filter = "alpha(opacity=" + a + ")") : (d.style.opacity = e / 100, d.style.filter = "alpha(opacity=" + e + ")", clearInterval(h))
                        }, 20)
                    },
                    off: function() {
                        clearInterval(h), r = !1;
                        var e = 0;
                        d.style.opacity = a / 100, d.style.filter = "alpha(opacity=" + a + ")", h = setInterval(function() {
                            a > e ? (a -= u, d.style.opacity = a / 100, d.style.filter = "alpha(opacity=" + a + ")") : (d.style.opacity = e / 100, d.style.filter = "alpha(opacity=" + e + ")", d.style.visibility = "hidden", clearInterval(h))
                        }, 20)
                    },
                    getState: function() {
                        return r
                    }
                }
            },
            O = function(e) {
                Codo(e.DOM.container).add({
                    el: "div",
                    id: e.id + "-error-wrap",
                    className: e.className + "-error-wrap",
                    style: "position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: none;"
                });
                return {
                    on: function(t) {
                        t = t || "", Codo().log("Error: " + t), X.title("Error: " + t), a && q && q.off();
                        var n = e.playlist.getCurrentClip();
                        if (n && n.hasNext && !e.system.initPlayMade) var o = setTimeout(function() {
                            n.id == e.playlist.getCurrentClip().id && (e.system.firstClipOver = !0, e.playlist.next(), clearTimeout(o))
                        }, 3e3)
                    },
                    off: function() {}
                }
            },
            F = function() {
                return {
                    resize: function(e, t, n, o) {
                        var i = t / n,
                            r = U.settings.currentWidth / U.settings.currentHeight,
                            s = U.settings.mediaWidth = r > i ? U.settings.currentHeight * i : U.settings.currentWidth,
                            a = U.settings.mediaHeight = r > i ? U.settings.currentHeight : U.settings.currentWidth / i;
                        o || $.getState() || (U.DOM.parent.style.width = U.DOM.container.style.width = U.settings.currentWidth + "px", U.DOM.parent.style.minHeight = U.DOM.container.style.height = U.settings.currentHeight + "px"), e && (e.width = s, e.height = a, e.style.width = s + "px", e.style.height = a + "px", e.style.top = U.settings.currentHeight / 2 - a / 2 + "px", e.style.left = U.settings.currentWidth / 2 - s / 2 + "px", e.resize && e.resize(s, a)), q && q.resize(U.settings.currentWidth, U.settings.currentHeight), Z && Z.resize(U.settings.currentWidth, U.settings.currentHeight)
                    }
                }
            },
            W = function(e) {
                function t(t, i) {
                    t ? (o = !0,
                        e.DOM.container.requestFullScreen ? e.DOM.container.requestFullScreen() : e.DOM.container.mozRequestFullScreen ? e.DOM.container.mozRequestFullScreen() : e.DOM.container.webkitRequestFullScreen && e.DOM.container.webkitRequestFullScreen(), n && i ? (e.settings.currentWidth = screen.width, e.settings.currentHeight = screen.height) : (e.settings.currentWidth = Codo().screen().width, e.settings.currentHeight = Codo().screen().height), e.DOM.container.style.position = "fixed", e.DOM.container.style.top = "0px", e.DOM.container.style.left = "0px", e.DOM.container.style.width = "100%", e.DOM.container.style.height = "100%", e.DOM.container.style.zIndex = 999999999, X && X.showFullScreen()) : (o = !1, document.cancelFullScreen ? document.cancelFullScreen() : document.mozCancelFullScreen ? document.mozCancelFullScreen() : document.webkitCancelFullScreen && document.webkitCancelFullScreen(), e.settings.currentWidth = e.settings.width, e.settings.currentHeight = e.settings.height, e.DOM.container.style.position = "relative", e.DOM.container.style.width = e.settings.currentWidth + "px", e.DOM.container.style.height = e.settings.currentHeight + "px", e.DOM.container.style.zIndex = 0, X && X.hideFullScreen()), !e.settings.preload && !e.system.initPlayMade || "audio" == e.playlist.getCurrentClip().mediaType ? z.resize(e.media.getPoster(), e.settings.mediaWidth, e.settings.mediaHeight, "fullscreen") : "video" == e.playlist.getCurrentClip().mediaType && z.resize(e.media.getParent(), e.settings.mediaWidth, e.settings.mediaHeight, "fullscreen"), e.system.isFullScreen = o
                }
                var n, o = !1;
                return e.DOM.container.requestFullScreen ? n = "f" : e.DOM.container.mozRequestFullScreen ? n = "moz" : e.DOM.container.webkitRequestFullScreen && (n = "webkit"), n && Codo(document).on(n + "fullscreenchange", function(t) {
                    var n = !1;
                    document.fullscreenElement ? n = !0 : document.mozFullScreenElement ? n = !0 : document.webkitFullscreenElement && (n = !0), n || e.media.fullScreenExit()
                }), Codo(window).on("orientationchange", function(e) {
                    t(!0, e)
                }), {
                    on: function(e) {
                        t(!0, e)
                    },
                    off: function(e) {
                        t(!1, e)
                    },
                    getState: function() {
                        return o
                    }
                }
            },
            L = function(e, t, n) {
                function o() {
                    F(), fe = !1, pe = !1, he = !1, me = !1, q && q.on(), X.title(U.settings.controls.loadingText), X.setVolume(U.settings.volume || "0"), setTimeout(function() {
                        e.activeUrl.search("relative://") > -1 ? ue.src = U.system.rootPath + e.activeUrl.replace("relative://", "") : ue.src = e.activeUrl, ue.load(), W(), Codo().isTouch() && ee()
                    }, 500)
                }

                function i() {
                    de ? s() : r()
                }

                function r() {
                    fe ? (U.system.initClickMade = U.system.initPlayMade = de = !0, ue.play(), X.play()) : U.settings.preload || o(U.playlist.getCurrentClip())
                }

                function s() {
                    fe && (de = !1, ue.pause(), X.pause())
                }

                function a(e) {
                    fe ? ue.volume = e / 100 : (U.settings.volume = e, X.setVolume(e || "0"))
                }

                function l() {
                    return fe ? Math.round(100 * ue.volume) : void 0
                }

                function u() {
                    return fe ? ue.duration : void 0
                }

                function P(e) {
                    fe && (ue.currentTime = e)
                }

                function E() {
                    return ue.currentTime || "0"
                }

                function I(e) {
                    $.getState() ? B(e) : H(e)
                }

                function H(e) {
                    $.on(e), Ce.once(), te()
                }

                function B(e) {
                    $.off(e), Ce.once(), ne()
                }

                function O() {
                    Ce.end(), U.media = {}, Codo(ue).off("loadedmetadata", ee), Codo(ue).off("play", L), Codo(ue).off("pause", Q), Codo(ue).off("ended", V), Codo(ue).off("progress", R), Codo(ue).off("seeking", J), Codo(ue).off("seeked", G), Codo(ue).off("volumechange", Y), Codo(ue).off("error", _), ce && Codo(ce).remove(), ue && Codo(ue).remove()
                }

                function F() {
                    for (var e = 0; e < c.length; e++) c[e] && c[e]()
                }

                function W() {
                    for (var e = 0; e < d.length; e++) d[e] && d[e](U)
                }

                function L() {
                    if (fe) {
                        de = !0, Z && Z.off(), ge || X.play(), Ce.start(), pe || (pe = !0, oe());
                        for (var e = 0; e < A.length; e++) A[e] && A[e](ue.currentTime)
                    }
                }

                function Q() {
                    if (fe && Ae.duration - ue.currentTime > .1) {
                        de = !1, ge || (Z && Z.on(), X.pause());
                        for (var e = 0; e < g.length; e++) g[e] && g[e](ue.currentTime)
                    }
                }

                function V() {
                    de = !1, U.system.firstClipOver = !0, X.pause(), Ce.end(), q && q.on(), ae(), U.playlist.next();
                    for (var e = 0; e < p.length; e++) p[e] && p[e]()
                }

                function Y() {
                    U.settings.volume = l(), X.setVolume(U.settings.volume || "0");
                    for (var e = 0; e < C.length; e++) C[e] && C[e](U.settings.volume)
                }

                function R() {
                    if (fe) try {
                        X.buffer(ue.buffered.end(0));
                        for (var e = 0; e < m.length; e++) m[e] && m[e](ue.buffered.end(0))
                    } catch (t) {}
                }

                function j() {
                    if (ue.currentTime) {
                        X.progress(ue.currentTime);
                        for (var e = 0; e < h.length; e++) h[e] && h[e](ue.currentTime)
                    }
                }

                function J() {
                    Ce.end();
                    for (var e = 0; e < v.length; e++) v[e] && v[e](ue.currentTime)
                }

                function G() {
                    Ce.start();
                    for (var e = 0; e < y.length; e++) y[e] && y[e](ue.currentTime)
                }

                function _() {
                    de = !1, Ce.end();
                    var e = t + " not found";
                    K.on(e);
                    for (var n = 0; n < M.length; n++) M[n] && M[n](e)
                }

                function ee() {
                    if (fe = !0, "video" == t) Ae.width = ue.videoWidth || U.settings.currentWidth, Ae.height = ue.videoHeight || U.settings.currentHeight, Ae.duration = ue.duration, ce && (ce = Codo(ce).remove()), z.resize(ue, Ae.width, Ae.height);
                    else if ("audio" == t && (Ae.duration = ue.duration, e.poster)) {
                        var o = new Image;
                        o.src = e.poster, o.onload = function() {
                            Ae.width = o.width, Ae.height = o.height, ce && (ce = Codo(ce).remove()), ce = Codo(ve).add({
                                el: "img",
                                src: o.src,
                                style: "position: absolute; top: 0; left: 0;"
                            }), z.resize(ce, o.width, o.height)
                        }
                    }
                    "0" !== U.playlist.breakTime && P(U.playlist.breakTime), n ? r() : U.system.firstClipOver ? (e.hasPrevious || U.settings.loop) && r() : (!U.settings.preload || U.settings.autoplay) && r(), de ? q && q.off() : q && q.off("cover"), X.title(e.title || " "), X.time(), ue.volume = U.settings.volume / 100, Ce.once();
                    for (var i = 0; i < f.length; i++) f[i] && f[i](Ae)
                }

                function te() {
                    for (var e = 0; e < w.length; e++) w[e] && w[e]()
                }

                function ne() {
                    for (var e = 0; e < T.length; e++) T[e] && T[e]()
                }

                function oe() {
                    for (var e = 0; e < b.length; e++) b[e] && b[e]()
                }

                function ie() {
                    for (var e = 0; e < k.length; e++) k[e] && k[e]()
                }

                function re() {
                    for (var e = 0; e < D.length; e++) D[e] && D[e]()
                }

                function se() {
                    for (var e = 0; e < N.length; e++) N[e] && N[e]()
                }

                function ae() {
                    for (var e = 0; e < S.length; e++) S[e] && S[e]()
                }

                function le() {
                    for (var e = 0; e < x.length; e++) x[e] && x[e]()
                }
                var ue, ce, de = !1,
                    fe = !1,
                    Ae = {},
                    ge = !1,
                    pe = !1,
                    he = !1,
                    me = !1,
                    ve = U.DOM.containerScreenCanvas;
                if (ve.innerHTML = "", ue = Codo(ve).add({
                        el: t,
                        style: "position: absolute; top: 0; left: 0;"
                    }), Codo(ve).add({
                        el: "div",
                        style: "position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
                    }), U.settings.preload || U.system.initClickMade) o();
                else {
                    if (e.poster) {
                        var ye = new Image;
                        ye.src = e.poster, ye.onload = function() {
                            ce = Codo(ve).add({
                                el: "img",
                                src: ye.src,
                                style: "position: absolute; top: 0; left: 0;"
                            }), z.resize(ce, ye.width, ye.height), U.settings.responsive && U.resize()
                        }
                    }
                    X.title(e.title || " "), q && q.off("cover")
                }
                var Ce = function() {
                    var t;
                    return {
                        start: function() {
                            clearInterval(t);
                            var n, o;
                            t = setInterval(function() {
                                R(), j();
                                var t = Math.round(ue.duration),
                                    i = Math.round(ue.currentTime),
                                    r = Math.round(t / 4),
                                    s = Math.round(t / 2),
                                    a = Math.round(t - t / 4);
                                switch (he && i > (n || r) && (he = !1), me && i > o && (me = !1), i) {
                                    case Math.round(t / 4):
                                        he || (he = !0, n = r, ie());
                                        break;
                                    case Math.round(t / 2):
                                        he || (he = !0, n = s, re());
                                        break;
                                    case Math.round(t - t / 4):
                                        he || (he = !0, n = a, se())
                                }
                                e.cuepoints && !me && -1 != e.cuepoints.indexOf(i) && (me = !0, o = e.cuepoints[e.cuepoints.indexOf(i)], le())
                            }, 20)
                        },
                        end: function() {
                            clearInterval(t)
                        },
                        once: function() {
                            R(), j()
                        }
                    }
                }();
                return Codo(ue).on("loadedmetadata", ee), Codo(ue).on("play", L), Codo(ue).on("pause", Q), Codo(ue).on("ended", V), Codo(ue).on("progress", R), Codo(ue).on("seeking", J), Codo(ue).on("seeked", G), Codo(ue).on("volumechange", Y), Codo(ue).on("error", _), {
                    platformName: "video" == t ? "videoHTML5" : "audioHTML5",
                    isPlaying: function() {
                        return de
                    },
                    isMetaDataLoaded: function() {
                        return fe
                    },
                    onBeforeLoad: function(e) {
                        e && c.push(e)
                    },
                    onLoad: function(e) {
                        e && d.push(e)
                    },
                    onMetaData: function(e) {
                        e && f.push(e)
                    },
                    onPlay: function(e) {
                        e && A.push(e)
                    },
                    onPause: function(e) {
                        e && g.push(e)
                    },
                    onEnd: function(e) {
                        e && p.push(e)
                    },
                    onBuffer: function(e) {
                        e && m.push(e)
                    },
                    onProgress: function(e) {
                        e && h.push(e)
                    },
                    onSeekStart: function(e) {
                        e && v.push(e)
                    },
                    onSeekEnd: function(e) {
                        e && y.push(e)
                    },
                    onVolumeChange: function(e) {
                        e && C.push(e)
                    },
                    onFullScreenEnter: function(e) {
                        e && w.push(e)
                    },
                    onFullScreenExit: function(e) {
                        e && T.push(e)
                    },
                    onError: function(e) {
                        e && M.push(e)
                    },
                    getParent: function() {
                        return ue
                    },
                    getPoster: function() {
                        return ce
                    },
                    toggle: function() {
                        i()
                    },
                    play: function(t, i) {
                        t ? (e = t, n = i, o()) : r()
                    },
                    pause: function() {
                        s()
                    },
                    setVolume: function(e) {
                        a(e)
                    },
                    getVolume: function() {
                        return l()
                    },
                    getDuration: function() {
                        return u()
                    },
                    setCurrentTime: function(e) {
                        P(e)
                    },
                    getCurrentTime: function() {
                        return E()
                    },
                    toggleFullScreen: function(e) {
                        I(e)
                    },
                    fullScreenEnter: function() {
                        H()
                    },
                    fullScreenExit: function() {
                        B()
                    },
                    destroy: function() {
                        O()
                    },
                    onClipBegin: function(e) {
                        e && b.push(e)
                    },
                    onClipFirstQuarter: function(e) {
                        e && k.push(e)
                    },
                    onClipSecondQuarter: function(e) {
                        e && D.push(e)
                    },
                    onClipThirdQuarter: function(e) {
                        e && N.push(e)
                    },
                    onClipEnd: function(e) {
                        e && S.push(e)
                    },
                    onCuepoint: function(e) {
                        e && x.push(e)
                    }
                }
            },
            Q = function(e, t, n) {
                function o() {
                    fe.initClip(U.settings, U.playlist.getCurrentClip()), e.rtmp || r()
                }

                function i() {
                    r()
                }

                function r() {
                    if (U.settings.preload || U.system.initClickMade) s();
                    else {
                        if (e.poster) {
                            var t = new Image;
                            t.src = e.poster, t.onload = function() {
                                Ae = Codo(we).add({
                                    el: "img",
                                    src: t.src,
                                    style: "position: absolute; top: 0; left: 0;"
                                }), z.resize(Ae, t.width, t.height), U.settings.responsive && U.resize()
                            }
                        }
                        X.title(e.title || " "), q && q.off("cover")
                    }
                }

                function s() {
                    Q(), pe = !1, ve = !1, ye = !1, Ce = !1, q && q.on(), X.title(U.settings.controls.loadingText), X.setVolume(U.settings.volume || "0"), e.activeUrl.search("relative://") > -1 ? fe.setSrc(U.system.rootPath + e.activeUrl.replace("relative://", "")) : fe.setSrc(e.activeUrl), V(), Codo().isTouch() && oe()
                }

                function a() {
                    ge ? u() : l()
                }

                function l(t) {
                    pe ? (U.system.initClickMade = U.system.initPlayMade = ge = !0, fe.playClip(), e.rtmp && Y(), X.play()) : U.settings.preload || s(U.playlist.getCurrentClip())
                }

                function u() {
                    pe && (ge = !1, fe.pauseClip(), e.rtmp && R(), X.pause())
                }

                function P(e) {
                    pe ? fe.setVolume(e / 100 || "0") : (U.settings.volume = e, X.setVolume(e || "0")), te(e)
                }

                function E() {
                    return pe ? Math.round(100 * fe.getVolume()) : void 0
                }

                function I() {
                    return pe && fe.getDuration ? fe.getDuration() : void 0
                }

                function H(e) {
                    pe && fe.setCurrentTime(e)
                }

                function B() {
                    return fe.getCurrentTime ? fe.getCurrentTime() || "0" : void 0
                }

                function O(e) {
                    $.getState() ? W(e) : F(e)
                }

                function F(e) {
                    $.on(e), Te.once(), ie()
                }

                function W(e) {
                    $.off(e), Te.once(), re()
                }

                function L() {
                    Te.end(), U.API = {}, Ae && Codo(Ae).remove(), fe && Codo(fe).remove()
                }

                function Q() {
                    for (var e = 0; e < c.length; e++) c[e] && c[e]()
                }

                function V() {
                    for (var e = 0; e < d.length; e++) d[e] && d[e]()
                }

                function Y() {
                    if (pe) {
                        ge = !0, Z && Z.off(), me || X.play(), Te.start(), ve || (ve = !0, se());
                        for (var e = 0; e < A.length; e++) A[e] && A[e](fe.getCurrentTime())
                    }
                }

                function R() {
                    if (pe) {
                        ge = !1, me || (Z && Z.on(), X.pause());
                        for (var e = 0; e < g.length; e++) g[e] && g[e](fe.getCurrentTime())
                    }
                }

                function j() {
                    ge = !1, U.system.firstClipOver = !0, X.pause(), Te.end(), q && q.on(), ce(), U.playlist.next();
                    for (var e = 0; e < p.length; e++) p[e] && p[e]()
                }

                function J(e) {
                    if (pe) {
                        X.buffer(e);
                        for (var t = 0; t < m.length; t++) m[t] && m[t](e)
                    }
                }

                function G() {
                    if (fe.getCurrentTime && fe.getCurrentTime()) {
                        X.progress(fe.getCurrentTime());
                        for (var e = 0; e < h.length; e++) h[e] && fe.getCurrentTime && h[e](fe.getCurrentTime())
                    }
                }

                function _() {
                    Te.end();
                    for (var e = 0; e < v.length; e++) v[e] && v[e](fe.getCurrentTime())
                }

                function ee() {
                    Te.start();
                    for (var e = 0; e < y.length; e++) y[e] && y[e](fe.getCurrentTime())
                }

                function te(e) {
                    U.settings.volume = e, X.setVolume(e || "0");
                    for (var t = 0; t < C.length; t++) C[t] && C[t](U.settings.volume)
                }

                function ne() {
                    ge = !1, Te.end();
                    var e = t + " not found";
                    K.on(e);
                    for (var n = 0; n < M.length; n++) M[n] && M[n](e)
                }

                function oe(o) {
                    if (pe = !0, "video" == t) he = o, Ae && (Ae = Codo(Ae).remove()), z.resize(fe, he.width, he.height);
                    else if ("audio" == t && e.poster) {
                        var i = new Image;
                        i.src = e.poster, i.onload = function() {
                            he.width = i.width, he.height = i.height, Ae && (Ae = Codo(Ae).remove()), Ae = Codo(we).add({
                                el: "img",
                                src: i.src,
                                style: "position: absolute; top: 0; left: 0;"
                            }), z.resize(Ae, i.width, i.height)
                        }
                    }
                    "0" !== U.playlist.breakTime && H(U.playlist.breakTime), n ? l() : U.system.firstClipOver ? (e.hasPrevious || U.settings.loop) && l() : (!U.settings.preload || U.settings.autoplay) && l(), ge ? q && q.off() : q && q.off("cover"), X.title(e.title || " "), X.time(), fe.setVolume(U.settings.volume / 100 || "0"), Te.once();
                    for (var r = 0; r < f.length; r++) f[r] && f[r](he)
                }

                function ie() {
                    for (var e = 0; e < w.length; e++) w[e] && w[e]()
                }

                function re() {
                    for (var e = 0; e < T.length; e++) T[e] && T[e]()
                }

                function se() {
                    for (var e = 0; e < b.length; e++) b[e] && b[e]()
                }

                function ae() {
                    for (var e = 0; e < k.length; e++) k[e] && k[e]()
                }

                function le() {
                    for (var e = 0; e < D.length; e++) D[e] && D[e]()
                }

                function ue() {
                    for (var e = 0; e < N.length; e++) N[e] && N[e]()
                }

                function ce() {
                    for (var e = 0; e < S.length; e++) S[e] && S[e]()
                }

                function de() {
                    for (var e = 0; e < x.length; e++) x[e] && x[e]()
                }
                var fe, Ae, ge = !1,
                    pe = !1,
                    he = {},
                    me = !1,
                    ve = !1,
                    ye = !1,
                    Ce = !1,
                    we = U.DOM.containerScreenCanvas;
                we.innerHTML = "", Codo().isFlash() || K.on("Flash plugin not found"), we.innerHTML = "<object id='" + U.id + "-" + t + "-swf' name='" + U.id + "-" + t + "-swf' width='" + U.settings.currentWidth + "' height='" + U.settings.currentHeight + "' type='application/x-shockwave-flash' data='" + U.system.rootPath + "module.swf' style='position: absolute; top: 0; left: 0;'><param name='movie' value='" + U.system.rootPath + "module.swf'><param name='quality' value='high'><param name='allowScriptAccess' value='always'><param name='swliveconnect' value='true'><param name='wmode' value='transparent'><param name='flashVars' value='instance=" + U.instance + "&mediaType=" + t + "'></object>", fe = Codo("#" + U.id + "-" + t + "-swf").get()[0], Codo(we).add({
                    el: "div",
                    style: "position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: black; opacity: 0; filter: alpha(opacity=0);"
                });
                var Te = function() {
                    var t;
                    return {
                        start: function() {
                            clearInterval(t);
                            var n, o;
                            t = setInterval(function() {
                                if (fe.getDuration) {
                                    var t = Math.round(fe.getDuration());
                                    if (fe.getCurrentTime) {
                                        var i = Math.round(fe.getCurrentTime());
                                        G();
                                        var r = Math.round(t / 4),
                                            s = Math.round(t / 2),
                                            a = Math.round(t - t / 4);
                                        switch (ye && i > (n || r) && (ye = !1), Ce && i > o && (Ce = !1), i) {
                                            case Math.round(t / 4):
                                                ye || (ye = !0, n = r, ae());
                                                break;
                                            case Math.round(t / 2):
                                                ye || (ye = !0, n = s, le());
                                                break;
                                            case Math.round(t - t / 4):
                                                ye || (ye = !0, n = a, ue())
                                        }
                                        e.cuepoints && !Ce && -1 != e.cuepoints.indexOf(i) && (Ce = !0, o = e.cuepoints[e.cuepoints.indexOf(i)], de())
                                    }
                                }
                            }, 100)
                        },
                        end: function() {
                            clearInterval(t)
                        },
                        once: function() {
                            G()
                        }
                    }
                }();
                return {
                    platformName: "video" == t ? "videoSWF" : "audioSWF",
                    isPlaying: function() {
                        return ge
                    },
                    isMetaDataLoaded: function() {
                        return pe
                    },
                    onBeforeLoad: function(e) {
                        e && c.push(e)
                    },
                    onLoad: function(e) {
                        e && d.push(e)
                    },
                    onMetaData: function(e) {
                        e && f.push(e)
                    },
                    onPlay: function(e) {
                        e && A.push(e)
                    },
                    onPause: function(e) {
                        e && g.push(e)
                    },
                    onEnd: function(e) {
                        e && p.push(e)
                    },
                    onBuffer: function(e) {
                        e && m.push(e)
                    },
                    onProgress: function(e) {
                        e && h.push(e)
                    },
                    onSeekStart: function(e) {
                        e && v.push(e)
                    },
                    onSeekEnd: function(e) {
                        e && y.push(e)
                    },
                    onVolumeChange: function(e) {
                        e && C.push(e)
                    },
                    onFullScreenEnter: function(e) {
                        e && w.push(e)
                    },
                    onFullScreenExit: function(e) {
                        e && T.push(e)
                    },
                    onError: function(e) {
                        e && M.push(e)
                    },
                    system: {
                        onSwfLoaded: function() {
                            o()
                        },
                        onRtmpLoaded: function() {
                            i()
                        },
                        onPlay: function() {
                            Y()
                        },
                        onPause: function() {
                            R()
                        },
                        onEnd: function() {
                            j()
                        },
                        onWaiting: function() {
                            OnWaiting()
                        },
                        onSeekStart: function() {
                            _()
                        },
                        onSeekEnd: function() {
                            ee()
                        },
                        onBuffer: function(e) {
                            J(e)
                        },
                        onMetaData: function(e) {
                            oe(e)
                        },
                        onError: function() {
                            ne()
                        }
                    },
                    getParent: function() {
                        return fe
                    },
                    getPoster: function() {
                        return Ae
                    },
                    toggle: function() {
                        a()
                    },
                    play: function(t, o) {
                        t ? (e = t, n = o, s()) : l()
                    },
                    pause: function() {
                        u()
                    },
                    setVolume: function(e) {
                        P(e)
                    },
                    getVolume: function(e) {
                        return E()
                    },
                    getDuration: function() {
                        return I()
                    },
                    setCurrentTime: function(e) {
                        H(e)
                    },
                    getCurrentTime: function() {
                        return B()
                    },
                    toggleFullScreen: function(e) {
                        O(e)
                    },
                    fullScreenEnter: function(e) {
                        F(e)
                    },
                    fullScreenExit: function(e) {
                        W(e)
                    },
                    destroy: function() {
                        L()
                    },
                    onClipBegin: function(e) {
                        e && b.push(e)
                    },
                    onClipFirstQuarter: function(e) {
                        e && k.push(e)
                    },
                    onClipSecondQuarter: function(e) {
                        e && D.push(e)
                    },
                    onClipThirdQuarter: function(e) {
                        e && N.push(e)
                    },
                    onClipEnd: function(e) {
                        e && S.push(e)
                    },
                    onCuepoint: function(e) {
                        e && x.push(e)
                    }
                }
            },
            V = function(e, t, n) {
                function o() {
                    ve && (F(), de = !1, me = !1, ge = !1, pe = !1, he = !1, q && q.on(), X.title(U.settings.controls.loadingText), X.setVolume(U.settings.volume || "0"), setTimeout(function() {
                        ae.loadVideoById(e.activeUrl), W(), Codo().isTouch() && G()
                    }, 500))
                }

                function i() {
                    ce ? s() : r()
                }

                function r(e) {
                    de ? (U.system.initClickMade = U.system.initPlayMade = ce = !0, ae.playVideo(), X.play()) : U.settings.preload || o(U.playlist.getCurrentClip())
                }

                function s() {
                    de && (ce = !1, ae.pauseVideo(), X.pause())
                }

                function a(e) {
                    de ? ae.setVolume(e) : (U.settings.volume = e, X.setVolume(e || "0")), Y(e)
                }

                function l() {
                    return de ? ae.getVolume() : void 0
                }

                function u() {
                    return de ? ae.getDuration() : void 0
                }

                function P(e) {
                    de && ae.seekTo(e)
                }

                function E() {
                    return ae.getCurrentTime() || "0"
                }

                function I(e) {
                    $.getState() ? B(e) : H(e)
                }

                function H(e) {
                    $.on(e), we.once(), _()
                }

                function B(e) {
                    $.off(e), we.once(), ee()
                }

                function O() {
                    we.end(), U.API = {}, le && Codo(le).remove(), ue && Codo(ue).remove()
                }

                function F() {
                    for (var e = 0; e < c.length; e++) c[e] && c[e]()
                }

                function W() {
                    for (var e = 0; e < d.length; e++) d[e] && d[e]()
                }

                function L() {
                    if (de) {
                        ce = !0, Z && Z.off(), Ae || X.play(), we.start(), ge || (ge = !0, te());
                        for (var e = 0; e < A.length; e++) A[e] && A[e](ae.getCurrentTime())
                    }
                }

                function Q() {
                    if (de && ae.getDuration() - ae.getCurrentTime() > .1) {
                        ce = !1, Ae || (Z && Z.on(), X.pause());
                        for (var e = 0; e < g.length; e++) g[e] && g[e](ae.getCurrentTime())
                    }
                }

                function V() {
                    ce = !1, U.system.firstClipOver = !0, X.pause(), we.end(), Loading.on(), re(), U.playlist.next();
                    for (var e = 0; e < p.length; e++) p[e] && p[e]()
                }

                function Y(e) {
                    U.settings.volume = e, X.setVolume(e || "0");
                    for (var t = 0; t < C.length; t++) C[t] && C[t](U.settings.volume)
                }

                function R() {
                    if (de) {
                        X.buffer(100 * ae.getVideoLoadedFraction() * ae.getDuration() / 100);
                        for (var e = 0; e < m.length; e++) m[e] && m[e](100 * ae.getVideoLoadedFraction() * ae.getDuration() / 100)
                    }
                }

                function j() {
                    if (ae.getCurrentTime && ae.getCurrentTime()) {
                        X.progress(ae.getCurrentTime());
                        for (var e = 0; e < h.length; e++) h[e] && h[e](ae.getCurrentTime())
                    }
                }

                function J() {
                    ce = !1, we.end();
                    var e = t + " not found";
                    K.on(e);
                    for (var n = 0; n < M.length; n++) M[n] && M[n](e)
                }

                function G() {
                    de = !0, fe.width = U.settings.currentWidth, fe.height = U.settings.currentHeight, fe.duration = ae.getDuration(), le && (le = Codo(le).remove()), z.resize(ue, fe.width, fe.height), ae.pauseVideo(), "0" !== U.playlist.breakTime && P(U.playlist.breakTime), n ? r() : U.system.firstClipOver ? (e.hasPrevious || U.settings.loop) && r() : (!U.settings.preload || U.settings.autoplay) && r(), ce ? q && q.off() : q && q.off("cover"), X.title(e.title || " "), X.time(), ae.setVolume(U.settings.volume), we.once();
                    for (var t = 0; t < f.length; t++) f[t] && f[t](fe)
                }

                function _() {
                    for (var e = 0; e < w.length; e++) w[e] && w[e]()
                }

                function ee() {
                    for (var e = 0; e < T.length; e++) T[e] && T[e]()
                }

                function te() {
                    for (var e = 0; e < b.length; e++) b[e] && b[e]()
                }

                function ne() {
                    for (var e = 0; e < k.length; e++) k[e] && k[e]()
                }

                function oe() {
                    for (var e = 0; e < D.length; e++) D[e] && D[e]()
                }

                function ie() {
                    for (var e = 0; e < N.length; e++) N[e] && N[e]()
                }

                function re() {
                    for (var e = 0; e < S.length; e++) S[e] && S[e]()
                }

                function se() {
                    for (var e = 0; e < x.length; e++) x[e] && x[e]()
                }
                var ae, le, ue, ce = !1,
                    de = !1,
                    fe = {},
                    Ae = !1,
                    ge = !1,
                    pe = !1,
                    he = !1,
                    me = !1,
                    ve = !1,
                    ye = U.DOM.containerScreenCanvas;
                ye.innerHTML = "", Codo(ye).add({
                    el: "div",
                    id: U.id + "-youtube-iframe",
                    style: "position: absolute; top: 0; left: 0;"
                }), Codo().script("//www.youtube.com/iframe_api");
                var Ce = setInterval(function() {
                        YTiframeReady && (ae = new YT.Player(U.id + "-youtube-iframe", {
                            width: U.settings.currentWidth,
                            height: U.settings.currentHeight,
                            playerVars: {
                                controls: 0,
                                showinfo: 0
                            },
                            events: {
                                onReady: function(t) {
                                    if (ve = !0, ue = Codo("#" + U.id + "-youtube-iframe").get()[0], U.settings.preload || U.system.initClickMade) o();
                                    else {
                                        if (e.poster) {
                                            var n = new Image;
                                            n.src = e.poster, n.onload = function() {
                                                le = Codo(ye).add({
                                                    el: "img",
                                                    src: n.src,
                                                    style: "position: absolute; top: 0; left: 0;"
                                                }), z.resize(le, n.width, n.height), U.settings.responsive && U.resize()
                                            }
                                        }
                                        X.title(e.title || " "), q && q.off("cover")
                                    }
                                },
                                onStateChange: function(e) {
                                    switch (e.data) {
                                        case YT.PlayerState.PLAYING:
                                            de ? L() : G();
                                            break;
                                        case YT.PlayerState.PAUSED:
                                            me ? Q() : me = !0;
                                            break;
                                        case YT.PlayerState.ENDED:
                                            V()
                                    }
                                },
                                onError: function(e) {
                                    J(e)
                                }
                            }
                        }), clearInterval(Ce))
                    }, 100),
                    we = function() {
                        var t;
                        return {
                            start: function() {
                                clearInterval(t);
                                var n, o;
                                t = setInterval(function() {
                                    R(), j();
                                    var t = Math.round(ae.getDuration()),
                                        i = Math.round(ae.getCurrentTime()),
                                        r = Math.round(t / 4),
                                        s = Math.round(t / 2),
                                        a = Math.round(t - t / 4);
                                    switch (pe && i > (n || r) && (pe = !1), he && i > o && (he = !1), i) {
                                        case Math.round(t / 4):
                                            pe || (pe = !0, n = r, ne());
                                            break;
                                        case Math.round(t / 2):
                                            pe || (pe = !0, n = s, oe());
                                            break;
                                        case Math.round(t - t / 4):
                                            pe || (pe = !0, n = a, ie())
                                    }
                                    e.cuepoints && !he && -1 != e.cuepoints.indexOf(i) && (he = !0, o = e.cuepoints[e.cuepoints.indexOf(i)], se())
                                }, 100)
                            },
                            end: function() {
                                clearInterval(t)
                            },
                            once: function() {
                                R(), j()
                            }
                        }
                    }();
                return {
                    platformName: "YOUTUBE",
                    isPlaying: function() {
                        return ce
                    },
                    isMetaDataLoaded: function() {
                        return de
                    },
                    onBeforeLoad: function(e) {
                        e && c.push(e)
                    },
                    onLoad: function(e) {
                        e && d.push(e)
                    },
                    onMetaData: function(e) {
                        e && f.push(e)
                    },
                    onPlay: function(e) {
                        e && A.push(e)
                    },
                    onPause: function(e) {
                        e && g.push(e)
                    },
                    onEnd: function(e) {
                        e && p.push(e)
                    },
                    onBuffer: function(e) {
                        e && m.push(e)
                    },
                    onProgress: function(e) {
                        e && h.push(e)
                    },
                    onSeekStart: function(e) {
                        e && v.push(e)
                    },
                    onSeekEnd: function(e) {
                        e && y.push(e)
                    },
                    onVolumeChange: function(e) {
                        e && C.push(e)
                    },
                    onFullScreenEnter: function(e) {
                        e && w.push(e)
                    },
                    onFullScreenExit: function(e) {
                        e && T.push(e)
                    },
                    onError: function(e) {
                        e && M.push(e)
                    },
                    getParent: function() {
                        return ue
                    },
                    getPoster: function() {
                        return le
                    },
                    toggle: function() {
                        i()
                    },
                    play: function(t, i) {
                        t ? (e = t, n = i, o()) : r()
                    },
                    pause: function() {
                        s()
                    },
                    setVolume: function(e) {
                        a(e)
                    },
                    getVolume: function() {
                        return l()
                    },
                    getDuration: function() {
                        return u()
                    },
                    setCurrentTime: function(e) {
                        P(e)
                    },
                    getCurrentTime: function() {
                        return E()
                    },
                    toggleFullScreen: function(e) {
                        I(e)
                    },
                    fullScreenEnter: function(e) {
                        H(e)
                    },
                    fullScreenExit: function(e) {
                        B(e)
                    },
                    destroy: function() {
                        O()
                    },
                    onClipBegin: function(e) {
                        e && b.push(e)
                    },
                    onClipFirstQuarter: function(e) {
                        e && k.push(e)
                    },
                    onClipSecondQuarter: function(e) {
                        e && D.push(e)
                    },
                    onClipThirdQuarter: function(e) {
                        e && N.push(e)
                    },
                    onClipEnd: function(e) {
                        e && S.push(e)
                    },
                    onCuepoint: function(e) {
                        e && x.push(e)
                    }
                }
            },
            U = new u(t),
            z = new F(U);
        U.playlist = new E(U), Codo().link(U.system.rootPath + "styles/" + U.settings.style + "/style.css");
        var Y = "position: relative; width: 100%; height: 100%; cursor: default; -webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; -webkit-font-smoothing: antialiased; visibility: hidden; overflow: hidden;";
        if (n) {
            if (!Codo(n).get()[0]) return;
            Codo(n).add({
                el: "div",
                id: U.id,
                className: U.className,
                style: Y
            })
        } else document.write("<div id='" + U.id + "' class='" + U.className + "' style='" + Y + "'></div>");
        var R = U.DOM.parent = document.getElementById(U.id);
        U.settings.width = U.settings.currentWidth = U.settings.width || Codo(R).getWidth(), U.settings.height = U.settings.currentHeight = U.settings.height || Codo().getVideoHeight(U.settings.width, U.settings.ratio[0], U.settings.ratio[1]), R.style.width = U.settings.width + "px", R.style.minHeight = U.settings.height + "px";
        var j = Codo(R).add({
            el: "div",
            className: U.className + "-container",
            style: "position: relative; margin: 0; padding: 0; width: " + U.settings.width + "px; height: " + U.settings.height + "px;"
        });
        U.DOM.container = j;
        var J = Codo(j).add({
            el: "div",
            className: U.className + "-container-screen",
            style: "position: absolute; top: 0; left: 0; width: 100%; height: 100%; margin: 0; padding: 0; overflow: hidden;"
        });
        U.DOM.containerScreen = J, U.DOM.containerScreenCanvas = Codo(J).add({
            el: "div",
            className: U.className + "-container-screen-canvas",
            style: "position: absolute; top: 0; left: 0; width: 100%; height: 100%; margin: 0; padding: 0;"
        }), a && U.settings.logo && Codo(j).add({
            el: "img",
            src: U.settings.logo,
            className: U.className + "-container-logo",
            style: "position: absolute; top: 20px; right: 20px;"
        });
        var q = !0,
            Z = !0;
        q && (q = new H(U)), Z && (Z = new B(U)), !q && Z && Z.on();
        var G = new P(U),
            X = new I(U),
            K = new O(U),
            $ = new W(U);
        Codo(U.DOM.parent).on("contextmenu", function(e) {
            e.preventDefault ? e.preventDefault() : e.returnValue, Z && Z.menu()
        }), U.plugins.advertising || U.playlist.set(e, U.settings.autoplay);
        var _ = 0,
            ee = 0;
        for (var te in U.plugins) _++;
        for (var te in U.plugins) U.plugins[te] && Codo().script(U.system.rootPath + "plugins/" + te + "/codo-player-" + te + ".js", function(t, n) {
            "advertising" == n && (t ? (U.plugins[n].initCover = Codo(U.DOM.container).add({
                el: "div",
                className: U.className + "-advertising-init-cover",
                style: "position: absolute; top: 0; left: 0; width: 100%; height: 100%; visibility: visible; cursor: pointer; opacity: 0; filter: alpha(opacity=0);"
            }), U.plugins[n].autoplay = U.settings.autoplay, U.settings.preload = U.settings.autoplay = !1, U.playlist.set(e, U.settings.autoplay), U.plugins[n].system && U.plugins[n].system.init(U)) : U.plugins[n].fallback ? U.plugins[n].fallback(U) : U.playlist.set(e, U.settings.autoplay)), ee++, _ == ee && U.onReady && U.onReady(U)
        }, te);
        return _ || U.onReady && U.onReady(U), U
    }
    if (!CodoPlayerAPI.length) {
        var i = i || function(e) {
            if (1 === CodoPlayerAPI.length) {
                if ("INPUT" == e.target.nodeName || "TEXTAREA" == e.target.nodeName) return;
                e = e || window.event;
                var t = CodoPlayerAPI[0],
                    n = t.media.getVolume(),
                    o = t.media.getCurrentTime();
                switch (e.keyCode) {
                    case 70:
                        e.preventDefault(), t.media.toggleFullScreen(e);
                        break;
                    case 32:
                        e.preventDefault(), t.media.toggle();
                        break;
                    case 38:
                        e.preventDefault(), 100 >= n && (n += 10, n > 100 && (n = 100), t.media.setVolume(n));
                        break;
                    case 40:
                        e.preventDefault(), n >= 0 && (n -= 10, 0 > n && (n = 0), t.media.setVolume(n));
                        break;
                    case 39:
                        e.preventDefault(), t.settings.controls.seeking && o <= t.media.getDuration() && (o += 5, t.media.setCurrentTime(o));
                        break;
                    case 37:
                        e.preventDefault(), t.settings.controls.seeking && o >= 0 && (o -= 5, t.media.setCurrentTime(o))
                }
            }
        };
        Codo(document).off("keydown", i), Codo(document).on("keydown", i)
    }
    Codo(window).on("resize", function(e) {
        for (var t = 0; t < CodoPlayerAPI.length; t++) {
            var n = CodoPlayerAPI[t];
            !n.system.isFullScreen && n.settings.responsive && n.resize()
        }
    });
    var r = new o(e, t, n);
    return CodoPlayerAPI.push(r), r
}, window.YTiframeReady = !1, window.onYouTubeIframeAPIReady = function() {
    YTiframeReady = !0
};