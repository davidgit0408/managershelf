// var GazeCloudAPI = new function GazeCloudAPIInit() {
//     this.UseClickRecalibration = true;
//     this.OnResult = null;
//     this.OnCalibrationComplete = null;
//     this.OnCalibrationFail = null;
//     this.OnStopGazeFlow = null;
//     this.OnCamDenied = null;
//     this.OnError = null;
//     this.ShowCalibration = ShowCalibration;
//     this.OnGazeEvent = null;
//     this.StartEyeTracking = function (ServerUrl = "", Port = -1) {
//         if (ServerUrl != "") {
//             GazeCloudServerAdress = ServerUrl;
//             if (Port > 0) GazeCloudServerPort = Port;
//             StartGazeFlow();
//         } else {
//             if (!GetCloudAdressReady) {
//                 _WaitForGetCloudAdress = setInterval(() => {
//                     clearInterval(_WaitForGetCloudAdress);
//                     if (!GetCloudAdressReady)
//                         if (Logg) Logg("GetCloudAdress too long", 2);
//                     StartGazeFlow();
//                 }, 2000);
//             } else {
//                 StartGazeFlow();
//             }
//         }
//         if (true) InitOldAPI();
//     }
// this.StopEyeTracking = function () {
//     StopGazeFlow();
// }
// this.RestartEyeTracking = function () {
//     GetCloudAdress();
//     StopGazeFlow();
//     StartGazeFlow();
//     if (Logg) Logg("RestartEyeTracking", 2);
// }
// var dict;
// var code = 256;
// var codeInit = 256;
// var bUseUnicode = true;
// var WebEventStringStream = null;
// var WebEventStringStreamIx = 0;
// var WebEventStringPending = "";

// function RestlzwStream() {
//     WebEventStringPending = "";
//     WebEventStringStreamIx = 0;
//     WebEventStringStream = "";
//     dict = new Map();
//     code = codeInit;
// }
// RestlzwStream();

// function lzw_encode_stream(s) {
//     try {
//         if (!s) return s;
//         var out = [];
//         var data = (s + "").split("");
//         if (bUseUnicode) {
//             var uint8array = new TextEncoder("utf-8").encode(s);
//             data = [];
//             for (var i = 0; i < uint8array.length; i++) data[i] = String.fromCodePoint(uint8array[i]);
//         }
//         var currChar;
//         var phrase = data[0];
//         for (var i = 1; i < data.length; i++) {
//             currChar = data[i];
//             if (dict.has(phrase + currChar)) {
//                 phrase += currChar;
//             } else {
//                 if (phrase.length > 0) {
//                     out.push(phrase.length > 1 ? dict.get(phrase) : phrase.codePointAt(0));
//                     dict.set(phrase + currChar, code);
//                     code++;
//                     if (code === 0xd800) {
//                         code = 0xe000;
//                     }
//                 }
//                 phrase = currChar;
//             }
//         }
//         out.push(phrase.length > 1 ? dict.get(phrase) : phrase.codePointAt(0));
//         code++;
//         for (var i = 0; i < out.length; i++) {
//             out[i] = String.fromCodePoint(out[i]);
//         }
//         console.log("LZW MAP SIZE", dict.size, out.slice(-50), out.length, out.join("").length);
//         return out.join("");
//     } catch (e) {
//         var a = 1;
//         a++;
//     }
// }

// function lzw_encode(s) {
//     if (!s) return s;
//     var dict = new Map();
//     var data = (s + "").split("");
//     var out = [];
//     var currChar;
//     var phrase = data[0];
//     var code = 256;
//     for (var i = 1; i < data.length; i++) {
//         currChar = data[i];
//         if (dict.has(phrase + currChar)) {
//             phrase += currChar;
//         } else {
//             out.push(phrase.length > 1 ? dict.get(phrase) : phrase.codePointAt(0));
//             dict.set(phrase + currChar, code);
//             code++;
//             if (code === 0xd800) {
//                 code = 0xe000;
//             }
//             phrase = currChar;
//         }
//     }
//     out.push(phrase.length > 1 ? dict.get(phrase) : phrase.codePointAt(0));
//     for (var i = 0; i < out.length; i++) {
//         out[i] = String.fromCodePoint(out[i]);
//     }
//     console.log("LZW MAP SIZE", dict.size, out.slice(-50), out.length, out.join("").length);
//     return out.join("");
// }

//     function lzw_decode(s) {
//         var dict = new Map();
//         var data = Array.from(s + "");
//         var currChar = data[0];
//         var oldPhrase = currChar;
//         var out = [currChar];
//         var code = codeInit;
//         var phrase;
//         for (var i = 1; i < data.length; i++) {
//             var currCode = data[i].codePointAt(0);
//             if (currCode < 256) {
//                 phrase = data[i];
//             } else {
//                 phrase = dict.has(currCode) ? dict.get(currCode) : (oldPhrase + currChar);
//             }
//             out.push(phrase);
//             var cp = phrase.codePointAt(0);
//             currChar = String.fromCodePoint(cp);
//             dict.set(code, oldPhrase + currChar);
//             code++;
//             if (code === 0xd800) {
//                 code = 0xe000;
//             }
//             oldPhrase = phrase;
//         }
//         if (bUseUnicode) var data = (ss + "").split("");
//         var uint8array = new Uint8Array(data.length);
//         for (var i = 0; i < data.length; i++) uint8array[i] = data[i].codePointAt(0);
//         var back = new TextDecoder().decode(uint8array);
//         return back;
//     }
//     return out.join("");
// }

// function SendBinary(s) {
//     var uint8array = new TextEncoder("utf-8").encode(s);
//     ws.send(uint8array);
// }
var rrwebRecord = function () {
    "use strict";
    var e, t = function () {
        return (t = Object.assign || function (e) {
            for (var t, n = 1, r = arguments.length; n = e.length && (e = void 0), {
                value: e && e[n++],
                done: !e
            }

        }
    }

    // function r(e, t) {
    //         var n = "function" == typeof Symbol && e[Symbol.iterator];
    //         if (!n) return e;
    //         var r, o, a = n.call(e),
    //             i = [];
    //         try {
    //             for (;
    //                 (void 0 === t || t-- > 0) && !(r = a.next()).done;) i.push(r.value)
    //         } catch (e) {
    //             o = {
    //                 error: e
    //             }
    //         } finally {
    //             try {
    //                 r && !r.done && (n = a.return) && n.call(a)
    //             } finally {
    //                 if (o) throw o.error
    //             }
    //         }
    //         return i
    //     }

    function o() {
        for (var e = [], t = 0; t - 1 ? a.split("/").slice(0, 3).join("/") : (a.split("/")[0].split("?")[0] + i) + "')";
            var u = t.split("/"), l = i.split("/");
        u.pop();
        for (var d = 0, f = l; dt ? (r && (window.clearTimeout(r), r = null), o = i, e.apply(c, s)) : r || !1 === n.trailing || (r = window.setTimeout(function () {
            o = !1 === n.leading ? 0 : Date.now(), r = null, e.apply(c, s)
        }, u))
        }
}

function C() {
    return window.innerHeight || document.documentElement && document.documentElement.clientHeight || document.body && document.body.clientHeight
}

// function w() {
//     return window.innerWidth || document.documentElement && document.documentElement.clientWidth || document.body && document.body.clientWidth
// }

// function N(e, t) {
//     if (!e) return !1;
//     if (e.nodeType === e.ELEMENT_NODE) {
//         var n = !1;
//         return "string" == typeof t ? n = e.classList.contains(t) : e.classList.forEach(function (e) {
//             t.test(e) && (n = !0)
//         }), n || N(e.parentNode, t)
//     }
//     return N(e.parentNode, t)
// }

// function T(e) {
//     return Boolean(e.changedTouches)
// }

// function I(e, t) {
//     e.delete(t), t.childNodes.forEach(function (t) {
//         return I(e, t)
//     })
// }

// function S(e, t) {
//     var n = t.parentNode;
//     if (!n) return !1;
//     var r = b.getId(n);
//     return !!e.some(function (e) {
//         return e.id === r
//     }) || S(e, n)
// }

// function D(e, t) {
//     var n = t.parentNode;
//     return !!n && (!!e.has(n) || D(e, n))
// } ! function (e) {
//     e[e.DomContentLoaded = 0] = "DomContentLoaded", e[e.Load = 1] = "Load", e[e.FullSnapshot = 2] = "FullSnapshot", e[e.IncrementalSnapshot = 3] = "IncrementalSnapshot", e[e.Meta = 4] = "Meta", e[e.Custom = 5] = "Custom"
// }(h || (h = {})),
//     function (e) {
//         e[e.Mutation = 0] = "Mutation", e[e.MouseMove = 1] = "MouseMove", e[e.MouseInteraction = 2] = "MouseInteraction", e[e.Scroll = 3] = "Scroll", e[e.ViewportResize = 4] = "ViewportResize", e[e.Input = 5] = "Input", e[e.TouchMove = 6] = "TouchMove"
//     }(v || (v = {})),
//     function (e) {
//         e[e.MouseUp = 0] = "MouseUp", e[e.MouseDown = 1] = "MouseDown", e[e.Click = 2] = "Click", e[e.ContextMenu = 3] = "ContextMenu", e[e.DblClick = 4] = "DblClick", e[e.Focus = 5] = "Focus", e[e.Blur = 6] = "Blur", e[e.TouchStart = 7] = "TouchStart", e[e.TouchMove_Departed = 8] = "TouchMove_Departed", e[e.TouchEnd = 9] = "TouchEnd"
//     }(y || (y = {})),
//     function (e) {
//         e.Start = "start", e.Pause = "pause", e.Resume = "resume", e.Resize = "resize", e.Finish = "finish", e.FullsnapshotRebuilded = "fullsnapshot-rebuilded", e.LoadStylesheetStart = "load-stylesheet-start", e.LoadStylesheetEnd = "load-stylesheet-end", e.SkipStart = "skip-start", e.SkipEnd = "skip-end", e.MouseInteraction = "mouse-interaction"
//     }(g || (g = {}));
// var x = function (e, t) {
//     return e + "@" + t
// };

// function M(e) {
//     return "__sn" in e
// }

// function k(e, t, r, o) {
//     var a = new MutationObserver(function (a) {
//         var i, u, c, s, l = [],
//             d = [],
//             m = [],
//             h = [],
//             v = new Set,
//             y = new Set,
//             g = new Set,
//             E = {},
//             C = function (e, n) {
//                 if (!N(e, t)) {
//                     if (M(e)) {
//                         y.add(e);
//                         var r = null;
//                         n && M(n) && (r = n.__sn.id), r && (E[x(e.__sn.id, r)] = !0)
//                     } else v.add(e), g.delete(e);
//                     e.childNodes.forEach(function (e) {
//                         return C(e)
//                     })
//                 }
//             };
// a.forEach(function (e) {
//     var n = e.type,
//         r = e.target,
//         o = e.oldValue,
//         a = e.addedNodes,
//         i = e.removedNodes,
//         u = e.attributeName;
//     switch (n) {
//         case "characterData":
//             var c = r.textContent;
//             N(r, t) || c === o || l.push({
//                 value: c,
//                 node: r
//             });
//             break;
//         case "attributes":
//             c = r.getAttribute(u);
//             if (N(r, t) || c === o) return;
//             var s = d.find(function (e) {
//                 return e.node === r
//             });
// s || (s = {
//     node: r,
//     attributes: {}
// }, d.push(s)), s.attributes[u] = f(document, u, c);
// break;
// case "childList":
//     a.forEach(function (e) {
//         return C(e, r)
//     }), i.forEach(function (e) {
//         var n = b.getId(e),
//             o = b.getId(r);
//         N(e, t) || (v.has(e) ? (I(v, e), g.add(e)) : v.has(r) && -1 === n || function e(t) {
//             var n = b.getId(t);
//             return !b.has(n) || (!t.parentNode || t.parentNode.nodeType !== t.DOCUMENT_NODE) && (!t.parentNode || e(t.parentNode))
//         }(r) || (y.has(e) && E[x(n, o)] ? I(y, e) : m.push({
//             parentId: o,
//             id: n
//         })), b.removeNodeFromMap(e))
//     })
//     }
// });
// var w = [],
//     T = function (e) {
//         var n = b.getId(e.parentNode);
//         if (-1 === n) return w.push(e);
//         h.push({
//             parentId: n,
//             previousId: e.previousSibling ? b.getId(e.previousSibling) : e.previousSibling,
//             nextId: e.nextSibling ? b.getId(e.nextSibling) : e.nextSibling,
//             node: p(e, document, b.map, t, !0, r, o)
//         })
//     };
// try {
//     for (var k = n(y), L = k.next(); !L.done; L = k.next()) {
//         T(A = L.value)
//     }
// } catch (e) {
//     i = {
//         error: e
//     }
// } finally {
//     try {
//         L && !L.done && (u = k.return) && u.call(k)
//     } finally {
//         if (i) throw i.error
//     }
// }
// try {
//     for (var _ = n(v), O = _.next(); !O.done; O = _.next()) {
//         var A = O.value;
//         D(g, A) || S(m, A) ? D(y, A) ? T(A) : g.add(A) : T(A)
//     }
// } catch (e) {
//     c = {
//         error: e
//     }
// } finally {
//     try {
//         O && !O.done && (s = _.return) && s.call(_)
//     } finally {
//         if (c) throw c.error
//     }
// }
// for (; w.length && !w.every(function (e) {
//     return -1 === b.getId(e.parentNode)
// });) T(w.shift());
// var R = {
//     texts: l.map(function (e) {
//         return {
//             id: b.getId(e.node),
//             value: e.value
//         }
//     }).filter(function (e) {
//         return b.has(e.id)
//     }),
//     attributes: d.map(function (e) {
//         return {
//             id: b.getId(e.node),
//             attributes: e.attributes
//         }
//     }).filter(function (e) {
//         return b.has(e.id)
//     }),
//     removes: m,
//     adds: h
//     // };
//     (R.texts.length || R.attributes.length || R.removes.length || R.adds.length) && e(R)
// });
//     return a.observe(document, {
//         attributes: !0,
//         attributeOldValue: !0,
//         characterData: !0,
//         characterDataOldValue: !0,
//         childList: !0,
//         subtree: !0
//     }), a
// }

// function L(e, t) {
//     var n = [];
//     return Object.keys(y).filter(function (e) {
//         return Number.isNaN(Number(e)) && !e.endsWith("_Departed")
//     }).forEach(function (r) {
//         var o = r.toLowerCase(),
//             a = function (n) {
//                 return function (r) {
//                     if (!N(r.target, t)) {
//                         var o = b.getId(r.target),
//                             a = T(r) ? r.changedTouches[0] : r,
//                             i = a.clientX,
//                             u = a.clientY;
//                         e({
//                             type: y[n],
//                             id: o,
//                             x: i,
//                             y: u
//                         })
//                     }
//                 }
//             }(r);
//         n.push(m(o, a))
//     }),
//         function () {
//             n.forEach(function (e) {
//                 return e()
//             })
//         }
// }
// var _, O = ["INPUT", "TEXTAREA", "SELECT"],
//     A = ["color", "date", "datetime-local", "email", "month", "number", "range", "search", "tel", "text", "time", "url", "week"],
//     R = new WeakMap;

// function z(e, n, r, a) {
//     function i(e) {
//         var t = e.target;
//         if (t && t.tagName && !(O.indexOf(t.tagName) < 0) && !N(t, n)) {
//             var o = t.type;
//             if ("password" !== o && !t.classList.contains(r)) {
//                 var i = t.value,
//                     c = !1,
//                     s = A.includes(o) || "TEXTAREA" === t.tagName;
//                 "radio" === o || "checkbox" === o ? c = t.checked : s && a && (i = "*".repeat(i.length)), u(t, {
//                     text: i,
//                     isChecked: c
//                 });
//                 var l = t.name;
//                 "radio" === o && l && c && document.querySelectorAll('input[type="radio"][name="' + l + '"]').forEach(function (e) {
//                     e !== t && u(e, {
//                         text: e.value,
//                         isChecked: !c
//                     })
//                 })
//             }
//         }
//     }

// function u(n, r) {
//     var o = R.get(n);
//     if (!o || o.text !== r.text || o.isChecked !== r.isChecked) {
//         R.set(n, r);
//         var a = b.getId(n);
//         e(t({}, r, {
//             id: a
//         }))
//     }
// }
// var c = ["input", "change"].map(function (e) {
//     return m(e, i)
// }),
//     s = Object.getOwnPropertyDescriptor(HTMLInputElement.prototype, "value"),
//     l = [
//         [HTMLInputElement.prototype, "value"],
//         [HTMLInputElement.prototype, "checked"],
//         [HTMLSelectElement.prototype, "value"],
//         [HTMLTextAreaElement.prototype, "value"]
//     ];
// return s && s.set && c.push.apply(c, o(l.map(function (e) {
//     return function e(t, n, r, o) {
//         var a = Object.getOwnPropertyDescriptor(t, n);
//         return Object.defineProperty(t, n, o ? r : {
//             set: function (e) {
//                 var t = this;
//                 setTimeout(function () {
//                     r.set.call(t, e)
//                 }, 0), a && a.set && a.set.call(this, e)
//             }
//         }),
//             function () {
//                 return e(t, n, a || {}, !0)
//             }
//     }(e[0], e[1], {
//         set: function () {
//             i({
//                 target: this
//             })
//         }
//     })
// }))),
//         function () {
//             c.forEach(function (e) {
//                 return e()
//             })
//         }
// }

// function F(e, t) {
//     void 0 === t && (t = {}),
//         function (e, t) {
//             var n = e.mutationCb,
//                 r = e.mousemoveCb,
//                 a = e.mouseInteractionCb,
//                 i = e.scrollCb,
//                 u = e.viewportResizeCb,
//                 c = e.inputCb;
//             e.mutationCb = function () {
//                 for (var e = [], r = 0; r = i, u = a && e.timestamp - n.timestamp > a;
//                     (r || u) && S(!0)
//                 }
//         };
// try {
//     var D = [];
//     D.push(m("DOMContentLoaded", function () {
//         _(P({
//             type: h.DomContentLoaded,
//             data: {}
//         }))
//     }));
// var x = function () {
//     S(), D.push(F({
//         mutationCb: function (e) {
//             return _(P({
//                 type: h.IncrementalSnapshot,
//                 data: t({
//                     source: v.Mutation
//                 }, e)
//             }))
//         },
// mousemoveCb: function (e, t) {
//     return _(P({
//         type: h.IncrementalSnapshot,
//         data: {
//             source: t,
//             positions: e
//         }
//     }))
// },
// mouseInteractionCb: function (e) {
//     return _(P({
//         type: h.IncrementalSnapshot,
//         data: t({
//             source: v.MouseInteraction
//         }, e)
//     }))
// },
// scrollCb: function (e) {
//     return _(P({
//         type: h.IncrementalSnapshot,
//         data: t({
//             source: v.Scroll
//         }, e)
//     }))
// },
//         viewportResizeCb: function (e) {
//             return _(P({
//                 type: h.IncrementalSnapshot,
//                 data: t({
//                     source: v.ViewportResize
//                 }, e)
//             }))
//         },
//         inputCb: function (e) {
//             return _(P({
//                 type: h.IncrementalSnapshot,
//                 data: t({
//                     source: v.Input
//                 }, e)
//             }))
//         },
//         blockClass: c,
//         ignoreClass: l,
//         maskAllInputs: g,
//         inlineStylesheet: f,
//         mousemoveWait: T
//     }, E))
// };
//         return "interactive" === document.readyState || "complete" === document.readyState ? x() : D.push(m("load", function () {
//             _(P({
//                 type: h.Load,
//                 data: {}
//             })), x()
//         }, window)),
//             function () {
//                 D.forEach(function (e) {
//                     return e()
//                 })
//             }
//     } catch (e) {
//         console.warn(e)
//     }
// }
// return j.addCustomEvent = function (e, t) {
//     if (!_) throw new Error("please add custom event after start recording");
//     _(P({
//         type: h.Custom,
//         data: {
//             tag: e,
//             payload: t
//         }
//     }))
// }, j
// }();
// this.AddIFrameEvent = function (event) {
//     try {
//         if (stopFn != null) {
//             StopWebRec();
//         }
//         eventsWebRec.push(event);
//     } catch (e) { }
// }
// let eventsWebRec = [];
// var bStopR = false;
// var WebRecFinished = false;

// function StopWebRec() {
//     bStopR = true;
//     if (stopFn != null) {
//         stopFn();
//     }
//     if (false) {
//         while (true) {
//             if (WebRecFinished) break;
//         }
//     }
// }
// let stopFn = null;
// var WebRecIx = 0;

// function StartWebRec() {
//     WebRecFinished = false;
//     RestlzwStream();
//     eventsWebRec = [];
//     bStopR = false;
//     stopFn = rrwebRecord({
//         emit(event) {
//             if (!bStopR) eventsWebRec.push(event);
//             else {
//                 stopFn();
//                 WebRecFinished = true;
//             }
//         },
//     });
// }

// function ProcessWebRec() {
//     return;
//     if (CurFrameNr > 5) {
//         try {
//             if (eventsWebRec.length > WebRecIx) {
//                 if (false) {
//                     var eventsToSend = eventsWebRec.slice(WebRecIx, eventsWebRec.length);
//                     WebRecIx = eventsWebRec.length;
//                     var json_data = JSON.stringify(eventsToSend);
//                     ws.send(json_data);
//                 } else {
//                     var s = 0;
//                     while (WebRecIx 1000) break;
//                 }
//             }
//         }
//     } catch (e) { }
// }
// }

// function _ProcessWebRecStream() {
//     try {
//         if (eventsWebRec.length > WebRecIx) {
//             var s = 0;
//             var SendTxt = "";
//             while (WebRecIx 2000) break;
//         }
//         SendTxt = "we:" + SendTxt;
//         ws.send(SendTxt);
//         if (false) {
//             var back = lzw_decode(WebEventStringStream);
//             back += " ";
//         }
//     }
// } catch (e) { }
// }
// var bsWebRecStreamRunig = false;

// function ProcessWebRecStream__(bend = false) {
//     if (bsWebRecStreamRunig) {
//         var a = 1;
//         a++;
//     }
//     bsWebRecStreamRunig = true;
//     if (bend) {
//         if (WebEventStringStreamIx < 3) return;
//     }
// try {
//     if (eventsWebRec.length > WebRecIx) {
//         var s = 0;
//         var SendTxt = "";
//         var ll = eventsWebRec.length;
//         if (!bend) ll--;
//         while (WebRecIx1000) break;
//     }
//     SendTxt = "we:" + SendTxt;
//     ws.send(SendTxt);
//     if (false) {
//         var size = WebEventStringStream.length - WebEventStringStreamIx;
//         if (!bend) {
//             if (size > 1000) size = 1000;
//         }
//         if (size > 0) {
//             SendTxt = WebEventStringStream.substr(WebEventStringStreamIx, size);
//             WebEventStringStreamIx += size;
//             if (SendTxt.length > 0) {
//                 SendTxt = "we:" + SendTxt;
//                 ws.send(SendTxt);
//             } else {
//                 var a = 1;
//                 a++;
//             }
//         }
//     }
// }
// } catch (e) {
//     var a = 1;
//     a++;
// }
// bsWebRecStreamRunig = false;
// }

// function ProcessWebRecStream(bend = false) {
//     if (bend) {
//         if (WebEventStringStreamIx < 3) return;
//     }
//     try {
//         if (eventsWebRec.length > WebRecIx) {
//             var s = 0;
//             var SendTxt = "";
//             var ll = eventsWebRec.length;
//             if (!bend) ll--;
//             while (WebRecIx1000) break;
//         }
//         if (true) {
//             var size = WebEventStringStream.length - WebEventStringStreamIx;
//             if (!bend) {
//                 if (size > 1000) size = 1000;
//             }
//             if (size > 0) {
//                 SendTxt = WebEventStringStream.substr(WebEventStringStreamIx, size);
//                 SendTxt = lzw_encode_stream(SendTxt);
//                 WebEventStringStreamIx += size;
//                 if (SendTxt.length > 0) {
//                     SendTxt = "we:" + SendTxt;
//                     ws.send(SendTxt);
//                 } else {
//                     var a = 1;
//                     a++;
//                 }
//             }
//         }
//     }
// } catch (e) {
//     var a = 1;
//     a++;
// }
// }
// StartWebRec();
// var _GuiHtml = ' ';
// var video = null;
// showvideoid
// var videoOrginal = null;
// var _GazeData = {
//     state: -1
// };
// var _LastGazeData = null;
// var LastPixData;
// var PrevDif = 0;
// var _LastROI;
// var CurPixDataFull;
// var LastPixDataFull;
// var LastPixDataFull1;
// var LastPixDataFull2;

// function IsNewFrame(_video, GazeD) {
//     return true;
// }

// function GetPix() {
//     const __canvas = document.createElement('canvas');
//     var __canvasContext = __canvas.getContext('2d');
//     __canvas.width = video.videoWidth;
//     __canvas.height = video.videoHeight;
//     __canvasContext.drawImage(video, 0, 0, __canvas.width, __canvas.height);
//     var imgPixels = __canvasContext.getImageData(0, 0, __canvas.width, __canvas.height);
//     return imgPixels;
// }

// function PixDif(p1, p2) {
//     var dif = 0;
//     for (var y = 0; y < p1.height; y += 2) {
//         for (var x = 0; x < p1.width; x += 2) {
//             var i = (y * 4) * p1.width + x * 4;
//             var d = Math.abs(p1.data[i] - p2.data[i]);
//             dif += d;
//             if (d > 0) {
//                 return true;
//             }
//         }
//     }
//     return false;
// }
// var pix = null;
// var pixPrev = null;

// function IsNewFrame() {
//     if (video.videoWidth == 0 || video.videoHeight == 0) return false;
//     if (pixPrev == null) {
//         pix = GetPix();
//         pixPrev = GetPix();
//         return true;
//     } else {
//         pixPrev = pix;
//         pix = GetPix();
//         return PixDif(pix, pixPrev);
//     }
// }
// var CurCalPoint = null;
// var bIsRunCalibration = false;
// var bIsProcesingCalibration = false;
// var bIsCalibrated = false;

// function doKeyDown(e) {
//     if (e.keyCode == 27)
//         if (bIsRunCalibration) FinishCalibration();
// }
// var _LoopCalibration;

// function AbortCalibration() {
//     bIsCalibrated = false;
//     CurCalPoint = null;
//     bIsProcesingCalibration = false;
//     bIsRunCalibration = false;
//     clearInterval(_LoopCalibration);
//     document.getElementById("CalCanvasId").style.backgroundColor = 'white';
//     document.getElementById("CalDivId").style.display = "none";
//     document.getElementById("infoWaitForCalibration").style.display = "none";
//     closeFullscreen();
//     if (false)
//         if (GazeCloudAPI.OnCalibrationFail != null) GazeCloudAPI.OnCalibrationFail();
//     GUIState = 'InvalidCalibration';
//     if (true) UpdateGUI(_GazeData);
// }

// function FinishCalibration() {
//     if (true) SendStat();
//     if (true) {
//         camid.style = ' z-index: 1000;position:fixed; left:0%; top:0%; opacity: 0.7; display:none ';
//     }
//     CurCalPoint = null;
//     bIsProcesingCalibration = true;
//     bIsRunCalibration = false;
//     clearInterval(_LoopCalibration);
//     document.getElementById("CalCanvasId").style.backgroundColor = 'white';
//     document.getElementById("CalDivId").style.display = "none";
//     ws.send("cmd:FinishCalibration");
//     bIsRunCalibration = false;
//     closeFullscreen();
//     if (false) InitClickCalibration();
//     document.getElementById("infoWaitForCalibration").style.display = "block";
//     GUIState = 'WaitForCalibrationComplete';
//     camid.style.position = "fixed";
//     camid.style.left = "0%";
//     camid.style.top = "0%";
//     camid.style.opacity = 0.7;
//     if (true) UpdateGUI(_GazeData);
//     if (true) RePlayVideo();
// }
// var ctx = null;

// function InitCalibration() {
//     var canvas = document.getElementById("CalCanvasId");
//     canvas.width = window.innerWidth;
//     canvas.height = window.innerHeight;
//     ctx = canvas.getContext("2d");
//     canvas.style.backgroundColor = "white";
//     canvas.style.cursor = 'none';
//     ctx.clearRect(0, 0, canvas.width, canvas.height);
// }
// var CalDeviceRation = window.devicePixelRatio;

// function etmp() { }

// function ShowCalibration() {
//     if (Logg) Logg("ShowCalibration", 2);
//     setTimeout(_ShowCalibration, 200);
// }

// function _ShowCalibration() {
//     if (true) {
//         camid.style = ' z-index: 1000;position:fixed; left:0%; top:0%; opacity: 0.7; display:none ';
//     }
//     var InfoPlot = document.getElementById('calinfoid');
//     var InfoWaitPlot = document.getElementById('calinfoWaitid');
//     if (true) UpdateGUI(_GazeData);
//     GUIState = 'RunCalibration';
//     if (true) showinit.style.display = 'none';
//     const imagearrow = document.getElementById('arrowimgid');
//     const imagecal = document.getElementById('calimgid');
//     CalDeviceRation = devicePixelRatio;
//     CalDeviceRation = (window.innerWidth * window.devicePixelRatio) / window.screen.width;
//     bIsCalibrated = false;
//     bIsRunCalibration = true;
//     document.getElementById("CalDivId").style.display = "block";
//     var canvas = document.getElementById("CalCanvasId");
//     canvas.width = window.innerWidth;
//     canvas.height = window.innerHeight;
//     if (true) UpdateGUI(_GazeData);
//     document.addEventListener('keydown', doKeyDown);
//     var vPoints = [];
//     var mx = .03;
//     var my = .05;
//     var step = 3;
//     var stepx = step;
//     var stepy = step;
//     var isMobile = window.orientation > -1;
//     if (isMobile) {
//         stepx = 2;
//         stepy = 2;
//     }
//     if (window.innerWidth < window.innerHeight) {
//         stepx = stepy - 1;
//     }
//     if (false) {
//         stepx = 2;
//         stepy = 2;
//     }
//     if (false) {
//         stepx = stepy = 2;
//     }
// var calinfolook = document.getElementById('calinfolook').innerHTML;
// var calinfohead = document.getElementById('calinfohead').innerHTML;
// var SizeF = 1.0
// if (isMobile) SizeF = .7;
// var minSize = 16;
// var AddSize = 20;
// var showtime = 1200;
// var infotime = 2500;
// AddSize = 20 * SizeF;
// minSize = 10 * SizeF;
// var MainColor = "#777777";
// "gray";
// vPoints.push({
//     x: .5,
//     y: .5,
//     color: MainColor,
//     txt: calinfolook,
//     type: -1,
//     time: infotime
// });
// vPoints.push({
//     x: .5,
//     y: .5,
//     color: MainColor,
//     type: 0,
//     time: showtime
// });
// for (var y = my; y <= 1.0 - my; y += (1.0 - 2 * my) / stepy)
//     for (var x = mx; x <= 1.0 - mx; x += (1.0 - 2 * mx) / stepx) {
//         if (true) {
//             px = vPoints[vPoints.length + -1].x;
//             py = vPoints[vPoints.length + -1].y;
//             var d = Math.sqrt((x - px) * (x - px) + (y - py) * (y - py));
//             var MoveTime = 200 + (600.0 * d);
//             var pp = {
//                 x: x,
//                 y: y,
//                 color: MainColor,
//                 type: 1,
//                 time: MoveTime
//             };
//             vPoints.push(pp);
//         }
//     var p = {
//         x: x,
//         y: y,
//         color: MainColor,
//         type: 0,
//         time: showtime
//     };
//     vPoints.push(p);
// }
// if (true) {
//     var x = .5;
//     var y = .5;
//     px = vPoints[vPoints.length + -1].x;
//     py = vPoints[vPoints.length + -1].y;
//     var d = Math.sqrt((x - px) * (x - px) + (y - py) * (y - py));
//     var MoveTime = 200 + (600.0 * d);
//     var pp = {
//         x: x,
//         y: y,
//         color: MainColor,
//         type: 1,
//         time: MoveTime
//     };
//     vPoints.push(pp);
// }
// if (true) {
//     var MoveTime = 1000;
//     vPoints.push({
//         x: .5,
//         y: .5,
//         color: "play",
//         type: 0,
//         time: 1.5 * showtime
//     });
//     vPoints.push({
//         x: .1,
//         y: .1,
//         color: "black",
//         type: 1,
//         time: MoveTime
//     });
//     vPoints.push({
//         x: .1,
//         y: .1,
//         color: "black",
//         type: 0,
//         time: showtime
//     });
//     vPoints.push({
//         x: .9,
//         y: .1,
//         color: "black",
//         type: 1,
//         time: MoveTime
//     });
//     vPoints.push({
//         x: .9,
//         y: .1,
//         color: "black",
//         type: 0,
//         time: showtime
//     });
//     vPoints.push({
//         x: .9,
//         y: .9,
//         color: "black",
//         type: 1,
//         time: MoveTime
//     });
//     vPoints.push({
//         x: .9,
//         y: .9,
//         color: "black",
//         type: 0,
//         time: showtime
//     });
//     vPoints.push({
//         x: .1,
//         y: .9,
//         color: "black",
//         type: 1,
//         time: MoveTime
//     });
//     vPoints.push({
//         x: .1,
//         y: .9,
//         color: "black",
//         type: 0,
//         time: showtime
//     });
// }
// if (true) {
//     var headmove = .3;
//     vPoints.push({
//         x: .5,
//         y: .5,
//         color: "white",
//         txt: calinfohead,
//         type: -1,
//         time: infotime,
//         head: document.getElementById('arrowleft')
//     });
//     vPoints.push({
//         x: .5 - headmove,
//         y: .5,
//         color: "white",
//         type: 1,
//         time: showtime,
//         head: document.getElementById('arrowleft')
//     });
// vPoints.push({
//     x: .5 - headmove,
//     y: .5,
//     color: "white",
//     type: 0,
//     time: showtime
// });
// vPoints.push({
//     x: .5,
//     y: .5,
//     color: "white",
//     type: 0,
//     time: showtime
// });
// vPoints.push({
//     x: .5 + headmove,
//     y: .5,
//     color: "white",
//     type: 1,
//     time: showtime,
//     head: document.getElementById('arrowright')
// });
// vPoints.push({
//     x: .5 + headmove,
//     y: .5,
//     color: "white",
//     type: 0,
//     time: showtime
// });
// vPoints.push({
//     x: .5,
//     y: .5,
//     color: "white",
//     type: 0,
//     time: showtime
// });
// vPoints.push({
//     x: .5,
//     y: .5 - headmove,
//     color: "white",
//     type: 1,
//     time: showtime,
//     head: document.getElementById('arrowup')
// });
// vPoints.push({
//     x: .5,
//     y: .5 - headmove,
//     color: "white",
//     type: 0,
//     time: showtime
// });
// vPoints.push({
//     x: .5,
//     y: .5,
//     color: "white",
//     type: 0,
//     time: showtime
// });
// vPoints.push({
//     x: .5,
//     y: .5 + headmove,
//     color: "white",
//     type: 1,
//     time: showtime,
//     head: document.getElementById('arrowdown')
// });
//     vPoints.push({
//         x: .5,
//         y: .5 + headmove,
//         color: "white",
//         type: 0,
//         time: showtime
//     });
//     vPoints.push({
//         x: .5,
//         y: .5,
//         color: "white",
//         type: 0,
//         time: showtime
//     });
// }
// var Ix = 0;
// var x = 0;
// var y = .3;
// var size = 1.0;
// var startTime = Date.now();
// size = -1;
// Ix = -1;
// Ix = 0;
// size = 1.0;
// if (true) {
//     ws.send(sendScreensize());
//     ws.send("cmd:StartCalibration");
// }
// if (true) UpdateGUI(_GazeData);
// if (true) {
//     RePlayVideo();
// }
// var StartPointFrameNr = CurFrameNr;
// _LoopCalibration = setInterval(() => {
//     if (TrackingLostShow) {
//         return;
//     }
//     if (!bIsRunCalibration) return;
//     var duration = Date.now() - startTime;
//     try {
//         if (Ix > -1)
//             if (vPoints[Ix].color == "play") showtime *= 1.5;
//         var _conf = duration / vPoints[Ix].time;
//         if (_conf > 1) _conf = 1;
//         size = 1.0 - _conf;
//         var FrameCountOk = true;
//         if (vPoints[Ix].type == 0) {
//             if (CurFrameNr - StartPointFrameNr < 15) FrameCountOk = false;
//             if (duration / vPoints[Ix].time > 2.5) FrameCountOk = true
//         }
//         if (FrameCountOk)
//             if (size < .01 || Ix < 0) {
//                 Ix++;
//                 size = 1.0;
//                 startTime = Date.now();
//                 StartPointFrameNr = CurFrameNr;
//     } if (Ix > vPoints.length - 1) {
//         FinishCalibration();
//         return;
//     }
// if (vPoints[Ix].color == "play") {
//     var c = size * 255;
//     var color = 'rgb(' + c + ' , ' + c + ' , ' + c + ' )';
//     canvas.style.backgroundColor = color;
// } else canvas.style.backgroundColor = vPoints[Ix].color;
// x = vPoints[Ix].x;
// y = vPoints[Ix].y;
// if (Ix > 0 && vPoints[Ix].type == 1) {
//     var f = 1.0 - size;
//     f = 1.0 - Math.sin(f * (3.14 / 2.0));
//     x = (vPoints[Ix - 1].x * f + (1.0 - f) * vPoints[Ix].x);
//     y = (vPoints[Ix - 1].y * f + (1.0 - f) * vPoints[Ix].y);
//     size = 1.0 - size;
// }
// if (false) ctx.globalCompositeOperation = 'destination-over';
// if (false) {
//     ctx.fillStyle = "#646C7F";
//     ctx.fillRect(0, 0, canvas.width, canvas.height);
// } else ctx.clearRect(0, 0, canvas.width, canvas.height);
// if (true) {
//     if (typeof vPoints[Ix].txt !== 'undefined') {
//         InfoPlot.innerHTML = vPoints[Ix].txt;
//         InfoWaitPlot.innerHTML = Math.round(size * 3.0);
//         size = 1;
//     } else {
//         InfoPlot.innerHTML = "";
//         InfoWaitPlot.innerHTML = "";
//     }
// }
// ctx.fillStyle = 'red';
// ctx.beginPath();
// ctx.arc(x * canvas.width, y * canvas.height, (minSize + AddSize * size), 0, 2 * Math.PI);
// ctx.fill();
// ctx.stroke();
// if (typeof vPoints[Ix].head !== 'undefined') {
//     var ss = 2 * (minSize + AddSize) + 3;
//     ctx.drawImage(vPoints[Ix].head, x * canvas.width - ss / 2, y * canvas.height - ss / 2, ss, ss);
// } else {
//     var ss = 2 * (minSize + AddSize) + 3;
//     ctx.drawImage(imagecal, x * canvas.width - ss / 2, y * canvas.height - ss / 2, ss, ss);
// }
// } catch (ee) { }
//         if (_GazeData.state == -1) {
//             if (vPoints[Ix].type == 1) {
//                 Ix++;
//             }
//             CurCalPoint = null;
//         } else {
//             if (true) {
//                 if (vPoints[Ix].type != 0) _conf = 0;
//                 var isMobile = window.orientation > -1;
//                 if (true) {
//                     CurCalPoint = {
//                         x: x * window.innerWidth * window.devicePixelRatio / CalDeviceRation + window.screenX,
//                         y: y * window.innerHeight * window.devicePixelRatio / CalDeviceRation + window.screenY + (window.outerHeight - window.innerHeight * window.devicePixelRatio / CalDeviceRation),
//                         conf: _conf,
//                         type: 0
//                     };
//                 } else {
//                     CurCalPoint = {
//                         x: x * window.screen.width,
//                         y: y * window.screen.height,
//                         conf: _conf,
//                         type: 0
//                     };
//                 }
//                 if (false) {
//                     CurCalPoint = {
//                         x: x * window.innerWidth + window.screenX,
//                         y: y * window.innerHeight + window.screenY + (window.outerHeight - window.innerHeight),
//                         conf: _conf,
//                         type: 0
//                     };
//                 }
//             } else CurCalPoint = null;
//         }
//     }, 30);
//     if (true) UpdateGUI(_GazeData);
// }

// function isFullscreen() {
//     var st = screen.top || screen.availTop || window.screenTop;
//     if (st != window.screenY) {
//         return false;
//     }
//     return window.fullScreen == true || screen.height - document.documentElement.clientHeight <= 30;
// }

// function InitClickCalibration() {
//     console.log("InitClickCalibration click document.onmousedown ");
//     document.onmousedown = processClick;
//     return;
//     var cursorX;
//     var cursorY;
//     document.onmousedown = function (e) {
//         cursorX = e.screenX;
//         cursorY = e.screenY;
//         console.log("InitClickCalibration click document.onmousedown ");
//         if (!bIsRunCalibration) {
//             CurCalPoint = {
//                 x: cursorX,
//                 y: cursorY,
//                 conf: 1.0,
//                 type: 10
//             };
//         }
//     }
// }

// function processClick(e) {
//     if (!GazeCloudAPI.UseClickRecalibration) return;
//     var cursorX;
//     var cursorY;
//     cursorX = e.screenX;
//     cursorY = e.screenY;
//     console.log("processClick click document.onmousedown ");
//     if (!bIsRunCalibration) {
//         CurCalPoint = {
//             x: cursorX,
//             y: cursorY,
//             conf: 1.0,
//             type: 10
//         };
//     }
// }
// var _LastGazeD;
// var _OnlyEyesC = 0;
// var _OnlyEyesCount = 0;
// var ctx = null;
// var ctxL = null;
// var ctxR = null;
// var _canvas = null;
// var canvasContext = null;
// var bLastUseLowQuality = false;

// function getGrayFrameROIResize(_video, GazeD, bOnlyEyes = false, quality = .9) {
//     try {
//         if (_canvas == null) {
//             _canvas = document.createElement('canvas');
//             canvasContext = _canvas.getContext('2d');
//         }
//         var rx = 0;
//         var ry = 0;
//         var rw = _video.videoWidth;
//         var rh = _video.videoHeight;
//         if (typeof GazeD === 'undefined' || GazeD.state == -1) {
//             ;
//         } else {
//             {
//                 rx = GazeD.rx;
//                 ry = GazeD.ry;
//                 rw = GazeD.rw;
//                 rh = GazeD.rh;
//             }
//         }
//         _canvas.width = 80;
//         if (bLastUseLowQuality) fff *= .7;
//         if (false) {
//             if (SkipFactor < fff) {
//                 _canvas.width = 40;
//                 bLastUseLowQuality = true;
//             } else {
//                 bLastUseLowQuality = false;
//             }
//         }
//         _canvas.height = _canvas.width;
//         LastVideoTime = video.currentTime;
//         canvasContext.drawImage(_video, rx, ry, rw, rh, 0, 0, _canvas.width, _canvas.height);
//         if (true)
//             if (GazeD.state == -1) quality = .8;
//         const datagray = _canvas.toDataURL('image/jpeg', quality);
//         var r;
//         r = {
//             'imgdata': datagray,
//             'w': _video.videoWidth,
//             'h': _video.videoHeight,
//             'rx': rx,
//             'ry': ry,
//             'rw': rw,
//             'rh': rh,
//             's': _canvas.width
//         };
//         return r;
//     } catch (ee) {
//         if (Logg) Logg("getFrame exeption : " + ee.message, -2);
//     }
// }

// function dataURItoBlob(dataURI) {
//     var byteString;
//     if (dataURI.split(',')[0].indexOf('base64') >= 0) byteString = atob(dataURI.split(',')[1]);
//     else byteString = unescape(dataURI.split(',')[1]);
//     var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
//     var ia = new Uint8Array(byteString.length);
//     for (var i = 0; i < byteString.length; i++) {
//         ia[i] = byteString.charCodeAt(i);
//     }
//     return ia;
//     return new Blob([ia], {
//         type: mimeString
//     });
// }

// function sleep(time) {
//     return new Promise((resolve) => setTimeout(resolve, time));
// }
// var _LoopFPS;

// function GetFrameFPS() {
//     return 30;
// }
// var minNetworkDelay = 999999;
// var maxNetworkDelay = 0;
// var avrNetworkDelay = 0;
// var networkDelay = 0;
// var processkDelay = 0;
// var skipProcessCount = 0;
// var CamFPS = 0;

// function SendStat() {
//     try {
//         var stat = "CamFPS:" + CamFPS + " minNetworkDelay: " + minNetworkDelay + " maxNetworkDelay: " + maxNetworkDelay + " avrNetworkDelay: " + avrNetworkDelay + " skipProcessCount: " + skipProcessCount + " kipF: " + skipProcessCount / CurFrameNr;
//         if (Logg) Logg("stat : " + stat, 5);
//         ws.send(stat);
//     } catch (e) { }
// }
// var fpsstartTime = -1;
// var fpst = 0
// var fpscout = 0
// function UpdateCamFPS() {
//     if (fpsstartTime == -1) fpsstartTime = Date.now();
//     var t = video.currentTime;
//     if (t > fpst) {
//         fpscout++;
//     }
//     var tt = Date.now();
//     if (tt - fpsstartTime > 1000 * 2) {
//         CamFPS = 1000.0 * fpscout / (tt - fpsstartTime);
//         console.log(" CamFPS" + CamFPS);
//         if (true) {
//             clearInterval(_LoopCamSend);
//             var interval = 1000 / CamFPS;
//             _CamLoopInterval = interval;
//             interval += 3;
//             if (interval < 33) interval = 33;
//             if (false) {
//                 _CamLoopInterval = interval;
//                 _LoopCamSend = setInterval(CamSend, _CamLoopInterval);
//             }
//         }
//     } else {
//         fpst = t;
//         setTimeout(UpdateCamFPS, 2);
//     }
// }
// var _delaySendC = 0;
// var ConnectionAuthorizationStatus;
// var bCamOk = false;
// var ws = null;
// var _delaySendC = 0;
// var curTimeStap = 0;
// var CurFrameNr = 0;
// var CurFrameReciveNr = 0;
// var CurFrameReciveTime = 0;
// var CurFrameAckNr = 0;
// var CurFrameAckTime = 0;
// var _fps = -1;
// var _LoopCamSend = null;
// var oCamSendFPS = 30;
// this.SetFps = function (fps = 30) {
//     if (fps > 30) fps = 30;
//     if (fps < 3) fps = 3;
//     oCamSendFPS = fps;
// }
// var bGazeCloundLowFpsSend = false;
// this.SetLowFps = function (bval = false) {
//     bGazeCloundLowFpsSend = bval;
// }
// var SendFrameCountDelay = 0;
// var SkipCount = 0;
// var SkipFactor = 1;
// var CheckFpsDelayIter = 0;
// var ZoomCanvas = null;
// var ZoomCanvasStream = null;
// var ZoomCanvasCtx = null;
// var LastCamFrameNr = 0;
// var SkipCamFrameCount = 0;
// var LastVideoTime = 0;
// var LastVideoGrabTime = 0;
// var LastSendVidoTime = 0;
// var LastSendVideoTime = 0;

// function GetWebCamFrameNr() {
//     return video.presentedFrames ? !0 : video.mozPaintedFrames
// };
// var LastSendTime = 0;
// var FrameTime = 0;
// var LastWaitT = 30;
// var bExitCamSendLoop = false;

// function CamSendLoop() {
//     if (bExitCamSendLoop) return;
//     var videoTime = video.currentTime;
//     var bNewVideoGrap = true;
//     if (true) {
//         var tt = Date.now();
//         if (videoTime <= LastVideoTime) {
//             bNewVideoGrap = false;
//             if (false) video.play();
//             SkipCamFrameCount++;
//             var dd = tt - LastVideoGrabTime;
//             if (dd > 500) {
//                 video.play();
//                 if (Logg) Logg("frozen video : " + SkipCamFrameCount + " dt " + dd, 2);
//                 console.log(" frozen replay " + SkipCamFrameCount + " dt " + dd);
//                 LastVideoGrabTime = tt;
//                 LastVideoTime = videoTime;
//                 SkipCamFrameCount = 0;
//             }
//             if (true) {
//                 console.log(" no video change " + videoTime + " ; " + LastVideoTime);
//                 1);
//                 requestAnimationFrame(CamSendLoop);
//                 return;
//             }
//         } else {
//             LastVideoGrabTime = tt;
//             LastVideoTime = videoTime;
//             SkipCamFrameCount = 0;
//         }
//     }
// if (ws == null) return;
// if (ws.readyState != WebSocket.OPEN) return;
// var bSend = true;
// var BuforMaxC = 6;
// if (true) {
//     BuforMaxC = 5 + minNetworkDelay / 33;
//     if (BuforMaxC > 15) BuforMaxC = 15;
//     if (BuforMaxC < 5) BuforMaxC = 5;
//     if (true) {
//         BuforMaxC *= oCamSendFPS / 30.0;
//         if (BuforMaxC < 2) BuforMaxC = 2;
//     }
// }
// var FrameCountDelay = CurFrameNr - CurFrameReciveNr;
// var FrameCountDelayAck = CurFrameNr - CurFrameAckNr;
// if (true) {
//     if (_GazeData.state == -1) {
//         if (FrameCountDelay > 2) bSend = false;
//     } else {
//         if (FrameCountDelay > BuforMaxC) bSend = false;
//     }
//     if (bGazeCloundLowFpsSend) {
//         if (FrameCountDelay > 1) bSend = false;
//     }
//     var waitT = 33;
//     if (FrameCountDelay >= BuforMaxC) waitT = 66;
// if (false)
//     if (FrameCountDelay > 4) {
//         var t = Date.now();
//         var delayProcess = t - CurFrameReciveTime;
//         var dif = t - LastSendTime;
//         if (waitT > 200) waitT = 200;
//         if (waitT < _CamLoopInterval) waitT = _CamLoopInterval;
//         var networkDelay = t - CurFrameAckTime;
//         if (networkDelay < minNetworkDelay * 1.2 || networkDelay < .3 * avrNetworkDelay) waitT = _CamLoopInterval;
//     } if (waitT < .8 * LastWaitT) waitT = .8 * LastWaitT;
// if (true) {
//     waitT = 1.0 / oCamSendFPS * 1000.0 - 2;
// }
//     var t = Date.now();
//     var dif = t - LastSendTime;
//     if (bSend)
//         if (dif < waitT) {
//             bSend = false;
//             console.log(" wait send to hight cpu " + dif);
//         } console.log(" waitT " + waitT);
//     waitT = LastWaitT * .9 + .1 * waitT;
//     LastWaitT = waitT;
//     SendFrameCountDelay = FrameCountDelay;
// }
// if (bNewVideoGrap) SkipCount++;
// if (bSend && !bNewVideoGrap) {
//     console.log(" no video change try resend prev " + LastSendVideoTime + " ; " + LastVideoTime);
// }
// if (bSend) {
//     SkipCount = 0;
//     var OnlyEyes = false;
//     if (CurCalPoint != null) {
//         var cp = Object.assign({}, CurCalPoint);
//         var json_data = JSON.stringify(cp);
//         ws.send(json_data);
//         if (CurCalPoint.type == 10) CurCalPoint = null;
//     }
//     FrameTime = Date.now();
//     LastSendTime = FrameTime;
//     var dd = getGrayFrameROIResize(video, _GazeData, OnlyEyes);
//     LastSendVideoTime = LastVideoTime;
//     curTimeStap = Date.now();
//     dd.time = curTimeStap;
//     dd.FrameNr = CurFrameNr;
//     CurFrameNr++;
//     var myJSON = JSON.stringify(dd);
//     if (false) SendBinary(myJSON);
//     else ws.send(myJSON);
//     if (true) ProcessWebRecStream();
// }
//     if (bNewVideoGrap) {
//         CheckFpsDelayIter++;
//         if (_GazeData.state != -1) (!bGazeCloundLowFpsSend) {
//             if (CurFrameNr > 100) {
//                 var s = 1;
//                 if (!bSend) s = 0;
//                 SkipFactor = .95 * SkipFactor + s * .05;
//             }
//         }
//         var FrameCountDelay = CurFrameNr - CurFrameReciveNr;
//         var waitT = 33;
//         var processDelay = Date.now() - LastSendTime;
//         waitT = _CamLoopInterval;
//         waitT -= processDelay;
//         if (false)
//             if (FrameCountDelay > 4) waitT += (FrameCountDelay - 4) / 10.0 * 100.0;
//         if (waitT < 5) waitT = 5;
//     }
//     if (bSend) {
//         var dd = (FrameCountDelay - BuforMaxC * .7) / BuforMaxC;
//         if (dd < 0) dd = 0;
//         var ww = 30 + 30 * dd;
//         setTimeout(function () {
//             requestAnimationFrame(CamSendLoop);
//         }, 5););
//     } else requestAnimationFrame(CamSendLoop);
// }
// var bIsSending = false;

// function CamSend() {
//     return;
//     bIsSending = true;
//     if (true) {
//         var videoTime = video.currentTime;
//         if (videoTime <= LastVideoTime) {
//             if (false) video.play();
//             SkipCamFrameCount++;
//             console.log(" SkipCamFrameCount " + SkipCamFrameCount);
//             if (SkipCamFrameCount > 10 * 33.0 / _CamLoopInterval) {
//                 video.play();
//                 RePlayVideo();
//                 if (Logg) Logg("frozen video : " + SkipCamFrameCount, 2);
//                 console.log(" frozen replay " + SkipCamFrameCount);
//             }
//             bIsSending = false;
//             return;
//         }
//         LastVideoTime = videoTime;
//         SkipCamFrameCount = 0;
//     }
//     if (false) {
//         if (false)
//             if (ZoomCanvas == null) {
//             ZoomCanvas = document.createElement("canvas");
//             ZoomCanvas.width = videoOrginal.videoWidth;
//             ZoomCanvas.height = videoOrginal.videoHeight;
//             ZoomCanvasStream = ZoomCanvas.captureStream(5);
//             video.src = ZoomCanvasStream;
//         } if (ZoomCanvasCtx) ZoomCanvasCtx.drawImage(videoOrginal, (videoOrginal.videoWidth - ZoomCanvas.width) / 2, (videoOrginal.videoHeight - ZoomCanvas.height) / 2, ZoomCanvas.width, ZoomCanvas.height, 0, 0, ZoomCanvas.width, ZoomCanvas.height);
// }
// if (ws == null) {
//     bIsSending = false;
//     return;
// }
// if (ws.readyState != WebSocket.OPEN) {
//     bIsSending = false;
//     return;
// }
// var bSend = true;
// if (true) {
//     var FrameCountDelay = CurFrameNr - CurFrameReciveNr;
//     var FrameCountDelayAck = CurFrameNr - CurFrameAckNr;
//     if (FrameCountDelay > FrameCountDelayAck - 1) FrameCountDelay = FrameCountDelayAck - 1;
//     if (_GazeData.state == -1) {
//         if (FrameCountDelay > 2) bSend = false;
//     } else {
//         if (FrameCountDelay > 6) bSend = false;
//     }
//     if (bGazeCloundLowFpsSend) {
//         if (FrameCountDelay > 1) bSend = false;
//     }
//     SendFrameCountDelay = FrameCountDelay;
// }
//     SkipCount++;
//     if (bSend) {
//         bIsSending = true;
//         SkipCount = 0;
//         var OnlyEyes = false;
//         if (CurCalPoint != null) {
//             var cp = Object.assign({}, CurCalPoint);
//             var json_data = JSON.stringify(cp);
//             ws.send(json_data);
//             if (CurCalPoint.type == 10) CurCalPoint = null;
//         }
//         var dd = getGrayFrameROIResize(video, _GazeData, OnlyEyes);
//         curTimeStap = Date.now();
//         dd.time = curTimeStap;
//         dd.FrameNr = CurFrameNr;
//         CurFrameNr++;
//         var myJSON = JSON.stringify(dd);
//         ws.send(myJSON);
//     }
//     CheckFpsDelayIter++;
//     if (_GazeData.state != -1)
//         if (!bGazeCloundLowFpsSend) {
//             if (CurFrameNr > 100) {
//                 var s = 1;
//                 if (!bSend) s = 0;
//                 SkipFactor = .95 * SkipFactor + s * .05;
//             }
//         } bIsSending = false;
// }
// if (false) setInterval(function () {
//     try {
//         ws.send(" ");
//     } catch (e) { }
// }, 10);
// var curFps = 100;

// function SetSendFps(vfps = 29) {
//     return;
//     if (vfps < 10) vfps = 10;
//     if (vfps > 30) vfps = 30;
//     console.log(" SetSendFps : " + vfps);
//     if (curFps == vfps) return;
//     curFps = vfps;
//     console.log("SetSendFps : " + vfps);
//     if (Logg) Logg("SetSendFps : " + vfps, 2);
//     if (_LoopCamSend) clearInterval(_LoopCamSend);
//     _LoopCamSend = setInterval(CamSend, 1000 / vfps);
// }
// var _LoopPlay = null;
// var _CamLoopInterval = 36;

// function InitCamSend() {
//     var FPS = 30;
//     try {
//         if (_fps < 0) {
//             _fps = video.srcObject.getVideoTracks()[0].getSettings().frameRate;
//             FPS = _fps;
//             FPS = 28;
//         }
//     } catch (err) {
//         ;
//     }
//     bExitCamSendLoop = false;
//     CamSendLoop();
// }
// var MediaStrem = null;

// function HideGUI() {
//     try {
//         var GazeFlowContainer = document.getElementById("GazeFlowContainer");
//         GazeFlowContainer.style.display = 'none';
//         showinit.style.display = 'none';
//         loadid.style.display = 'none';
//         initializingid.style.display = 'none';
//         CalDivId.style.display = 'none';
//         infoWaitForCalibration.style.display = 'none';
//         errid.style.display = 'none';
//         demoid.style.display = 'none';
//         CamAccessid.style.display = 'none';
//         camid.style.display = 'none';
//         disableStyle('GazeCloudAPI.css', true);
//     } catch (ee) { }
// }

// function CloseWebCam() {
//     try {
//         bExitCamSendLoop = true;
//         if (true) {
//             LastVideoTime = 0;
//             LastCamFrameNr = 0;
//             SkipCamFrameCount = 0;
//             curFps = 30;
//             SkipFactor = 1;
//             _delaySendC = 0;
//             bCamOk = false;
//             _delaySendC = 0;
//             curTimeStap = 0;
//             CurFrameNr = 0;
//             CurFrameReciveNr = 0;
//             CurFrameReciveTime = 0;
//             CurFrameAckNr = 0;
//             CurFrameAckTime = 0;
//             _GazeData.FrameNr = 0;
//             _GazeData.state = -1;
//         }
//         RetrayCountNoSlot = 0;
//         ConnectCount = 0;
//         GoodFrameCount = 0;
//         BadFrameCount = 0;
//         bCamOk = false;
//         _delaySendC = 0;
//         curTimeStap = 0;
//         CurFrameNr = 0;
//         CurFrameReciveNr = 0;
//         CurFrameAckNr = 0;
//         CurFrameAckTime = 0;
//         RedirectCount = 0;
//         if (_LoopCamSend != null) clearInterval(_LoopCamSend);
//         _LoopCamSend = null;
//         if (MediaStrem != null) MediaStrem.getTracks()[0].stop();
//         Disconect();
//         UpdateGUI(_GazeData);
//     } catch (a) {
//         ;
//     }
//     try {
//         if (OnStopGazeFlow != null) OnStopGazeFlow();
//     } catch (e) { }
// }
// if (navigator.mediaDevices === undefined) {
//     navigator.mediaDevices = {};
// }

// function CheckgetUserMedia() {
//     if (navigator.mediaDevices.getUserMedia === undefined) {
//         navigator.mediaDevices.getUserMedia = function (constraints) {
//             var getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
//             if (!getUserMedia) {
//                 Logg("getUserMedia is not implemented in this browser! ", -2);
//                 alert("Camera access is not supported by this browser! Try: Chrome 53+ | Edge 12+ | Firefox 42+ | Opera 40+ | Safari 11+ ");
//                 return Promise.reject(new Error('getUserMedia is not implemented in this browser'));
//             }
//             return new Promise(function (resolve, reject) {
//                 getUserMedia.call(navigator, constraints, resolve, reject);
//             });
//         }
//     }
// }
var DeneidCount = 0;

// function deniedStream(err) {
//     DeneidCount++;
//     (function () {
//         alert("Camera access denied! Please, Allow Camera Access to start Eye-Tracking");
//         if (Logg) Logg("Camera access denied! " + err.message, 2);
//         if (DeneidCount == 0) StartCamera();
//         else {
//             StopGazeFlow();
//             if (GazeCloudAPI.OnCamDenied != null) GazeCloudAPI.OnCamDenied();
//         }
//         3000);
//     }

// function errorStream(e) {
//     if (e) {
//         console.error(e);
//         if (Logg) Logg("errorStream " + e.name + ": " + e.message, -2);
//     }
//     StopGazeFlow();
//     if (GazeCloudAPI.OnCamDenied != null) GazeCloudAPI.OnCamDenied();
// }
// var backgroundCanvas = null;
// var bgCanCon = null;

// function RePlayVideo() {
//     return;
//     try {
//         video.play();
//         bgCanCon.drawImage(video, 0, 0);
//         setTimeout(function () {
//             video.play();
//         }, 1000);, 1500);
//     } catch (e) {
//         if (Logg) Logg("RePlayVideo exeption" + e.mesage, -2);
//     }
// }

// function startStream(stream) {
//     DeneidCount = 0;
//     backgroundCanvas = document.getElementById('bgcanvas');
//     bgCanCon = backgroundCanvas.getContext('2d');
//     MediaStrem = stream;
//     bgCanCon.drawImage(video, 0, 0);
//     video.addEventListener('canplay', function DoStuff() {
//         bgCanCon.drawImage(video, 0, 0);
//         video.removeEventListener('canplay', DoStuff, true);
//         setTimeout(function () {
//             video.play();
//             UpdateCamFPS();
//         }, 1000);
//     }, true);
//     video.srcObject = stream;
//     video.play();
//     if (false) {
//         bgCanCon.drawImage(videoOrginal, 0, 0);
//         videoOrginal.addEventListener('canplay', function DoStuff() {
//             bgCanCon.drawImage(videoOrginal, 0, 0);
//             videoOrginal.removeEventListener('canplay', DoStuff, true);
//             setTimeout(function () {
//                 videoOrginal.play();
//             }, 1000);
//         }, true);
//         videoOrginal.srcObject = stream;
//         videoOrginal.play();
//     }
//     InitVideo(stream);
// }
// var video_constraints = {
//     width: {
//         min: 1920,
//         max: 1920
//     },
//     height: {
//         min: 1080,
//         max: 1080
//     },
//     require: ["width", "height"]
// };

// function StartCamera() {
//     if (true) {
//         try {
//             CheckgetUserMedia();
//         } catch (ee) {
//             if (Logg) Logg("CheckgetUserMedia exeption" + ee.mesage, -2);
//         }
//     }
//     try {
//         navigator.mediaDevices.getUserMedia({
//             video: true,
//             audio: false,
//             width: 480,
//             height: 640
//         }).then(startStream).catch(deniedStream);
//     } catch (e) {
//         try {
//             navigator.mediaDevices.getUserMedia('video', startStream, deniedStream);
//         } catch (e) {
//             errorStream(e);
//         }
//     }
//     video.loop = video.muted = true;
//     video.autoplay = true;
//     video.load();
//     videoOrginal.loop = video.muted = true;
//     videoOrginal.autoplay = true;
//     videoOrginal.load();
// }

// function OpenWebCam() {
//     bExitCamSendLoop = false;
//     video = document.getElementById("showvideoid");
//     videoOrginal = document.createElement('video');
//     videoOrginal.width = 640;
//     videoOrginal.height = 480;
//     if (true) {
//         video.onended = function () {
//             if (Logg) Logg("video has ended", -2);
//         }
//         video.onpause = function () {
//             RePlayVideo();
//             if (Logg) Logg("video has onpause", -2);
//         }
//     }
//     try {
//         if (true) {
//             video.setAttribute("playsinline", true);
//             videoOrginal.setAttribute("playsinline", true);
//         }
//     } catch (ee) { }
//     document.getElementById("CamAccessid").style.display = 'block';;
//     GUIState = 'WaitWebCam';
//     var ff = .5;
//     if (false)
//         if (true) {
//             _w_ = 1280;
//             _h_ = 720;
//         } var isMobile = window.orientation > -1;
//     if (false) {
//         try {
//             navigator.mediaDevices.getUserMedia({
//                 video: {
//                     width: _w_,
//                     height: _h_
//                 }
//             }).then((stream) => InitVideo(stream)).catch(function (err) {
//                 StopGazeFlow();
//                 alert("Can not acces webcam " + err.name + ": " + err.message);
//                 console.log(err.name + ": " + err.message);
//                 if (Logg) Logg(err.name + ": " + err.message, -2);
//             });
//         } catch (error) {
//             alert("init webcam fail" + error);
//             if (Logg) Logg("init webcam fail" + error, -2);
//         }
//     }
//     StartCamera();
//     if (false) UpdateGUI(_GazeData);
// }
// var MediaInfo = "";

// function InitVideo(s) {
//     try {
//         if (false) {
//             MediaStrem = s;
//             videoOrginal = document.createElement('video');
//             video.srcObject = s;
//             videoOrginal.srcObject = s;
//             videoOrginal.autoplay = true;
//         }
//         if (false) videoOrginal.onplay = function () {
//             if (ZoomCanvas == null) {
//                 try {
//                     ZoomCanvas = document.createElement("canvas");
//                     ZoomCanvas.width = 640;
//                     ZoomCanvas.height = 480;
//                     ZoomCanvasStream = ZoomCanvas.captureStream(30);
//                     ZoomCanvasCtx = ZoomCanvas.getContext('2d');
//                     video.srcObject = ZoomCanvasStream;
//                 } catch (e) { }
//             }
//         }
//         bCamOk = true;
//         Connect();
//         if (false) video.onplay = function () {
//             Connect();
//         }
//         if (true) document.getElementById("CamAccessid").style.display = "none";
//         if (true) UpdateGUI(_GazeData);
//         if (false)
//             if (true) {
//                 navigator.mediaDevices.enumerateDevices().then(function (devices) {
//                     devices.forEach(function (device) {
//                         MediaInfo += device.kind + ": " + device.label + " id = " + device.deviceId;
//                     });
//                 }).catch(function (err) {
//                     console.log(err.name + ": " + err.message);
//                 });
//             }
//     } catch (error) {
//         console.log("InitVideo err " + error);
//     }
// }

// function StreamReady() { }
// var GazeFlowSesionID = null;
// var GazeCloudServerAdress = "wss://cloud.gazerecorder.com:";
// var GazeCloudServerPort = 43334;
// var isWaitForAutoryzation = null;
// var RedirectCount = 0;
// var ConnectionOk = false;
// var ConnectCount = 0;
// var GoodFrameCount = 0;
// var BadFrameCount = 0;
// var RedirectPort = 43335;
// var GetCloudAdressReady = false;
// var _WaitForGetCloudAdress = null;
// var GetCloudAdresInfo = null;

// function GetCloudAdress() {
//     GetCloudAdressReady = false;
//     var url = 'https://api.gazerecorder.com/GetCloudAdress/';
//     let req = new XMLHttpRequest();
//     let formData = new FormData();
//     req.open("GET", url);
//     req.onload = function () {
//         try {
//             var info = JSON.parse(req.response);
//             GetCloudAdresInfo = info;
//             if (typeof info.err !== 'undefined')
//                 if (info.err != "") {
//                     ShowErr("info.err");
//                     return;
//                 } GazeCloudServerAdress = info.adress;
//             GazeCloudServerPort = info.port;
//             GetCloudAdressReady = true;
//         } catch (e) { }
//     }
//     req.onerror = function (e) {
//         if (Logg) Logg("GetCloudAdress err ");
//     }
//     req.send(null);
// }
// GetCloudAdress();

// function WaitForAutoryzation() {
//     RedirectPort = GazeCloudServerPort + 1;
//     if (isWaitForAutoryzation != null) {
//         clearTimeout(isWaitForAutoryzation);
//         isWaitForAutoryzation = null;
//     }
//     isWaitForAutoryzation = setTimeout(function () {
//         if (true)
//             if (isWaitForAutoryzation == null) return;
//         if (!ConnectionOk) {
//             console.log("WaitForAutoryzation fail: reconect");
//             if (false) {
//                 ws.onopen = null;
//                 ws.onerror = null;
//                 ws.onmessage = null;
//                 ws.onclose = null;
//                 delete ws;
//             }
//             if (true) {
//                 if (RedirectCount > 4) RedirectPort = GazeCloudServerPort + 2;
//                 RedirectPort = GazeCloudServerPort + ConnectCount;
//                 if (RedirectPort > GazeCloudServerPort + 8) RedirectPort = GazeCloudServerPort + 1;
//                 var _url = GazeCloudServerAdress + RedirectPort;
//                 console.log("RedirectCount: " + RedirectCount + " url " + _url);
//                 if (Logg) Logg("RedirectCount: " + RedirectCount + " url " + _url, 2);
//                 Connect(_url);
//             } else Connect();
//         }
//         if (isWaitForAutoryzation != null) {
//             clearTimeout(isWaitForAutoryzation);
//             isWaitForAutoryzation = null;
//         }
//     }, 5000););
// }

// function OnMessageGaze(evt) {
//     if (!ConnectionOk) {
//         if (evt.data.substring(0, 2) == "ws") {
//             console.log("redirect: " + evt.data);
//             if (Logg) Logg("redirect: " + evt.data, 2);
//             Connect(evt.data);
//             return;
//         }
//         if (evt.data == "no free slots") {
//             console.log("no free slots");
//             WaitForSlot();
//             ws.onclose = null;
//             ws.close();
//             return;
//         }
//         if (evt.data.substring(0, 2) == "ok") {
//             GazeFlowSesionID = evt.data.substring(2);
//             ConnectionOk = true;
//             if (isWaitForAutoryzation != null) {
//                 clearTimeout(isWaitForAutoryzation);
//                 isWaitForAutoryzation = null;
//             }
//             console.log("authorization ok");
//             if (Logg) {
//                 Logg("authorization ok", 2);
//                 Logg("GazeFlowSesionID: " + GazeFlowSesionID, 2);
//             }
//             ws.send(sendScreensize());
//             InitCamSend();
//             if (false) {
//                 if (initializingid.style.display != 'none') initializingid.style.display = 'none';
//             }
//             return;
//         }
//     } {
// var received_msg = evt.data;
// if (evt.data.substring(2, 7) == "AckNr") {
//     var ack = JSON.parse(evt.data);
//     console.log(evt.data + " AckNr " + ack.AckNr);
//     networkDelay = Date.now() - ack.time;
//     if (networkDelay < minNetworkDelay) minNetworkDelay = networkDelay;
//     if (networkDelay > maxNetworkDelay) maxNetworkDelay = networkDelay;
//     if (networkDelay < 10 * minNetworkDelay) avrNetworkDelay = (avrNetworkDelay * ack.AckNr + networkDelay) / (ack.AckNr + 1);
//     console.log(" network delay " + networkDelay);
//     CurFrameAckNr = ack.AckNr;
//     CurFrameAckTime = ack.time;
//     return;
// }
// if (evt.data.substring(0, 4) == "Demo") {
//     console.log(evt.data);
//     ShowDemoLimit();
//     ws.onclose = null;
//     if (false) ws.close();
//     return;
// }
// if (evt.data.substring(0, 4) == "Cal:") {
//     if (Logg) Logg("cal complete " + evt.data, 2);
//     try {
//         document.getElementById("infoWaitForCalibration").style.display = "none";
//         try {
//             if (GazeCloudAPI.OnCalibrationComplete != null) GazeCloudAPI.OnCalibrationComplete();
//             if (true) disableStyle('GazeCloudAPI.css', true);
//         } catch (e) {
//             ;
//         }
//     } catch (e) { };
//     bIsProcesingCalibration = false;
//     bIsCalibrated = true;
//     if (evt.data.substring(4, 6) == "ok") {
//         if (true) InitClickCalibration();
//     }
//     if (evt.data.substring(4, 6) == "no") {
//         ShowErr("Invalid Calibration!");
//     }
//     return;
// }
//         try {
//             if (_GazeData.state == -1) {
//                 GoodFrameCount = 0;
//                 BadFrameCount++;
//             } else {
//                 GoodFrameCount++;
//                 BadFrameCount = 0;
//             }
//             _LastGazeData = Object.assign({}, _GazeData);
//             var GazeData = JSON.parse(received_msg);
//             var LastNr = _GazeData.FrameNr;
//             _GazeData = GazeData;
//             CurFrameReciveNr = GazeData.FrameNr;
//             CurFrameReciveTime = GazeData.time;
//             processkDelay = Date.now() - GazeData.time;
//             var skipC = _GazeData.FrameNr - LastNr - 1;
//             if (!isNaN(skipC))
//                 if (skipC > 0) skipProcessCount += skipC;
//             console.log("processkDelay" + processkDelay + " skipC " + skipC);
//             PlotGazeData(GazeData);
//             return;
//         } catch (error) {
//             console.error(error);
//         }
//     }
// }

// function Disconect() {
//     try {
//         ConnectionOk = false;
//         if (ws != null) {
//             ws.onopen = null;
//             ws.onerror = null;
//             ws.onmessage = null;
//             ws.onclose = null;
//             try {
//                 ProcessWebRecStream(true);
//                 ws.send('exit');
//             } catch (ee) { }
//             ws.close();
//             delete ws;
//             ws = null;
//         }
//         if (isWaitForAutoryzation != null) {
//             clearTimeout(isWaitForAutoryzation);
//             isWaitForAutoryzation = null;
//         }
//         if (Logg) Logg("Disconect", 2);
//     } catch (error) { }
// }

// function Connect(_url = "") {
//     try {
//         bIsCalibrated = false;
//         bIsRunCalibration = false;
//         bIsProcesingCalibration = false;
//         Disconect();
//         ConnectCount++;
//         if (ConnectCount > 4) {
//             console.log("try connect count" + ConnectCount);
//             ShowErr("Can not connect to GazeFlow server!");
//             return;
//         }
//         AppKey = "AppKeyDemo";
//         ConnectionOk = false;
//         if ("WebSocket" in window) {
//             var port = GazeCloudServerPort;
//             var url = GazeCloudServerAdress + port;
//             if (_url == "") {
//                 _url = GazeCloudServerAdress + GazeCloudServerPort;
//                 try {
//                     ws = new WebSocket(_url);
//                 } catch (ec) {
//                     if (Logg) Logg(" connect exeption: " + ec.message, -2);
//                 };
//             } else {
//                 var _ws;
//                 try {
//                     _ws = new WebSocket(_url);
//                 } catch (ecc) {
//                     if (Logg) Logg(" reconnect exeption: " + ecc.message, -2);
//                 };
//                 ws = _ws;
//             }
// if (Logg) {
//     Logg("connecting: " + _url, 2);
// }
// console.log("connecting: " + _url);
// ws.onopen = function () {
//     if (Logg) {
//         Logg("Connected", -2);
//     }
//     console.log("connected");
//     WaitForAutoryzation();
//     ws.send("AppKey:" + AppKey);
// }
// ws.onerror = function (error) {
//     if (Logg) {
//         var myJSON = JSON.stringify(error);
//         Logg(ConnectCount + " ws.onerror ConnectionOk: " + ConnectionOk, -2);
//     }
//     if (!ConnectionOk)
//         if (ConnectCount < 4) {
//             var port = GazeCloudServerPort + ConnectCount
//             if (ConnectCount == 3) port = 80;
//             var _url = GazeCloudServerAdress + port;
//             console.log("ws.onerror ConnectCount try again" + ConnectCount + "url " + _url);
//             Connect(_url);
//         } else ShowErr("Can not connect to GazeCloud server!");
// }
//             ws.onmessage = OnMessageGaze;
//             ws.onclose = function (event) {
//                 if (Logg) {
//                     var myJSON = JSON.stringify(event);
//                     Logg(" ws.onclose " + myJSON, -2);
//                 }
//                 if (bIsProcesingCalibration || bIsRunCalibration) {
//                     AbortCalibration();
//                     ShowErr("Invalid Calibration");
//                 } else ShowErr("GazeCloud server connection lost!");
//             };
//         } else {
//             alert("WebSocket NOT supported by your Browser!");
//             if (Logg) Logg("WebSocket NOT supported by your Browser", -2);
//         }
//         document.getElementById("initializingid").style.display = 'block';
//         GUIState = 'InitConnection';
//     } catch (ee) {
//         if (Logg) Logg(" Connect exeption " + JSON.stringify(ee), -2);
//     }
// }
// var RetrayCount = 0;
// var RetrayCountNoSlot = 0;

// function WaitForSlot() {
//     if (Logg) Logg("WaitForSlot", 2);
//     GUIState = 'WaitForSlot';
//     if (true)
//         if (isWaitForAutoryzation != null) {
//             clearTimeout(isWaitForAutoryzation);
//             isWaitForAutoryzation = null;
//         } document.getElementById("waitslotid").style.display = 'block';
//     document.getElementById("waitslottimeid").innerHTML = "30";
//     var start = Date.now();
//     var _LoopSlotWait = setInterval(() => {
//         var t = 30 - (Date.now() - start) / 1000.0;
//         t = Math.round(t);
//         document.getElementById("waitslottimeid").innerHTML = t;
//         if (t < 0) {
//             clearInterval(_LoopSlotWait);
//             document.getElementById("waitslotid").style.display = 'none';
//             Connect();
//             RetrayCount++;
//             RetrayCountNoSlot++;
//         }
//     }, 1000);
// }

// function ScreenPixT(x, y, inv = false) {
//     if (inv) {
//         x = x - window.screenX;
//         y = y - window.screenY;
//         (window.outerHeight - window.innerHeight);
//     } else { }
// }
// let GazeResultEvents = [];

// function GazeEvent() {
//     this.docX = 0;
//     this.docY = 0;
//     this.time = 0;
//     this.state = -1;
// }

// function GetGazeEvent(time) {
//     var BestIx = 0;
//     var BestDif = 99999999999999;
//     var fLen = GazeResultEvents.length;
//     if (fLen == 0) return null;
//     if (LastGetGazeEvent == null) LastGetGazeEvent = GazeResultEvents[0];
//     for (i = 0; i < fLen; i++) {
//         var dif = Math.abs(GazeResultEvents[i].time - time);
//         if (dif < BestDif) {
//             BestDif = dif;
//             BestIx = i;
//         } else break;
//     }
//     if (BestDif > 200) return null;
//     var out = GazeResultEvents[BestIx];
//     LastGetGazeEvent = out;
//     return out;
// }
// var maxDelay = 0;
// var avrDelay = 33;
// var LastGetGazeEventIx = 0;
// var LastGetGazeEvent = null;

// function PlotGazeData(GazeData) {
//     var delay = Date.now() - GazeData.time;
//     var FrameCountDelay = CurFrameNr - CurFrameReciveNr;
//     if (delay > maxDelay) maxDelay = delay;
//     avrDelay = .99 * avrDelay + .01 * delay;
//     var x = GazeData.GazeX - window.screenX;
//     var y = GazeData.GazeY - window.screenY - (window.outerHeight - window.innerHeight * window.devicePixelRatio / CalDeviceRation);
//     x /= window.devicePixelRatio / CalDeviceRation;
//     y /= window.devicePixelRatio / CalDeviceRation;
//     if (true) {
//         var _m = 50;
//         if (_m < window.innerWidth / 12.0) _m = window.innerWidth / 12.0;
//         if (_m < window.innerHeight / 12.0) _m = window.innerHeight / 12.0
//         var _h_ = (window.outerHeight - window.innerHeight);;
//         if (x < 0 && x > -_m) x = .2 * x;
//         x = 0;
//         if (y < 0 && y > -_m) y = .2 * y;
//         var _w = window.innerWidth;
//         var _h = window.innerHeight;;
//         if (x > _w && x - _w < _m) x = .2 * x + .8 * _w;
//         if (y > _h && y - _h < _m) y = .2 * y + .8 * _h;
//     }
// if (true) {
//     var scrollY = Math.max(document.body.scrollTop, window.scrollY) var scrollX = Math.max(document.body.scrollLeft, window.scrollX) x += scrollX;
//     y += scrollY;
// }
// if (true) {
//     GazeData.Xview = x / window.innerWidth;
//     GazeData.Yview = y / window.innerHeight GazeData.docX = x;
//     GazeData.docY = y;
// }
// if (GazeCloudAPI.OnResult != null) {
//     var outGazeData = GazeData;
//     outGazeData.docX = x;
//     outGazeData.docY = y;
//     GazeCloudAPI.OnResult(outGazeData);
// }
// var Gazeevent = new GazeEvent();
// Gazeevent.docX = Math.round(x);
// Gazeevent.docY = Math.round(y);
// Gazeevent.time = GazeData.time;
// Gazeevent.state = GazeData.state;
// GazeResultEvents.push(Gazeevent);
// if (true) {
//     var t = Date.now();
//     var webevent = {
//         type: 20,
//         data: Gazeevent,
//         timestamp: t
//     };
//     eventsWebRec.push(webevent);
//     try {
//         if (GazeCloudAPI.OnGazeEvent != null) {
//             GazeCloudAPI.OnGazeEvent(webevent);
//         }
//     } catch (e) { } /* */
// }
//     if (typeof heatmap !== 'undefined')
//         if (heatmap != null)
//             if (!bIsRunCalibration && !bIsProcesingCalibration && bIsCalibrated) {
//                 if (GazeData.state == 0) {
//                     var Precision = 1;
//                     var _x = Math.round(x / Precision) * Precision + (.5 * Precision - .5);
//                     var _y = Math.round(y / Precision) * Precision + (.5 * Precision - .5);
//                     _x = Math.round(_x);
//                     _y = Math.round(_y);
//                     var timedif = _GazeData.time - _LastGazeData.time;
//                     console.log("timedif " + timedif);
//                     var v = timedif / 33;
//                     if (v > 5) v = 5;
//                     try {
//                         heatmap.addData({
//                             x: _x,
//                             y: _y,
//                             value: v
//                         });
//                     } catch (e) { }
//                 }
//             } UpdateGUI(GazeData);
// }
// var GUIState = 'none';
// var ButtonCalibrate = document.getElementById("_ButtonCalibrateId");
// var facemaskimgOk = document.getElementById("facemaskimgok");
// var facemaskimgNo = document.getElementById("facemaskimgno");
// var showinit = document.getElementById("showinit");
// var camid = document.getElementById("camid");
// var loadid = document.getElementById("loadid");
// var initializingid = document.getElementById("initializingid");
// var DocmentLoaded = false;
// var CalDivId = document.getElementById("CalDivId");
// var infoWaitForCalibration = document.getElementById("infoWaitForCalibration");
// var waitslotid = document.getElementById("waitslotid");
// var errid = document.getElementById("errid");
// var demoid = document.getElementById("demoid");
// var CamAccessid = document.getElementById("CamAccessid");
// var GazeFlowContainer = document.getElementById("GazeFlowContainer");
// var corectpositionid = document.getElementById("corectpositionid");
// var GUIInitialized = false;
// var disableStyle = function (styleName, disabled) {
//     return;
//     try {
//         var styles = document.styleSheets;
//         var href = "";
//         for (var i = 0; i < styles.length; i++) {
//             href = styles[i].href.split("/");
//             href = href[href.length - 1];
//             if (href === styleName) {
//                 styles[i].disabled = disabled;
//                 break;
//             }
//         }
//     } catch (e) { }
// };
// if (true) {
//     try {
//         var style = ' ';
//         document.getElementsByTagName('head')[0].insertAdjacentHTML('afterbegin', style);
//     } catch (e) { }
// }

// function InitGUI() {
//     if (!GUIInitialized) {
//         document.body.insertAdjacentHTML('afterbegin', _GuiHtml);
//     }
//     try {
//         disableStyle('GazeCloudAPI.css', false);
//     } catch (e) { }
//     GUIInitialized = true;
//     DocmentLoaded = true;
//     video = document.querySelector('video');
//     ButtonCalibrate = document.getElementById("_ButtonCalibrateId");
//     facemaskimgOk = document.getElementById("facemaskimgok");
//     facemaskimgNo = document.getElementById("facemaskimgno");
//     corectpositionid = document.getElementById("corectpositionid");
//     showinit = document.getElementById("showinit");
//     camid = document.getElementById("camid");
//     loadid = document.getElementById("loadid");
//     initializingid = document.getElementById("initializingid");
//     infoWaitForCalibration = document.getElementById("infoWaitForCalibration");
//     waitslotid = document.getElementById("waitslotid");
//     errid = document.getElementById("errid");
//     demoid = document.getElementById("demoid");
//     CamAccessid = document.getElementById("CamAccessid");
//     CalDivId = document.getElementById("CalDivId");
//     GazeFlowContainer = document.getElementById("GazeFlowContainer");
//     showinit.style.display = 'block';
//     GazeFlowContainer.style.display = 'block';
//     if (true) {
//         camid.style.marginLeft = -camid.scrollWidth / 2;
//         if (true) {
//             facemaskimgOk.width = video.width;
//             facemaskimgOk.height = video.height;
//             facemaskimgNo.width = video.width;
//             facemaskimgNo.height = video.height;
//         }
//     }
//     InitCalibration();
// }
// var TrackingLostShow = true;
// var LatTrackingLostShow = true;

// function UpdateGUI(GazeData) {
//     try {
//         var GuiState = 0; {
//             if (CurFrameReciveNr > 2) {
//                 if (initializingid.style.display != 'none') {
//                     initializingid.style.display = 'none';
//                     if (Logg) Logg("Initialized ", 2);
//                 }
//             } else return;
//         }
// var showInit = false;
// showInit = (!bIsCalibrated && !bIsProcesingCalibration && !bIsRunCalibration);
// var delayC = 5;
// if (TrackingLostShow) {
//     if (GoodFrameCount > delayC) TrackingLostShow = false;
// } else {
//     if (BadFrameCount > delayC) TrackingLostShow = true;
// }
// var display = 'none';
// if (TrackingLostShow || (!bIsCalibrated && !bIsProcesingCalibration && !bIsRunCalibration)) display = 'block';
// else display = 'none';
// if (bIsProcesingCalibration) display = 'none';
// if (false)
//     if (display != camid.style.display) {
//         camid.style.display = display;
//         if (true)
// } if (display == 'none') {
//     bHideVideo = true;
//     camid.style.display = 'block';
// }
// if (false) {
//     if (camid.style.display == 'none') {
//         _w = 320;
//         _h = 240;
//     } else {
//         if (bIsCalibrated || bIsRunCalibration) {
//             if (video.videoHeight > video.videoWidth) {
//                 _h = 200;
//                 _w = _h * video.videoWidth / video.videoHeight;
//             } else {
//                 _w = 200;
//             }
//         } else {
//             _w = 320;
//             _h = 240;
//         }
//     }
// }
// if (true) {
//     if (bHideVideo) {
//         _w = 1;
//         _h = 1;
//     } else {
//         _w = 320;
//         _h = 240;
//     }
// }
// if (video.width != _w || video.height != _h) {
//     {
//         video.width = _w;
//         video.height = _h;
//     }
//     facemaskimgOk.width = video.width;
//     facemaskimgOk.height = video.height;
//     facemaskimgNo.width = video.width;
//     facemaskimgNo.height = video.height;
//     if (true) RePlayVideo();
// }
//         if (LatTrackingLostShow != TrackingLostShow) {
//             if (Logg) Logg("Face : " + TrackingLostShow, 2);
//             if (!TrackingLostShow) {
//                 facemaskimgOk.style.display = "block";
//                 facemaskimgNo.style.display = "none";
//             } else {
//                 facemaskimgOk.style.display = "none";
//                 facemaskimgNo.style.display = "block";
//             }
//             if (ButtonCalibrate.disabled != TrackingLostShow) ButtonCalibrate.disabled = TrackingLostShow;
//             if (display) {
//                 var d = null;
//                 if (TrackingLostShow) d = 'block';
//                 else d = 'none';
//                 if (corectpositionid.style.display != d) corectpositionid.style.display = d;
//             }
//         }
//         var dd = null;
//         if (showinit.style.display != 'none') dd = "block";
//         else dd = "none";
//         if (GazeFlowContainer.style.display != dd) GazeFlowContainer.style.display = dd;
//         LatTrackingLostShow = TrackingLostShow;
//     } catch (e) {
//         console.log("update gui exeption ");
//     }
// }

// function ShowDemoLimit() {
//     if (Logg) Logg("DemoLimit", 2);
//     GUIState = 'DemoLimit';
//     document.getElementById("demoid").style.display = "block";
//     setTimeout(StopGazeFlow, 3000);
// }

// function ShowErr(txt) {
//     if (document.getElementById("errid").style.display != "none") return;
//     CloseWebCam();
//     if (Logg) Logg("ShowErr:" + txt, 2);
//     GUIState = 'Err';
//     document.getElementById("errid").style.display = "block";
//     document.getElementById("errmsgid").innerHTML = txt;
//     UpdateGUI(_GazeData);
//     if (GazeCloudAPI.OnError != null) GazeCloudAPI.OnError(txt);
// }
// this.get_browser_info = get_browser_info;

// function get_browser_info() {
//     var ua = navigator.userAgent,
//         tem, M = ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
//     if (/trident/i.test(M[1])) {
//         tem = /\brv[ :]+(\d+)/g.exec(ua) || [];
//         return {
//             name: 'IE ',
//             version: (tem[1] || '')
//         };
//     }
//     if (M[1] === 'Chrome') {
//         tem = ua.match(/\bOPR\/(\d+)/) if (tem != null) {
//             return {
//                 name: 'Opera',
//                 version: tem[1]
//             };
//         }
//     }
//     M = M[2] ? [M[1], M[2]] : [navigator.appName, navigator.appVersion, '-?'];
//     if ((tem = ua.match(/version\/(\d+)/i)) != null) {
//         M.splice(1, 1, tem[1]);
//     }
//     return {
//         name: M[0],
//         version: M[1]
//     };
// }

// function sendScreensize() {
//     try {
//         var aa = document.getElementById('dpimm');
//         var mm_x = document.getElementById('dpimm').offsetWidth;
//         var mm_y = document.getElementById('dpimm').offsetHeight;
//         if (false) {
//             var wmm = window.screen.width / mm_x / window.devicePixelRatio;
//             var hmm = window.screen.height / mm_y / window.devicePixelRatio;
//             var w = window.screen.width * window.devicePixelRatio;
//             var h = window.screen.height * window.devicePixelRatio;
//             var wmm = window.screen.width / mm_x;
//             var hmm = window.screen.height / mm_y;
//             var w = window.screen.width;
//             var h = window.screen.height;
//         }
// var wmm = window.screen.width / mm_x;
// var hmm = window.screen.height / mm_y;
// var w = window.screen.width;
// var h = window.screen.height;
// if (true) {
//     wmm /= CalDeviceRation;
//     hmm /= CalDeviceRation;
// }
// var orientation = window.orientation;
// var isMobile = window.orientation > -1;
// if (typeof window.orientation === 'undefined') orientation = 10;
// var info = get_browser_info();
// info.platform = navigator.platform;
// info.userAgent = navigator.userAgent;
// info.Media = MediaInfo;
// var r = {
//     'wmm': wmm,
//     'hmm': hmm,
//     'wpx': w,
//     'hpx': h,
//     'ratio': window.devicePixelRatio,
//     orientation: orientation,
//     winx: window.screenX,
//     winy: window.screenY,
//     aW: screen.availWidth,
//     aH: screen.availHeight,
//     'innerWidth': window.innerWidth,
//     'outerWidth': window.outerWidth,
//     'innerHeight': window.innerHeight,
//     'outerHeight': window.outerHeight,
//     "mm_x": mm_x,
//     "mm_y": mm_y,
//     "CalDeviceRation": CalDeviceRation,
//     'camw': video.videoWidth,
//     'camh': video.videoHeight,
//     info: info
// };
//         var myJSON = JSON.stringify(r);
//         if (false) alert("screen s" + myJSON);
//         return myJSON;
//     } catch (e) {
//         console.log("sendScreensize exeption ");
//     }
// }

// function openFullscreen(callback) {
//     return;
//     try {
//         var elem = document.body;
//         if (elem.requestFullscreen) {
//             elem.requestFullscreen().then(callback);
//         } else if (elem.mozRequestFullScreen) {
//             /* Firefox */
//             elem.mozRequestFullScreen().then(callback);;
//         } else if (elem.webkitRequestFullscreen) {
//             /* Chrome, Safari and Opera */
//             elem.webkitRequestFullscreen().then(callback);;
//         } else if (elem.msRequestFullscreen) {
//             elem.msRequestFullscreen().then(callback);;
//             if (false)
//                 if (callback) callback();
//         }
//     } catch (ee) {
//         if (callback) callback();
//     }
// } /* Close fullscreen */
// function closeFullscreen() {
//     return;
//     if (false) {
//         var isMobile = window.orientation > -1;
//         if (isMobile) {
//             return;
//         }
//     }
//     try {
//         if (document.exitFullscreen) {
//             document.exitFullscreen();
//         } else if (document.mozCancelFullScreen) {
//             /* Firefox */
//             document.mozCancelFullScreen();
//         } else if (document.webkitExitFullscreen) {
//             document.webkitExitFullscreen();
//         } else if (document.msExitFullscreen) {
//             document.msExitFullscreen();
//         }
//     } catch (error) {
//         ;
//     }
// }

// function ResetIntervals() {
//     try {
//         if (isWaitForAutoryzation != null) {
//             clearTimeout(isWaitForAutoryzation);
//             isWaitForAutoryzation = null;
//         }
//         if (_LoopSlotWait != null) {
//             clearInterval(_LoopSlotWait);
//             _LoopSlotWait = null;
//         }
//     } catch (e) { }
// }
// var bStarted = false;
// function StartGazeFlow() {
//     RestlzwStream();
//     if (bStarted) return;
//     if (bStarted) CloseWebCam();
//     bStarted = true; InitGUI();
//     if (true) {
//         ResetIntervals();
//         document.getElementById("waitslotid").style.display = 'none';
//         document.getElementById("infoWaitForCalibration").style.display = "none";
//         document.getElementById("errid").style.display = "none";
//         document.getElementById("errmsgid").innerHTML = "";
//         camid.style = ' z-index: 1000;position:absolute; left:50%; top:2% ; margin-left: -160px; ';
//         camid.style.marginLeft = -camid.scrollWidth / 2;
//     }
//     GazeResultEvents = [];
//     if (typeof GetCloudAdresInfo != null) {
//         if (typeof GetCloudAdresInfo.err !== 'undefined')
//             if (GetCloudAdresInfo.err != null)
//                 if (GetCloudAdresInfo.err != "") {
//                     ShowErr(GetCloudAdresInfo.err);
//                     return;
//                 }
//     }
//     OpenWebCam();
//     if (Logg) Logg("StartGazeFlow", 2);
// }

// function StopGazeFlow() {
//     try {
//         SendStat();
//         CloseWebCam();
//         HideGUI();
//         if (Logg) Logg("StopGazeFlow", 2);
//         bStarted = false;
//     } catch (error) {
//         ;
//     }
// }
// window.addEventListener("beforeunload", function (e) {
//     GazeCloudAPI.StopEyeTracking();
// }, false);

// function httpGetAsync(theUrl, callback) {
//     var xmlHttp = new XMLHttpRequest();
//     xmlHttp.onreadystatechange = function () {
//         if (xmlHttp.readyState == 4 && xmlHttp.status == 200) callback(xmlHttp.responseText);
//     }
//     xmlHttp.open("GET", theUrl, true);
//     xmlHttp.send(null);
// }

// function callbackCheckiFrame(htm) {
//     var out = htm;
//     if (out != '200') { } else {
//         _opencontenet('d');
//     }
// }

// function uuidv4() {
//     return 'API:' + 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
//         var r = Math.random() * 16 | 0,
//             v = c == 'x' ? r : (r & 0x3 | 0x8);
//         return v.toString(16);
//     });
// }
// var LogSesionID = uuidv4();
// if (true) {
//     var info = get_browser_info();
//     info.platform = navigator.platform;
//     info.userAgent = navigator.userAgent;
//     info.Media = MediaInfo;
//     var myJSON = JSON.stringify(info);
//     Logg(myJSON, type = -1);
// }

// function Logg(txt, type = 0) {
//     try {
//         let req = new XMLHttpRequest();
//         let formData = new FormData();
//         req.withCredentials = false;
//         formData.append("RecordinSesionId", LogSesionID);
//         formData.append("log", txt);
//         formData.append("type", type);
//         formData.append("sesionid", LogSesionID);
//         req.open("POST", 'https://api.gazerecorder.com/Logs.php');
//         req.send(formData);
//     } catch (e) { }
// }
// if (true) window.addEventListener('DOMContentLoaded', function (event) {
//     if (Logg) Logg("GazeCloundAPI v:1.0.1 ", 2);
// });
// }
var StartGazeFlow = GazeCloudAPI.StartEyeTracking;
var StopGazeFlow = GazeCloudAPI.StopEyeTracking;
var SetLowFps = GazeCloudAPI.SetLowFps;
var get_browser_info = GazeCloudAPI.get_browser_info;
var MediaInfo = "";
var OnResult = null;
var OnCalibrationComplete = null;
var OnCalibrationFail = null;
var OnStopGazeFlow = null;
var OnCamDenied = null;

function InitOldAPI() {
    try {
        if (typeof OnResult !== 'undefined') GazeCloudAPI.OnResult = OnResult;
        if (typeof OnCalibrationComplete !== 'undefined') GazeCloudAPI.OnCalibrationComplete = OnCalibrationComplete;
        if (typeof OnCalibrationFail !== 'undefined') GazeCloudAPI.OnCalibrationFail = OnCalibrationFail;
        if (typeof OnStopGazeFlow !== 'undefined') GazeCloudAPI.OnStopGazeFlow = OnStopGazeFlow;
        if (typeof OnCamDenied !== 'undefined') GazeCloudAPI.OnCamDenied = OnCamDenied;
        if (typeof OnError !== 'undefined') GazeCloudAPI.OnError = OnError;
    } catch (e) { }
}
var processClick = GazeCloudAPI.processClick;